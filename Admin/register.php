<?php
include("connection.php");

$message = '';
$message_class = '';

if (isset($_POST['submit'])) {
    // Get form data
    $fname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $class = mysqli_real_escape_string($conn, $_POST['class']);

    // Check for existing student by first name, last name, and email
    $check_duplicate_sql = "SELECT * FROM school WHERE `First Name` = '$fname' AND `Last Name` = '$lname' AND Email = '$email'";
    $result = mysqli_query($conn, $check_duplicate_sql);

    if (mysqli_num_rows($result) > 0) {
        $message = "This student is already registered.";
        $message_class = 'error';
    } else {
        // SQL query to insert data into the 'school' table
        $sql = "INSERT INTO school (`First Name`, `Last Name`, Email, Class) VALUES ('$fname', '$lname', '$email', '$class')";

        if (mysqli_query($conn, $sql)) {
            $message = "Registration successful.";
            $message_class = 'success';
        } else {
            $message = "Error: " . mysqli_error($conn);
            $message_class = 'error';
        }
    }
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ULK Campus Map</title>
    <link rel="icon" href="Images/ULK logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        
        .registration-container {
            width: 100%;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: whitesmoke;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            margin-top: 10px;
        }

        .registration-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        /* Sidebar styling */
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
    </style>
</head>
<body>
    <div class="menu">
        <div class="logo">
            <img src="images/ulk logo 2.png" alt="ULK Logo">
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
            <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="register.php"> Student Registration</a></li>
            <li class="nav-item"><a class="nav-link" href="addclass.php">Add Class</a></li>
            <li class="nav-item"><a class="nav-link" href="addresource.php">Add Resource</a></li>
            <li class="nav-item"><a class="nav-link" href="manageclass.php">Manage Classes</a></li>
            <li class="nav-item"><a class="nav-link" href="messages.php">Messages</a></li>
            <li class="nav-item"><a class="nav-link" href="managemessage.php">Manage Message</a></li>
            <li class="nav-item"><a class="nav-link" href="updateresource.php">Update Availability</a></li>
            <li class="nav-item"><a class="nav-link" href="ticket.php">Tickets</a></li>
            <li class="nav-item"><a class="nav-link" href="adminnotify.php">Notifications</a></li>
            <li class="nav-item"><a class="nav-link" href="usermanagement.php">User Management</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="registration-container">
        <h2>Student Registration</h2>
        <div class="message">
            <?php if ($message != ''): ?>
                <p class="alert <?php echo ($message_class == 'success') ? 'alert-success' : 'alert-danger'; ?>">
                    <?php echo $message; ?>
                </p>
            <?php endif; ?>
        </div>

        <form action="" method="post">
            <div class="mb-3">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" required>
            </div>
            <div class="mb-3">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" required>
            </div>
            <div class="mb-3">
                <label for="class" class="form-label">Class</label>
                <input type="text" class="form-control" id="class" name="class" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</body>
</html>
