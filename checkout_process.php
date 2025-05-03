<?php
session_start();
require_once 'includes/database.php';

if (!isset($_SESSION['maKH'])) {
    echo "Bạn chưa đăng nhập!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['products'])) {
    echo "Dữ liệu không hợp lệ!";
    exit;
}

$maKH = $_SESSION['maKH'];

$ngayDat = date('Y-m-d H:i:s');
$tongTien = floatval($_POST['total'] ?? 0);
$ghiChu = $_POST['note'] ?? '';
$tenNguoiNhan = $_POST['customer_name'] ?? 'Không rõ';
$soDienThoai = $_POST['customer_phone'] ?? 'Không rõ';
$diaChi = $_POST['customer_address'] ?? 'Không rõ';
$trangThai = 'Đang xử lý';
$giamGia= $_POST['discount'];

$phuongThucTT = ($_POST['payment'] ?? '') === 'cod' ? 'Thanh toán khi nhận hàng' : 'Thanh toán Online';
$ngayThanhToan = ($phuongThucTT === 'Thanh toán Online') ? date('Y-m-d H:i:s') : 'Chưa có';
$soTien = floatval($_POST['total'] ?? 0);
$trangThaiTT = ($phuongThucTT === 'Thanh toán Online') ? 'Đã thanh toán' : 'Chưa thanh toán';

$db = new Database();
$conn = $db->getConnection(); 

try {
    // Bắt đầu transaction
    $conn->begin_transaction();

    // Chèn vào bảng donhang
    $sql1 = "INSERT INTO donhang (MaKH, NgayDat, GiamGia, TongTien, GhiChu, TenNguoiNhan, SoDienThoai, DiaChi, TrangThai) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $db->query($sql1, [$maKH, $ngayDat,$giamGia, $tongTien, $ghiChu, $tenNguoiNhan, $soDienThoai, $diaChi, $trangThai]);

    $maDH = $db->getLastInsertId();

    // Chèn vào chitietdonhang
    foreach ($_POST['products'] as $product) {
        $maSP = intval($product['id']);
        $soLuong = intval($product['quantity']);
        $donGia = floatval($product['price']);
        $thanhTien = $soLuong * $donGia;

        $sql2 = "INSERT INTO chitietdonhang (MaDH, MaSP, SoLuong, DonGia, ThanhTien)
                 VALUES (?, ?, ?, ?, ?)";
        $db->query($sql2, [$maDH, $maSP, $soLuong, $donGia, $thanhTien]);
    }

    //Chèn vào thanhtoan
    $sql3= "INSERT INTO thanhtoan (MaDH, PhuongThucTT, NgayThanhToan, SoTien, TrangThaiTT) VALUES (?, ?, ?, ?, ?)";
    $db->query($sql3, [$maDH, $phuongThucTT, $ngayThanhToan, $soTien, $trangThaiTT]);

    $conn->commit();

    //Xoá sản phẩm đã thanh toán khỏi giỏ hàng
    foreach ($_POST['products'] as $product) {
        $maSP = intval($product['id']);
        $sqlDelete = "DELETE FROM giohang WHERE MaKH = ? AND MaSP = ?";
        $db->query($sqlDelete, [$maKH, $maSP]);
    }

    header("Location: hoadon.php?maDH={$maDH}");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    echo "<p>❌ Lỗi khi đặt hàng: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
