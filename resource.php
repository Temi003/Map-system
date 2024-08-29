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
    <div class="dashboard-content">
        <h2>Avalibility Of Resources</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Lecturer Name</th>
                    <th>Class Year</th>
                    <th>Added At</th>
                    <th>Resource</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'connection.php'; // Include the database connection

                // Fetch recent activities from the database
                $sql = "SELECT * FROM classes ORDER BY `Added At` DESC";
                $result = $conn->query($sql);

                if (!$result) {
                    die("Error: " . $conn->error);
                }
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['Course Name']}</td>
                                <td>{$row['Lecturer Name']}</td>
                                <td>{$row['Class Year']}</td>
                                <td>{$row['Added At']}</td>
                                <td>{$row['Resource']}</td>
                                <td>{$row['Start Time']}</td>
                                <td>{$row['End Time']}</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No recent activities</td></tr>"; 
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>