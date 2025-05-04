<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_dong_ho";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$sql = "SELECT dh.TrangThai, COUNT(*) AS SoLuong FROM donhang dh GROUP BY dh.TrangThai";
$result = $conn->query($sql);

$statusMap = [
    0 => "Chờ xử lý",
    1 => "Đã xác nhận",
    2 => "Đã giao",
    3 => "Bị hủy"
];

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        "TrangThai" => $statusMap[$row["TrangThai"]],
        "SoLuong" => $row["SoLuong"]
    ];
}

header("Content-Type: application/json");
echo json_encode($data);
?>
