<?php
    session_start();
    $tenNguoiDung = isset($_SESSION["hoTen"]) ? htmlspecialchars($_SESSION["hoTen"]) : null;
    $isLoggedIn = isset($_SESSION["email"]);
?> <!-- Thêm khúc này để lấy tên người dùng, email bắt đầu phiên làm việc-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Watch Shop</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style> /* style cho phần menu tài khoản */
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropbtn {
            cursor: pointer;
            padding: 10px;
            text-decoration: none;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 120px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 4px;
        }
        .dropdown-content a {
            color: black;
            padding: 10px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 999;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            width: 400px;
            height: 500px;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }
        .modal .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            color: #0e0d0d;
            cursor: pointer;
            transition: color 0.3s;
            border-radius: 8px;
            border: 1px solid red;
            width: 30px;
            text-align: center;
            background-color: red;
        }
        .close-btn:hover {
            transform: scale(1.1);
        } 
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="index.php" class="logo">Timepiece</a>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">Shop</a></li>
                    <li><a href="#">Collections</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                    <li class="dropdown"> <!-- hiển thị tên người dùng sau khi đăng nhập -->
                        <a href="#" class="dropbtn">
                            <i class="fas fa-user"></i> 
                            <?php echo $isLoggedIn ? $tenNguoiDung : "Account"; ?>
                        </a>
                        <div class="dropdown-content">
                            <?php if (!$isLoggedIn): ?>
                                <a href="#" onclick="showModal('loginModal')">Login</a>
                                <a href="#" onclick="showModal('registerModal')">Register</a>
                            <?php else: ?>
                                <a href="./includes/logout.php">Logout</a>
                            <?php endif; ?>
                        </div>
                    </li> <!--   tới đây -->
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Timeless Elegance</h1>
                <p>Discover our exquisite collection of luxury watches</p>
                <a href="products.php" class="btn">Shop Now</a>
            </div>
        </div>
    </section>

    <section class="products">
        <div class="container">
            <h2 class="section-title">Featured Watches</h2>
            <div class="product-grid">
                <!-- Product 1 -->
                <div class="product-card" data-product-id="1">
                    <img src="public/images/casio3.jpg" alt="Luxury Watch" class="product-img">
                    <div class="product-info">
                        <h3 class="product-title">Đồng hồ Casio MTP-1374L-1AVDF 6 Kim</h3>
                        <p class="product-price">1.589.000₫</p>
                        <button class="btn add-to-cart" data-product-id="1">Add to Cart</button>
                    </div>
                </div>
                
                <!-- Product 2 -->
                <div class="product-card" data-product-id="2">
                    <img src="public/images/casio2.jpg" alt="Luxury Watch" class="product-img">
                    <div class="product-info">
                        <h3 class="product-title">Đồng Hồ Casio Mtp Nam MTP-1374L-7AVDF</h3>
                        <p class="product-price">1.589.000₫</p>
                        <button class="btn add-to-cart" data-product-id="2">Add to Cart</button>
                    </div>
                </div>
                
                <!-- Product 3 -->
                <div class="product-card" data-product-id="3">
                    <img src="public/images/casio1.jpg" alt="Luxury Watch" class="product-img">
                    <div class="product-info">
                        <h3 class="product-title">Đồng Hồ Casio Nam MTP-1374L-9AVDF</h3>
                        <p class="product-price">1.589.000₫</p>
                        <button class="btn add-to-cart" data-product-id="3">Add to Cart</button>
                    </div>
                </div>
                <!-- Product 3 -->
                <div class="product-card" data-product-id="3">
                    <img src="public/images/casio0.jpg" alt="Luxury Watch" class="product-img">
                    <div class="product-info">
                        <h3 class="product-title">Đồng Hồ Casio Nam MTP-1374L-9AVDF</h3>
                        <p class="product-price">1.589.000₫</p>
                        <button class="btn add-to-cart" data-product-id="3">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <ul class="footer-links">
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="#">Shipping Policy</a></li>
                <li><a href="#">Returns</a></li>
            </ul>
            <p class="copyright">© 2023 Timepiece. All rights reserved.</p>
        </div>
    </footer>
        <!-- Modal đăng nhập -->
    <div id="loginModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-btn" onclick="hideModal('loginModal')">&times;</span>
            <iframe src="includes/login.php" frameborder="0" style="width:100%; height:100%;"></iframe>
        </div>
    </div>

    <!-- Modal đăng ký -->
    <div id="registerModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-btn" onclick="hideModal('registerModal')">&times;</span>
            <iframe src="includes/register.php" frameborder="0" style="width:100%; height:100%;"></iframe>
        </div>
    </div>
    <script src="public/js/main.js"></script>
    <script> // script cho phần thông báo, xin chào, chuyển đổi giữa đăng nhập đăng ký
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.get("success") === "1") {
                alert("Đăng nhập thành công. Xin chào <?php echo $tenNguoiDung; ?> !");
            }
            if (urlParams.get("logout") === "success") {
                alert("Đăng xuất thành công!");
            }
        };
        function showModal(id) {
            document.getElementById(id).style.display = 'flex';
        }
        function hideModal(id) {
            document.getElementById(id).style.display = 'none';
        }
    </script>
</body>
</html>
