<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_dong_ho";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết Nối Thất Bại: " . $conn->connect_error);
}

if (isset($_GET["status"])) {
    $filter_status = $_GET["status"];
} else {
    $filter_status = "";
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

if (isset($_GET["location"])) {
    $location = $_GET["location"];
} else {
    $location = "";
}

if (isset($_GET["page"])) {
    $page = intval($_GET["page"]);
} else {
    $page = 1;
}

$limit = 5;
$offset = ($page - 1) * $limit;

$condition = [];

if ($filter_status !== "") {
    $condition[] = "dh.TrangThai = " . intval($filter_status);
}

if ($form_date !== "") {
    $condition[] = "dh.NgayDat >= '" . $conn->real_escape_string($form_date) . "'";
}

if ($to_date !== "") {
    $condition[] = "dh.NgayDat <= '" . $conn->real_escape_string($to_date) . "'";
}

if ($location !== "") {
    $condition[] = "dh.DiaChi LIKE '%" . $conn->real_escape_string($location) . "%'";
}

$where = "";

if (!empty($condition)) {
    $where = 'WHERE ' . implode(' AND ', $condition);
}

$sqlCountRow = "SELECT COUNT(dh.MaDH) AS Total
FROM donhang dh
JOIN khachhang kh ON dh.MaKH = kh.MaKH
$where";

$queryResult = $conn->query($sqlCountRow);
$totalResult = $queryResult->fetch_assoc();
$totalRow = $totalResult["Total"];
$totalPages = ceil($totalRow / $limit);

$sql = "SELECT dh.MaDH, kh.HoTen, dh.DiaChi, dh.NgayDat, dh.TrangThai
FROM `donhang` dh
JOIN khachhang kh
ON dh.MaKH = kh.MaKH 
$where
ORDER BY dh.NgayDat DESC
LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();

$result = $stmt->get_result();

$status = [
    0 => "Chưa Xác Nhận",
    1 => "Đã Xác Nhận",
    2 => "Giao Hàng Thành Công",
    3 => "Đã Hủy Đơn"
];

$html = "<table border='1' cellpadding = '8' cellspacing ='0'>
    <thead>
        <tr>
            <th>Mã Đơn Hàng</th>
            <th>Tên Khách Hàng</th>
            <th>Địa Chỉ</th>
            <th>Ngày Đặt</th>
            <th>Trạng Thái</th>
            <th>Hành Động</th>
        </tr>
    </thead>

    <tbody>";

while ($row = $result->fetch_assoc()) {
    $html .= "<tr>
        <td> {$row["MaDH"]} </td>
        <td> {$row["HoTen"]} </td>
        <td> {$row["DiaChi"]} </td>
        <td> {$row["NgayDat"]} </td>
        <td>" . (isset($status[$row["TrangThai"]]) ? $status[$row["TrangThai"]] : "Trạng thái không hợp lệ") . "</td>
        <td>
            <select onchange=\"updateStatus({$row["MaDH"]}, this.value )\" style=\"min-width: 180px; text-align: center;\">
                <option value=''>Cập Nhật</option>";

    for ($i = $row["TrangThai"] + 1; $i <= 3; $i++) {
        $html .= "<option value='$i'>{$status[$i]}</option>";
    }
    $html .= "</select>
        </td>
    </tr>";
}

$html .= " </tbody>
</table>";

$pagination = "";
if ($totalPages > 1) {
    $pagination .= "<div class='pagination'>";
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = "";
        if ($i == $page) {
            $active = "active-page";
        }
        $pagination .= "<button class='page-btn $active' onclick='goToPage($i)'>$i</button>";
    }
    $pagination .= "</div>";
}

echo json_encode(["table" => $html, "pagination" => $pagination]);

$conn->close();
?>