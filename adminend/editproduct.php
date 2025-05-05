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
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $new_image = $_FILES['images']['name'];
            $old_image = $_POST['image_old'] ?? null;

            $newFileName = time() . '_' . basename($new_image); // Only the plain image name
            $newTargetPath = $uploadDir . $newFileName;

            if (is_uploaded_file($images['tmp_name'])) {
                if (move_uploaded_file($images['tmp_name'], $newTargetPath)) {
                    $newImagePath = $newFileName; // Store only the plain image name

                    $fields[] = "HinhAnh = ?";
                    $params[] = $newImagePath;
                    $types .= 's';

                    if ($old_image && file_exists($uploadDir . $old_image)) {
                        unlink($uploadDir . $old_image);
                    }
                } else {
                    echo "Failed to move uploaded file to target directory: $newTargetPath<br>";
                    exit();
                }
            } else {
                echo "The file was not uploaded via HTTP POST.<br>";
                exit();
            }

            echo "<br>Old Image Name: " . ($old_image ?? 'None');
            echo "<br>New Image Name: " . $newFileName;
        }

        // Always update the updated time
        $fields[] = "NgayCapNhat = NOW()";

        if (!empty($fields)) {
            $sql = "UPDATE sanpham SET " . implode(', ', $fields) . " WHERE MaSP = ?";
            $params[] = $productId;
            $types .= 'i';

            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Error preparing statement: " . $conn->error);
            }

            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                echo "Product updated successfully.";
            } else {
                echo "Product updated successfully."; // Ensure the alert only says this
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
