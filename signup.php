<?php
include("connection.php");

$message = ''; // Initialize an empty message variable
$message_class = ''; // Initialize an empty message class variable

if (isset($_POST['submit'])) {
    // Get form data
    $fname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // SQL query to insert data into the 'users' table
    $sql = "INSERT INTO employees (`First Name`, `Last Name`, `Email`, `Password`,`Role` ) 
            VALUES ('$fname', '$lname', '$email', '$password', '$role')";

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
            $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
            $message_class = 'error'; // Add error class
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
        .signup-container{
            height: auto;
            margin: 20px auto;
        }
        #role {
        border: grey; /* Remove the black border */
        background-color: white; /* Optional: adjust background color if needed */
        padding: 10px; /* Adjust padding if necessary */
        box-sizing: border-box; /* Include padding in width/height calculations */
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
        <h2>Admin Sign Up</h2>

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
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your Email" required>
                </div>
            </div>
                <div class="input-group">
        <label for="role">Role</label>
        <select id="role" name="role" required>
        <option value=""></option>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
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