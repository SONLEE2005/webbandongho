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

$sql = "SELECT dh.MaDH, kh.HoTen, dh.DiaChi, dh.NgayDat, dh.TrangThai
FROM `donhang` dh
JOIN khachhang kh
ON dh.MaKH = kh.MaKH 
$where
ORDER BY dh.NgayDat DESC";

$result = $conn->query($sql);

$status = [
    0 => "Chưa Xác Nhận",
    1 => "Đã Xác Nhận",
    2 => "Giao Hàng Thành Công",
    3 => "Đã Hủy Đơn"
];

while ($row = $result->fetch_assoc()) {
     echo "<tr>
        <td> {$row["MaDH"]} </td>
        <td> {$row["HoTen"]} </td>
        <td> {$row["DiaChi"]} </td>
        <td> {$row["NgayDat"]} </td>
        <td>" . (isset($status[$row["TrangThai"]]) ? $status[$row["TrangThai"]] : "Trạng thái không hợp lệ") . "</td>
        <td>
            <select onchange=\"updateStatus({$row["MaDH"]}, this.value )\" style=\"min-width: 180px; text-align: center;\">
                <option value=''>Cập Nhật</option>";
                
                for ($i = $row["TrangThai"] + 1; $i <= 3; $i++) {
                    echo "<option value='$i'>{$status[$i]}</option>";
                }
            echo "</select>
        </td>
    </tr>";
}

$conn->close();
?>