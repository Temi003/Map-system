<?php
session_start();
require 'connection.php'; // Database connection

// Initialize message variable
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $courseName = $_POST['courseName'];
    $lecturerName = $_POST['lecturerName'];
    $classYear = $_POST['classYear'];
    $resource = $_POST['resource'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];

    // Convert times to DateTime objects for comparison
    $startTimeDate = new DateTime($startTime);
    $endTimeDate = new DateTime($endTime);

    // Check if start time is before end time
    if ($startTimeDate >= $endTimeDate) {
        $message = "<div class='alert alert-danger' role='alert'>End time must be after start time.</div>";
    } else {
        // Check for duplicates
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM classes WHERE `Course Name` = ? AND `Lecturer Name` = ? AND `Class Year` = ? AND `Resource` = ?");
        
        if ($checkStmt === false) {
            die("Error preparing the check statement: " . $conn->error);
        }

        $checkStmt->bind_param("ssss", $courseName, $lecturerName, $classYear, $resource);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        // Check if the count is greater than 0
        if ($count > 0) {
            $message = "<div class='alert alert-danger' role='alert'>A class with the same details already exists.</div>";
        } else {
            // Prepare the SQL query for insertion
            $stmt = $conn->prepare("INSERT INTO classes (`Course Name`, `Lecturer Name`, `Class Year`, `Resource`, `Start Time`, `End Time`, `Added At`) VALUES (?, ?, ?, ?, ?, ?, NOW())");

            if ($stmt === false) {
                die("Error preparing the insert statement: " . $conn->error);
            }

            // Bind parameters
            $stmt->bind_param("ssssss", $courseName, $lecturerName, $classYear, $resource, $startTime, $endTime);

            // Execute the query and handle possible errors
            if ($stmt->execute()) {
                $message = "<div class='alert alert-success' role='alert'>Class added successfully!</div>";
            } else {
                // Specific error message for duplicate entry
                if ($conn->errno == 1062) { // Duplicate entry error code
                    $message = "<div class='alert alert-danger' role='alert'>A class with the same details already exists.</div>";
                } else {
                    $message = "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
                }
            }

            $stmt->close();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Class</title>
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

        .dashboard-content {
            margin-left: 0; /* Ensure there's no extra margin on the left */
            padding: 20px; /* Add padding around the content, but remove padding-top */
            padding-top: 0; /* Remove the space above the content */
        }

        .dashboard-content h2 {
            text-align: center; /* Center the header text */
            margin-bottom: 0; /* Adjust the space below the header */
            margin-top: 10px;
            color: black; /* Optional: Set a color for the header */
            padding: 0; /* Ensure no extra padding around the header */
        }

        .addclassform {
            display: flex;
            justify-content: center; /* Center form horizontally */
            margin-top: 0; /* Adjust space above the form container as needed */
            padding: 20px; /* Optional: Add padding around the form container */
        }

        /* Form styling */
        form {
            max-width: 600px;
            width: 100%; /* Ensure the form takes the full width of its container */
            padding: 20px;
            background: #f8f9fa; /* Optional: Add a background color */
            border-radius: 8px; /* Optional: Add rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Optional: Add a shadow effect */
            margin-bottom: 20px; /* Adjust space below the form as needed */
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .message-container {
            margin-top: 20px; /* Space between header and messages */
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
                <a class="nav-link" href="usermanagement.php">User management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>

    <div class="dashboard-content">
        <div class="container">
            <h2>Add Class</h2>
            <?php if ($message): ?>
                <div class="message-container">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="addclassform">
                <form method="post">
                    <div class="mb-3">
                        <label for="courseName" class="form-label">Course Name</label>
                        <input type="text" class="form-control" id="courseName" name="courseName" required>
                    </div>
                    <div class="mb-3">
                        <label for="lecturerName" class="form-label">Lecturer Name</label>
                        <input type="text" class="form-control" id="lecturerName" name="lecturerName" required>
                    </div>
                    <div class="mb-3">
                        <label for="classYear" class="form-label">Class Year</label>
                        <input type="text" class="form-control" id="classYear" name="classYear" required>
                    </div>
                    <div class="mb-3">
                        <label for="resource" class="form-label">Resource</label>
                        <input type="text" class="form-control" id="resource" name="resource" required>
                    </div>
                    <div class="mb-3">
                        <label for="startTime" class="form-label">Start Time</label>
                        <input type="time" class="form-control" id="startTime" name="startTime" required>
                    </div>
                    <div class="mb-3">
                        <label for="endTime" class="form-label">End Time</label>
                        <input type="time" class="form-control" id="endTime" name="endTime" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Class</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
