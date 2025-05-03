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

    // Truy vấn thông tin sản phẩm
    $products = $db->query("SELECT * FROM sanpham WHERE MaSP = ?", [$product_id]);

    if (empty($products)) continue; //Nếu không tìm thấy sản phẩm nào thì bỏ qua

    $product = $products[0];
    $image_url = !empty($product['HinhAnh']) ? 'public/images/' . $product['HinhAnh'] : 'public/images/casio0.jpg';
    $price = $product['Gia'];
    $total_price = $price * $quantity;

    // Hiển thị thông tin sản phẩm trong giỏ hàng
    echo '<div class="cart-item" id="product-' . htmlspecialchars($product_id) . '">';
    echo '  <img class="cart-item-image" src="' . htmlspecialchars($image_url) . '" alt="' . htmlspecialchars($product['TenSP']) . '">';
    echo '  <div class="cart-item-info">';
    echo '      <h4>' . htmlspecialchars($product['TenSP']) . '</h4>';
    echo '      <p>Giá: $' . number_format($price, 2) . '</p>';

    // Nút tăng giảm
    echo '      <div class="quantity-control">';
    echo '          Số lượng:';
    echo '          <button type="button" class="decrease-btn" data-id="' . htmlspecialchars($product_id) . '">-</button>';
    echo '          <input type="text" class="quantity-input" id="qty-' . htmlspecialchars($product_id) . '" value="' . intval($quantity) . '" readonly>';
    echo '          <button type="button" class="increase-btn" data-id="' . htmlspecialchars($product_id) . '">+</button>';
    echo '      </div>';

    echo '      <p>Tổng tiền: $' . number_format($total_price, 2) . '</p>';
    echo '      <p>Mô tả: ' . htmlspecialchars($product['MoTa']) . '</p>';
    echo '  </div>';

    // Checkbox chọn mua
    echo '  <input 
                type="checkbox" 
                title="Chọn sản phẩm" 
                class="custom-checkbox" 
                name="selected_products[' . htmlspecialchars($product_id) . ']" 
                data-price="' . floatval($price) . '"
            >';

    // Nút xóa
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
            let match = cb.name.match(/\[(\d+)\]/); // Lấy productId từ name của checkbox
            if (match) {
                let productId = match[1];
                let quantityInput = document.getElementById('qty-' + productId);
                let quantity = parseInt(quantityInput.value);
                let price = parseFloat(cb.dataset.price);
                total += price * quantity;
            }
        }
    });

    document.getElementById('total-selected').textContent = total.toFixed(2);
}

function sendSelectedProducts() {
    const selectedCheckboxes = document.querySelectorAll('.custom-checkbox:checked');
    const selectedProducts = {};
    let total = 0;

    selectedCheckboxes.forEach(checkbox => {
        const productId = checkbox.name.match(/\[(\d+)\]/)[1]; // Lấy productId từ name đúng cách
        
        const quantityInput = document.getElementById('qty-' + productId); // Lấy input để lấy quantity thực tế
        const quantity = parseInt(quantityInput.value); // Số lượng

        const price = parseFloat(checkbox.dataset.price); // Giá sản phẩm

        selectedProducts[productId] = quantity;
        total += quantity * price; // Tính tổng
    });

    fetch('save_selected_products.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            selectedProducts: selectedProducts,
            total: total
        })
    })
    .then(response => response.text())
    .then(data => {
        if (data === "OK") {
            window.location.href = "checkout.php"; // Nếu thành công, chuyển hướng tới trang thanh toán
        } else {
            alert("Lỗi khi lưu sản phẩm: " + data);
        }
    })
    .catch(error => {
        console.error("Lỗi khi gửi dữ liệu:", error);
    });
}

// Gắn sự kiện cho checkbox khi chọn/bỏ chọn
document.querySelectorAll('.custom-checkbox').forEach(cb => {
    cb.addEventListener('change', updateSelectedTotal);
});

// Gắn sự kiện cho nút tăng/giảm số lượng
document.querySelectorAll('.increase-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        let productId = btn.dataset.id;
        let qtyInput = document.getElementById('qty-' + productId);
        let currentQty = parseInt(qtyInput.value) || 0;
        qtyInput.value = currentQty + 1;
        updateSelectedTotal();
    });
});

document.querySelectorAll('.decrease-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        let productId = btn.dataset.id;
        let qtyInput = document.getElementById('qty-' + productId);
        let currentQty = parseInt(qtyInput.value) || 0;
        if (currentQty > 1) {
            qtyInput.value = currentQty - 1;
            updateSelectedTotal();
        }
    });
});

// Gọi lần đầu khi trang tải
updateSelectedTotal();
</script>

