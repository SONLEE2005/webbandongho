<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_dong_ho";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
    die("Kết Nối Thất Bại: " . $conn->connect_error);
}

if(isset($_GET["form_date"])){
    $form_date = $_GET["form_date"];
} else {
    $form_date = "";
}

if(isset($_GET["to_date"])){
    $to_date = $_GET["to_date"];
} else {
    $to_date = "";
}

if(isset($_GET["sort"])){
    $sort = $_GET["sort"];
} else {
    $sort = "";
}

$condition = [];

if(!empty($form_date)){
    $condition[] = "dh.NgayDat >= '" . $conn->real_escape_string($form_date) . "'";
}

if(!empty($to_date)){
    $condition[] = "dh.NgayDat <= '" . $conn->real_escape_string($to_date) . "'";
}

$whereDate = "";

if(!empty($condition)){
    $whereDate = 'WHERE ' . implode(' AND ', $condition);
}

$orderBy = $sort == 1 ? "ASC" : "DESC";

$sql = "SELECT dh.MaDH, kh.HoTen, dh.NgayDat, SUM(dh.TongTien) AS TongTien
FROM donhang dh
JOIN khachhang kh ON dh.MaKH = kh.MaKH
$whereDate
GROUP BY kh.MaKH
ORDER BY dh.TongTien $orderBy";

$result = $conn->query($sql);

while($row = $result->fetch_assoc()){
    echo "
        <tr>
            <td> {$row["MaDH"]} </td>
            <td> {$row["HoTen"]} </td>
            <td> {$row["NgayDat"]} </td>
            <td> {$row["TongTien"]} </td>
            <td><button onclick=\"showOrderDetail('{$row['MaDH']}')\">Xem Chi Tiết</button></td>
        </tr>
    ";
}

$conn->close();
?>