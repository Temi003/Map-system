<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes</title>
    <link rel="icon" href="Images/ULK logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
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

        #sidebar .nav-link.active {
            font-weight: bold;
            color: white;
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
            background: red;
            margin: 5px 0;
            transition: 0.3s;
        }

        #menu-toggle:checked ~ .hamburger-icon span:nth-child(1) {
            transform: rotate(-45deg) translate(-5px, 6px);
        }

        #menu-toggle:checked ~ .hamburger-icon span:nth-child(2) {
            opacity: 0;
        }

        #menu-toggle:checked ~ .hamburger-icon span:nth-child(3) {
            transform: rotate(45deg) translate(-5px, -6px);
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
    margin: 8% auto; /* Center the form horizontally */
}

/* Label styling */
form label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

form h1{
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

    <form action="bookinghistory.php" method="post">
    <h1>Booking History</h1>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email to view booking history" required>
        <input type="submit" value="View History">
    </form>
</body>
</html>


<?php
include 'connection.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Fetch booking history
    $stmt = $conn->prepare("SELECT resource, booking_time FROM bookings WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2>Your Booking History:</h2>";
        echo "<table border='1'>
                <tr>
                    <th>Resource</th>
                    <th>Booking Time</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['resource']) . "</td>
                    <td>" . htmlspecialchars($row['booking_time']) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No bookings found for this email.";
    }

    $stmt->close();
    $conn->close();
}
?>
