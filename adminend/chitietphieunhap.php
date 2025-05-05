<?php
include 'databases/db_connection.php';

// Get the MaPhieuNhap from the query string
$maPhieuNhap = isset($_GET['MaPhieuNhap']) ? intval($_GET['MaPhieuNhap']) : 0;

// Fetch details of the specified MaPhieuNhap
$sql = "SELECT ctpn.*, sp.TenSP 
        FROM chitietphieunhap ctpn
        JOIN sanpham sp ON ctpn.MaSP = sp.MaSP
        WHERE ctpn.MaPhieuNhap = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $maPhieuNhap);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Phiếu Nhập</title>
    <!-- Add Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2 class="mb-4 text-center">Chi Tiết Phiếu Nhập - Mã: <?= htmlspecialchars($maPhieuNhap) ?></h2>

    <!-- Back Button -->
    <div class="mb-3">
        <a href="phieunhap.php" class="btn btn-secondary">Quay Lại</a>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
            <tr>
                <th>Mã Chi Tiết</th>
                <th>Tên Sản Phẩm</th>
                <th>Số Lượng</th>
                <th>Đơn Giá</th>
                <th>Thành Tiền</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['MaChiTietPhieuNhap'] ?></td>
                    <td><?= htmlspecialchars($row['TenSP']) ?></td>
                    <td><?= $row['SoLuong'] ?></td>
                    <td><?= number_format($row['DonGia'], 2) ?></td>
                    <td><?= number_format($row['ThanhTien'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
