<?php
session_start();
require 'connection.php'; // Database connection

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update class details
    $oldCourseName = $_POST['oldCourseName'];
    $oldLecturerName = $_POST['oldLecturerName'];
    $courseName = $_POST['courseName'];
    $lecturerName = $_POST['lecturerName'];
    $classYear = $_POST['classYear'];
    $classroom = $_POST['classroom'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];

    // Check if start time is before end time
    if ($startTime >= $endTime) {
        $message = "<div class='alert alert-danger' role='alert'>Error: Start time must be before end time.</div>";
    } else {
        // Check if the record to be updated exists
        $existsStmt = $conn->prepare("SELECT COUNT(*) FROM classes WHERE `Course Name` = ? AND `Lecturer Name` = ?");
        if ($existsStmt === false) {
            die("Error preparing the exists statement: " . $conn->error);
        }

        $existsStmt->bind_param("ss", $oldCourseName, $oldLecturerName);
        $existsStmt->execute();
        $existsStmt->bind_result($existsCount);
        $existsStmt->fetch();
        $existsStmt->close();

        if ($existsCount === 0) {
            $message = "<div class='alert alert-danger' role='alert'>Error: No matching class found to update.</div>";
        } else {
            // Check for duplicates in other rows
            $checkStmt = $conn->prepare("SELECT COUNT(*) FROM classes WHERE (`Course Name` = ? AND `Lecturer Name` = ? AND `Class Year` = ? AND `Class Room` = ?) AND NOT (`Course Name` = ? AND `Lecturer Name` = ? AND `Class Year` = ? AND `Class Room` = ?)");
            if ($checkStmt === false) {
                die("Error preparing the check statement: " . $conn->error);
            }

            $checkStmt->bind_param("ssssssss", $courseName, $lecturerName, $classYear, $classroom, $oldCourseName, $oldLecturerName, $oldClassYear, $oldClassroom);
            $checkStmt->execute();
            $checkStmt->bind_result($count);
            $checkStmt->fetch();
            $checkStmt->close();

            if ($count > 0) {
                $message = "<div class='alert alert-danger' role='alert'>Error: A class with the same Course Name, Lecturer Name, Class Year, or Class Room already exists.</div>";
            } else {
                // Prepare the SQL query for update
                $stmt = $conn->prepare("UPDATE classes SET `Course Name`=?, `Lecturer Name`=?, `Class Year`=?, `Class Room`=?, `Start Time`=?, `End Time`=? WHERE `Course Name`=? AND `Lecturer Name`=? AND `Class Year`=? AND `Class Room`=?");
                if ($stmt === false) {
                    die("Error preparing the update statement: " . $conn->error);
                }

                // Bind parameters
                $stmt->bind_param("ssssssssss", $courseName, $lecturerName, $classYear, $classroom, $startTime, $endTime, $oldCourseName, $oldLecturerName, $oldClassYear, $oldClassroom);

                if ($stmt->execute()) {
                    $message = "<div class='alert alert-success' role='alert'>Class updated successfully!</div>";
                } else {
                    $message = "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
                }

                $stmt->close();
            }
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
    <title>Update Class</title>
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
        .table tbody tr {
            background-color: rgb(245, 245, 245); /* Row background color */
            width: 100%;
        }
        .table td, .table th {
            padding: 12px; /* Add padding for better appearance */
            text-align: left;
        }
        .dashboard-content {
            display: flex;
            flex-direction: column;
            align-items: center; /* Center horizontally */
            justify-content: flex-start; /* Align content at the top of the remaining space */
            height: calc(100vh - 14vh); /* Full height minus the header height */
            padding-top: 0; /* Space between the header and the content */
            margin-left: 0;
        }

        .dashboard-content h2 {
            text-align: center;
            margin-bottom: 10px; /* Space between heading and form */
            margin-top: 5px;
            color: black;
        }

        .addclassform {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        form {
            max-width: 600px;
            width: 100%;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
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
            <li><a href="contact.html">Contact Us</a></li>
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
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>

    <div class="dashboard-content">
        <h2>Update Class</h2>
        <?php if (isset($message)) echo $message; ?>
        <form action="updateclass.php" method="POST">
            <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
            <input type="hidden" name="oldCourseName" value="<?php echo isset($_GET['courseName']) ? htmlspecialchars($_GET['courseName']) : ''; ?>">
            <input type="hidden" name="oldLecturerName" value="<?php echo isset($_GET['lecturerName']) ? htmlspecialchars($_GET['lecturerName']) : ''; ?>">

            <div class="mb-3">
                <label for="courseName" class="form-label">Course Name</label>
                <input type="text" class="form-control" id="courseName" name="courseName" value="<?php echo isset($_GET['courseName']) ? htmlspecialchars($_GET['courseName']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="lecturerName" class="form-label">Lecturer Name</label>
                <input type="text" class="form-control" id="lecturerName" name="lecturerName" value="<?php echo isset($_GET['lecturerName']) ? htmlspecialchars($_GET['lecturerName']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="classYear" class="form-label">Class Year</label>
                <input type="text" class="form-control" id="classYear" name="classYear" value="<?php echo isset($_GET['classYear']) ? htmlspecialchars($_GET['classYear']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="classroom" class="form-label">Class Room</label>
                <input type="text" class="form-control" id="classroom" name="classroom" value="<?php echo isset($_GET['classroom']) ? htmlspecialchars($_GET['classroom']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="startTime" class="form-label">Start Time</label>
                <input type="time" class="form-control" id="startTime" name="startTime" value="<?php echo isset($_GET['startTime']) ? htmlspecialchars($_GET['startTime']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="endTime" class="form-label">End Time</label>
                <input type="time" class="form-control" id="endTime" name="endTime" value="<?php echo isset($_GET['endTime']) ? htmlspecialchars($_GET['endTime']) : ''; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
