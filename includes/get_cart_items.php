<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo '<p>Your cart is empty.</p>';
    return;
}

$db = new Database();

$cart = $_SESSION['cart'];
$subtotal = 0;

echo '<div class="cart-items-list">';

foreach ($cart as $product_id => $quantity) {
    // Fetch product details
    $products = $db->query("SELECT * FROM SanPham WHERE MaSP = ?", [$product_id]);
    if (empty($products)) {
        continue;
    }
    $product = $products[0];

    $image_url = !empty($product['HinhAnh']) ? 'public/images/' . $product['HinhAnh'] : 'public/images/casio0.jpg';
    $price = $product['Gia'];
    $total_price = $price * $quantity;
    $subtotal += $total_price;

    echo '<div class="cart-item">';
    echo '  <div class="cart-item-image">';
    echo '      <img src="' . htmlspecialchars($image_url) . '" alt="' . htmlspecialchars($product['TenSP']) . '">';
    echo '  </div>';
    echo '  <div class="cart-item-details">';
    echo '      <h4>' . htmlspecialchars($product['TenSP']) . '</h4>';
    echo '      <p>Price: $' . number_format($price, 2) . '</p>';
    echo '      <p>Quantity: ' . intval($quantity) . '</p>';
    echo '      <p>Total: $' . number_format($total_price, 2) . '</p>';
    echo '  </div>';
    echo '</div>';
}

echo '</div>';

// Output subtotal in a way that cart.php can update the summary
echo "<script>
    document.querySelector('.subtotal').textContent = '$" . number_format($subtotal, 2) . "';
    document.querySelector('.total-price').textContent = '$" . number_format($subtotal, 2) . "';
</script>";
?>
