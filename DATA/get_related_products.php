<?php
require_once 'database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid product ID');
}

$db = new Database();
$product_id = intval($_GET['id']);

// Get current product's category
$current_product = $db->query("SELECT MaDanhMuc FROM SanPham WHERE MaSP = ?", [$product_id]);
if (empty($current_product)) {
    die('Product not found');
}
$category_id = $current_product[0]['MaDanhMuc'];

// Get 4 related products from same category (excluding current product)
$related_products = $db->query(
    "SELECT * FROM SanPham 
    WHERE MaDanhMuc = ? AND MaSP != ? 
    ORDER BY RAND() LIMIT 4",
    [$category_id, $product_id]
);

if (!empty($related_products)):
    foreach ($related_products as $product):
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
    echo '<p>Không tìm thấy sản phẩm liên quan.</p>';
endif;
?>
