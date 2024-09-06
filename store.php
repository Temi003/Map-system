<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Sidebar</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        #sidebar {
            background-color: rgb(1, 1, 31);
            width: 250px;
            height: 100vh; /* Full height of viewport */
            position: fixed;
            top: 0;
            left: 0; /* Show sidebar */
            overflow-y: auto; /* Enable vertical scrolling */
            overflow-x: hidden; /* Hide horizontal scrollbar if any */
            -ms-overflow-style: none; /* IE and Edge */
            scrollbar-width: none; /* Firefox */
        }

        #sidebar::-webkit-scrollbar {
            display: none; /* Chrome, Safari, and Opera */
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .sidebar-content {
            height: 2000px; /* Enough height to enable scrolling */
            color: white;
            padding: 10px;
        }
    </style>
</head>
<body>
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
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>


    <div class="content">
        <h1>Main Content</h1>
        <p>This is the main content area.</p>
    </div>
</body>
</html>
