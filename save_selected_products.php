<?php
session_start();

// Lấy dữ liệu JSON gửi từ phía client
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra lỗi giải mã JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Dữ liệu JSON không hợp lệ.";
    exit;
}

// Kiểm tra xem có dữ liệu và các trường cần thiết không
if (isset($data['selectedProducts']) && isset($data['total'])) {
    // Lưu vào session
    $_SESSION['selectedProducts'] = $data['selectedProducts'];
    $_SESSION['selectedTotal'] = $data['total'];

    // Gửi phản hồi thành công
    echo "OK";
} else {
    // Nếu dữ liệu không hợp lệ, trả về thông báo lỗi
    echo "Dữ liệu không hợp lệ.";
}
?>
