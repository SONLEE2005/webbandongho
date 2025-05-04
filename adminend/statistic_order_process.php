<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_dong_ho";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết Nối Thất Bại: " . $conn->connect_error);
}

if (isset($_GET["form_date"])) {
    $form_date = $_GET["form_date"];
} else {
    $form_date = "";
}

if (isset($_GET["to_date"])) {
    $to_date = $_GET["to_date"];
} else {
    $to_date = "";
}

if (isset($_GET["sort"])) {
    $sort = $_GET["sort"];
} else {
    $sort = "";
}

if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}

$limit = 5;
$offset = ($page - 1) * $limit;

$condition = [];

if (!empty($form_date)) {
    $condition[] = "dh.NgayDat >= '" . $conn->real_escape_string($form_date) . "'";
}

if (!empty($to_date)) {
    $condition[] = "dh.NgayDat <= '" . $conn->real_escape_string($to_date) . "'";
}

$whereDate = "";

if (!empty($condition)) {
    $whereDate = 'WHERE ' . implode(' AND ', $condition);
}

$sqlCountRow = "SELECT COUNT(dh.MaDH) AS Total
FROM donhang dh
JOIN khachhang kh ON dh.MaKH = kh.MaKH
$whereDate";

$queryResult = $conn->query($sqlCountRow);
$totalResult = $queryResult->fetch_assoc();
$totalRow = $totalResult["Total"];
$totalPages = ceil($totalRow / $limit);

$orderBy = $sort == 1 ? "ASC" : "DESC";

$sql = "SELECT dh.MaDH, kh.HoTen, dh.NgayDat, dh.TongTien
FROM donhang dh
JOIN khachhang kh ON dh.MaKH = kh.MaKH
$whereDate
ORDER BY dh.TongTien $orderBy
LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();

$result = $stmt->get_result();

$html = "<table border='1' cellpadding='8' cellspacing='0'>
        <thead>
            <th>Mã Đơn Hàng</th>
            <th>Tên Khách Hàng</th>
            <th>Ngày Mua Hàng</th>
            <th>Tổng Giá Trị Mua Hàng</th>
            <th>Xem Chi Tiết</th>
        </thead>
        <tbody>";

while ($row = $result->fetch_assoc()) {
    $html .= "<tr>
            <td> {$row["MaDH"]} </td>
            <td> {$row["HoTen"]} </td>
            <td> {$row["NgayDat"]} </td>
            <td> {$row["TongTien"]} </td>
            <td><button onclick=\"showOrderDetail('{$row['MaDH']}')\">Xem Chi Tiết</button></td>
            </tr>";
}

$html .= "</tbody></table>";

$pagination = "";
if($totalPages > 1){
    $pagination .= "<div class='pagination'>";
    for($i = 1; $i <= $totalPages; $i++){
        $active = "";
        if($i == $page){
            $active = "active-page";
        }
        $pagination .= "<button class='page-btn $active' onclick='goToPage($i)'>$i</button>";
    }
    $pagination .= "</div>";
}

echo json_encode(["table" => $html, "pagination" => $pagination]);

$conn->close();
?>