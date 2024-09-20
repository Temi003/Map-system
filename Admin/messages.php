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

        .table tbody tr {
            background-color: rgb(245, 245, 245); /* Row background color */
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
                <a class="nav-link" href="register.php"> Student Registration</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="addclass.php">Add Class</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="addresource.php">Add resource</a>
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
                <a class="nav-link" href="adminnotify.php">Notifications</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="usermanagement.php">User management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>

    
    </div>
    <div class="dashboard-content">
    <h2>Recent Messages</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'connection.php'; // Include the database connection

            // Fetch recent messages from the database
            $sql = "SELECT `First Name`, `Last Name`, `Email`, `Message` FROM `contact us`";
            $result = $conn->query($sql);

            if (!$result) {
                die("Error: " . $conn->error);
            }

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['First Name']}</td>
                            <td>{$row['Last Name']}</td>
                            <td>{$row['Email']}</td>
                            <td>{$row['Message']}</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No recent messages</td></tr>"; 
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</div>



</body>
</html>