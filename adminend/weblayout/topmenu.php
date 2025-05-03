<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div id="topmenu">
        <ul>
            <li>Search: </li>
            <li>
                <form action="search.php" method="GET">
                    <input type="text" name="query" id="searchQuery" placeholder="Enter search term">
                    <button type="submit">Search</button>
                </form>
            </li>
            <div>Sort by:</div>
            <select name="" id="">
                <option value="">Price</option>
                <option value="">Name</option>
                <option value="">Date</option>
            </select>
            <li>
                <?php
                if (isset($_SESSION['user_data']['HoTen'])) {
                    echo "Welcome back, " . htmlspecialchars($_SESSION['user_data']['HoTen']);
                } else {
                    echo "Welcome back, Guest";
                }
                ?>
            </li>
            <button id="signout">Sign out</button>
        </ul>
    </div>
    <script>
        document.getElementById('signout').addEventListener('click', function() {
            alert('Signing out...');
            window.location.href = '../../loginpage/login.php'; // Redirect to logout page
        });
    </script>
</body>
</html>
