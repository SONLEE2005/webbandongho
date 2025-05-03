<?php
    session_start();
    require_once 'includes/database.php';
    
    if (!isset($_SESSION['selectedProducts']) || !isset($_SESSION['selectedTotal'])) {
        echo "Không có sản phẩm nào được chọn.";
        exit;
    }
    
    $db = new Database();
    $selectedProducts = $_SESSION['selectedProducts'];
    $total = $_SESSION['selectedTotal'];
    
    // Tạo danh sách sản phẩm cần thanh toán
    $productsToCheckout = [];
    foreach ($selectedProducts as $product_id => $quantity) {
        // Truy vấn thông tin sản phẩm từ sanpham
        $products = $db->query("SELECT * FROM sanpham WHERE MaSP = ?", [$product_id]);
        if (!empty($products)) {
            $product = $products[0];
            $productsToCheckout[] = [
                'id' => $product_id,
                'name' => $product['TenSP'],
                'price' => $product['Gia'],
                'quantity' => intval($quantity),  // lấy đúng số lượng chọn
                'image' => !empty($product['HinhAnh']) ? 'public/images/' . $product['HinhAnh'] : 'public/images/casio0.jpg',
            ];
        }
    }

    // Lấy thông tin người dùng
    $customerName = isset($_SESSION['hoTen']) ? htmlspecialchars($_SESSION['hoTen']) : 'Chưa có tên';
    $customerPhone = isset($_SESSION['soDienThoai']) ? htmlspecialchars($_SESSION['soDienThoai']) : 'Chưa có số';
    $customerAddress = isset($_SESSION['diaChi']) ? htmlspecialchars($_SESSION['diaChi']) : 'Chưa có địa chỉ';
    
?> <!-- Thêm khúc này để lấy tên người dùng, email bắt đầu phiên làm việc-->
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

        .small-btn {
            font-size: 13px;
            padding: 6px 10px;
            background-color: #eee;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
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

    <section class="checkout-section">
        <div class="container">
            <h2 class="section-title">Checkout</h2>
            <div class="checkout-container">        
                <div class="checkout-form">
                    <h3>Shipping Information</h3>
                    <form action="checkout_process.php" id="checkout_process" method="POST">
                        <!-- Customer Info Section -->                       
                        <div class="form-group">
                            <label for="customerInfo">📌 Customer Info</label>
                            <div id="customerInfo">
                                <div name="customer_name"><strong>Name:</strong> <?= $customerName ?></div>
                                <div name="customer_phone"><strong>Phone:</strong> <?= $customerPhone ?></div>
                                <div><strong>Address:</strong> 
                                    <span id="customerAddress" name="customer_address"><?= $customerAddress ?></span>
                                    <button type="button" class="edit-btn" onclick="openEditModal()">Edit</button>
                                </div>
                            </div>
                        </div>

                        <!-- Input ẩn để gửi qua POST -->
                        <input type="hidden" name="customer_name" value="<?= htmlspecialchars($customerName) ?>">
                        <input type="hidden" name="customer_phone" value="<?= htmlspecialchars($customerPhone) ?>">
                        <input type="hidden" name="customer_address" id="customerAddressInput" value="<?= htmlspecialchars($customerAddress) ?>">

                        <!-- Modal for editing address -->
                        <div class="modal" id="addressModal">
                            <div class="modal-content">
                                <h3>Edit Address</h3>
                                <label for="newAddress">Choose from saved addresses or enter a new one:</label>
                                <select id="savedAddresses" onchange="selectAddress()">
                                    <option value="">Select Address</option>
                                    <option value="<?=$customerAddress?>"><?=$customerAddress?></option>
                                    <option value="Enter new address">Enter new address</option>
                                </select>
                                <br><br>
                                <input type="text" id="newAddress" name="newAddress" placeholder="Enter new address" style="display:none;" />
                                <br>
                                <button type="button" onclick="saveAddress()">Save</button>
                                <button type="button" onclick="closeEditModal()">Cancel</button>
                            </div>
                        </div>
                    
                        <!-- Product list -->
                        <div class="product-list">
                            <h3>Order Summary</h3>

                            <?php foreach ($productsToCheckout as $index => $product): ?>
                                <div class="product-item">
                                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="Product Image" style="width:100px;height:100px;">
                                    <div class="product-info">
                                        <h4><?= htmlspecialchars($product['name']) ?></h4>
                                        <span class="product-price" data-price="<?= $product['price'] ?>">
                                            $<?= number_format($product['price'], 2, ',', '.') ?>
                                        </span>
                                    </div>

                                    <div style="display:flex;flex-direction:column">
                                        <!-- Hiển thị số lượng mà không có nút tăng giảm -->
                                        <div class="quantity-display">
                                            Số lượng: <?= $product['quantity'] ?>
                                        </div>

                                        <span class="item-total">
                                            Số tiền: $<?= number_format($product['price'] * $product['quantity'], 2, ',', '.') ?>
                                        </span>
                                    </div>
                                    
                                    <!-- Các input hidden để gửi qua POST -->
                                    <input type="hidden" name="products[<?= $index ?>][id]" value="<?= htmlspecialchars($product['id']) ?>">
                                    <input type="hidden" name="products[<?= $index ?>][name]" value="<?= htmlspecialchars($product['name']) ?>">
                                    <input type="hidden" name="products[<?= $index ?>][price]" value="<?= $product['price'] ?>">
                                    <input type="hidden" name="products[<?= $index ?>][quantity]" value="<?= $product['quantity'] ?>">

                                </div>
                            <?php endforeach; ?>

                            <!-- Discount promotion -->
                            <div class="summary discount">
                                <span>Discount promotion:</span>
                                <strong id="discountAmount">$0,00</strong>

                                <input type="hidden" name="discount" id="discountInput" value="0.00">

                                <br><br>
                                <button type="button" class="small-btn" id="showDiscountBtn" onclick="showDiscountInput()">Nhập mã giảm giá</button>

                                <div id="discountCodeSection" style="display: none; margin-top: 10px;">
                                    <input type="text" id="discountCodeInput" placeholder="TET2025, SUMMER25">
                                    <button type="button" onclick="applyDiscountCode()">Áp dụng</button>
                                    <p id="discountMessage" style="color: green; font-weight: bold;"></p>
                                </div>
                            </div>


                            <!-- Total -->
                            <div class="summary total">
                                <span>Total:</span>
                                <strong>$0,00</strong>

                                <input type="hidden" name="total" id="orderTotal" value="0.00">
                            </div>
                        </div>

                        <!-- Payment -->
                        <div class="form-group">
                            <label>Payment Method</label><br>
                            <input type="radio" name="payment" id="cod" value="cod" checked>
                            <label for="cod">Cash on Delivery</label><br>
                            <input type="radio" name="payment" id="online" value="online">
                            <label for="online">Online Payment</label>
                        </div>
                        
                        <!-- Order Notes -->
                        <div class="form-group">
                            <label for="note">Order Note (optional)</label>
                            <textarea id="note" name="note" rows="3" placeholder="e.g. Please call before delivery..."></textarea>
                        </div>
                        
                        <!-- Submit -->
                        <div class="form-group" style="display: flex;align-items: center;justify-content:center;">
                            <button type="submit" class="btn">Confirm & Place Order</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

    <script>
        function openEditModal() {
            document.getElementById("addressModal").style.display = "flex";
        }

        function closeEditModal() {
            document.getElementById("addressModal").style.display = "none";
        }

        function selectAddress() {
            const selectedValue = document.getElementById("savedAddresses").value;
            const newAddressInput = document.getElementById("newAddress");

            if (selectedValue === "Enter new address") {
                newAddressInput.style.display = "inline-block"; // Show input for new address
                newAddressInput.value = ""; // Clear input
            } else {
                newAddressInput.style.display = "none"; // Hide input
                document.getElementById("newAddress").value = selectedValue;
            }
        }

        function saveAddress() {
            const selected = document.getElementById("savedAddresses").value;
            const newAddressInput = document.getElementById("newAddress");
            const addressInput = document.getElementById("customerAddressInput");
            const addressDisplay = document.getElementById("customerAddress");

            let finalAddress = selected;

            if (selected === "Enter new address") {
                finalAddress = newAddressInput.value;
            }

            // Cập nhật hiển thị
            addressDisplay.textContent = finalAddress;

            // Cập nhật giá trị input hidden
            addressInput.value = finalAddress;

            closeEditModal();
        }
        let discount = 0; 

        function showDiscountInput() {
            document.getElementById("discountCodeSection").style.display = "block";
            document.getElementById("showDiscountBtn").style.display = "none";
        }

        function updateDiscountDisplay(value) {
            document.querySelector('#discountAmount').innerText = '$' + value.toLocaleString('vi-VN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            document.querySelector('#discountInput').value = value.toFixed(2);
            updateOrderTotal();  // Cập nhật lại tổng tiền sau khi giảm giá
        }

        function applyDiscountCode() {
            const code = document.getElementById("discountCodeInput").value.trim().toUpperCase();
            const message = document.getElementById("discountMessage");
            let subtotal = 0;

            document.querySelectorAll('.item-total').forEach(item => {
                let itemText = item.innerText
                    .replace('Số tiền:', '')   // ✅ Bỏ chữ "Số tiền:"
                    .replace('$', '')          // Bỏ $
                    .replace(/\./g, '')        // Bỏ chấm
                    .replace(',', '.');        // Đổi phẩy thành chấm
                subtotal += parseFloat(itemText);
            });

            if (code === "TET2025") {
                discount = subtotal * 0.10;
                message.innerText = "Áp dụng mã TET2025 thành công: Giảm giá 10% !";
                message.style.color = "green";
            } else if (code === "SUMMER25") {
                discount = subtotal * 0.15;
                message.innerText = "Áp dụng mã SUMMER25 thành công: Giảm giá 15% !";
                message.style.color = "green";
            } else {
                discount = 0;
                message.innerText = "Mã không hợp lệ";
                message.style.color = "red";
            }

            updateDiscountDisplay(discount);
        }

        // Hiển thị giá trị giảm giá ban đầu
        document.querySelector('.discount strong').innerText = '$' + discount.toLocaleString('vi-VN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        document.querySelector('#discountInput').value = discount.toFixed(2);

        // Cập nhật tổng tiền của từng sản phẩm
        function updateItemTotal(btn) {
            let productItem = btn.closest('.product-item');
            let price = parseFloat(productItem.querySelector('.product-price').getAttribute('data-price'));
            let quantity = parseInt(productItem.querySelector('input').value);
            let itemTotal = price * quantity;

            // Cập nhật giá trị số lượng vào các input hidden
            let quantityInput = productItem.querySelector('input[type="hidden"][name*="quantity"]');
            if (quantityInput) {
                quantityInput.value = quantity;
            }

            productItem.querySelector('.item-total').innerText = '$' + itemTotal.toLocaleString('vi-VN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            updateOrderTotal(); // Cập nhật tổng khi thay đổi số lượng
        }

        // Cập nhật tổng tiền đơn hàng
        function updateOrderTotal() {
            let itemTotals = document.querySelectorAll('.item-total');
            let subtotal = 0;

            itemTotals.forEach(item => {
                let itemText = item.innerText
                    .replace('Số tiền:', '')   // ✅ Bỏ chữ "Số tiền:"
                    .replace('$', '')          // Bỏ dấu $
                    .replace(/\./g, '')        // Bỏ dấu chấm ngăn cách nghìn
                    .replace(',', '.');        // Đổi dấu phẩy thành chấm
                subtotal += parseFloat(itemText);
            });

            let finalTotal = subtotal - discount;
            if (finalTotal < 0) finalTotal = 0;

            document.querySelector('.total strong').innerText = '$' + finalTotal.toLocaleString('vi-VN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            document.querySelector('#orderTotal').value = finalTotal.toFixed(2);
        }

        // Cập nhật tổng ban đầu khi trang load
        document.addEventListener('DOMContentLoaded', function () {
            updateOrderTotal();
        });
    </script>
</body>
</html>
