<?php
require_once 'database.php';

$db = new Database();

$category_id = isset($_GET['category']) && $_GET['category'] !== 'all' ? intval($_GET['category']) : null;
$brand = isset($_GET['brand']) && $_GET['brand'] !== 'all' ? trim($_GET['brand']) : null;
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : null;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? max(intval($_GET['page']), 1) : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

// Build base query
$query = "SELECT * FROM SanPham WHERE 1=1";
$countQuery = "SELECT COUNT(*) as total FROM SanPham WHERE 1=1";
$params = [];
$countParams = [];

// Add category filter if specified
if ($category_id) {
    $query .= " AND MaDanhMuc = ?";
    $countQuery .= " AND MaDanhMuc = ?";
    $params[] = $category_id;
    $countParams[] = $category_id;
}

// Add brand filter if specified
if ($brand) {
    $query .= " AND ThuongHieu = ?";
    $countQuery .= " AND ThuongHieu = ?";
    $params[] = $brand;
    $countParams[] = $brand;
}

// Add price range filter if specified
if ($min_price !== null) {
    $query .= " AND Gia >= ?";
    $countQuery .= " AND Gia >= ?";
    $params[] = $min_price;
    $countParams[] = $min_price;
}
if ($max_price !== null) {
    $query .= " AND Gia <= ?";
    $countQuery .= " AND Gia <= ?";
    $params[] = $max_price;
    $countParams[] = $max_price;
}

// Add search filter if specified
if ($search !== '') {
    $query .= " AND TenSP LIKE ?";
    $countQuery .= " AND TenSP LIKE ?";
    $searchParam = '%' . $search . '%';
    $params[] = $searchParam;
    $countParams[] = $searchParam;
}

// Add pagination
$query .= " LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;

// Get total count for pagination
$totalResult = $db->query($countQuery, $countParams);
$totalProducts = $totalResult[0]['total'] ?? 0;
$totalPages = ceil($totalProducts / $limit);

// Execute query
$products = $db->query($query, $params);
?>

<div id="product-cards">
<?php if (!empty($products)): ?>
    <?php foreach ($products as $product): 
        $image_url = !empty($product['HinhAnh']) ? 'public/images/' . $product['HinhAnh'] : 'public/images/casio0.jpg';
    ?>
        <div class="product-card" data-product-id="<?= $product['MaSP'] ?>">
            <a href="product-detail.php?id=<?= $product['MaSP'] ?>">
                <div class="product-image">
                    <img class="product-img" src="<?= $image_url ?>" alt="<?= htmlspecialchars($product['TenSP']) ?>">
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
    <?php endforeach; ?>
<?php else: ?>
    <p>Không tìm thấy sản phẩm nào.</p>
<?php endif; ?>
</div>

<div id="pagination-controls">
<?php if ($totalPages > 1): ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <button class="pagination-btn" data-page="<?= $i ?>" <?= $i === $page ? 'disabled' : '' ?>>
            <?= $i ?>
        </button>
    <?php endfor; ?>
<?php endif; ?>
</div>
