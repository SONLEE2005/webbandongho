<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Timepiece</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="public/css/checkout.css">
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
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        .product-info {
            display: flex;
            align-items: center;
        }

        .product-info h4 {
            margin-left: 15px;
            font-size: 1.1rem;
            color: #333;
        }

        .product-info span {
            font-size: 0.9rem;
            color: #777;
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
                </ul>
            </nav>
        </div>
    </header>

    <section class="checkout-section">
        <div class="container">
            <h2 class="section-title">Checkout</h2>
            <div class="checkout-container">
                <!-- Customer Info Section -->
                <div class="checkout-form">
                    <h3>Shipping Information</h3>
                    <div class="form-group">
                        <label for="customerInfo">📌 Customer Info</label>
                        <div id="customerInfo">
                            <div><strong>Name:</strong> <span id="customerName">John Doe</span></div>
                            <div><strong>Phone:</strong> <span id="customerPhone">123-456-7890</span></div>
                            <div><strong>Address:</strong> 
                                <span id="customerAddress">1234 Main St, City</span>
                                <button class="edit-btn" onclick="openEditModal()">Edit</button>
                            </div>
                        </div>
                    </div>

                <!-- Modal for editing address -->
                <div class="modal" id="addressModal">
                    <div class="modal-content">
                        <h3>Edit Address</h3>
                        <label for="newAddress">Choose from saved addresses or enter a new one:</label>
                        <select id="savedAddresses" onchange="selectAddress()">
                            <option value="">Select Address</option>
                            <option value="1234 Main St, City">1234 Main St, City</option>
                            <option value="5678 Another St, Town">5678 Another St, Town</option>
                            <option value="Enter new address">Enter new address</option>
                        </select>
                        <br><br>
                        <input type="text" id="newAddress" name="newAddress" placeholder="Enter new address" style="display:none;" />
                        <br>
                        <button onclick="saveAddress()">Save</button>
                        <button onclick="closeEditModal()">Cancel</button>
                    </div>
                </div>
                
                <!-- Product List -->
                <div class="product-list">
                    <h3>Order Summary</h3>
                    <div class="product-item">
                        <div class="product-info">
                            <img src="product-image.jpg" alt="Product Image">
                            <h4>Product Name</h4>
                            <span>Price: $25.00</span>
                        </div>
                        <div class="quantity-control">
                            <button>-</button>
                            <input type="text" value="1" readonly>
                            <button>+</button>
                        </div>
                        <span>$25.00</span>
                    </div>
                    <div class="product-item">
                        <div class="product-info">
                            <img src="product-image.jpg" alt="Product Image">
                            <h4>Product Name</h4>
                            <span>Price: $15.00</span>
                        </div>
                        <div class="quantity-control">
                            <button>-</button>
                            <input type="text" value="1" readonly>
                            <button>+</button>
                        </div>
                        <span>$15.00</span>
                    </div>

                    <!-- Fee -->
                    <div class="summary">
                        <span>Fee:</span>
                        <strong>$2.00</strong>
                    </div>

                    <!-- Discount promotion -->
                    <div class="summary">
                        <span>Discount promotion:</span>
                        <strong>$0.00</strong>
                    </div> 
                    
                    <!-- Total -->
                    <div class="summary">
                        <span>Total:</span>
                        <strong>$42.00</strong>
                    </div>
                </div>

                <!-- Payment and Order Notes -->
                <div class="form-group">
                    <label>Payment Method</label><br>
                    <input type="radio" name="payment" id="cod" value="cod" checked>
                    <label for="cod">Cash on Delivery</label><br>
                    <input type="radio" name="payment" id="online" value="online">
                    <label for="online">Online Payment</label>
                </div>

                <div class="form-group">
                    <label for="note">Order Note (optional)</label>
                    <textarea id="note" name="note" rows="3" placeholder="e.g. Please call before delivery..."></textarea>
                </div>

                <div class="form-group" style="display: flex;align-items: center;justify-content:center;">
                    <button type="submit" class="btn">Confirm & Place Order</button>
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
            const newAddress = document.getElementById("newAddress").value;
            if (newAddress) {
                document.getElementById("customerAddress").innerText = newAddress;
            }
            closeEditModal();
        }
    </script>
</body>
</html>
