<?php
require_once 'database.php';

$db = new Database();

// Get filter parameters
$category_id = isset($_GET['category']) ? intval($_GET['category']) : null;
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : null;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : null;

// Build base query
$query = "SELECT * FROM SanPham WHERE 1=1";
$params = [];

// Add category filter if specified
if ($category_id) {
    $query .= " AND MaDanhMuc = ?";
    $params[] = $category_id;
}

// Add price range filter if specified
if ($min_price !== null) {
    $query .= " AND Gia >= ?";
    $params[] = $min_price;
}
if ($max_price !== null) {
    $query .= " AND Gia <= ?";
    $params[] = $max_price;
}

// Execute query
$products = $db->query($query, $params);

// Display products
if (!empty($products)):
    foreach ($products as $product):
        $image_url = !empty($product['HinhAnh']) ? $product['HinhAnh'] : 'images/default-watch.jpg';
?>
        <div class="product-card">
            <a href="product-detail.php?id=<?= $product['MaSP'] ?>">
                <div class="product-image">
                    <img src="<?= $image_url ?>" alt="<?= htmlspecialchars($product['TenSP']) ?>">
                </div>
                <div class="product-info">
                    <h3><?= htmlspecialchars($product['TenSP']) ?></h3>
                    <div class="price">$<?= number_format($product['Gia'], 2) ?></div>
                </div>
            </a>
            <form action="add_to_cart.php" method="post" class="add-to-cart-form">
                <input type="hidden" name="product_id" value="<?= $product['MaSP'] ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn-add-to-cart">Thêm vào giỏ</button>
            </form>
        </div>
<?php
    endforeach;
else:
    echo '<p>Không tìm thấy sản phẩm nào.</p>';
endif;
?>
