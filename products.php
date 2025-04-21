<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Collection | Timepiece</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

    <section class="products">
        <div class="container">
            <h2 class="section-title">Our Collection</h2>
            <div class="product-filters">
                <select id="category-filter">
                    <option value="all">All Categories</option>
                    <option value="men">Men's Watches</option>
                    <option value="women">Women's Watches</option>
                    <option value="sports">Sports Watches</option>
                </select>
                <select id="price-filter">
                    <option value="all">All Prices</option>
                    <option value="duoi1tr">Dưới 1 triệu</option>
                    <option value="1tr-3tr">1 triệu - 3 triệu</option>
                    <option value="3tr-5tr">3 triệu - 5 triệu</option>
                </select>
            </div>
            <div class="product-grid">
                <!-- Products will be loaded dynamically from PHP -->
                <?php include 'includes/get_products.php'; ?>
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

    <script src="public/js/main.js"></script>
    <script src="public/js/products.js"></script>
</body>
</html>
