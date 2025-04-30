<?php
    include 'weblayout/topmenu.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User end</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: 100%; /* Ensure the body takes the full height of the viewport */
            display: flex;
            flex-direction: column; /* Make the body a flex container */
        }
        body {
            font-family: Arial, sans-serif;
        }
        #header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        #header > div {
            margin-left: 100px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        #header h1 {
            margin: 0;
        }
        #header > nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 50px;
            margin-bottom: 20px;
        }
        #header > nav > ul {
            margin-left: 50px;
            margin-right: 100px;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
        }
        li > input {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-right: 10px;
        }
        ul > button {
            padding: 5px 10px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        nav ul li {
            display: inline;
            margin-right: 20px;
            border: 1px solid white;
            padding: 5px 10px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
        }
        #topmenu {
            display: flex;
            justify-content: center;
            background-color:rgb(240, 234, 39);
            padding: 10px 20px;
        }
        #topmenu ul {
            display: flex;
            list-style-type: none;
            flex-direction: row;
            gap: 15px;
            align-items: center;
            margin-left: 100px;
        }
        #main-content {
            display: flex;
            margin-top: 0px;
            flex: 1; /* Allow the main content to grow and take available space */
        }
        #sidebar {
            position: absolute;
            top: 0;
            left: 0;
            width: 20%;
            height: 100%;
            padding: 20px;
            background-color: #f4f4f4;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: transform 0.3s ease;
            transform: translateX(-100%); /* Initially hidden */
        }
        #sidebar.visible {
            transform: translateX(0); /* Slide in when visible */
        }
        #sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        #sidebar ul {
            padding: 0;
            margin: 0;
        }
        #sidebar>ul{
            list-style-type: none;
            text-align: center;
        }
        #sidebar>ul>li{
            margin-bottom: 16px;
        }
        #sidebar ul li button {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        #sidebar ul li button:hover {
            background-color: #555;
        }
        #sidebar ul li button:active {
            background-color: #777;
        }
        #content-area {
            position: relative;
            width: 100%; /* Ensure full width when sidebar is hidden */
            padding: 20px;
            background-color: #fff;
            overflow-y: auto; /* Allows scrolling if content overflows */
            height: calc(100vh - 200px); /* Adjust height to fit the viewport */
        }
        #item-lists {
            width: 80%;
            padding: 20px;
            display: flex;
            flex-wrap: wrap; /* Allows wrapping to the next row */
            gap: 20px; /* Adds spacing between items */
        }
        .item {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
            flex: 1 1 calc(20% - 20px); /* Ensures 5 items per row */
            box-sizing: border-box;
        }
        .item img {
            width: 100%; /* Makes the image responsive */
            height: auto; /* Maintains aspect ratio */
            max-width: 100%;
            height: auto;
            display: block;
        }
        #footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
        #toggle-sidebar {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 10px 15px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
        }
        #toggle-sidebar:hover {
            background-color: #555;
        }
        #close-sidebar {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #close-sidebar:hover {
            background-color: #555;
        }
        #sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0); /* Fully transparent initially */
            z-index: 999; /* Below the sidebar but above the content */
            display: none; /* Hidden by default */
            transition: background-color 0.3s ease; /* Smooth transition for background color */
        }
        #sidebar-overlay.visible {
            display: block; /* Show when the sidebar is visible */
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
            transition: background-color 0.3s ease; /* Smooth transition for background color */
        }
    </style>
</head>
<body>
    <div id="main-content">
        <button id="toggle-sidebar">Toggle Sidebar</button>
        <div id="sidebar-overlay"></div>
        <div id="sidebar">
            <button id="close-sidebar">Close</button>
            <div>
                <h2>Categories</h2>
            </div>
            <ul>
                <li><button id="home">Home page</button></li>
                <li><button id="prod-lists">Product listing</button></li>
                <li><button id="users-lists">Users database</button></li>
                <li><button id="admin-database">Admin database</button></li>
                <li><button id="status">Shipping status</button></li>
                <li><button id="statistic">Statistic</button></li>
            </ul>
        </div>
        <div id="content-area">
            <?php
                $page = $_GET['page'] ?? 'main';

                // Extract the base page name before any query parameters
                if (strpos($page, '?') !== false) {
                    $page = explode('?', $page)[0];
                }

                if ($page === 'productlist') {
                    include 'productlist.php';
                } 
                elseif ($page === 'userlists') {
                    include 'userlists.php';
                }
                elseif ($page === 'shippingstatus') {
                    include 'shippingstatus.php';
                }    
                elseif ($page === 'adminlist') {
                    include 'adminlist.php';
                }    
                elseif ($page === 'statistic') {
                    include 'statistic.php';
                }    
                elseif ($page === 'details') {
                    if (isset($_GET['id'])) {
                        include 'proddetails.php'; // Include proddetails.php and pass the id parameter
                    } else {
                        die("Product ID not provided.");
                    }
                }  
                else {
                    include 'homepage.php'; // Default to homepage.php
                }
            ?>  
        </div>
    </div>
    <div id="footer">
        <p>&copy; 2023 Your Company. All rights reserved.</p>
        <p>Privacy Policy | Terms of Service</p>
        <p>Follow us on: 
            <a href="#">Facebook</a> | 
            <a href="#">Twitter</a> | 
            <a href="#">Instagram</a>
        </p>    
    </div>
    <script>
        // JavaScript code can be added here for interactivity
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        document.getElementById('home').addEventListener('click', function() {
            window.location.href = 'index.php?page=home';
        });
        document.getElementById('prod-lists').addEventListener('click', function() {
            window.location.href = 'index.php?page=productlist';
        });
        document.getElementById('users-lists').addEventListener('click', function() {
            window.location.href = 'index.php?page=userlists';
        });
        document.getElementById('admin-database').addEventListener('click', function() {
            window.location.href = 'index.php?page=adminlist';
        });
        document.getElementById('status').addEventListener('click', function() {
            window.location.href = 'index.php?page=shippingstatus';
        });
        document.getElementById('statistic').addEventListener('click', function() {
            window.location.href = 'index.php?page=statistic';
        });
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            sidebar.classList.toggle('visible');
            sidebarOverlay.classList.toggle('visible');
        });
        document.getElementById('close-sidebar').addEventListener('click', function() {
            sidebar.classList.remove('visible');
            sidebarOverlay.classList.remove('visible');
        });
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('visible');
            sidebarOverlay.classList.remove('visible');
        });
    </script>
</body>
</html>
