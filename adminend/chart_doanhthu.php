<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_dong_ho";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
    die("Kết Nối Thất Bại: " . $conn->connect_error);
}

$sql = "SELECT SUM(dh.TongTien) AS DoanhThu, MONTH(dh.NgayDat) AS Thang
FROM donhang dh
GROUP BY Thang
ORDER BY Thang ASC";

$result = $conn->query($sql);

$data = [];
while($row = $result->fetch_assoc()){
    $data[] = $row;
}

header("Content-Type: application/json");
echo json_encode($data);
?>