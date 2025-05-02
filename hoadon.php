<?php
session_start();
require_once 'includes/database.php';

if (!isset($_SESSION['maKH'])) {
    echo "Bạn chưa đăng nhập!";
    exit;
}

if (!isset($_GET['maDH'])) {
    echo "Mã đơn hàng không tồn tại!";
    exit;
}

$maKH = $_SESSION['maKH'];
$maDH = intval($_GET['maDH']);

$db = new Database();
$conn = $db->getConnection();

// Lấy thông tin đơn hàng
$sql1 = "SELECT * FROM donhang WHERE MaDH = ? AND MaKH = ?";
$order = $db->fetchOne($sql1, [$maDH, $maKH]);

if (!$order) {
    echo "Không tìm thấy đơn hàng.";
    exit;
}

// Lấy chi tiết đơn hàng
$sql2 = "SELECT ct.*, sp.TenSP, dh.GiamGia
         FROM chitietdonhang ct
         JOIN sanpham sp ON ct.MaSP = sp.MaSP
         JOIN donhang dh ON ct.MaDH = dh.MaDH
         WHERE ct.MaDH = ?";
$items = $db->fetchAll($sql2, [$maDH]);

// Lấy thông tin thanh toán
$sql3 = "SELECT * FROM thanhtoan WHERE MaDH = ?";
$payment = $db->fetchOne($sql3, [$maDH]);

// Lấy thông tin người dùng
$customerName = isset($_SESSION['hoTen']) ? htmlspecialchars($_SESSION['hoTen']) : 'Chưa có tên';
$customerPhone = isset($_SESSION['soDienThoai']) ? htmlspecialchars($_SESSION['soDienThoai']) : 'Chưa có số';
$customerAddress = isset($_SESSION['diaChi']) ? htmlspecialchars($_SESSION['diaChi']) : 'Chưa có địa chỉ';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Timepiece</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="public/css/checkout.css">
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
            border-radius: 4px;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }   
        .invoice-container {
            background-color: #fff;
            padding: 30px;
            margin: 50px auto;
            max-width: 800px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            color: #333;
            font-size: 1rem;
        }

        .invoice-container h2,
        .invoice-container h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-table th,
        .invoice-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .invoice-table th {
            background-color: #f8f8f8;
            color: #444;
        }

        .total-amount {
            font-size: 1.2rem;
            text-align: right;
            margin-top: 10px;
        }

        .status {
            padding: 4px 10px;
            border-radius: 4px;
            background-color: #d1ecf1;
            color: #0c5460;
            font-weight: bold;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }

        .back-link:hover {
            text-decoration: underline;
        }     
    </style>
    <style>
        /* Các cải tiến về layout và kiểu dáng */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: white;
            padding: 15px 0;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .logo {
            font-size: 1.5rem;
            color: white;
            text-decoration: none;
        }

        .nav-links {
            list-style: none;
            display: flex;
        }

        .nav-links li {
            margin-left: 20px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
        }

        .checkout-section {
            background-color: white;
            padding: 50px 0;
        }

        .checkout-container {
            max-width: 900px;
            margin: 0 auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 30px;
            background-color: #ffffff;
        }

        .section-title {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 30px;
            color: #333;
        }

        .checkout-form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            color: #555;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1rem;
            color: #333;
        }

        .form-group input[type="radio"] {
            width: auto;
            margin-right: 10px;
        }

        .form-group textarea {
            resize: vertical;
            height: 100px;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            font-size: 1.1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .form-group p {
            font-size: 1.1rem;
            color: #333;
        }

        .checkout-form input[type="radio"]:checked + label {
            color: #007bff;
        }

        /* Product List */
        .product-list {
            margin-top: 30px;
            border-top: 2px solid #eee;
            padding-top: 20px;
        }

        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }

        .product-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }

        .product-info {
            display: flex;
            flex-direction: column; /* Đảm bảo chữ nằm dọc */
            margin-left: 10px; /* Khoảng cách giữa ảnh và thông tin */
        }

        .product-info h4, .product-info span {
            margin: 0; /* Loại bỏ margin để chúng không bị cách nhau quá xa */
        }

        .product-info h4 {
            font-size: 1.1rem;
            color: #333;
        }

        .product-info span {
            font-size: 0.9rem;
            color: #777;
        }

        .quantity-control {
            /* Đặt các nút và ô input ở bên phải của item */
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .quantity-control {
            display: flex;
            align-items: center;
        }

        .quantity-control button {
            padding: 5px 10px;
            margin: 0 5px;
            font-size: 1rem;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            cursor: pointer;
        }

        .quantity-control input {
            width: 30px;
            text-align: center;
            font-size: 1rem;
            padding: 5px;
        }

        /* Summary */
        .summary {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            font-size: 1.1rem;
            color: #333;
        }

        .summary strong {
            font-weight: bold;
        }

        /* Edit Button */
        .edit-btn {
            cursor: pointer;
            color: #007bff;
            font-size: 1rem;
            text-decoration: underline;
        }

        /* Modal for editing address */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            width: 100%;
        }

        .modal-content input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .modal-content button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal-content button:hover {
            background-color: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .checkout-container {
                padding: 20px;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .product-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .product-info {
                margin-bottom: 10px;
            }

            .quantity-control {
                margin-bottom: 10px;
            }

            .summary {
                flex-direction: column;
            }
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
                            <?php echo  $customerName  ?>
                        </a>
                        <div class="dropdown-content">
                            <a href="./includes/logout.php" style="color: black">Logout</a>  
                        </div>
                    </li> <!--   tới đây -->
                </ul>
            </nav>
        </div>
    </header>    
    <div class="invoice-container">
        <h2>🧾 HÓA ĐƠN ĐẶT HÀNG</h2>
        <p><strong>Mã đơn hàng:</strong> #<?= $order['MaDH'] ?></p>
        <p><strong>Ngày đặt:</strong> <?= $order['NgayDat'] ?></p>
        <p><strong>Trạng thái:</strong> <span class="status"><?= $order['TrangThai'] ?></span></p>
        <p><strong>Tên người nhận:</strong> <?= $order['TenNguoiNhan'] ?></p>
        <p><strong>Số điện thoại:</strong> <?= $order['SoDienThoai'] ?></p>
        <p><strong>Địa chỉ giao:</strong> <?= $order['DiaChi'] ?></p>
        <p><strong>Ghi chú khi giao:</strong> <?= $order['GhiChu'] ?></p>

        <h3>🛒 Chi tiết đơn hàng:</h3>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Giảm giá</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['TenSP']) ?></td>
                    <td><?= number_format($item['DonGia'], 0, ',', '.') ?> đ</td>
                    <td><?= $item['SoLuong'] ?></td>
                    <td><?= number_format($item['ThanhTien'], 0, ',', '.') ?> đ</td>
                    <td><?= number_format($item['GiamGia'], 0, ',', '.') ?> đ</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="total-amount"><strong>Tổng tiền:</strong> <?= number_format($order['TongTien'], 0, ',', '.') ?> đ</p>

        <h3>💳 Thông tin thanh toán:</h3>
        <p><strong>Phương thức:</strong> <?= $payment['PhuongThucTT'] ?></p>
        <p><strong>Ngày thanh toán:</strong> <?= $payment['NgayThanhToan'] ?></p>
        <p><strong>Số tiền:</strong> <?= number_format($payment['SoTien'], 0, ',', '.') ?> đ</p>
        <p><strong>Trạng thái:</strong> <?= $payment['TrangThaiTT'] ?></p>
        <p><a href="lichsumuahang.php" class="back-link">⬅ Quay lại lịch sử mua hàng</a></p>
    </div>
</body>
</html>    
