<?php
include("connection.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

session_start();

// Initialize message variables
$message = '';
$message_class = '';
$email_verified = false; // Flag to track email verification

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check for action type (login or verify OTP)
    if (isset($_POST['action']) && $_POST['action'] === 'verify_otp') {
        $input_otp = $_POST['otp'];
        if ($input_otp == $_SESSION['otp']) {
            // Successful login
            $_SESSION['firstname'] = $_SESSION['db_firstname']; // Use session variable
            $_SESSION['role'] = $_SESSION['db_role']; // Use session variable

            // If "Remember Me" is checked
            if (isset($_POST['remember-me'])) {
                setcookie('email', $_SESSION['email'], time() + 86400, "/", "", true, true);
            }

            // Redirect to the management page
            header("Location: management.php");
            exit();
        } else {
            $message = "Invalid OTP.";
            $message_class = "danger";
        }
    } else {
        // Handle login
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Prepare and execute the query to find the user by email
        $stmt = $conn->prepare("SELECT Email, Password, role, `First Name`, failed_attempts, lockout_time FROM users WHERE Email = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($db_email, $db_password, $db_role, $db_firstname, $failed_attempts, $lockout_time);
            $stmt->fetch();

            // Check if the account is locked
            if ($failed_attempts >= 5) {
                $current_time = new DateTime();
                $lockout_end = new DateTime($lockout_time);
                $lockout_end->modify('+5 minutes');

                if ($current_time < $lockout_end) {
                    $message = "Your account is locked. Try again later.";
                    $message_class = "danger";
                } else {
                    // Reset failed attempts if lockout period has passed
                    $failed_attempts = 0;
                    $lockout_time = null;
                }
            }

            // Compare the entered password
            if ($failed_attempts < 5) { // Only check password if not locked
                if ($password === $db_password) {
                    // Reset failed attempts and lockout time on successful login
                    $failed_attempts = 0;
                    $lockout_time = null;

                    // Generate a random OTP
                    $otp = rand(100000, 999999);
                    $_SESSION['otp'] = $otp; // Store OTP in session
                    $_SESSION['email'] = $email; // Store email in session
                    $_SESSION['db_firstname'] = $db_firstname; // Store firstname in session
                    $_SESSION['db_role'] = $db_role; // Store role in session

                    // Send OTP to user's email using PHPMailer
                    $mail = new PHPMailer(true);
                    try {
                        // Server settings
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'temidudu2003@gmail.com'; // Your Gmail address
                        $mail->Password   = 'ymkvadsdzdovxbwu'; // Your app password
                        $mail->SMTPSecure = 'tls';
                        $mail->Port       = 587;

                        // Recipients
                        $mail->setFrom('temidudu2003@gmail.com', 'Temidayo');
                        $mail->addAddress($email); // Add a recipient (user's email)

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'Your OTP Code';
                        $mail->Body    = "Your OTP code is: $otp";
                        $mail->AltBody = "Your OTP code is: $otp";

                        $mail->send();
                        $message = "An OTP has been sent to your email. Please enter it below.";
                        $message_class = "success";
                        $email_verified = true; // Mark email as verified
                    } catch (Exception $e) {
                        $message = "Email sending failed: {$mail->ErrorInfo}";
                        $message_class = "danger";
                    }

                    // Update failed attempts and lockout time in the database
                    $update_stmt = $conn->prepare("UPDATE users SET failed_attempts = ?, lockout_time = ? WHERE Email = ?");
                    $update_stmt->bind_param("iss", $failed_attempts, $lockout_time, $db_email);
                    $update_stmt->execute();
                    $update_stmt->close();
                } else {
                    // Increment failed attempts on incorrect password
                    $failed_attempts++;
                    $lockout_time = ($failed_attempts >= 5) ? date('Y-m-d H:i:s') : $lockout_time;

                    // Update failed attempts and lockout time in the database
                    $update_stmt = $conn->prepare("UPDATE users SET failed_attempts = ?, lockout_time = ? WHERE Email = ?");
                    $update_stmt->bind_param("iss", $failed_attempts, $lockout_time, $db_email);
                    $update_stmt->execute();
                    $update_stmt->close();

                    $message = "Invalid password.";
                    $message_class = "danger";
                }
            }
        } else {
            $message = "No account found with that email address.";
            $message_class = "danger";
        }

        $stmt->close();
        mysqli_close($conn);
    }
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
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $message_class; ?>" role="alert">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="post">
    <div class="input-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
    </div>
    <div class="input-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>" required>
    </div>
    
    <?php if ($email_verified): // Show OTP form if email is verified ?>
        <div class="input-group">
            <label for="otp">Enter OTP</label>
            <input type="text" id="otp" name="otp" placeholder="Enter your OTP" required>
        </div>
        <input type="hidden" name="action" value="verify_otp"> <!-- Hidden input for OTP verification -->
    <?php else: ?>
        <input type="hidden" name="action" value="login"> <!-- Hidden input for login -->
    <?php endif; ?>

    <button type="submit"><?php echo $email_verified ? 'Verify OTP' : 'Login'; ?></button>

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
