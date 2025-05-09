<?php
session_start();
$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "shop_dong_ho";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eMail = $_POST["email"];
    $matKhau = $_POST["password"];

    // Kiểm tra xem thông tin tài khoản (email) có tồn tại không
    $sql = "SELECT MaKH, HoTen, MatKhau, SoDienThoai, DaKhoa, DiaChi FROM khachhang WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $eMail);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($maKH, $hoTen, $hashed_password, $soDienThoai, $daKhoa, $diaChi);
        $stmt->fetch();
        
        // Kiểm tra mật khẩu
        if (password_verify($matKhau, $hashed_password)) {
            if($daKhoa=="Yes"){
                header("Location: login.php?error=daKhoa");
                exit();
            }
            $_SESSION["email"] = $eMail; // Lưu tên đăng nhập vào session
            $_SESSION["hoTen"] = $hoTen; // Lưu họ tên
            $_SESSION["diaChi"]= $diaChi;  //Lưu địa chỉ
            $_SESSION["soDienThoai"]= $soDienThoai;  //Lưu số điện thoại
            $_SESSION["maKH"]= $maKH;  //Lưu mã khách hàng
            echo "<script>
                window.parent.location.href = '../index.php?success=1';
            </script>";
        } else {
            header("Location: login.php?error=wrongpass&email=" . urlencode($eMail));
            exit();
        }
    } else {
        header("Location: login.php?error=notfound");
        exit();
    }
    
    $stmt->close();
}
$conn->close();
?>
