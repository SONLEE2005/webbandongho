<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_dong_ho";

$conn = new mysqli($servername, $username, $password, $dbname);

if( $conn->connect_error ){
    die("Kết nối thất bại: " . $conn->connect_error);
}

if( $_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST["email"];
    $matKhau = $_POST["password"];

    $sql = "SELECT HoTen, MatKhau
            FROM admin
            WHERE Email = ? AND DaKhoa = 0";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows() > 0){
        $stmt->bind_result($hoTen, $hashed_password);
        $stmt->fetch();

        if($matKhau === $hashed_password){
            $_SESSION["email"] = $email;
            $_SESSION["hoTen"] = $hoTen;

            echo "
                <script>
                    window.parent.location.href = '../adminend/index.php?success=1';
                </script>
            ";
        } else {
            header("Location: login_admin.php?error=wrongpass&email=" . urlencode($email));
            exit();
        }
    } else {
        header("Location: login_admin.php?error=notfound");
        exit;
    }
    $stmt->close();
}
$conn->close();
?>