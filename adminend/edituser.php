<?php
include 'databases/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $phonenumber = $_POST['phonenumber'] ?? '';
    $address = $_POST['address'] ?? '';

    if (empty($userId)) {
        http_response_code(400);
        echo "User ID is required.";
        exit;
    }

    // Fetch existing user data
    $stmt = $conn->prepare("SELECT HoTen, Email, SoDienThoai, DiaChi FROM khachhang WHERE MaKH = ?");
    if (!$stmt) {
        http_response_code(500);
        echo "Failed to prepare SELECT statement: " . $conn->error;
        exit;
    }
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo "User not found.";
        exit;
    }
    $existingUser = $result->fetch_assoc();
    $stmt->close();

    // Use existing values if fields are empty
    $username = !empty($username) ? $username : $existingUser['HoTen'];
    $email = !empty($email) ? $email : $existingUser['Email'];
    $phonenumber = !empty($phonenumber) ? $phonenumber : $existingUser['SoDienThoai'];
    $address = !empty($address) ? $address : $existingUser['DiaChi'];

    // Check if no changes after handling empty fields
    if (
        $username === $existingUser['HoTen'] &&
        $email === $existingUser['Email'] &&
        $phonenumber === $existingUser['SoDienThoai'] &&
        $address === $existingUser['DiaChi']
    ) {
        echo "No changes were made to the user.";
        exit;
    }

    // Perform update
    $stmt = $conn->prepare("UPDATE khachhang SET HoTen = ?, Email = ?, SoDienThoai = ?, DiaChi = ? WHERE MaKH = ?");
    if (!$stmt) {
        http_response_code(500);
        echo "Failed to prepare UPDATE statement: " . $conn->error;
        exit;
    }

    $stmt->bind_param("ssssi", $username, $email, $phonenumber, $address, $userId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "User updated successfully.";
        } else {
            echo "No changes were made to the user.";
        }
    } else {
        http_response_code(500);
        echo "Failed to update user: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
