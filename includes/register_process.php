<?php
$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "shop_dong_ho";

// Kết nối database
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoTen = $_POST["username"];
    $diaChi = $_POST["diachi"];
    $soDienThoai = $_POST["sdt"];
    $eMail= $_POST["email"];
    $matKhau = password_hash($_POST["password"], PASSWORD_DEFAULT); // Mã hóa mật khẩu

    // Kiểm tra xem tên đăng nhập đã tồn tại chưa
    $checkSql = "SELECT Email FROM khachhang WHERE Email = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $eMail);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Nếu tên đăng nhập đã tồn tại, chuyển hướng về form đăng ký với thông báo lỗi
        $stmt->close();
        $conn->close();
        header("Location: register.php?error=exists&email=" . urlencode($eMail) .
           "&username=" . urlencode($hoTen) .
           "&sdt=" . urlencode($soDienThoai) .
           "&diachi=" . urlencode($diaChi));
        exit();
    }

    $stmt->close();

    $sql = "INSERT INTO khachhang (HoTen, Email, MatKhau, SoDienThoai, DiaChi) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $hoTen, $eMail, $matKhau, $soDienThoai, $diaChi);

    if ($stmt->execute()) {
        header("Location: register.php?success=1"); // Chuyển hướng về trang đăng ký
    } else {
        echo "Lỗi: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
