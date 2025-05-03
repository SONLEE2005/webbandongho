<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['maKH'])) {
    header("Location: ../includes/login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_dong_ho";

// Kết nối database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra phương thức POST và dữ liệu đầu vào
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_id"], $_POST["quantity"])) {
    $maKH = $_SESSION["maKH"];
    $maSP = intval($_POST["product_id"]);
    $soLuong = intval($_POST["quantity"]);
    $ngayThem = date("Y-m-d H:i:s");

    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $sql_check = "SELECT SoLuong FROM giohang WHERE MaKH = ? AND MaSP = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $maKH, $maSP);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Đã có, thì cập nhật số lượng
        $stmt_check->bind_result($soLuongCu);
        $stmt_check->fetch();
        $soLuongMoi = $soLuongCu + $soLuong;

        $stmt_update = $conn->prepare("UPDATE giohang SET SoLuong = ?, NgayThem = ? WHERE MaKH = ? AND MaSP = ?");
        $stmt_update->bind_param("isii", $soLuongMoi, $ngayThem, $maKH, $maSP);
        $stmt_update->execute();
        $stmt_update->close();
    } else {
        // Chưa có, thì chèn mới
        $stmt_insert = $conn->prepare("INSERT INTO giohang (MaKH, MaSP, SoLuong, NgayThem) VALUES (?, ?, ?, ?)");
        $stmt_insert->bind_param("iiis", $maKH, $maSP, $soLuong, $ngayThem);
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    $stmt_check->close();
    $conn->close();

    // Quay về trang trước hoặc chuyển hướng
    header('Location: product-detail.php?id='.$maSP.'&success=1');
    exit();
} else {
    // Nếu dữ liệu không hợp lệ
    header("Location: /index.php?error=invalid_data");
    exit();
}

?>
