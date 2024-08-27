<?php
session_start();
require 'connection.php'; // Database connection

// Initialize message variables
$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $courseName = $_POST['courseName'];
        $lecturerName = $_POST['lecturerName'];

        // Prepare the SQL query for deletion
        $stmt = $conn->prepare("DELETE FROM classes WHERE `Course Name`=? AND `Lecturer Name`=?");
        if ($stmt === false) {
            die("Error preparing the statement: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("ss", $courseName, $lecturerName);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success' role='alert'>Class deleted successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}

// Fetch classes from database
$classes = $conn->query("SELECT `Course Name`, `Lecturer Name`, `Class Year`, `Resource`, `Start Time`, `End Time` FROM classes");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes</title>
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

        #sidebar .nav-link.active {
            font-weight: bold;
            color: white;
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
            background: red;
            margin: 5px 0;
            transition: 0.3s;
        }

        #menu-toggle:checked ~ .hamburger-icon span:nth-child(1) {
            transform: rotate(-45deg) translate(-5px, 6px);
        }

        #menu-toggle:checked ~ .hamburger-icon span:nth-child(2) {
            opacity: 0;
        }

        #menu-toggle:checked ~ .hamburger-icon span:nth-child(3) {
            transform: rotate(45deg) translate(-5px, -6px);
        }

        .table-container {
            margin-top: 20px;
        }

        .table-container table {
            width: 100%;
            margin-top: 20px;
        }

        .alert {
            margin-top: 20px;
        }

        .update-form {
            margin-top: 20px;
        }

        table {
            width: 100%;
            margin-bottom: 20px;
        }
        th, td {
            text-align: left;
            padding: 8px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .update {
            width: 10px;
            padding: 10px;
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
                <a class="nav-link active" href="manageclass.php">Manage Classes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>

    <div class="dashboard-content">
        <h1>Manage Classes</h1>
        <?php if ($message): ?>
            <div class="alert-container">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Lecturer Name</th>
                        <th>Class Year</th>
                        <th>Resource</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $classes->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Course Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Lecturer Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Class Year']); ?></td>
                        <td><?php echo htmlspecialchars($row['Resource']); ?></td>
                        <td><?php echo htmlspecialchars($row['Start Time']); ?></td>
                        <td><?php echo htmlspecialchars($row['End Time']); ?></td>
                        <td>
                            <!-- Update Button -->
                            <a href="updateclass.php?courseName=<?php echo urlencode($row['Course Name']); ?>&lecturerName=<?php echo urlencode($row['Lecturer Name']); ?>" class="update">Update</a>
                            
                            <!-- Delete Button -->
                            <form method="POST" action="manageclass.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this class?');">
                                <input type="hidden" name="courseName" value="<?php echo htmlspecialchars($row['Course Name']); ?>">
                                <input type="hidden" name="lecturerName" value="<?php echo htmlspecialchars($row['Lecturer Name']); ?>">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
