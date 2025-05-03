<?php
include 'databases/db_connection.php';
?>
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($userId > 0 && in_array($action, ['lock', 'unlock'])) {
        $status = ($action === 'lock') ? 1 : 0; // 1 for locked, 0 for unlocked
        $sql = "UPDATE khachhang SET DaKhoa = ? WHERE MaKH = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $status, $userId);

        if ($stmt->execute()) {
            echo $action === 'lock' ? "User locked successfully." : "User unlocked successfully.";
        } else {
            echo "Error updating user status: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Invalid user ID or action.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
