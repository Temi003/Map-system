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

        /* Form styling */
        form {
            max-width: 600px; /* Limit the maximum width of the form */
            width: 100%; /* Ensure the form takes the full width of its container */
            padding: 20px;
            background: #f8f9fa; /* Background color */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Shadow effect */
            margin: 1% auto; /* Center the form horizontally */
        }

        /* Label styling */
        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        form h1 {
            text-align: center; /* Center text horizontally */
            margin-bottom: 20px; /* Space below the heading */
            margin-top: 0; /* Remove space above the heading */
        }

        /* Input fields styling */
        form input[type="email"],
        form input[type="submit"] {
            width: calc(100% - 22px); /* Full width minus padding */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 16px;
        }

        /* Submit button styling */
        form input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Optional: Additional styles for consistency */
        form h2 {
            text-align: center; /* Center the header text */
            margin-bottom: 20px; /* Space below the header */
            color: black; /* Set a color for the header */
        }

        /* Error message box */
        .error-box {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
        }

        /* Info message box */
        .info-box {
            background-color: #00008B;
            color: white;
            border: 1px solid #bee5eb;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
        }

        /* Print and Download button styling */
        #printButton, #downloadButton {
            display: none;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 14px;
            width: auto; /* Adjust width to fit content */
            height: 40px; /* Ensure both buttons have the same height */
        }

        /* Show both buttons and position them */
        .table-container.print-ready #printButton,
        .table-container.print-ready #downloadButton {
            display: inline-block;
            margin-left: 10px; /* Add spacing between buttons */
        }

        .table-container .button-container {
            display: flex;
            justify-content: flex-start; /* Align buttons to the left or right */
            margin-bottom: 20px; /* Space below the buttons */
        }

        /* Optional: Additional styles for consistency */
        .hide-when-printing {
            display: none;
        }

        /* Print styling */
        @media print {
            #printButton, #downloadButton {
                display: none; /* Hide buttons when printing */
            }
            body * {
                visibility: hidden; /* Hide all content */
            }
            .table-container, .table-container * {
                visibility: visible; /* Show only table content */
            }
            .table-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%; /* Ensure the table takes full width */
            }
        }