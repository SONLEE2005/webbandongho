<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_dong_ho";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Kết nối thất bại: " . $conn->connect_error]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoTen = $_POST["username"] ?? '';
    $diaChi = $_POST["diachi"] ?? '';
    $soDienThoai = $_POST["sdt"] ?? '';
    $eMail = $_POST["email"] ?? '';
    $matKhau = password_hash($_POST["password"] ?? '', PASSWORD_DEFAULT);

    // Kiểm tra email đã tồn tại
    $checkSql = "SELECT Email FROM khachhang WHERE Email = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $eMail);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Email đã được đăng ký."]);
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();

    // Thêm người dùng mới
    $sql = "INSERT INTO khachhang (HoTen, Email, MatKhau, SoDienThoai, DiaChi) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $hoTen, $eMail, $matKhau, $soDienThoai, $diaChi);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Đăng ký thành công!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Lỗi khi đăng ký: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
