<?php
include 'databases/db_connection.php';

if (isset($_GET['MaSP'])) {
    $maSP = intval($_GET['MaSP']);

    // Fetch product details
    $sql = "SELECT sp.*, ctsp.* 
            FROM sanpham sp 
            LEFT JOIN chitietsanpham ctsp ON sp.MaSP = ctsp.MaSP 
            WHERE sp.MaSP = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $maSP);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "Invalid product ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .product-details {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .product-details h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .product-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .product-details th, .product-details td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .product-details th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="product-details">
        <div>
            <h1>Product Details</h1>
            <button id="close-redirect">X</button>
        </div>
        <table>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Product Name</td>
                <td><?= htmlspecialchars($product['TenSP']) ?></td>
            </tr>
            <tr>
                <td>Brand</td>
                <td><?= htmlspecialchars($product['ThuongHieu']) ?></td>
            </tr>
            <tr>
                <td>Origin</td>
                <td><?= htmlspecialchars($product['XuatXu']) ?></td>
            </tr>
            <tr>
                <td>Target Audience</td>
                <td><?= htmlspecialchars($product['DoiTuong']) ?></td>
            </tr>
            <tr>
                <td>Product Line</td>
                <td><?= htmlspecialchars($product['DongSanPham']) ?></td>
            </tr>
            <tr>
                <td>Water Resistance</td>
                <td><?= htmlspecialchars($product['KhangNuoc']) ?></td>
            </tr>
            <tr>
                <td>Movement Type</td>
                <td><?= htmlspecialchars($product['LoaiMay']) ?></td>
            </tr>
            <tr>
                <td>Glass Material</td>
                <td><?= htmlspecialchars($product['ChatLieuKinh']) ?></td>
            </tr>
            <tr>
                <td>Strap Material</td>
                <td><?= htmlspecialchars($product['ChatLieuDay']) ?></td>
            </tr>
            <tr>
                <td>Case Size</td>
                <td><?= htmlspecialchars($product['SizeMat']) ?></td>
            </tr>
            <tr>
                <td>Thickness</td>
                <td><?= htmlspecialchars($product['DoDay']) ?></td>
            </tr>
            <tr>
                <td>Series</td>
                <td><?= htmlspecialchars($product['Series']) ?></td>
            </tr>
            <tr>
                <td>Features</td>
                <td><?= htmlspecialchars($product['TienIch']) ?></td>
            </tr>
        </table>
    </div>
    <script>
        document.getElementById('close-redirect').addEventListener('click', function() {
            window.location.href = 'index.php?page=productlist';
        });
    </script>
</body>
</html>
