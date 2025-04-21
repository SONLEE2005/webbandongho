<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail | Timepiece</title>
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

    <main class="container">
        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
            <div class="alert success">
                <i class="fas fa-check-circle"></i> Product added to cart successfully!
            </div>
        <?php endif; ?>

        <section class="product-detail">
            <div class="breadcrumb">
                <a href="index.php">Home</a> > 
                <a href="products.php">Shop</a> > 
                <span>Product Detail</span>
            </div>
            
            <div class="product-content">
                <?php include 'includes/get_product_detail.php'; ?>
            </div>
        </section>

        <section class="product-reviews">
            <h2 class="section-title">Customer Reviews</h2>
            <div class="reviews-container">
                <?php include 'includes/get_product_reviews.php'; ?>
            </div>
        </section>
    </main>

    <section class="related-products">
        <div class="container">
            <h2 class="section-title">You May Also Like</h2>
            <div class="product-grid">
                <?php include 'includes/get_related_products.php'; ?>
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
