<?php
// Start the session
session_start();

// Include the database connection
include 'connection.php';

// Initialize variables
$table_html = "";
$message = "";
$showPrintIcon = false; // Initialize flag

// Check if user is logged in and email is available in the session
if (!isset($_SESSION['user_email'])) {
    die("User not logged in.");
}

$user_email = $_SESSION['user_email']; // Get logged-in user's email

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];

        // Validate the email address
        if ($email !== $user_email) {
            $message = "You are not authorized to check the waitlist history for this user.";
        } else {
            // Prepare and execute the SQL statement
            $stmt = $conn->prepare("SELECT resource, signup_time, `reserved time` FROM waitlist WHERE email = ?");
            if ($stmt === false) {
                die("Error preparing statement: " . htmlspecialchars($conn->error));
            }

            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $showPrintIcon = true; // Set flag to show print icon
                $table_html .= "<table border='1'>
                        <tr>
                            <th>Resource</th>
                            <th>Signup Time</th>
                            <th>Reserved Time</th>
                        </tr>";
                while ($row = $result->fetch_assoc()) {
                    $table_html .= "<tr>
                            <td>" . htmlspecialchars($row['resource']) . "</td>
                            <td>" . htmlspecialchars($row['signup_time']) . "</td>
                            <td>" . htmlspecialchars($row['reserved time']) . "</td>
                          </tr>";
                }
                $table_html .= "</table>";
            } else {
                $message = 'No waitlist found for this user.';
            }

            $stmt->close();
            $conn->close();
        }
    } else {
        $message = "Email field is missing.";
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes</title>
    <link rel="icon" href="Images/ULK logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Add this line for Font Awesome -->
    <style>
        /* Sidebar styling */
        #sidebar {
            background-color: rgb(1, 1, 31);
            width: 250px;
            height: calc(100vh - 14vh); /* Height minus header height */
            position: fixed;
            top: 14vh; /* Start below the header */
            left: -250px; /* Hide by default */
            transition: left 0.3s ease;
            z-index: 999; /* Ensure it stays below header */
            overflow-y: auto; /* Enable vertical scrolling */
            overflow-x: hidden; /* Hide horizontal scrollbar if any */
            -ms-overflow-style: none; /* IE and Edge */
            scrollbar-width: none; /* Firefox */
        }

        #sidebar::-webkit-scrollbar {
            display: none; /* Chrome, Safari, and Opera */
        }

        #sidebar h2 {
            color: pink;
            text-align: center;
        }

        #sidebar .nav-link {
            color: white;
            display: block;
            padding: 10px 20px;
            text-decoration: none;
        }
        #sidebar .nav-link:hover{
            font-size: 25px;
            color: lightgray;
        }

        .dashboard-content {
            margin-left: 0; /* Adjust for sidebar width */
            padding: 20px;
            padding-top: 14vh; /* Adjust for header height */
            transition: margin-left 0.3s ease;
        }

        #menu-toggle {
            display: none;
        }

        #menu-icon {
            font-size: 30px;
            cursor: pointer;
            position: fixed;
            top: 100px; /* Adjust to position the icon */
            left: 20px; /* Adjust to position the icon */
            z-index: 1001; /* Ensure it is above the sidebar */
        }

        #menu-toggle:checked ~ #sidebar {
            left: 0; /* Show sidebar */
        }

        #menu-toggle:checked ~ .dashboard-content {
            margin-left: 250px; /* Adjust for sidebar width */
        }

        .hamburger-icon {
            display: inline-block;
            cursor: pointer;
        }

        .hamburger-icon span {
            display: block;
            width: 25px;
            height: 3px;
            background: black;
            margin: 5px 0;
            transition: 0.3s;
        }

        #menu-toggle:checked ~ .hamburger-icon span:nth-child(1) {
            transform: rotate(-45deg) translate(-5px, 6px);
            background: white;
        }

        #menu-toggle:checked ~ .hamburger-icon span:nth-child(2) {
            opacity: 0;
        }

        #menu-toggle:checked ~ .hamburger-icon span:nth-child(3) {
            transform: rotate(45deg) translate(-5px, -6px);
            background: white;
        }

        .table-container {
            margin-top: 20px;
        }

        .table-container table {
            width: 100%;
            margin-top: 20px;
        }

        .alert {
            margin-top: 20px;
        }

        .update-form {
            margin-top: 20px;
        }

        table {
            width: 100%;
            margin-bottom: 20px;
        }
        th, td {
            text-align: left;
            padding: 8px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Form styling */
        form {
            max-width: 600px; /* Limit the maximum width of the form */
            width: 100%; /* Ensure the form takes the full width of its container */
            padding: 20px;
            background: #f8f9fa; /* Background color */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Shadow effect */
            margin: 1% auto; /* Center the form horizontally */
        }

        /* Label styling */
        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        form h1 {
            text-align: center; /* Center text horizontally */
            margin-bottom: 20px; /* Space below the heading */
            margin-top: 0; /* Remove space above the heading */
        }

        /* Input fields styling */
        form input[type="email"],
        form input[type="submit"] {
            width: calc(100% - 22px); /* Full width minus padding */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 16px;
        }

        /* Submit button styling */
        form input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Optional: Additional styles for consistency */
        form h2 {
            text-align: center; /* Center the header text */
            margin-bottom: 20px; /* Space below the header */
            color: black; /* Set a color for the header */
        }

        /* Alert/Error Message Styling */
        .alert {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #dc3545; /* Red border for error */
            border-radius: 5px;
            background-color: #f8d7da; /* Light red background */
            color: #721c24; /* Dark red text color */
            font-size: 16px; /* Adjust font size for readability */
            line-height: 1.5; /* Improve readability */
            text-align: center; /* Center align text */
        }

        .alert p {
            margin: 0; /* Remove default margin from paragraphs */
        }

        /* Print and Download button styling */
#printButton, #downloadButton {
    display: none;
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    font-size: 14px;
    width: auto; /* Adjust width to fit content */
    height: 40px; /* Ensure both buttons have the same height */
}

/* Show both buttons and position them */
.table-container.print-ready #printButton,
.table-container.print-ready #downloadButton {
    display: inline-block;
    margin-left: 10px; /* Add spacing between buttons */
}

.table-container .button-container {
    display: flex;
    justify-content: flex-start; /* Align buttons to the left or right */
    margin-bottom: 20px; /* Space below the buttons */
}

/* Optional: Additional styles for consistency */
.hide-when-printing {
    display: none;
}

/* Print styling */
@media print {
    #printButton, #downloadButton {
        display: none; /* Hide buttons when printing */
    }
    body * {
        visibility: hidden; /* Hide all content */
    }
    .table-container, .table-container * {
        visibility: visible; /* Show only table content */
    }
    .table-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%; /* Ensure the table takes full width */
    }
}

.no-data-message {
            background-color: #00008B; /* Match the color of the first info-box */
            color: white; /* Match the text color of the first info-box */
            border: 1px solid #bee5eb; /* Match the border color of the first info-box */
            border-radius: 5px; /* Match the border radius of the first info-box */
            padding: 15px; /* Match the padding of the first info-box */
            margin-top: 20px; /* Match the margin of the first info-box */
            font-size: 16px; /* Adjust the font size for consistency */
            line-height: 1.5; /* Improve readability */
            text-align: center; /* Center align text */
        }

    </style>
</head>
<body>
    <div class="menu">
        <div class="logo">
            <img src="images/ulk logo 2.png" alt="">
            <h2>KIGALI INDEPENDENT UNIVERSITY (ULK)</h2>
        </div>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="menu.html">Menu</a></li>
            <li><a href="contact.php">Contact Us</a></li>
            <li><a href="https://ulk.ac.rw/" target="_blank">School Website</a></li>
        </ul>
    </div>

    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" id="menu-icon" class="hamburger-icon">
        <span></span>
        <span></span>
        <span></span>
    </label>

    <div id="sidebar">
        <h2>User Dashboard</h2>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="management.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="resource.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="waitlist.php">Waitlist</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="waitlisthistory.php">Waitlist History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="book.php">Book Resource</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="bookinghistory.php">Booking History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="notify.php">Notifications</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="support.php">Support</a>
            </li>
        </ul>
    </div>

    <div class="dashboard-content">
        <form action="waitlisthistory.php" method="post">
            <h1>Waitlist History</h1>
            <!-- Display the message if it exists -->
            <?php if (!empty($message)) { echo "<div class='alert'>$message</div>"; } ?>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email to view waitlist history" required>
            <input type="submit" value="View History">
        </form>

        <!-- Display the table and print button if they exist -->
        <div class="table-container<?php echo $showPrintIcon ? ' print-ready' : ''; ?>">
    <?php if (!empty($table_html)) { 
        echo "<div style='display: flex; align-items: center;'>";
        echo "<h2 style='margin: 0;'>Your Waitlist History:</h2>";
        echo "</div>";
        echo $table_html;
        echo "<div>"; // Corrected this line
        echo "<button id='printButton'><i class='fas fa-print'></i> Print</button>";
        echo "<button id='downloadButton'><i class='fas fa-download'></i> Download</button>";
        echo "</div>"; // Corrected this line
    } ?>
</div>


    <!-- JavaScript to handle the print button functionality -->
    <script>
         document.addEventListener('DOMContentLoaded', function () {
    var printButton = document.getElementById('printButton');
    var downloadButton = document.getElementById('downloadButton');

    if (printButton) {
        printButton.addEventListener('click', function () {
            window.print();
        });
    }

    if (downloadButton) {
        downloadButton.addEventListener('click', function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Capture the table HTML and download
            html2canvas(document.querySelector('.table-container')).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                doc.addImage(imgData, 'PNG', 10, 20, 180, 0);
                doc.save('waitlist-history.pdf');
            }).catch(function (error) {
                alert('An error occurred while downloading. Please try again.');
                console.error('Error capturing the table:', error);
            });
        });
    }
});

    </script>
</body>
</html>