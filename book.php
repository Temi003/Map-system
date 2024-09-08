<?php
include 'connection.php'; // Include the database connection

$alertMessage = ''; // Variable to hold the alert message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $resource = $_POST['resource'];
    $booking_time = $_POST['booking_time'];

    // Get current user email from session
    session_start();
    $current_user_email = $_SESSION['user_email']; // Assuming the user's email is stored in session

    if ($email !== $current_user_email) {
        // User is trying to book for someone else
        $alertMessage = "<p class='alert alert-danger'>You can only book for yourself.</p>";
    } else {
        // Check if the user already has a booking for the same resource at the same time
        $stmt = $conn->prepare("SELECT * FROM bookings WHERE email = ? AND resource = ? AND booking_time = ?");
        $stmt->bind_param("sss", $email, $resource, $booking_time);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User already has a booking for this resource at this time
            $alertMessage = "<p class='alert alert-danger'>You already have a booking for this resource at this time.</p>";
        } else {
            // Insert booking into the database
            $stmt = $conn->prepare("INSERT INTO bookings (email, resource, booking_time) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $resource, $booking_time);
            if ($stmt->execute()) {
                $alertMessage = "<p class='alert alert-success'>Booking successful!</p>";
            } else {
                $alertMessage = "<p class='alert alert-danger'>Error: Could not complete booking.</p>";
            }
            $stmt->close();
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Resource</title>
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

        /* Form styling for booking */
        .booking-form {
            max-width: 600px;
            width: 100%;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            margin: -5% auto; /* Adjust margin to ensure form is centered */
        }

        .booking-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .booking-form input[type="email"],
        .booking-form input[type="text"],
        .booking-form input[type="datetime-local"],
        .booking-form select,
        .booking-form input[type="submit"] {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 16px;
        }

        .booking-form input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .booking-form input[type="submit"]:hover {
            background-color: #0056b3;
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
        <form action="book.php" method="post" class="booking-form">
            <h1>Book a Resource</h1>
            
            <!-- Display the alert message -->
            <?php if ($alertMessage != ''): ?>
                <?php echo $alertMessage; ?>
            <?php endif; ?>
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            
            <label for="resource">Resource:</label>
                <select id="resource" name="resource" required>
                    <option value=""></option>
                    <option value="Room 305 4th floor">Room 305 4th floor</option>
                    <option value="Room 307 3rd floor">Room 307 3rd floor</option>
                    <option value="Lab 2">Lab 2</option>
                </select>
            
            <label for="booking_time">Booking Time</label>
            <input type="datetime-local" id="booking_time" name="booking_time" required>

            <input type="submit" value="Book Resource">
        </form>
    </div>
</body>
</html>
