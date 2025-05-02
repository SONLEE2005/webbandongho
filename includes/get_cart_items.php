<?php
//session_start();
require_once 'database.php';

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['maKH'])) {
    echo '<p>Please login to view your cart.</p>';
    return;
}

$db = new Database();
$maKH = $_SESSION['maKH'];  // Lấy MaKH từ session lúc đăng nhập

// Truy vấn giỏ hàng của người dùng từ bảng giohang
$cart_query = $db->query("SELECT MaSP, SoLuong FROM giohang WHERE MaKH = ?", [$maKH]);

// Kiểm tra nếu giỏ hàng không có sản phẩm
if (empty($cart_query)) {
    echo '<p>Your cart is empty.</p>';
    return;
}

$subtotal = 0;  // Tổng tiền giỏ hàng


echo '<div class="cart-items-list">';

foreach ($cart_query as $cart_item) {
    $product_id = $cart_item['MaSP'];
    $quantity = $cart_item['SoLuong'];

    // Truy vấn thông tin sản phẩm từ bảng SanPham
    $products = $db->query("SELECT * FROM sanpham WHERE MaSP = ?", [$product_id]);

    if (empty($products)) {
        continue;  // Nếu không tìm thấy sản phẩm, bỏ qua
    }

    $product = $products[0];  // Lấy thông tin sản phẩm

    // Lấy URL hình ảnh sản phẩm
    $image_url = !empty($product['HinhAnh']) ? 'public/images/' . $product['HinhAnh'] : 'public/images/casio0.jpg';
    $price = $product['Gia'];
    $total_price = $price * $quantity;

    // Hiển thị thông tin sản phẩm trong giỏ hàng
    echo '<div class="cart-item" id="product-' . htmlspecialchars($product_id) . '">';
    echo '  <img class="cart-item-image" src="' . htmlspecialchars($image_url) . '" alt="' . htmlspecialchars($product['TenSP']) . '">';
    echo '  <div class="cart-item-info">';
    echo '      <h4>' . htmlspecialchars($product['TenSP']) . '</h4>';
    echo '      <p>Giá      : $' . number_format($price, 2) . '</p>';
    echo '      <p>Số lượng   : ' . intval($quantity) . '</p>';
    echo '      <p>Tổng tiền      : $' . number_format($total_price, 2) . '</p>';
    echo '      <p>Mô tả: ' . htmlspecialchars($product['MoTa']) . '</p>';
    echo '  </div>'; 
    echo '  <input 
                type="checkbox" 
                title="Chọn sản phẩm" 
                class="custom-checkbox" 
                name="selected_products[' . htmlspecialchars($product_id) . ']" 
                value="' . intval($quantity) . '" 
                data-price="' . floatval($price) . '" 
                data-quantity="' . intval($quantity) . '"
            > 
            '; 
    echo '  <form action="remove_from_cart.php" method="POST" class="remove-form">';
    echo '      <input type="hidden" name="maSP" value="' . htmlspecialchars($product_id) . '">';
    echo '      <button type="submit" class="remove-btn">Xóa sản phẩm</button>';
    echo '  </form>';
    echo '</div>';
    
}

echo '</div>';

// Hiển thị tổng tiền giỏ hàng
echo '<div style="flex; flex-direction:column; text-align:center;">';
echo '<p>Tổng số tiền: $<span id="total-selected">0.00</span></p>';
echo '<button type="button" class="btn" onclick="sendSelectedProducts()">Thanh Toán</button>';
echo '</div>';
?>



<script>
function updateSelectedTotal() {
    let checkboxes = document.querySelectorAll('.custom-checkbox');
    let total = 0;
    checkboxes.forEach(cb => {
        if (cb.checked) {
            let price = parseFloat(cb.dataset.price);
            let quantity = parseInt(cb.dataset.quantity);
            total += price * quantity;
        }
    });
    document.getElementById('total-selected').textContent = total.toFixed(2);
}

document.querySelectorAll('.custom-checkbox').forEach(cb => {
    cb.addEventListener('change', updateSelectedTotal);
});

updateSelectedTotal();

function sendSelectedProducts() {
    let checkboxes = document.querySelectorAll('.custom-checkbox');
    let selectedProducts = {};
    let total = 0;

    checkboxes.forEach(cb => {
        if (cb.checked) {
            let match = cb.name.match(/\[(\d+)\]/);
            if (match) {
                let productId = match[1];
                let quantity = parseInt(cb.dataset.quantity);
                let price = parseFloat(cb.dataset.price);
                total += price * quantity;
                selectedProducts[productId] = {
                    quantity: quantity,
                    price: price
                };
            }
        }
    });

    if (Object.keys(selectedProducts).length === 0) {
        alert("Vui lòng chọn ít nhất một sản phẩm để thanh toán.");
        return;
    }

    fetch('save_selected_products.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            selectedProducts: selectedProducts,
            total: total.toFixed(2)
        })
    })
    .then(response => response.text())
    .then(result => {
        if (result === "OK") {
            window.location.href = 'checkout.php';
        } else {
            alert("Lỗi: " + result);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert("Đã xảy ra lỗi khi gửi dữ liệu.");
    });
}
</script>

