<?php
require_once 'database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid product ID');
}

$db = new Database();
$product_id = intval($_GET['id']);

// Get product reviews
$reviews = $db->query(
    "SELECT r.*, u.username, u.avatar 
    FROM product_reviews r
    JOIN users u ON r.user_id = u.id
    WHERE r.product_id = ?
    ORDER BY r.created_at DESC",
    [$product_id]
);

if (!empty($reviews)):
    foreach ($reviews as $review):
        $stars = str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']);
?>
        <div class="review-card">
            <div class="review-header">
                <div class="user-info">
                    <img src="<?= !empty($review['avatar']) ? $review['avatar'] : 'images/default-avatar.jpg' ?>" 
                         alt="<?= htmlspecialchars($review['username']) ?>" 
                         class="user-avatar">
                    <span class="username"><?= htmlspecialchars($review['username']) ?></span>
                </div>
                <div class="review-meta">
                    <div class="rating"><?= $stars ?></div>
                    <div class="date"><?= date('M j, Y', strtotime($review['created_at'])) ?></div>
                </div>
            </div>
            <div class="review-content">
                <h3><?= htmlspecialchars($review['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($review['content'])) ?></p>
            </div>
        </div>
<?php
    endforeach;
else:
    echo '<div class="no-reviews">';
    echo '<i class="far fa-comment-dots"></i>';
    echo '<p>No reviews yet. Be the first to review this product!</p>';
    echo '</div>';
endif;
?>
