<?php
session_start();
require_once 'includes/database.php';

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['maKH'])) {
    echo "Bạn cần đăng nhập để thực hiện chức năng này.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy MaKH từ session và MaSP từ form
    $maKH = $_SESSION['maKH'];
    $maSP = $_POST['maSP'] ?? '';

    if (!empty($maSP)) {
        $db = new Database();

        // Xoá sản phẩm khỏi giỏ hàng
        $result = $db->query("DELETE FROM giohang WHERE MaKH = ? AND MaSP = ?", [$maKH, $maSP]);

        if ($result !== false) {
            // Quay lại trang giỏ hàng
            header("Location: cart.php");
            exit;
        } else {
            echo "Lỗi khi xoá sản phẩm. Vui lòng thử lại.";
        }
    } else {
        echo "Thiếu mã sản phẩm cần xoá.";
    }
} else {
    echo "Phương thức không hợp lệ.";
}
?>
