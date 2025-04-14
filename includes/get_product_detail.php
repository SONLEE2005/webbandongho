<?php
require_once 'database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid product ID');
}

$db = new Database();
$product_id = intval($_GET['id']);

// Get product details
$product = $db->query("SELECT * FROM SanPham WHERE MaSP = ?", [$product_id]);
if (empty($product)) {
    die('Product not found');
}
$product = $product[0];

// Get product images
$images = $db->query("SELECT HinhAnh FROM SanPham WHERE MaSP = ?", [$product_id]);
?>

<div class="product-container">
    <div class="product-gallery">
        <?php if (!empty($images)): ?>
            <div class="main-image">
<img src="<?= $images[0]['HinhAnh'] ?>" alt="<?= htmlspecialchars($product['TenSP']) ?>">
            </div>
            <div class="thumbnail-container">
                <?php foreach ($images as $image): ?>
                <img src="<?= $image['HinhAnh'] ?>" alt="Thumbnail" class="thumbnail">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="product-info">
        <h1><?= htmlspecialchars($product['TenSP']) ?></h1>
        <div class="price">$<?= number_format($product['Gia'], 2) ?></div>
        
<div class="product-meta">
    <div class="sku">Mã SP: <?= htmlspecialchars($product['MaSP']) ?></div>
    <div class="availability"><?= $product['SoLuongTon'] > 0 ? 'Còn hàng' : 'Hết hàng' ?></div>
</div>

        <form action="add_to_cart.php" method="post" class="add-to-cart-form">
            <input type="hidden" name="product_id" value="<?= $product['MaSP'] ?>">
            <div class="quantity-selector">
                <label for="quantity">Số lượng:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= $product['SoLuongTon'] ?>">
            </div>
            <button type="submit" class="btn-add-to-cart" <?= $product['SoLuongTon'] > 0 ? '' : 'disabled' ?>>
                <?= $product['SoLuongTon'] > 0 ? 'Thêm vào giỏ' : 'Hết hàng' ?>
            </button>
        </form>

        <div class="product-description">
            <h3>Mô tả</h3>
            <p><?= nl2br(htmlspecialchars($product['MoTa'])) ?></p>
        </div>

        <div class="product-specs">
            <h3>Thông số kỹ thuật</h3>
            <ul>
                <li><strong>Thương hiệu:</strong> <?= htmlspecialchars($product['ThuongHieu']) ?></li>
                <li><strong>Danh mục:</strong> <?= htmlspecialchars($product['MaDanhMuc']) ?></li>
            </ul>
        </div>
    </div>
</div>
