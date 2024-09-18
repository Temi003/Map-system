<?php
session_start();
include 'connection.php';

// Check if user email is set
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Function to notify all admins
function notifyAllAdmins($message) {
    global $conn;

    // Prepare a query to fetch admin emails
    $stmt = $conn->prepare("SELECT email FROM employees WHERE role = 'admin'");
    if (!$stmt) {
        die("Error preparing SQL: " . $conn->error);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $admin_email = $row['email'];

            // Debugging output
            // echo "Notifying admin: $admin_email<br>";

            // Check if a similar notification already exists to avoid duplicates
            $stmtCheck = $conn->prepare("SELECT id FROM `admin notify` WHERE user_email = ? AND message = ?");
            if (!$stmtCheck) {
                die("Error preparing SQL: " . $conn->error);
            }
            $stmtCheck->bind_param("ss", $admin_email, $message);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows == 0) {
                $stmtNotify = $conn->prepare("INSERT IGNORE INTO `admin notify` (user_email, message, timestamp) VALUES (?, ?, NOW())");
                if (!$stmtNotify) {
                    die("Error preparing SQL for admin notification: " . $conn->error);
                }
                $stmtNotify->bind_param("ss", $admin_email, $message);
                if (!$stmtNotify->execute()) {
                    die("Error executing SQL for admin notification: " . $stmtNotify->error);
                }
                $stmtNotify->close();
            }
            $stmtCheck->close(); // Close the check statement
        }
    } else {
        // Debugging output
        echo "No admins found.<br>";
    }

    $stmt->close();
}

// Check for expired bookings and notify admin
function checkExpiredBookings() {
    global $conn;
    $current_time = date('Y-m-d H:i:s');

    $stmt = $conn->prepare(
        "SELECT bookings.email, bookings.resource, bookings.`End time`
         FROM bookings
         WHERE bookings.`End time` <= ?"
    );
    if (!$stmt) {
        die("Error preparing SQL: " . $conn->error);
    }
    $stmt->bind_param("s", $current_time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $expiredBookingEmail = $row['email'];
            $expiredResource = $row['resource'];
            $expiredEndTime = $row['End time'];

            // Debugging output
            // echo "Expired Booking Found: $expiredBookingEmail, $expiredResource, $expiredEndTime<br>";

            // Format message for the notification
            $adminNotificationMessage = "Booking for resource '$expiredResource' by $expiredBookingEmail has expired (ended at $expiredEndTime).";

            // Notify all admins
            notifyAllAdmins($adminNotificationMessage);
        }
    } else {
        // Debugging output
        // echo "No expired bookings found.<br>";
    }

    $stmt->close();
}

// Call the function to check for expired bookings whenever the script runs
checkExpiredBookings();

// Function to fetch all notifications
function getAllNotifications() {
    global $conn;

    $sql = "
        SELECT message, timestamp 
        FROM `admin notify`
        ORDER BY timestamp DESC
    ";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . htmlspecialchars($conn->error));
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];
    $notificationCount = 0;

    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
        $notificationCount++;
    }

    $stmt->close();
    return [$notifications, $notificationCount];
}

// Fetch all notifications and store them in $notifications and $notificationCount
list($notifications, $notificationCount) = getAllNotifications();

// Handle form submission to clear notifications
if (isset($_POST['clear_notifications']) && isset($_POST['confirm_clear']) && $_POST['confirm_clear'] == 'Yes') {
    clearAllNotifications();
    header("Location: adminnotify.php");
    exit();
}

// Function to clear all notifications
function clearAllNotifications() {
    global $conn;
    $sql = "DELETE FROM `admin notify`";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error preparing statement: " . htmlspecialchars($conn->error));
    }
    
    $stmt->execute();
    $stmt->close();
}
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
        .bg-primary{
            border-radius: 50%;

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
        <h2>Admin Dashboard</h2>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="addclass.php">Add Class</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="addresource.php">Add resource</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manageclass.php">Manage Classes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="messages.php">Messages</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="managemessage.php">Manage Message</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="updateresource.php">Update Availability</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="ticket.php">Tickets</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="adminnotify.php">Notifications</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="usermanagement.php">User management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
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
if (!empty($notifications) && is_array($notifications)) {
    foreach ($notifications as $notification) {
        $message = isset($notification['message']) ? htmlspecialchars($notification['message']) : 'No message';
        $timestamp = isset($notification['timestamp']) ? htmlspecialchars($notification['timestamp']) : 'No timestamp';

        echo '<div class="notification">';
        echo '<p>' . $message . '</p>';
        echo '<small>' . $timestamp . '</small>';
        echo '</div>';
    }
} else {
    echo '<p>No new notifications.</p>';
}
?>

    </div>
</body>
</html>
