<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_dong_ho";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$sql = "SELECT SUM(dh.TongTien) AS DoanhThu
 FROM donhang dh";

$result = $conn->query($sql);

$row = $result->fetch_assoc();
if (!$row || !isset($row["DoanhThu"])) {
    die(json_encode(["error" => "Không có dữ liệu doanh thu"]));
}
$data = ["Doanh Thu" => number_format($row["DoanhThu"], 0, ',', '.')];

header("Content-Type: application/json");

echo json_encode ($data);