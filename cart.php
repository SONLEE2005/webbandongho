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
    <title>Your Cart | Timepiece</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="public/css/cart.css">
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
    <style>
        .cart-items-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .cart-item {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 12px;
            background-color: #f9f9f9;
            position: relative;
        }

        .cart-item-image {
            width: 150px;
            height: 150px;
            object-fit: contain;
            margin-right: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-info h4 {
            margin: 0 0 5px;
        }

        .cart-item-info p {
            margin: 4px 0;
            font-size: 14px;
        }

        .cart-summary {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        .btn {
            margin: 10px;
            background-color: #28a745;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #218838;
        }
        .cart-item-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            padding-left: 10px;
        }
        .remove-form {
            position: absolute;
            bottom: 10px;
            right: 10px; 
        }

        .remove-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
        }

        .remove-btn:hover {
            background-color: #c82333;
        }
        .custom-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #28a745; /* màu khi được chọn */
        }

        .custom-checkbox:hover {
            box-shadow: 0 0 3px #555;
        }
    </style>
    <style>
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .quantity-input {
            width: 40px;
            text-align: center;
        }
        .increase-btn, .decrease-btn {
            padding: 2px 8px;
            cursor: pointer;
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

    <section class="cart-section">
        <div class="container">
            <h2 class="section-title">Your Shopping Cart</h2>
            <a href="lichsumuahang.php">Lịch Sử Mua Hàng</a> 
            <div class="cart-container">
                <div class="cart-items">
                    <?php include 'includes/get_cart_items.php'; ?>
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
    <script> // script cho phần thông báo, xin chào, chuyển đổi giữa đăng nhập đăng ký
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.get("success") === "1") {
                alert("Đăng nhập thành công. Xin chào <?php echo $tenNguoiDung; ?> !");
            }
            if (urlParams.get("logout") === "success") {
                alert("Đăng xuất thành công!");
            }

            // Search form submit event to redirect to products.php with search query
            const searchForm = document.getElementById('search-form');
            searchForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const query = document.getElementById('search-input').value.trim();
                if (query !== '') {
                    window.location.href = 'products.php?search=' + encodeURIComponent(query);
                }
            });
        };
        function showModal(id) {
            document.getElementById(id).style.display = 'flex';
        }
        function hideModal(id) {
            document.getElementById(id).style.display = 'none';
        }
    </script>
    <script src="public/js/main.js"></script>
    <script src="public/js/cart.js"></script>
</body>
</html>
