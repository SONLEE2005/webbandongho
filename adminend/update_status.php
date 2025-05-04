<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["hoTen"])) {
    http_response_code(403);
    echo "Bạn Không Có Quyền Thực Hiện Hành Động Này";
    exit();
}

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["id"]) || !isset($data["status"])) {
    echo json_encode(["error" => "Thiếu Dữ Liệu Đầu Vào"]);
    exit();
}


$id = intval($data["id"]);
$newStatus = intval($data["status"]);


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_dong_ho";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết Nối Thất Bại: " . $conn->connect_error);
}

$sql = "SELECT TrangThai
    FROM donhang
    WHERE MaDH = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($currentStatus);
if ($stmt->fetch()) {
    if ($newStatus > $currentStatus) {
        $stmt->close();
        $sqlUpdate = "UPDATE donhang SET TrangThai = ? WHERE MaDH = ?";
        $stmt2 = $conn->prepare($sqlUpdate);
        $stmt2->bind_param("ii", $newStatus, $id);
        if ($stmt2->execute()) {
            echo "Cập Nhật Thành Công";
        } else {
            echo "Cập Nhật Thất Bại" . $stmt2->error;
        }
        $stmt2->close();
    } else {
        echo "Không Thể Cập Nhật Trạng Thái Ngược Hoặc Giống Nhau";
    }
} else {
    echo "Không Tìm Thấy Đơn Hàng";
    $stmt->close();
}
$conn->close();
