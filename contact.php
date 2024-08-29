<?php
include("connection.php");

// Initialize message variables
$message = ''; 
$message_class = ''; 

// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (isset($_POST['submit'])) {
    // Get form data and sanitize
    $fname = trim($_POST['firstname']);
    $lname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $cmessage = trim($_POST['message']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
        $message_class = 'error'; // Error class
    } elseif (empty($fname) || empty($lname) || empty($email) || empty($cmessage)) {
        $message = "All fields are required.";
        $message_class = 'error'; // Error class
    } else {
        // Prepare an SQL statement
        $stmt = $conn->prepare("INSERT INTO `contact us` (`First Name`, `Last Name`, `Email`, `Message`) VALUES (?, ?, ?, ?)");

        // Check if prepare() returned false
        if ($stmt === false) {
            $message = "Failed to prepare the SQL statement.";
            $message_class = 'error'; // Error class
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("ssss", $fname, $lname, $email, $cmessage);

        // Execute the statement
        if ($stmt->execute()) {
            $message = "Message Sent.";
            $message_class = 'success'; // Success class
        } else {
            // Check for duplicate entry error
            if ($stmt->errno == 1062) {
                $message = "Duplicate entry detected. Please use a different email.";
                $message_class = 'error'; // Error class
            } else {
                $message = "Error: " . $stmt->error;
                $message_class = 'error'; // Error class
            }
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the connection
$conn->close();
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
        .success { color: green; }
        .error { color: red; }
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
    <div class="group">
        <div class="contact">
            <div class="contact-container">
                <h2 class="contact-h2">Contact Us</h2>
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
                <form action="contact.php" class="contact-form" method="POST">
                    <div class="name-fields">
                        <div class="name-field">
                            <label for="firstname">First Name:</label>
                            <input type="text" id="firstname" name="firstname" required>
                        </div>
                        <div class="name-field">
                            <label for="lastname">Last Name:</label>
                            <input type="text" id="lastname" name="lastname" required>
                        </div>
                    </div>
                    
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                    
                    <button type="submit" name="submit">Submit</button>
                </form>
            </div>
        </div>
        <div class="map-side">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d510443.6816922997!2d29.324585731380836!3d-1.8038009616247952!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19dca3f75b7b8b91%3A0xc9ab953c704fa9d3!2sKigali%20Independent%20University!5e0!3m2!1sen!2srw!4v1723207146302!5m2!1sen!2srw" width="600" height="450" style="border:1;" ></iframe>
        </div>
    </div>
    <script src="main.js"></script>
</body>
</html>
