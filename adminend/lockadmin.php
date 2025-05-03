<?php
include 'databases/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminId = isset($_POST['admin_id']) ? intval($_POST['admin_id']) : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($adminId > 0 && in_array($action, ['lock', 'unlock'])) {
        $status = ($action === 'lock') ? 1 : 0; // 1 for locked, 0 for unlocked
        $sql = "UPDATE admin SET DaKhoa = ? WHERE MaAdmin = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $status, $adminId);

        if ($stmt->execute()) {
            echo $action === 'lock' ? "Admin locked successfully." : "Admin unlocked successfully.";
        } else {
            echo "Error updating admin status: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Invalid admin ID or action.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
