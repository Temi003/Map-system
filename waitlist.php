<?php
// Include the database connection
include 'connection.php';

// Initialize message variable
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $resource = $_POST['resource'];
    $email = $_POST['email'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO waitlist (resource, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $resource, $email);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $message = "You have been added to the waitlist.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to the form page with a message
    header("Location: waitlist.php?message=" . urlencode($message) . "&type=" . (strpos($message, 'Error') === false ? 'success' : 'error'));
    exit();
}

// Get the message and type from the URL query string if they exist
$message = isset($_GET['message']) ? $_GET['message'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
?>

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

        .dashboard-content h2 {
            text-align: center; /* Center the header text */
            margin-bottom: 0; /* Adjust the space below the header */
            margin-top: 10px;
            color: black; /* Optional: Set a color for the header */
            padding: 0; /* Ensure no extra padding around the header */
        }

        .waitlist {
            display: flex;
            justify-content: center; /* Center form horizontally */
            margin-top: 0; /* Adjust space above the form container as needed */
            padding: 20px; /* Optional: Add padding around the form container */
            margin: -4% auto;
        }

        .waitlist form {
            max-width: 600px;
            width: 100%; /* Ensure the form takes the full width of its container */
            padding: 20px;
            background: #f8f9fa; /* Optional: Add a background color */
            border-radius: 8px; /* Optional: Add rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Optional: Add a shadow effect */
            margin-bottom: 20px; /* Adjust space below the form as needed */
        }

        .waitlist h1 {
            text-align: center; /* Center text horizontally */
            margin-bottom: 20px; /* Space below the heading */
            margin-top: 0; /* Remove space above the heading */
        }

        .waitlist label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .waitlist input[type="email"],
        .waitlist select {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 16px;
        }

        .waitlist input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .waitlist input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
        text-align: center;
        margin-bottom: 20px;
        padding: 10px;
        border-radius: 5px;
        font-size: 16px;
        width: 100%; /* Ensure the box is full width within its container */
        max-width: 600px; /* Set a maximum width for the box */
        margin-left: auto; /* Center the box horizontally */
        margin-right: auto;
    }

    .message.success {
        background-color: #d4edda; /* Light green background for success */
        color: #155724; /* Dark green text for success */
        border: 1px solid #c3e6cb; /* Green border for success */
    }

    .message.error {
        background-color: #f8d7da; /* Light red background for error */
        color: #721c24; /* Dark red text for error */
        border: 1px solid #f5c6cb; /* Red border for error */
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
    <div class="dashboard-content">
        <div class="waitlist">
            <form action="waitlist.php" method="post">
                <h1>Waitlist Form</h1>
                <?php if (!empty($message)): ?>
                    <div class="message <?= htmlspecialchars($type) ?>">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>
                <label for="resource">Resource:</label>
                <select id="resource" name="resource" required>
                    <option value="">Resource</option>
                    <option value="Room 305 4th floor">Room 305 4th floor</option>
                    <option value="Room 307 3rd floor">Room 307 3rd floor</option>
                    <option value="Lab 2">Lab 2</option>
                </select><br><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br><br>
                <input type="submit" value="Join Waitlist">
            </form>
        </div>
    </div>
</body>
</html>
