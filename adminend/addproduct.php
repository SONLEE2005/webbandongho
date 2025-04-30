<?php
include 'databases/db_connection.php';

// Directory to store uploaded images
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = $conn->real_escape_string($_POST['name']);
    $brand = $conn->real_escape_string($_POST['brand']);
    $price = floatval($_POST['price']);
    $description = $conn->real_escape_string($_POST['description']);
    $quantity = intval($_POST['quantity']);

    // Handle image uploads
    $imageFilenames = [];
    if (isset($_FILES['images']) && $_FILES['images']['error'][0] === UPLOAD_ERR_OK) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['images']['name'][$key]);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                $imageFilenames[] = $fileName;
            } else {
                echo "Error uploading file: " . htmlspecialchars($_FILES['images']['name'][$key]) . "<br>";
            }
        }
    }

    // Insert product data into the database
    $sql = "INSERT INTO sanpham (TenSP, ThuongHieu, Gia, MoTa, SoLuongTon)
            VALUES ('$name', '$brand', $price, '$description', $quantity)";

    if ($conn->query($sql) === TRUE) {
        // Get the last inserted product ID
        $productId = $conn->insert_id;

        // Save image filenames to the database (assuming you have an images table or store them in a comma-separated string)
        if (!empty($imageFilenames)) {
            $imageData = implode(',', $imageFilenames); // Store filenames as a comma-separated string
            $updateSql = "UPDATE sanpham SET HinhAnh = '$imageData' WHERE MaSP = $productId";
            $conn->query($updateSql);
        }

        echo "Product added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Redirect back to the product list page
    header("Location: index.php?page=productlist");
    exit();
}
?>
