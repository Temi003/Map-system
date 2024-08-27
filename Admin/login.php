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
    $stmt = $conn->prepare("SELECT Email, Password FROM employees WHERE Email = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($db_email, $db_password);
        $stmt->fetch();

        // Compare the entered password directly with the stored password
        if ($password === $db_password) {
            // Set session variables
            $_SESSION['email'] = $email;

            // If "Remember Me" is checked, set a cookie for 1 day
            if ($remember) {
                setcookie('email', $email, time() + 86400, "/", "", true, true); // Secure and HttpOnly flags
                setcookie('password', $password, time() + 86400, "/", "", true, true); // Secure and HttpOnly flags
            }

            // Set success message and class
            $message = "Login successful! Redirecting...";
            $message_class = "success";

            // Prevent caching
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

            header("refresh:2; url=dashboard.php");
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
</head>
<body>
    
    <a href="menu.html" class="back-button">
    <i class="fa-solid fa-arrow-left" style="color: #ffffff;"></i>
    </a>
    
    <div class="login-container">
        <h2 style="margin-top: 8px;">Login</h2>

        <!-- Display message if any -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo htmlspecialchars($message_class); ?>" role="alert">
                <?php echo htmlspecialchars($message); ?>
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

            <div class="remember-me" style="margin-top: 20px;">
                <input type="checkbox" id="remember-me" name="remember-me">
                <label for="remember-me">Remember me</label>
            </div>
            <div style="margin-top: 20px;">
                <a href="forgetpassword.php">Forgot password?</a>
            </div>
        </form>
    </div>
    <script src="main.js"></script>
</body>
</html>
