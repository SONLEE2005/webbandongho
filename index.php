<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Watch Shop</title>
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

    <script src="public/js/main.js"></script>
</body>
</html>
