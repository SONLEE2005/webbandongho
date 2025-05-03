<?php
    session_start();
    $isLongin = isset($_SESSION["email"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div id="header">
            <div>
                <h1>Logo here*</h1>
                <h1>Welcome to the Admin End</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
                <ul>
                    <!-- <li><button id="profile">Profile</button></li> -->
                    <!-- <li><button id="login">Login</button></li>
                    <li><button id="signup">Sign up</button></li> -->
                    <!-- <li><button id="logout"><a href="../logout_admin.php">Logout</a></button></li>
                    <li><button id="cart">Cart</button></li> -->

                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Cart</a></li>
                    <?php if($isLongin): ?>
                        <li><a href="./logout_admin.php">Logout</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
    </div>
    <script src="../button.js"></script>
</body>
</html>
