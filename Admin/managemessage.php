<?php
// Include database connection
include 'connection.php';

// Initialize message variable
$message = '';

// Handle deletion request
if (isset($_POST['delete'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // SQL query to delete a message
    $sql = "DELETE FROM `contact us` WHERE `Email` = '$email'";

    if ($conn->query($sql) === TRUE) {
        $message = "Message deleted successfully.";
    } else {
        $message = "Error deleting message: " . $conn->error;
    }

    $conn->close();
    header("Location: managemessage.php"); // Redirect to refresh the page
    exit();
}

// Fetch contact messages for display
$sql = "SELECT `First Name`, `Last Name`, `Email`, `Message` FROM `contact us`";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching messages: " . $conn->error);
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Class</title>
    <link rel="icon" href="Images/ULK logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
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


        .dashboard-content {
            margin-left: 0; /* Adjust for sidebar width */
            padding: 20px;
            padding-top: 14vh; /* Adjust for header height */
            transition: margin-left 0.3s ease;
        }

        .table tbody tr {
            background-color: whitesmoke; /* Row background color */
            width: 100%;
        }
        .table td, .table th {
            padding: 12px; /* Add padding for better appearance */
            text-align: left;
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

    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" id="menu-icon" class="hamburger-icon">
        <span></span>
        <span></span>
        <span></span>
    </label>

    <div id="sidebar">
        <h2>Admin Dashboard</h2>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="addclass.php">Add Class</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manageclass.php">Manage Classes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="messages.php">Messages</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="managemessage.php">Manage Message</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="updateresource.php">Update Availability</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="ticket.php">Tickets</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>

    <div class="dashboard-content">
    <h1>Contact Us Messages</h1>
    <?php if ($message): ?>
        <div class="alert-container">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Include database connection
                include 'connection.php';

                // Fetch contact messages from the database
                $sql = "SELECT `First Name`, `Last Name`, `Email`, `Message` FROM `contact us`";
                $result = $conn->query($sql);

                if (!$result) {
                    die("Error: " . $conn->error);
                }

                while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['First Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['Last Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['Email']); ?></td>
                    <td><?php echo htmlspecialchars($row['Message']); ?></td>
                    <td>
                        <!-- Delete Button -->
                        <form method="POST" action="managemessage.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this message?');">
                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($row['Email']); ?>">
                            <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>




</body>
</html>
