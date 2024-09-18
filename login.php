<?php
include("connection.php");

session_start(); // Start the session

// Initialize message variables
$message = '';
$message_class = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $remember = isset($_POST['remember-me']); // Check if "Remember Me" is checked

    // Prepare and execute the query to find the user by email
    $stmt = $conn->prepare("SELECT Email, Password, role, `First Name` FROM users WHERE Email = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($db_email, $db_password, $db_role, $db_firstname);
        $stmt->fetch();

        // Compare the entered password directly with the stored password
        if ($password === $db_password) {
            // Set session variables
            $_SESSION['firstname'] = $db_firstname; // Corrected
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $db_role; // Store role in session

            // If "Remember Me" is checked, set a cookie for 1 day
            if ($remember) {
                setcookie('email', $email, time() + 86400, "/", "", true, true); // Secure and HttpOnly flags
            } else {
                // If "Remember Me" is not checked, clear any existing cookies
                setcookie('email', '', time() - 3600, "/", "", true, true);
            }

            // Set success message and class
            $message = "Login successful! Redirecting...";
            $message_class = "success";

            // Redirect to the management page
            header("Location: management.php");
            exit();
        } else {
            // Set error message and class
            $message = "Invalid password.";
            $message_class = "danger";
        }
    } else {
        // Set error message and class
        $message = "No account found with that email address.";
        $message_class = "danger";
    }

    $stmt->close();
    mysqli_close($conn);
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
