<?php
session_start(); // Start the session at the very beginning

include 'connection.php';

$message = ''; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resource = $_POST['resource'];
    $availability = isset($_POST['availability']) ? 1 : 0; // Handle checkbox

    // Update resource availability
    $stmt = $conn->prepare("UPDATE resources SET available = ? WHERE name = ?");
    $stmt->bind_param("is", $availability, $resource);

    if ($stmt->execute()) {
        if ($availability) {
            // Fetch waitlisted users for this resource
            $stmt = $conn->prepare("SELECT email, `reserved time` FROM waitlist WHERE resource = ?");
            $stmt->bind_param("s", $resource);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $user_email = $row['email'];
                $reserved_time = $row['reserved time'];
                $booking_time = date("Y-m-d H:i:s"); // Set the current time for booking

                // Insert booking for each waitlisted user
                $stmt = $conn->prepare("INSERT INTO bookings (resource, email, booking_time) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $resource, $user_email, $booking_time);
                $stmt->execute();

                // Remove from waitlist
                $stmt = $conn->prepare("DELETE FROM waitlist WHERE email = ? AND resource = ? AND `reserved time` = ?");
                $stmt->bind_param("sss", $user_email, $resource, $reserved_time);
                $stmt->execute();

                // Insert notification for each waitlisted user
                $stmt = $conn->prepare("INSERT INTO notifications (user_email, message, timestamp) VALUES (?, ?, NOW())");
                $message = "The resource '$resource' is now available and has been booked for you.";
                $stmt->bind_param("ss", $user_email, $message);
                $stmt->execute();
            }

            $message = "Resource availability updated, bookings made, and notifications sent.";
        } else {
            $message = "Resource availability updated.";
        }
    } else {
        $message = "Error updating resource availability: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Store message in session to be displayed later
    $_SESSION['message'] = $message;

    // Redirect to the same page to prevent resubmission on refresh
    header("Location: updateresource.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Resource Availability</title>
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

        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #001f3f; /* Dark blue */
        }

        .form-container label {
            font-weight: bold;
        }

        .form-container input[type="submit"] {
            background-color: #0056b3; /* Blue */
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-container input[type="submit"]:hover {
            background-color: #004494; /* Darker blue */
        }
        .message-box {
            padding: 15px;
            border-radius: 5px;
            background-color: #90EE90;
            color: green; 
            border: 1px solid #90EE90; /* Light red border */
            margin-bottom: 20px;
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
        <h2>Admin Dashboard</h2>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="addclass.php">Add Class</a>
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
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>

    <div class="dashboard-content">
        <div class="form-container">
            <h1>Update Resource Availability</h1>

            <?php
            if (isset($_SESSION['message'])) {
                echo '<div class="message-box">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']); // Clear message after displaying
            }
            ?>
            <form action="updateresource.php" method="post">
                <div class="mb-3">
                    <label for="resource" class="form-label">Resource:</label>
                    <select id="resource" name="resource" class="form-select" required>
                        <option value=""></option>
                        <option value="Room 305 4th floor">Room 305 4th floor</option>
                        <option value="Room 307 3rd floor">Room 307 3rd floor</option>
                        <option value="Lab 2">Lab 2</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="availability" class="form-check-label">Available:</label>
                    <input type="checkbox" id="availability" name="availability" value="1" class="form-check-input"> Yes
                </div>
                <input type="submit" value="Update Availability">
            </form>
        </div>
    </div>
</body>
</html>