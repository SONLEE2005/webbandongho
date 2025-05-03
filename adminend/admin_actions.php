<?php
include 'databases/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $adminId = $_POST['admin_id'] ?? null; // Allow admin_id to be null for adding
        $adminUsername = $_POST['admin_username'] ?? '';
        $adminPassword = $_POST['admin_password'] ?? '';
        $adminFullname = $_POST['admin_fullname'] ?? '';
        $adminEmail = $_POST['admin_email'] ?? '';
        $adminRole = $_POST['admin_role'] ?? '';
        $userId = $_POST['user_id'] ?? null;

        if (empty($adminUsername) || empty($adminPassword) || empty($adminFullname) || empty($adminEmail) || empty($adminRole)) {
            echo "Error: All fields are required.";
            exit();
        }

        if (!empty($userId)) {
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
            } else {
                echo "Error: User with MaKH $userId does not exist in the khachhang database.";
                exit();
            }
        }

        // Check for duplicate username
        $duplicateCheckQuery = "SELECT * FROM admin WHERE TenDangNhap = ?";
        $stmt = $conn->prepare($duplicateCheckQuery);
        $stmt->bind_param("s", $adminUsername);
        $stmt->execute();
        $duplicateResult = $stmt->get_result();

        if ($duplicateResult->num_rows > 0) {
            echo "Error: Username '$adminUsername' already exists. Please choose a different username.";
            exit();
        }

        // Insert admin data into the admin table
        $addAdminQuery = "INSERT INTO admin (TenDangNhap, MatKhau, HoTen, Email, VaiTro, NgayTao, NgayCapNhat) 
                          VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($addAdminQuery);
        $stmt->bind_param("sssss", $adminUsername, password_hash($adminPassword, PASSWORD_BCRYPT), $adminFullname, $adminEmail, $adminRole);
        if ($stmt->execute()) {
            echo "Admin added successfully.";
            header("Location: index.php?page=adminlist");
            exit();
        } else {
            echo "Error adding admin: " . $conn->error;
        }
    }

    if ($action === 'edit') {
        $adminId = $_POST['admin_id'] ?? ''; // Ensure admin_id is retrieved
        $adminUsername = $_POST['admin_username'] ?? '';
        $adminPassword = $_POST['admin_password'] ?? '';
        $adminFullname = $_POST['admin_fullname'] ?? '';
        $adminEmail = $_POST['admin_email'] ?? '';
        $adminRole = $_POST['admin_role'] ?? '';

        if (empty($adminId)) {
            echo "Error: Admin ID is required.";
            exit();
        }

        // Build the update query dynamically based on provided fields
        $updateFields = [];
        $params = [];
        $types = '';

        if (!empty($adminUsername)) {
            $updateFields[] = "TenDangNhap = ?";
            $params[] = $adminUsername;
            $types .= 's';
        }
        if (!empty($adminPassword)) {
            $updateFields[] = "MatKhau = ?";
            $params[] = password_hash($adminPassword, PASSWORD_BCRYPT);
            $types .= 's';
        }
        if (!empty($adminFullname)) {
            $updateFields[] = "HoTen = ?";
            $params[] = $adminFullname;
            $types .= 's';
        }
        if (!empty($adminEmail)) {
            $updateFields[] = "Email = ?";
            $params[] = $adminEmail;
            $types .= 's';
        }
        if (!empty($adminRole)) {
            $updateFields[] = "VaiTro = ?";
            $params[] = $adminRole;
            $types .= 's';
        }

        $updateFields[] = "NgayCapNhat = NOW()"; // Always update the timestamp
        $query = "UPDATE admin SET " . implode(', ', $updateFields) . " WHERE MaAdmin = ?";
        $params[] = $adminId;
        $types .= 's';

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            echo "Error preparing statement: " . $conn->error;
            exit();
        }

        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            echo "Admin updated successfully.";
        } else {
            echo "Error updating admin: " . $stmt->error;
        }

        $stmt->close();
        exit();
    }
}
?>
