<?php
require_once 'database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid product ID');
}

$db = new Database();
$product_id = intval($_GET['id']);

// Get product details with join to chitietsanpham
$product = $db->query("SELECT sp.*, ctsp.ThuongHieu AS ct_ThuongHieu, ctsp.XuatXu, ctsp.DoiTuong, ctsp.DongSanPham, ctsp.KhangNuoc, ctsp.LoaiMay, ctsp.ChatLieuKinh, ctsp.ChatLieuDay, ctsp.SizeMat, ctsp.DoDay, ctsp.Series, ctsp.TienIch FROM SanPham sp LEFT JOIN chitietsanpham ctsp ON sp.MaSP = ctsp.MaSP WHERE sp.MaSP = ?", [$product_id]);
if (empty($product)) {
    die('Product not found');
}
$product = $product[0];

// Get product images
$images = $db->query("SELECT HinhAnh FROM SanPham WHERE MaSP = ?", [$product_id]);
?>

<div class="product-detail-container">
    <div class="product-gallery">
        <?php if (!empty($images)): ?>
            <div class="main-image-container">
                <img src="<?= 'public/images/' . $images[0]['HinhAnh'] ?>" 
                     alt="<?= htmlspecialchars($product['TenSP']) ?>" 
                     class="main-image"
                     id="zoom-image">
            </div>
            <div class="thumbnail-gallery">
                <?php foreach ($images as $index => $image): ?>
                <div class="thumbnail-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="<?= 'public/images/' . $image['HinhAnh'] ?>" 
                         alt="Thumbnail" 
                         class="thumbnail"
                         data-index="<?= $index ?>">
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="product-details">
        <div class="product-header">
            <h1 class="product-title"><?= htmlspecialchars($product['TenSP']) ?></h1>
            <div class="product-meta">
                <div class="product-rating">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="review-count">(12 đánh giá)</span>
                </div>
                <div class="product-sku">Mã SP: <?= htmlspecialchars($product['MaSP']) ?></div>
            </div>
        </div>

        <div class="product-price-section">
            <div class="price"><?= number_format($product['Gia'], 0, ',', '.') ?>₫</div>
            <div class="availability <?= $product['SoLuongTon'] > 0 ? 'in-stock' : 'out-of-stock' ?>">
                <i class="fas fa-<?= $product['SoLuongTon'] > 0 ? 'check' : 'times' ?>"></i>
                <?= $product['SoLuongTon'] > 0 ? 'Còn hàng' : 'Hết hàng' ?>
            </div>
        </div>

        <div class="product-actions">
            <form action="add_to_cart.php" method="post" class="add-to-cart-form">
                <input type="hidden" name="product_id" value="<?= $product['MaSP'] ?>">
                <div class="quantity-selector">
                    <button type="button" class="qty-btn minus">-</button>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= $product['SoLuongTon'] ?>">
                    <button type="button" class="qty-btn plus">+</button>
                </div>
                <button type="submit" class="btn-add-to-cart" <?= $product['SoLuongTon'] > 0 ? '' : 'disabled' ?>>
                    <i class="fas fa-shopping-cart"></i>
                    <?= $product['SoLuongTon'] > 0 ? 'Thêm vào giỏ' : 'Hết hàng' ?>
                </button>
            </form>

            <div class="wishlist-compare">
                <button class="btn-wishlist">
                    <i class="far fa-heart"></i> Yêu thích
                </button>
                <button class="btn-compare">
                    <i class="fas fa-exchange-alt"></i> So sánh
                </button>
            </div>
        </div>

        <div class="product-description">
            <h3 class="section-title">Mô tả sản phẩm</h3>
            <div class="description-content">
                <?= nl2br(htmlspecialchars($product['MoTa'])) ?>
            </div>
        </div>

        <div class="product-specifications">
            <h3 class="section-title">Thông số kỹ thuật</h3>
            <div class="specs-grid">
                <div class="spec-item">
                    <span class="spec-label">Thương hiệu:</span>
                    <span class="spec-value"><?= htmlspecialchars($product['ct_ThuongHieu'] ?? $product['ThuongHieu']) ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Danh mục:</span>
                    <span class="spec-value"><?= htmlspecialchars($product['MaDanhMuc']) ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Xuất xứ:</span>
                    <span class="spec-value"><?= htmlspecialchars($product['XuatXu'] ?? '') ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Đối tượng:</span>
                    <span class="spec-value"><?= htmlspecialchars($product['DoiTuong'] ?? '') ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Dòng sản phẩm:</span>
                    <span class="spec-value"><?= htmlspecialchars($product['DongSanPham'] ?? '') ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Kháng nước:</span>
                    <span class="spec-value"><?= htmlspecialchars($product['KhangNuoc'] ?? '') ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Loại máy:</span>
                    <span class="spec-value"><?= htmlspecialchars($product['LoaiMay'] ?? '') ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Chất liệu kính:</span>
                    <span class="spec-value"><?= htmlspecialchars($product['ChatLieuKinh'] ?? '') ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Chất liệu dây:</span>
                    <span class="spec-value"><?= htmlspecialchars($product['ChatLieuDay'] ?? '') ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Size mặt:</span>
                    <span class="spec-value"><?= htmlspecialchars($product['SizeMat'] ?? '') ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Độ dày:</span>
                    <span class="spec-value"><?= htmlspecialchars($product['DoDay'] ?? '') ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Series:</span>
                    <span class="spec-value"><?= htmlspecialchars($product['Series'] ?? '') ?></span>
                </div>
                <div class="spec-item full-width">
                    <span class="spec-label">Tiện ích:</span>
                    <span class="spec-value"><?= nl2br(htmlspecialchars($product['TienIch'] ?? '')) ?></span>
                </div>
            </div>
        </div>

        <div class="social-sharing">
            <span>Chia sẻ:</span>
            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-pinterest"></i></a>
        </div>
    </div>
</div>

<script>
// Simple image gallery functionality
document.addEventListener('DOMContentLoaded', function() {
    const thumbnails = document.querySelectorAll('.thumbnail');
    const mainImage = document.querySelector('.main-image img');
    
    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            // Update main image
            mainImage.src = this.src;
            
            // Update active thumbnail
            document.querySelectorAll('.thumbnail-item').forEach(item => {
                item.classList.remove('active');
            });
            this.parentElement.classList.add('active');
        });
    });

    // Quantity selector buttons
    const qtyInput = document.querySelector('.quantity-selector input');
    document.querySelector('.qty-btn.minus').addEventListener('click', () => {
        if (qtyInput.value > 1) qtyInput.value--;
    });
    document.querySelector('.qty-btn.plus').addEventListener('click', () => {
        if (qtyInput.value < <?= $product['SoLuongTon'] ?>) qtyInput.value++;
    });
});
</script>
