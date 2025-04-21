<?php
include 'databases/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $phonenumber = $conn->real_escape_string($_POST['phonenumber']);
    $address = $conn->real_escape_string($_POST['address']);

    // Insert product data into the database
    $sql = "INSERT INTO khachhang (HoTen, Email, MatKhau, SoDienThoai, DiaChi)
            VALUES ('{$name}', '{$email}', '{$password}', '{$phonenumber}', '{$address}')";

    if ($conn->query($sql) === TRUE) {
        // Get the last inserted product ID
        $userId = $conn->insert_id;
        echo "User added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Redirect back to the product list page
    header("Location: index.php?page=userlists");
    exit();
}
?>