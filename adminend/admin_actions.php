<?php
include 'databases/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $adminId = $_POST['admin_id'];
        $adminUsername = $_POST['admin_username'];
        $adminPassword = $_POST['admin_password'];
        $adminFullname = $_POST['admin_fullname'];
        $adminEmail = $_POST['admin_email'];
        $adminRole = $_POST['admin_role'];
        $userId = $_POST['user_id'];

        // Check if the user exists in the khachhang table
        $userCheckQuery = "SELECT HoTen, Email FROM khachhang WHERE MaKH = ?";
        $stmt = $conn->prepare($userCheckQuery);
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $userResult = $stmt->get_result();

        if ($userResult->num_rows > 0) {
            // User exists, fetch details
            $user = $userResult->fetch_assoc();
            $adminFullname = $user['HoTen'];
            $adminEmail = $user['Email'];

            // Insert admin data into the admin table
            $addAdminQuery = "INSERT INTO admin (MaAdmin, TenDangNhap, MatKhau, HoTen, Email, VaiTro, NgayTao, NgayCapNhat) 
                              VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $conn->prepare($addAdminQuery);
            $stmt->bind_param("ssssss", $adminId, $adminUsername, $adminPassword, $adminFullname, $adminEmail, $adminRole);
            if ($stmt->execute()) {
                echo "Admin added successfully.";
                header("Location: index.php?page=adminlist");
                exit();
            } else {
                echo "Error adding admin: " . $conn->error;
            }
        } else {
            // User does not exist
            echo "Error: User with MaKH $userId does not exist in the khachhang database.";
        }
    }

    // ...existing code for other actions...
}
?>
