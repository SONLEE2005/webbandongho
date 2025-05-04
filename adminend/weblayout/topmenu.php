<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["hoTen"])) {
    header("Location: ./login_admin.php");
    exit();
}
?>

<style>
    /* Định dạng chung */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
}

/* Menu trên cùng */
#topmenu {
    background-color: #343a40;
    padding: 15px 20px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
}

#topmenu ul {
    list-style: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0;
    margin: 0;
}

#topmenu li {
    color: white;
    font-size: 18px;
    padding: 10px 15px;
    margin: auto;
}

/* Nút đăng xuất */
#signout {
    background-color: #ff4d4d;
    color: white;
    border: none;
    padding: 10px 16px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 6px;
    transition: background 0.3s ease-in-out;
}

#signout:hover {
    background-color: #cc0000;
}

</style>

<body>
    <div id="topmenu">
        <ul>
            <!-- <li>Search: </li>
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
            </select> -->
            <li>
                <?php
                if (isset($_SESSION['hoTen'])) {
                    echo "Welcome back, " . htmlspecialchars($_SESSION['hoTen']);
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
            window.location.href ="./logout_admin.php"; // Redirect to logout page
        });
    </script>
</body>
</html>