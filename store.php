<?php
require 'connection.php'; // Ensure this file has the correct database connection parameters

// Fetch data from users table
$sql_users = "SELECT `First Name`, `Last Name`, `Email`, `Role` FROM users";
$result_users = $conn->query($sql_users);

// Fetch data from employees table
$sql_employees = "SELECT `First Name`, `Last Name`, `Email`, `Role` FROM employees";
$result_employees = $conn->query($sql_employees);

// Combine data into one array
$combined_data = [];

if ($result_users->num_rows > 0) {
    while ($row = $result_users->fetch_assoc()) {
        $combined_data[] = $row;
    }
}

if ($result_employees->num_rows > 0) {
    while ($row = $result_employees->fetch_assoc()) {
        $combined_data[] = $row;
    }
}

// Close the initial database connection
$conn->close();

// Reopen the database connection for bookings data
$conn = new mysqli("localhost", "root", "", "map");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch used resources
$sql_used_resources = "SELECT COUNT(DISTINCT resource) as used_resources
                       FROM bookings
                       WHERE `End time` >= NOW()";

$result_used_resources = $conn->query($sql_used_resources);

if (!$result_used_resources) {
    die("Query failed: " . $conn->error);
}

$used_resources_data = $result_used_resources->fetch_assoc();
$used_resources = $used_resources_data['used_resources'];

// Fetch total resources
$sql_total_resources = "SELECT COUNT(*) as total_resources FROM resources";
$result_total_resources = $conn->query($sql_total_resources);

if (!$result_total_resources) {
    die("Query failed: " . $conn->error);
}

$total_resources_data = $result_total_resources->fetch_assoc();
$total_resources = $total_resources_data['total_resources'];

// Fetch unused resources
$sql_unused_resources = "SELECT COUNT(*) as unused_resources
                         FROM resources
                         WHERE id NOT IN (
                             SELECT DISTINCT resource
                             FROM bookings
                             WHERE `End time` >= NOW()
                         )";

$result_unused_resources = $conn->query($sql_unused_resources);

if (!$result_unused_resources) {
    die("Query failed: " . $conn->error);
}

$unused_resources_data = $result_unused_resources->fetch_assoc();
$unused_resources = $unused_resources_data['unused_resources'];

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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

        .table tbody tr {
            background-color: rgb(245, 245, 245); /* Row background color */
            width: 100%;
        }
        .table td, .table th {
            padding: 12px; /* Add padding for better appearance */
            text-align: left;
        }
        .dashboard-content {
            display: flex;
            flex-direction: column;
            align-items: center; /* Center horizontally */
            justify-content: flex-start; /* Align content at the top of the remaining space */
            height: calc(100vh - 14vh); /* Full height minus the header height */
            padding-top: 0; /* Space between the header and the content */
            margin-left: 0;
        }

        .dashboard-content h2 {
            text-align: center;
            margin-bottom: 10px; /* Space between heading and form */
            margin-top: 5px;
            color: black;
        }

        .addclassform {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        form {
            max-width: 600px;
            width: 100%;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .message-container {
            margin-top: 20px; /* Space between header and messages */
        }

/* Button styling */
.btn {
    display: inline-block;
    padding: 0.5rem 1rem; /* Button size */
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1.5;
    border-radius: 0.25rem;
    text-align: center;
    cursor: pointer;
    border: 1px solid transparent;
    text-decoration: none; /* Removes underline from links */
    color: #fff; /* Text color */
    margin: 0; /* Ensures no extra margin */
    box-sizing: border-box; /* Ensures padding and border are included in the element's total width and height */
    width: 100%; /* Forces the button to take up full width of the container */
    transition: 0.8s;
}

/* Blue button style */
.btn-blue {
    background-color: #007bff; /* Blue background color */
    border-color: #007bff; /* Blue border color */
    margin-bottom: 2px;
}

/* Red button style */
.btn-red {
    background-color: #dc3545; /* Red background color */
    border-color: #dc3545; /* Red border color */
    margin-top: 2px;
}

/* Hover States */
.btn-blue:hover {
    background-color: #0056b3; /* Lighter blue on hover */
    border-color: #0056b3; /* Lighter blue border on hover */
}

.btn-red:hover {
    background-color: #c82333; /* Lighter red on hover */
    border-color: #c82333; /* Lighter red border on hover */
}

/* Focus and Active States */
.btn:focus, .btn:active {
    outline: none; /* Remove default focus outline */
}

/* Ensure no underline on hover */
.btn-blue:hover, .btn-red:hover {
    text-decoration: none; /* Ensure no underline on hover */
}

/* Message styling */
.message {
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.message.success {
    background-color: #d4edda;
    color: #155724;
}

.message.error {
    background-color: #f8d7da;
    color: #721c24;
}

/* Chart container */
.chart-container {
    margin-top: 50px;
    width: 50%;
    height: 80vh;
    margin: auto;
    display: none; /* Initially hidden */
}

.legend {
    list-style: none;
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.legend li {
    margin: 0 10px;
}

.legend span {
    display: inline-block;
    width: 20px;
    height: 20px;
    margin-right: 5px;
}

/* Report section */
.report-section {
    display: none; /* Hide report section initially */
}

.generate-btn {
    margin-top: 20px;
}

/* Shorten button size */
.btn-short {
    padding: 10px 20px; /* Adjust padding */
    font-size: 15px; /* Font size */
    width: auto; /* Adjust width to fit content */
    margin: 0 10px; /* Space between buttons */
    display: flex;
    justify-content: center;
}

/* Buttons initially hidden */
#printChartBtn, #downloadChartBtn {
    display: none; /* Hide by default */
}

/* Only show buttons when chart-container is visible */
#chart-container:target ~ #printChartBtn,
#chart-container:target ~ #downloadChartBtn {
    display: inline-block; /* Show buttons when chart-container is visible */
}

/* Print CSS */
@media print {
    body * {
        visibility: hidden;
    }

    #chart-container, #ledger-container, #report-section {
        visibility: visible;
        position: absolute;
        left: 0;
    }

    #chart-container {
        top: 0;
    }

    #ledger-container {
        top: 300px; /* Adjust this value depending on your layout */
    }

    #report-section {
        top: 600px; /* Adjust this value depending on your layout */
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

    <!-- Sidebar -->
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
                <a class="nav-link" href="usermanagement.php">User management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>

        <div class="container">
            <h1>User and Employee Management</h1>
            <div class="table-container">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($combined_data as $data): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($data['First Name']); ?></td>
                            <td><?php echo htmlspecialchars($data['Last Name']); ?></td>
                            <td><?php echo htmlspecialchars($data['Email']); ?></td>
                            <td><?php echo htmlspecialchars($data['Role']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button class="btn btn-blue generate-btn" id="generateReportBtn">Generate Report</button>
            <!-- Buttons to print and download -->

        <!-- Chart and Report section -->
        <div class="chart-container" id="chart-container">
            <canvas id="resourceChart"></canvas>
            <ul class="legend">
                <li><span style="background-color: #007bff;"></span> Used Resources</li>
                <li><span style="background-color: #dc3545;"></span> Unused Resources</li>
            </ul>
        </div>
        
        <div class="report-section" id="report-section">
            <h3>Resource Usage Report</h3>
            <p>Total Resources: <?php echo $total_resources; ?></p>
            <p>Used Resources: <?php echo $used_resources; ?></p>
            <p>Unused Resources: <?php echo $unused_resources; ?></p>
        </div>
    </div>
    <!-- Script imports -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>

    <script>
        document.getElementById('generateReportBtn').addEventListener('click', function() {
    // Show the chart and report section
    document.getElementById('chart-container').style.display = 'block';
    document.getElementById('report-section').style.display = 'block';
    document.getElementById('printChartBtn').style.display = 'inline-block';
    document.getElementById('downloadChartBtn').style.display = 'inline-block';

    // Check if Chart.js is loaded
    if (typeof Chart !== 'undefined') {
        var ctx = document.getElementById('resourceChart').getContext('2d');

        // Ensure the canvas context is valid
        if (ctx) {
            var chart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Used Resources', 'Unused Resources'],
                    datasets: [{
                        data: [<?php echo $used_resources; ?>, <?php echo $unused_resources; ?>],
                        backgroundColor: ['#007bff', '#dc3545']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        } else {
            console.error('Canvas context not found.');
        }
    } else {
        console.error('Chart.js library is not loaded.');
    }
});



    </script>
</body>
</html>