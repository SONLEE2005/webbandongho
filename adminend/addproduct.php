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
    $imagePath = null;
    if (isset($_FILES['images']) && $_FILES['images']['error'][0] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['images']['name'][0]);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['images']['tmp_name'][0], $targetPath)) {
            $imagePath = $targetPath; // Store the full path to the image
        } else {
            echo "Error uploading file: " . htmlspecialchars($_FILES['images']['name'][0]) . "<br>";
        }
    }

    // Insert product data into the database
    $sql = "INSERT INTO sanpham (TenSP, ThuongHieu, Gia, MoTa, SoLuongTon, HinhAnh)
            VALUES ('$name', '$brand', $price, '$description', $quantity, '$imagePath')";

    if ($conn->query($sql) === TRUE) {
        echo "Product added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Redirect back to the product list page
    header("Location: index.php?page=productlist");
    exit();
}
?>
