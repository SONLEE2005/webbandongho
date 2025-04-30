<?php
include 'databases/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($productId > 0) {
        $sql = "DELETE FROM sanpham WHERE MaSP = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);

        if ($stmt->execute()) {
            echo "Product deleted successfully.";
        } else {
            echo "Error deleting product: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Invalid product ID.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
