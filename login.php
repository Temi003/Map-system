<?php
// Start the session
session_start();

// Include the database connection file
include("connection.php");

// Initialize error message
$error = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve email and password from form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the query to find the user by email
    $stmt = $conn->prepare("SELECT `First Name`, Password FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($db_firstname, $db_password);

    if ($stmt->num_rows === 1) {
        $stmt->fetch();

        // Compare the entered password directly with the stored password
        if ($password === $db_password) {
            // Set session variables
            $_SESSION['user_email'] = $email;
            $_SESSION['firstname'] = $db_firstname; // Store first name in session

            // Redirect to a default page or user dashboard
            header("Location: management.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "No account found with that email.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="Images/ULK logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            margin: 1% auto;
            height: auto;
            max-width: 400px;
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
            position: absolute;
            top: 110px;
            left: 20px;
        }
        button[type="submit"] {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #0099cc;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
        button[type="submit"]:hover {
            background-color: #007399;
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
    
    <a href="menu.html" class="back-button">
        <i class="fa-solid fa-arrow-left" style="color: #ffffff;"></i>
    </a>
    
    <div class="login-container">
        <h2>User Login</h2>

        <!-- Display message if any -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="login">Login</button>

            <div class="remember-me" style="margin-top: 3px;">
                <input type="checkbox" id="remember-me" name="remember-me">
                <label for="remember-me">Remember me</label>
            </div>
            <div style="margin-top: 10px;">
                <a href="forgetpassword.php">Forgot password?</a>
            </div>
            <div style="margin-top: 10px;">
                New Student? <a href="signup.php">Sign up here.</a>
            </div>
        </form>
    </div>
</body>
</html>
