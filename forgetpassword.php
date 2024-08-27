<?php
require 'connection.php'; // Include the connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Validate email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if email exists in database
        $stmt = $conn->prepare("SELECT `Roll Number` FROM users WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Email is valid. Redirecting...";
            $message_class = 'success'; 
            // Redirect to update password page with email as query parameter
            header("refresh:2; url=updatepassword.php?email=" . urlencode($email));
        } else {
            $message = "Invalid email";
            $message_class = 'error'; // Set error class
        }

        $stmt->close();
    } else {
        $message = "Invalid email format";
        $message_class = 'error'; // Set error class
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="icon" href="Images/ULK logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Add this style to center the container in the viewport */
        .centered-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            text-align: center;
            background-color: whitesmoke;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            width: 430px;
            margin-bottom: 140px;
        }
        .form-container h2 {
            color: black;
            margin-bottom: 20px;
        }
        .form-container label {
            margin-bottom: 10px;
            font-weight: bold;
            color: black;
        }
        .form-container input[type="email"] {
            padding: 10px;
            width: 100%;
            max-width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        .form-container input[type="submit"] {
            background-color: #0099cc;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-container .alert {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
            color: #fff; /* Text color for all alerts */
        }
        .form-container .alert.success {
            background-color: green; /* Green background for success */
        }
        .form-container .alert.error {
            background-color: red; /* Red background for error */
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
            <li><a href="contact.html">Contact Us</a></li>
            <li><a href="https://ulk.ac.rw/" target="_blank">School Website</a></li>
        </ul>
    </div>
    <a href="login.php" class="back-button">
    <i class="fa-solid fa-arrow-left" style="color: #ffffff;"></i>
    </a>
    <div class="centered-container">
        <div class="form-container">
            <h2>Forgot Password</h2>
            <form action="" method="post">
                <label for="email">Enter your email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <input type="submit" value="Submit">
            </form>
            <?php if (isset($message)): ?>
                <div class="alert <?php echo htmlspecialchars($message_class); ?>" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
