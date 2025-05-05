<?php
include 'databases/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = isset($_POST['productId']) ? intval($_POST['productId']) : 0;
    $name = $_POST['name'] ?? null;
    $brand = $_POST['brand'] ?? null;
    $price = $_POST['price'] ?? null;
    $description = $_POST['description'] ?? null;
    $quantity = $_POST['quantity'] ?? null;
    $images = isset($_FILES['images']) && $_FILES['images']['error'] === UPLOAD_ERR_OK ? $_FILES['images'] : null;

    if ($productId > 0) {
        $fields = [];
        $params = [];
        $types = '';

        if ($name !== null) {
            $fields[] = "TenSP = ?";
            $params[] = $name;
            $types .= 's';
        }
        if ($brand !== null) {
            $fields[] = "ThuongHieu = ?";
            $params[] = $brand;
            $types .= 's';
        }
        if ($price !== null) {
            $fields[] = "Gia = ?";
            $params[] = $price;
            $types .= 'd';
        }
        if ($description !== null) {
            $fields[] = "MoTa = ?";
            $params[] = $description;
            $types .= 's';
        }
        if ($quantity !== null) {
            $fields[] = "SoLuongTon = ?";
            $params[] = $quantity;
            $types .= 'i';
        }
        if ($images !== null) {
            $uploadDir = '../public/images/';
            $fileName = basename($images['name']);
            $targetPath = $uploadDir . $fileName;

            // Ensure the target directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($images['tmp_name'], $targetPath)) {
                $fields[] = "HinhAnh = ?";
                $params[] = $fileName; // Store only the filename
                $types .= 's';
            } else {
                echo "Failed to move uploaded file to target directory: $targetPath<br>";
                exit();
            }
        }

        // Always update the updated time
        $fields[] = "NgayCapNhat = NOW()";

        if (!empty($fields)) {
            $sql = "UPDATE sanpham SET " . implode(', ', $fields) . " WHERE MaSP = ?";
            $params[] = $productId;
            $types .= 'i';

            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                echo "Product updated successfully.";
            } else {
                echo "Error updating product: " . $conn->error;
            }

            $stmt->close();
        } else {
            echo "No fields to update.";
        }
    } else {
        echo "Invalid product ID.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
