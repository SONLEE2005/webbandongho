<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_dong_ho";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
    die("Kết Nối Thất Bại: " . $conn->connect_error);
}

$sql = "SELECT dh.TrangThai, COUNT(dh.MaDH) AS SoLuong
FROM donhang dh
GROUP BY TrangThai";

$result = $conn->query($sql);

$data = [];
while($row = $result->fetch_assoc()){
    $data[] = $row;
}

header("Content-Type: application/json");
echo json_encode($data);
?>