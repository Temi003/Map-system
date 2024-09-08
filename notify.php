<?php
session_start();
include 'connection.php';

// Check if user email is set
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user_email'];

// Function to fetch notifications for a specific user
function getNotificationsForUser($user_email) {
    global $conn;

    $sql = "
        SELECT message, timestamp 
        FROM notifications
        WHERE user_email = ?
        ORDER BY timestamp DESC
    ";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];

    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }

    $stmt->close();
    return $notifications;
}

// Handle form submission to clear notifications
if (isset($_POST['clear_notifications']) && isset($_POST['confirm_clear']) && $_POST['confirm_clear'] == 'Yes') {
    clearNotifications($user_email);
    header("Location: notify.php");
    exit();
}

// Function to clear all notifications for the current user
function clearNotifications($user_email) {
    global $conn;
    $sql = "DELETE FROM notifications WHERE user_email = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error preparing statement: " . htmlspecialchars($conn->error));
    }
    
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $stmt->close();
}

// Fetch notifications for the current user and store them in $notifications
$notifications = getNotificationsForUser($user_email);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="icon" href="Images/ULK logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Sidebar styling */
        #sidebar {
            background-color: rgb(1, 1, 31);
            width: 250px;
            height: calc(100vh - 14vh);
            position: fixed;
            top: 14vh;
            left: -250px;
            transition: left 0.3s ease;
            z-index: 999;
            overflow-y: auto;
            overflow-x: hidden;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        #sidebar::-webkit-scrollbar {
            display: none;
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
            margin-left: 0;
            padding: 20px;
            padding-top: 14vh;
            transition: margin-left 0.3s ease;
        }

        #menu-toggle {
            display: none;
        }

        #menu-icon {
            font-size: 30px;
            cursor: pointer;
            position: fixed;
            top: 100px;
            left: 20px;
            z-index: 1001;
        }

        #menu-toggle:checked ~ #sidebar {
            left: 0;
        }

        #menu-toggle:checked ~ .dashboard-content {
            margin-left: 250px;
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

        /* Notifications styling */
        .notification {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-right: 50px;
        }

        .notification p {
            margin: 0;
            font-size: 16px;
            color: #333;
        }

        .notification small {
            display: block;
            margin-top: 8px;
            color: #666;
            font-size: 12px;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Flexbox container for heading and button */
        .heading-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .heading-container h2 {
            margin: 0;
        }

        .heading-container .btn-danger {
            font-size: 15px;
            padding: 10px 10px;
        }

    </style>
    <script>
        function confirmClear() {
            return confirm("Are you sure you want to clear all notifications?");
        }
    </script>
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
        <div class="heading-container">
            <h2>Notifications</h2>
            <!-- Button to clear all notifications -->
            <form method="post" action="" onsubmit="return confirmClear();">
                <input type="hidden" name="confirm_clear" value="Yes">
                <button type="submit" name="clear_notifications" class="btn btn-danger">Clear All Notifications</button>
            </form>
        </div>

        <?php
        if (!empty($notifications)) {
            foreach ($notifications as $notification) {
                echo '<div class="notification">';
                echo '<p>' . htmlspecialchars($notification['message']) . '</p>';
                echo '<small>' . htmlspecialchars($notification['timestamp']) . '</small>';
                echo '</div>';
            }
        } else {
            echo '<p>No new notifications.</p>';
        }
        ?>
    </div>
</body>
</html>
