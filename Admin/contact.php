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

.group {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 0; /* Removed margin */
    height: calc(100vh - 16vh); /* Full height minus the header height */
    margin: 5px auto;
}

.contact,
.map-side {
    flex: 1 1 300px;
    max-width: 600px;
    padding: 10px;
    height: 100% auto; /* Ensure these sections take full height of .group */
    box-sizing: border-box; /* Include padding in the height calculation */
}

.contact-container {
    background-color: whitesmoke;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    display: flex;
    flex-direction: column;
    justify-content: flex-start; /* Aligns items at the start */
    height: 100% auto; /* Ensure the container takes full height */
}

.contact-h2 {
    margin-bottom: 0;
    color: #343a40;
    text-align: center;
    margin-top: 1px;
}

.message p {
    margin-bottom: 20px;
    text-align: center;
    padding: 10px;
    border-radius: 5px;
    font-weight: bold;
}

.contact-form {
    display: flex;
    flex-direction: column;
    flex: 1; /* Allow form to expand */
    gap: 15px; /* Space between form elements */
}

.contact-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #343a40;
}

.contact-form input, 
.contact-form textarea {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
    box-sizing: border-box; /* Include padding in the width calculation */
}

.contact-form textarea {
    resize: vertical; /* Allows vertical resizing */
}

.contact-form input:focus, 
.contact-form textarea:focus {
    outline: none;
    border-color: #007bff;
}

.name-fields {
    display: flex;
    gap: 10px;
}

.name-field {
    flex: 1;
}

.contact-form button {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s ease-in-out;
    margin: 10px auto;
}

.contact-form button:hover {
    background-color: #0056b3;
}

.map-side {
    background-color: white;
    height: 100% auto; /* Ensure the map section takes full height */
    display: flex;
    align-items: center;
    justify-content: center;
}

iframe {
    width: 100%;
    height: 100%;
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

/* Responsive Styles */
@media (max-width: 768px) {
    .group {
        flex-direction: column;
        align-items: center;
    }

    .contact,
    .map-side {
        flex: 1 1 100%; /* Stack vertically on small screens */
        padding: 0; /* Remove padding on smaller screens */
    }
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
    <div class="group">
        <div class="contact">
            <div class="contact-container">
                <h2 class="contact-h2">Contact Us</h2><br>
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
                    <textarea id="message" name="message" rows="1" required></textarea>
                    
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
