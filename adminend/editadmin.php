<?php
include 'databases/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminId = $_POST['admin_id'] ?? '';
    $username = $_POST['username'] ?? '';
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($adminId)) {
        http_response_code(400);
        echo "Admin ID is required.";
        exit;
    }

    // Validate email format
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Invalid email format.";
        exit;
    }

    // Fetch existing admin data
    $stmt = $conn->prepare("SELECT TenDangNhap, HoTen, Email, VaiTro FROM admin WHERE MaAdmin = ?");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo "Admin not found.";
        exit;
    }
    $existingAdmin = $result->fetch_assoc();
    $stmt->close();

    // Use existing values if fields are empty
    $username = !empty($username) ? $username : $existingAdmin['TenDangNhap'];
    $fullname = isset($_POST['fullname']) && $_POST['fullname'] !== '' ? $fullname : $existingAdmin['HoTen']; // Optional fullname
    $email = !empty($email) ? $email : $existingAdmin['Email'];
    $role = !empty($role) ? $role : $existingAdmin['VaiTro'];
    $passwordHash = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : null;

    // Prepare SQL query based on whether password is provided
    if ($passwordHash) {
        $stmt = $conn->prepare("UPDATE admin SET TenDangNhap = ?, HoTen = ?, Email = ?, VaiTro = ?, MatKhau = ?, NgayCapNhat = NOW() WHERE MaAdmin = ?");
        $stmt->bind_param("sssssi", $username, $fullname, $email, $role, $passwordHash, $adminId);
    } else {
        $stmt = $conn->prepare("UPDATE admin SET TenDangNhap = ?, HoTen = ?, Email = ?, VaiTro = ?, NgayCapNhat = NOW() WHERE MaAdmin = ?");
        $stmt->bind_param("ssssi", $username, $fullname, $email, $role, $adminId);
    }

    // Perform update
    if ($stmt->execute()) {
        echo "Admin updated successfully.";
    } else {
        echo "Failed to update admin: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

// Generate the form with pre-filled values
?>
<form method="POST">
    <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($adminId); ?>">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>">
    
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" placeholder="Enter new password (leave blank to keep current)"> <!-- Optional password -->
    
    <label for="fullname">Full Name:</label>
    <input type="text" name="fullname" id="fullname" value="<?php echo htmlspecialchars($fullname); ?>"> <!-- Optional fullname -->
    
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>">
    
    <label for="role">Role:</label>
    <input type="text" name="role" id="role" value="<?php echo htmlspecialchars($role); ?>">
    
    <button type="submit">Update Admin</button>
</form>
<?php
?>
