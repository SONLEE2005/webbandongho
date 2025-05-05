<?php
include 'databases/db_connection.php';

// Directory to store uploaded images
$uploadDir = '../public/images/';
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

    // Handle single image upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = $fileName; // Store only the filename
        } else {
            echo "Failed to move uploaded file to target directory: $targetPath<br>";
            exit();
        }
    } else {
        echo "No file uploaded or file upload error.<br>";
        exit();
    }

    // Debugging the image path
    if ($imagePath === null) {
        echo "Image Path is null.<br>";
        exit();
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
