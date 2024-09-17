<?php
include("connection.php");

$message = ''; // Initialize an empty message variable
$message_class = ''; // Initialize an empty message class variable
if (isset($_POST['submit'])) {
    // Get form data
    $fname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $rnumber = mysqli_real_escape_string($conn, $_POST['rollnumber']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $class = mysqli_real_escape_string($conn, $_POST['class']);
    $course = isset($_POST['course']) ? mysqli_real_escape_string($conn, $_POST['course']) : ''; // Get the course value from the form, if available

    // Check if the student exists in the school table with all provided information
    $checkStudentQuery = "
        SELECT * 
        FROM school 
        WHERE `Roll Number` = '$rnumber' 
        AND `First Name` = '$fname'
        AND `Last Name` = '$lname'
        AND `class` = '$class'
        AND `Email` = '$email'
    ";

    $studentResult = mysqli_query($conn, $checkStudentQuery);

    if (!$studentResult) {
        // Query failed
        $message = "Error checking student records: " . mysqli_error($conn);
        $message_class = 'error'; // Add error class
    } elseif (mysqli_num_rows($studentResult) == 0) {
        // No matching student found in the school table
        $message = "The information you provided does not match the records in our system. Please check your details and try again.";
        $message_class = 'error'; // Add error class
    } else {
        // Check if email already exists in the users table
        $checkEmailQuery = "SELECT * FROM users WHERE `Email` = '$email'";
        $result = mysqli_query($conn, $checkEmailQuery);

        if (!$result) {
            // Query failed
            $message = "Error checking email records: " . mysqli_error($conn);
            $message_class = 'error'; // Add error class
        } elseif (mysqli_num_rows($result) > 0) {
            // Email already exists
            $message = "The email address is already in use. Please use a different email.";
            $message_class = 'error'; // Add error class
        } else {
            // SQL query to insert data into the 'users' table (no course field)
            $sql = "
                INSERT INTO users (`First Name`, `Last Name`, DOB, `Roll Number`, `Email`, Password, `class`, role) 
                VALUES ('$fname', '$lname', '$dob', '$rnumber', '$email', '$password', '$class', 'user')";

            // Execute the query
            if (mysqli_query($conn, $sql)) {
                $message = "New record created successfully.";
                $message_class = 'success'; // Add success class
                header("refresh:2; url=login.php"); // Redirect to login.php after 2 seconds
            } else {
                // Check for duplicate entry error
                if (mysqli_errno($conn) == 1062) { // 1062 is the MySQL error code for duplicate entry
                    $message = "Duplicate entry detected. Please use a different roll number.";
                    $message_class = 'error'; // Add error class
                } else {
                    $message = "Error: " . mysqli_error($conn);
                    $message_class = 'error'; // Add error class
                }
            }
        }
    }
}

// Close the connection
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
        /* Signup container specific styling */
  .signup-container {
    width: 700px; /* Width */
    height: auto; /* Height relative to viewport height */
  }
        .back-button {
            width: 50px;
            height: 50px;
            background-color: #0099cc;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
            line-height: 50px;
            text-decoration: none;
            color: white;
            font-size: 20px;
            position: absolute; /* Make sure it stays in position */
            top: 110px; /* Adjust as needed */
            left: 20px; /* Adjust as needed */
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
    <a href="login.php" class="back-button">
    <i class="fa-solid fa-arrow-left" style="color: #ffffff;"></i>
    </a>
    <div class="signup-container">
        <h2>User Sign Up</h2>

        <div class="message">
    <?php if ($message != ''): ?>
        <p style="
            color: <?php echo ($message_class == 'success') ? 'green' : 'red'; ?>;
            background-color: <?php echo ($message_class == 'success') ? '#e6ffe6' : '#ffe6e6'; ?>;
            border: 1px solid <?php echo ($message_class == 'success') ? 'green' : 'red'; ?>;
            font-size: 16px;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-top: 10px;">
            <?php echo $message; ?>
        </p>
    <?php endif; ?>
</div>


        <form action="" method="post">
                <div class="input-group">
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" name="firstname" placeholder="Enter your First name" required>
                </div>
                <div class="input-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" name="lastname" placeholder="Enter your Last name" required>
                </div>
            <div class="row">
                <div class="input-group">
                    <label for="dob">DOB</label>
                    <input type="date" id="dob" name="dob" required>
                </div>
                <div class="input-group">
                    <label for="roll-number">Roll Number</label>
                    <input type="text" id="roll-number" name="rollnumber" placeholder="Enter your Roll Number" required>
                </div>
            </div>
            <div class="input-group">
                    <label for="class">Class</label>
                    <select id="class" name="class" required>
                        <option value="" disabled selected>Select your class</option>
                        <option value="Year 1">Year 1</option>
                        <option value="Year 2">Year 2</option>
                        <option value="Year 3">Year 3</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your Email" required>
                </div>
                <div class="input-group">
                    <label for="new-password">Password</label>
                    <input type="password" id="new-password" name="password" placeholder="Enter your password" required>
                </div>
            <button type="submit" name="submit">Sign Up</button>
        </form>
    </div>
    <script src="main.js"></script>
</body>
</html>