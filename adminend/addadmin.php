<?php
include 'databases/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);

    // Insert admin data into the database
    $sql = "INSERT INTO admin (TenDangNhap, MatKhau, HoTen, Email, VaiTro, NgayTao)
            VALUES ('{$username}', '{$password}', '{$fullname}', '{$email}', '{$role}', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "Admin added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Redirect back to the admin list page
    header("Location: index.php?page=adminlist");
    exit();
}
?>
