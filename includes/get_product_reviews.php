<?php
require_once 'database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid product ID');
}

try {
    $db = new Database();
    $product_id = intval($_GET['id']);

    // Check if product_reviews table exists
    $tableExists = $db->query("SHOW TABLES LIKE 'product_reviews'");
    
    if (!empty($tableExists)) {
        // Get product reviews with user info if table exists
        $reviews = $db->query("
            SELECT r.*, u.HoTen 
            FROM product_reviews r
            JOIN KhachHang u ON r.MaKH = u.MaKH
            WHERE r.MaSP = ?
            ORDER BY r.NgayTao DESC
        ", [$product_id]);
    }

    if (!empty($reviews)) {
        foreach ($reviews as $review) {
            echo '<div class="review">';
            echo '<div class="review-header">';
            echo '<span class="review-author">' . htmlspecialchars($review['HoTen']) . '</span>';
            echo '<span class="review-date">' . date('d/m/Y', strtotime($review['NgayTao'])) . '</span>';
            echo '</div>';
            echo '<div class="review-rating">';
            for ($i = 0; $i < 5; $i++) {
                echo $i < $review['Rating'] ? '★' : '☆';
            }
            echo '</div>';
            echo '<div class="review-content">' . nl2br(htmlspecialchars($review['NoiDung'])) . '</div>';
            echo '</div>';
        }
    } else {
        echo '<p>Chưa có đánh giá nào cho sản phẩm này.</p>';
    }
} catch (Exception $e) {
    // Silently fail and show no reviews if there's an error
    echo '<p>Chưa có đánh giá nào cho sản phẩm này.</p>';
}
