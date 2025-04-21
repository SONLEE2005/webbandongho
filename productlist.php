<?php
    include 'databases/db_connection.php';
    $sql = "SELECT * FROM sanpham";
    $result = $conn->query($sql);
    $products = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product lists</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0px;
        }
        #prod{
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin-bottom: 20px;
        }
        #overlay>div>div{
            display: flex;
            justify-content: space-between;
        }
        #prod button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        td:first-child { /* Product ID */
            min-width: 80px;
        }
        td:nth-child(2) { /* Product Name */
            min-width: 150px;
        }
        td:nth-child(3) { /* Description */
            min-width: 200px;
        }
        td:nth-child(4) { /* Price */
            min-width: 100px;
        }
        td:nth-child(5) { /* Stock */
            min-width: 80px;
        }
        td:nth-child(6) { /* Category */
            min-width: 120px;
        }
        td:nth-child(7) { /* Image */
            min-width: 100px;
        }
        td:nth-child(8) { /* Created */
            min-width: 120px;
        }
        td:nth-child(9) { /* Updated */
            min-width: 120px;
        }
        td:nth-child(10) { /* Actions */
            min-width: 120px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        button {
            padding: 5px 10px;
            margin-right: 5px;
            cursor: pointer;
        }
        h1 {
            text-align: center;
            margin-bottom: 15px; /* Reduced margin */
            font-size: 1.5em; /* Smaller font size */
        }
        form {
            display: grid;
            grid-template-columns: 1fr 2fr; /* Two-column layout */
            gap: 10px; /* Reduced gap between rows/columns */
        }
        label {
            text-align: right;
            font-size: 0.9em; /* Smaller font size */
            padding-top: 5px; /* Align labels vertically */
        }
        input[type="text"], input[type="file"] {
            padding: 5px; /* Reduced padding */
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 0.9em; /* Smaller font size */
        }
        input[type="submit"] {
            grid-column: span 2; /* Span across both columns */
            background-color: #4CAF50;
            color: white;
            padding: 8px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 0.9em; /* Smaller font size */
        }
</style>
</head>
<body>
    <div id="overlay" style="display: none; display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:10; padding-top: 60px;">
        <div style="background-color:#fff; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 600px;">
            <div>
                <h1>Product Addition</h1>
                <button id="close-overlay">X</button>
            </div>
            <form action="addproduct.php" method="post" enctype="multipart/form-data">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required>
    
                <label for="brand">Brand Name:</label>
                <input type="text" id="brand" name="brand" required>
    
                <label for="price">Price:</label>
                <input type="number" step="0.01" id="price" name="price" required>
    
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" cols="50" required></textarea>
    
                <label for="images">Images (Multiple):</label>
                <input type="file" id="images" name="images[]" multiple required>
    
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" required>
    
                <button type="submit">Add product</button>
            </form>
        </div>
    </div>
    <div id="prod">
        <h1>Product List</h1>
        <button id="add-product">Add Product</button>
    </div>
    <table>
        <tr>
            <td>Product ID</td>
            <td>Product Name</td>
            <td>Ma danh muc</td>
            <td>Brand name</td>
            <td>Price</td>
            <td>Description</td>
            <td>Image</td>
            <td>Quantity</td>
            <td>Creation date</td>
            <td>Update time</td>
            <td>Action</td>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr data-id="<?php echo htmlspecialchars($product['MaSP']); ?>">
            <td><?php echo htmlspecialchars($product['MaSP']); ?></td>
            <td><?php echo htmlspecialchars($product['TenSP']); ?></td>
            <td><?php echo htmlspecialchars($product['MaDanhMuc']); ?></td>
            <td><?php echo htmlspecialchars($product['ThuongHieu']); ?></td>
            <td>$<?php echo number_format($product['Gia'], 2); ?></td>
            <td><?php echo htmlspecialchars($product['MoTa']); ?></td>
            <td><img src="<?php echo htmlspecialchars($product['HinhAnh']); ?>" alt="Image" width="50"></td>
            <td><?php echo htmlspecialchars($product['SoLuongTon']); ?></td>
            <td><?php echo htmlspecialchars($product['NgayTao']); ?></td>
            <td><?php echo htmlspecialchars($product['NgayCapNhat']); ?></td>
            <td>
                <button class="details">Details</button>
                <button class="edit">Edit</button>
                <button class="delete">Delete</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <script>
        document.getElementById('add-product').addEventListener('click', function() {
            document.getElementById('overlay').style.display = 'block';
        });
        document.getElementById('close-overlay').addEventListener('click', function() {
            document.getElementById('overlay').style.display = 'none';
        });
        document.querySelector('#overlay>form>input[type="submit"]').addEventListener('click', function() {
            alert('Product added successfully!');
            document.getElementById('overlay').style.display = 'none';
        });

        // Fix for "Details" button
        document.querySelectorAll('.details').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                if (row) {
                    const productId = row.dataset.id;
                    window.location.href = 'proddetails.php?id=' + productId; // Correct URL
                }
            });
        });

        // Add event listeners for sidebar buttons
        document.getElementById('home').addEventListener('click', function() {
            window.location.href = 'index.php?page=main';
        });
        document.getElementById('prod-lists').addEventListener('click', function() {
            window.location.href = 'index.php?page=productlist';
        });
        document.getElementById('users-lists').addEventListener('click', function() {
            window.location.href = 'index.php?page=userlists';
        });
        document.getElementById('status').addEventListener('click', function() {
            window.location.href = 'index.php?page=shippingstatus';
        });
        document.getElementById('statistic').addEventListener('click', function() {
            window.location.href = 'index.php?page=statistic';
        });
    </script>
</body>
</html>
