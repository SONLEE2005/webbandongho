<?php
    include 'databases/db_connection.php';

    // Get the search query and filters from the URL
    $query = isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '';
    $customPrice = isset($_GET['custom_price']) ? htmlspecialchars($_GET['custom_price']) : '';
    $priceFilter = isset($_GET['price_filter']) ? htmlspecialchars($_GET['price_filter']) : '';
    $priceMoreThan = isset($_GET['price_more_than']) ? floatval($_GET['price_more_than']) : '';
    $priceBelow = isset($_GET['price_below']) ? floatval($_GET['price_below']) : '';
    $releaseDate = isset($_GET['release_date']) ? htmlspecialchars($_GET['release_date']) : '';
    $releaseFrom = isset($_GET['release_from']) ? htmlspecialchars($_GET['release_from']) : '';
    $releaseTo = isset($_GET['release_to']) ? htmlspecialchars($_GET['release_to']) : '';
    $brandFilter = isset($_GET['brand_filter']) ? htmlspecialchars($_GET['brand_filter']) : '';
    $countryFilter = isset($_GET['country_filter']) ? htmlspecialchars($_GET['country_filter']) : '';
    $alphabeticalOrder = isset($_GET['alphabetical_order']) ? htmlspecialchars($_GET['alphabetical_order']) : '';
    $materialFilter = isset($_GET['material']) ? $_GET['material'] : [];
    $glassTypeFilter = isset($_GET['glass_type']) ? $_GET['glass_type'] : [];

    // Pagination logic
    $itemsPerPage = 20;
    $currentPage = isset($_GET['pagenum']) ? max(1, intval($_GET['pagenum'])) : 1;
    $offset = ($currentPage - 1) * $itemsPerPage;

    // Build the SQL query with filters
    $sql = "SELECT * FROM sanpham WHERE 1=1";

    if ($query) {
        $sql .= " AND TenSP LIKE '%" . $conn->real_escape_string($query) . "%'";
    }
    if ($customPrice) {
        $priceRange = explode('-', $customPrice);
        if (count($priceRange) === 2) {
            $sql .= " AND Gia BETWEEN " . floatval($priceRange[0]) . " AND " . floatval($priceRange[1]);
        }
    }
    if ($priceFilter === 'highest_to_lowest') {
        $sql .= " ORDER BY Gia DESC";
    } elseif ($priceFilter === 'lowest_to_highest') {
        $sql .= " ORDER BY Gia ASC";
    }
    if ($priceMoreThan) {
        $sql .= " AND Gia > $priceMoreThan";
    }
    if ($priceBelow) {
        $sql .= " AND Gia < $priceBelow";
    }
    if ($releaseDate) {
        $sql .= " AND YEAR(NgayTao) = " . intval($releaseDate);
    }
    if ($releaseFrom && $releaseTo) {
        $sql .= " AND YEAR(NgayTao) BETWEEN " . intval($releaseFrom) . " AND " . intval($releaseTo);
    }
    if ($brandFilter) {
        $brands = explode(',', $brandFilter);
        $brandConditions = array_map(function($brand) use ($conn) {
            return "ThuongHieu = '" . $conn->real_escape_string(trim($brand)) . "'";
        }, $brands);
        $sql .= " AND (" . implode(' OR ', $brandConditions) . ")";
    }
    if ($countryFilter) {
        $countries = explode(',', $countryFilter);
        $countryConditions = array_map(function($country) use ($conn) {
            return "XuatXu = '" . $conn->real_escape_string(trim($country)) . "'";
        }, $countries);
        $sql .= " AND (" . implode(' OR ', $countryConditions) . ")";
    }
    if ($alphabeticalOrder === 'a_to_z') {
        $sql .= " ORDER BY TenSP ASC";
    } elseif ($alphabeticalOrder === 'z_to_a') {
        $sql .= " ORDER BY TenSP DESC";
    }
    if (!empty($materialFilter)) {
        $materialConditions = array_map(function($material) use ($conn) {
            return "ChatLieu = '" . $conn->real_escape_string($material) . "'";
        }, $materialFilter);
        $sql .= " AND (" . implode(' OR ', $materialConditions) . ")";
    }
    if (!empty($glassTypeFilter)) {
        $glassConditions = array_map(function($glass) use ($conn) {
            return "LoaiKinh = '" . $conn->real_escape_string($glass) . "'";
        }, $glassTypeFilter);
        $sql .= " AND (" . implode(' OR ', $glassConditions) . ")";
    }

    $sql .= " LIMIT $itemsPerPage OFFSET $offset";

    $result = $conn->query($sql);
    $products = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    // Get total number of products for pagination
    $countResult = $conn->query("SELECT COUNT(*) AS total FROM sanpham WHERE 1=1");
    $totalItems = $countResult->fetch_assoc()['total'];
    $totalPages = ceil($totalItems / $itemsPerPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charst="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product lists</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        #prod {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin-bottom: 20px;
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
            font-size: 14px; /* Reduced font size */
        }
        th, td {
            padding: 8px; /* Reduced padding */
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        td:first-child { /* Product ID */
            min-width: 60px; /* Reduced width */
        }
        td:nth-child(2) { /* Product Name */
            min-width: 120px; /* Reduced width */
        }
        td:nth-child(3) { /* Description */
            min-width: 150px; /* Reduced width */
        }
        td:nth-child(4) { /* Price */
            min-width: 80px; /* Reduced width */
        }
        td:nth-child(5) { /* Stock */
            min-width: 60px; /* Reduced width */
        }
        td:nth-child(6) { /* Category */
            min-width: 100px; /* Reduced width */
        }
        td:nth-child(7) { /* Image */
            min-width: 80px; /* Reduced width */
        }
        td:nth-child(8) { /* Created */
            min-width: 100px; /* Reduced width */
        }
        td:nth-child(9) { /* Updated */
            min-width: 100px; /* Reduced width */
        }
        td:nth-child(10) { /* Actions */
            min-width: 100px; /* Reduced width */
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
            margin-bottom: 15px;
            font-size: 1.5em;
        }
        #edit-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 10;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #edit-overlay > div {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 500px;
            margin: auto; /* Ensure it is centered */
            text-align: left;
        }
        #edit-overlay h1 {
            font-size: 1.5em;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        #edit-overlay button#close-edit-overlay {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            float: right;
        }
        #edit-overlay form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        #edit-overlay label {
            font-size: 0.9em;
            color: #555;
        }
        #edit-overlay input[type="text"],
        #edit-overlay input[type="number"],
        #edit-overlay textarea,
        #edit-overlay input[type="file"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 0.9em;
        }
        #edit-overlay button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }
        #edit-overlay button[type="submit"]:hover {
            background-color: #45a049;
        }
        @media (max-width: 768px) {
            #prod {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            table {
                font-size: 0.9em;
                min-width: 900px; /* Ensure table doesn't shrink too much */
            }
            th, td {
                padding: 5px;
            }
            #pagination button {
                padding: 8px 10px;
                font-size: 0.9em;
            }
            #add-product, #filter-products {
                width: auto;
                max-width: 200px;
                font-size: 0.9em;
                padding: 8px 15px;
            }
            #table-wrapper {
                overflow-x: auto; /* Enable horizontal scrolling */
                -webkit-overflow-scrolling: touch; /* Smooth scrolling for mobile devices */
            }
        }
        @media (max-width: 480px) {
            body {
                font-size: 0.8em;
            }
            table {
                font-size: 0.8em;
            }
            form input, form select, form button {
                font-size: 0.8em;
                padding: 3px;
            }
            #pagination button {
                font-size: 0.8em;
                padding: 6px 8px;
            }
            #add-product {
                font-size: 0.8em;
                padding: 6px 12px;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="overlay" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 10; display: flex; justify-content: center; align-items: center;">
        <div style="background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); width: 90%; max-width: 500px; text-align: left;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1 style="font-size: 1.5em; margin: 0; color: #333;">Add Product</h1>
                <button id="close-overlay" style="background-color: red; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer;">X</button>
            </div>
            <form action="addproduct.php" method="post" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
                <label for="name" style="font-size: 0.9em; color: #555;">Product Name:</label>
                <input type="text" id="name" name="name" required style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; font-size: 0.9em;">

                <label for="brand" style="font-size: 0.9em; color: #555;">Brand Name:</label>
                <input type="text" id="brand" name="brand" required style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; font-size: 0.9em;">

                <label for="price" style="font-size: 0.9em; color: #555;">Price:</label>
                <input type="number" step="0.01" id="price" name="price" required style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; font-size: 0.9em;">

                <label for="description" style="font-size: 0.9em; color: #555;">Description:</label>
                <textarea id="description" name="description" rows="4" cols="50" required style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; font-size: 0.9em;"></textarea>

                <label for="images" style="font-size: 0.9em; color: #555;">Images (Multiple):</label>
                <input type="file" id="images" name="image" required style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; font-size: 0.9em;">

                <label for="quantity" style="font-size: 0.9em; color: #555;">Quantity:</label>
                <input type="number" id="quantity" name="quantity" required style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; font-size: 0.9em;">

                <button type="submit" style="background-color: #4CAF50; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 1em;">Add Product</button>
            </form>
        </div>
    </div>
    <div id="edit-overlay">
        <div>
            <button id="close-edit-overlay">X</button>
            <h1>Edit Product</h1>
            <form action="editproduct.php" method="post" enctype="multipart/form-data">
                <input type="hidden" id="edit-product-id" name="productId">
                <label for="edit-name">Product Name:</label>
                <input type="text" id="edit-name" name="name" required>
    
                <label for="edit-brand">Brand Name:</label>
                <input type="text" id="edit-brand" name="brand" required>
    
                <label for="edit-price">Price:</label>
                <input type="number" step="0.01" id="edit-price" name="price" required>
    
                <label for="edit-description">Description:</label>
                <textarea id="edit-description" name="description" rows="4" cols="50" required></textarea>
    
                <label for="edit-images">Image edit:</label>
                <input type="file" id="edit-images" name="images">
    
                <label for="edit-quantity">Quantity:</label>
                <input type="number" id="edit-quantity" name="quantity" required>
    
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>
    <div id="prod">
        <h1>Product List</h1>
        <form method="GET" action="index.php" style="display: flex; gap: 10px;">
            <input type="hidden" name="page" value="productlist">
            <input type="text" name="query" placeholder="Search products..." value="<?php echo htmlspecialchars($query); ?>" style="padding: 5px; border-radius: 5px; border: 1px solid #ccc;">
            <button type="submit" style="padding: 5px 10px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Search</button>
            <button type="button" id="reset-filters" style="padding: 5px 10px; background-color: red; color: white; border: none; border-radius: 5px; cursor: pointer;">X</button>
        </form>
        <button id="filter-products">Filter</button>
        <button id="add-product">Add Product</button>
    </div>

    <div id="filter-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 10;">
        <div style="background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); width: 90%; max-width: 500px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); max-height: 90%; overflow-y: auto; text-align: left;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1 style="font-size: 1.5em; margin: 0; color: #333;">Filter Products</h1>
                <button id="close-filter-overlay" style="background-color: red; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer;">X</button>
            </div>
            <form method="GET" action="index.php" style="display: flex; flex-direction: column; gap: 15px;">
                <input type="hidden" name="page" value="productlist">

                <!-- Price Filters -->
                <h3 style="margin: 0; color: #333;">Price</h3>
                <label for="custom-price" style="font-size: 0.9em; color: #555;">Custom Price Range:</label>
                <input type="text" id="custom-price" name="custom_price" placeholder="e.g., 100-500" style="padding: 8px; border: 1px solid #ccc; border-radius: 5px;">

                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label><input type="radio" name="price_filter" value="highest_to_lowest"> Highest to Lowest</label>
                    <label><input type="radio" name="price_filter" value="lowest_to_highest"> Lowest to Highest</label>
                    <label><input type="radio" name="price_filter" value="more_than"> More than $<input type="text" name="price_more_than" placeholder="xxx" style="width: 50px; padding: 5px; border: 1px solid #ccc; border-radius: 5px;"></label>
                    <label><input type="radio" name="price_filter" value="below"> Below $<input type="text" name="price_below" placeholder="xxx" style="width: 50px; padding: 5px; border: 1px solid #ccc; border-radius: 5px;"></label>
                </div>

                <!-- Release Date Filters -->
                <h3 style="margin: 0; color: #333;">Release Date</h3>
                <label for="release-date" style="font-size: 0.9em; color: #555;">Input Date/Year:</label>
                <input type="text" id="release-date" name="release_date" placeholder="e.g., 2025" style="padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                <label><input type="radio" name="release_filter" value="from_to"> From <input type="text" name="release_from" placeholder="Year" style="width: 50px; padding: 5px; border: 1px solid #ccc; border-radius: 5px;"> to <input type="text" name="release_to" placeholder="Year" style="width: 50px; padding: 5px; border: 1px solid #ccc; border-radius: 5px;"></label>

                <!-- Brand Name Filters -->
                <h3 style="margin: 0; color: #333;">Brand Name</h3>
                <div style="position: relative;">
                    <input type="text" id="brand-input" name="brand_filter" placeholder="Select brands..." style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; width: calc(100% - 40px);">
                    <button type="button" id="brand-plus" style="padding: 8px; border: none; background-color: #4CAF50; color: white; border-radius: 5px; cursor: pointer; position: absolute; right: 0; top: 0;">+</button>
                    <div id="brand-dropdown" style="display: none; position: absolute; top: 40px; left: 0; background-color: white; border: 1px solid #ccc; border-radius: 5px; width: 100%; max-height: 150px; overflow-y: auto; z-index: 100;">
                        <div style="padding: 5px; cursor: pointer;" data-value="Casio">Casio</div>
                        <div style="padding: 5px; cursor: pointer;" data-value="Orient">Orient</div>
                        <div style="padding: 5px; cursor: pointer;" data-value="Citizen">Citizen</div>
                        <div style="padding: 5px; cursor: pointer;" data-value="Olym Planus">Olym Planus</div>
                        <div style="padding: 5px; cursor: pointer;" data-value="Benley">Benley</div>
                        <div style="padding: 5px; cursor: pointer;" data-value="SRwatch">SRwatch</div>
                        <div style="padding: 5px; cursor: pointer;" data-value="Daniel Wellington">Daniel Wellington</div>
                    </div>
                </div>

                <!-- Alphabetical Order -->
                <h3 style="margin: 0; color: #333;">Alphabetical Order</h3>
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label><input type="radio" name="alphabetical_order" value="a_to_z"> A-Z</label>
                    <label><input type="radio" name="alphabetical_order" value="z_to_a"> Z-A</label>
                </div>
                <!-- Country of Origin -->
                <h3 style="margin: 0; color: #333;">Country of Origin</h3>
                <div style="position: relative;">
                    <input type="text" id="country-input" name="country_filter" placeholder="Select countries..." style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; width: calc(100% - 40px);">
                    <button type="button" id="country-plus" style="padding: 8px; border: none; background-color: #4CAF50; color: white; border-radius: 5px; cursor: pointer; position: absolute; right: 0; top: 0;">+</button>
                    <div id="country-dropdown" style="display: none; position: absolute; top: 40px; left: 0; background-color: white; border: 1px solid #ccc; border-radius: 5px; width: 100%; max-height: 150px; overflow-y: auto; z-index: 100;">
                        <div style="padding: 5px; cursor: pointer;" data-value="Japan">Japan</div>
                        <div style="padding: 5px; cursor: pointer;" data-value="USA">USA</div>
                        <div style="padding: 5px; cursor: pointer;" data-value="Germany">Germany</div>
                        <div style="padding: 5px; cursor: pointer;" data-value="France">France</div>
                        <div style="padding: 5px; cursor: pointer;" data-value="India">India</div>
                        <div style="padding: 5px; cursor: pointer;" data-value="China">China</div>
                        <div style="padding: 5px; cursor: pointer;" data-value="Italy">Italy</div>
                    </div>
                </div>

                <button type="submit" style="background-color: #4CAF50; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top: 20px;">Apply</button>
            </form>
        </div>
    </div>

    <div id="table-wrapper">
        <table id="product-table">
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
                <td>
                    <?php if (!empty($product['HinhAnh'])): ?>
                        <img src="<?= '../public/images/' . htmlspecialchars($product['HinhAnh']) ?>" alt="Image" width="50">
                    <?php else: ?>
                        <img src="public/images/casio0.jpg" alt="Default Image" width="50">
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($product['SoLuongTon']); ?></td>
                <td><?php echo htmlspecialchars($product['NgayTao']); ?></td>
                <td><?php echo htmlspecialchars($product['NgayCapNhat']); ?></td>
                <td>
                    <button class="edit" data-id="<?php echo htmlspecialchars($product['MaSP']); ?>">Edit</button>
                    <button class="delete" data-id="<?php echo htmlspecialchars($product['MaSP']); ?>">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Pagination controls -->
    <div id="pagination" style="text-align: center; margin: 20px 0;">
        <?php 
            // Ensure materialFilter and glassTypeFilter are arrays before using implode
            $materialFilter = is_array($materialFilter) ? $materialFilter : [];
            $glassTypeFilter = is_array($glassTypeFilter) ? $glassTypeFilter : [];

            // Preserve all active filters in the pagination links
            $queryParams = [
                'page' => 'productlist',
                'query' => $query,
                'custom_price' => $customPrice,
                'price_filter' => $priceFilter,
                'price_more_than' => $priceMoreThan,
                'price_below' => $priceBelow,
                'release_date' => $releaseDate,
                'release_from' => $releaseFrom,
                'release_to' => $releaseTo,
                'brand_filter' => $brandFilter,
                'country_filter' => $countryFilter,
                'alphabetical_order' => $alphabeticalOrder,
                'material' => implode(',', $materialFilter),
                'glass_type' => implode(',', $glassTypeFilter)
            ];
            $queryString = http_build_query($queryParams);
        ?>
        <?php if ($currentPage > 1): ?>
            <button onclick="window.location.href='index.php?<?php echo $queryString; ?>&pagenum=<?php echo $currentPage - 1; ?>'" style="margin-right: 10px;">Previous</button>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <button onclick="window.location.href='index.php?<?php echo $queryString; ?>&pagenum=<?php echo $i; ?>'" style="margin: 0 5px; <?php echo $i == $currentPage ? 'font-weight: bold;' : ''; ?>">
                <?php echo $i; ?>
            </button>
        <?php endfor; ?>
        <?php if ($currentPage < $totalPages): ?>
            <button onclick="window.location.href='index.php?<?php echo $queryString; ?>&pagenum=<?php echo $currentPage + 1; ?>'" style="margin-left: 10px;">Next</button>
        <?php endif; ?>
    </div>
    <script>
        $(document).ready(function() {
            // Ensure the edit overlay is hidden on page load
            $('#edit-overlay').hide();

            // Ensure the add product overlay is hidden on page load
            $('#overlay').hide();

            // Add Product overlay close button
            $('#close-overlay').on('click', function() {
                $('#overlay').hide();
            });

            // Add Product button functionality
            $('#add-product').on('click', function() {
                $('#overlay').css({
                    display: 'flex',
                    top: '50%',
                    left: '50%',
                    transform: 'translate(-50%, -50%)'
                });
            });

            // Edit Product overlay close button
            $('#close-edit-overlay').on('click', function() {
                $('#edit-overlay').hide();
            });

            // Filter overlay close button
            $('#close-filter-overlay').on('click', function() {
                $('#filter-overlay').hide();
            });

            // Show Filter overlay
            $('#filter-products').on('click', function() {
                $('#filter-overlay').css('display', 'block');
            });

            // Reset filters and search
            $('#reset-filters').on('click', function() {
                window.location.href = 'index.php?page=productlist';
            });

            // Format price to include commas and two decimal places
            function formatPrice(price) {
                return parseFloat(price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            // Edit button functionality
            $(document).on('click', '.edit', function() {
                const productId = $(this).data('id');
                const row = $(this).closest('tr');
                const name = row.find('td:nth-child(2)').text();
                const brand = row.find('td:nth-child(4)').text();
                const price = row.find('td:nth-child(5)').text().replace('$', '').replace(/,/g, ''); // Remove $ and commas
                const description = row.find('td:nth-child(6)').text() || ''; // Default to empty string if null
                const quantity = parseInt(row.find('td:nth-child(8)').text());

                $('#edit-product-id').val(productId);
                $('#edit-name').val(name);
                $('#edit-brand').val(brand);
                $('#edit-price').val(price); // Set the price value correctly
                $('#edit-description').val(description);
                $('#edit-quantity').val(quantity);

                $('#edit-overlay').css('display', 'block');
            });

            // Submit Edit Product form
            $('#edit-overlay form').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this); // Use FormData to handle file uploads

                $.ajax({
                    url: 'editproduct.php',
                    type: 'POST',
                    data: formData,
                    processData: false, // Prevent jQuery from processing the data
                    contentType: false, // Prevent jQuery from setting the content type
                    success: function(response) {
                        alert(response);
                        if (response.includes("successfully")) {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        alert("Error: " + xhr.responseText);
                    }
                });
            });

            // Delete button functionality
            $(document).on('click', '.delete', function() {
                const productId = $(this).data('id');
                if (confirm("Are you sure you want to delete this product?")) {
                    $.ajax({
                        url: 'deleteproduct.php',
                        type: 'POST',
                        data: { id: productId },
                        success: function(response) {
                            alert(response);
                            if (response.includes("successfully")) {
                                location.reload();
                            }
                        },
                        error: function(xhr) {
                            alert("Error: " + xhr.responseText);
                        }
                    });
                }
            });

            // Toggle brand dropdown
            $('#brand-plus').on('click', function() {
                $('#brand-dropdown').toggle();
            });

            // Add brand to input with multiple selections
            $('#brand-dropdown div').on('click', function() {
                const selectedBrand = $(this).data('value');
                const currentBrands = $('#brand-input').val().split(',').map(brand => brand.trim()).filter(Boolean);
                if (!currentBrands.includes(selectedBrand)) {
                    currentBrands.push(selectedBrand);
                    $('#brand-input').val(currentBrands.join(', '));
                }
                $('#brand-dropdown').hide();
            });

            // Toggle country dropdown
            $('#country-plus').on('click', function() {
                $('#country-dropdown').toggle();
            });

            // Add country to input with multiple selections
            $('#country-dropdown div').on('click', function() {
                const selectedCountry = $(this).data('value');
                const currentCountries = $('#country-input').val().split(',').map(country => country.trim()).filter(Boolean);
                if (!currentCountries.includes(selectedCountry)) {
                    currentCountries.push(selectedCountry);
                    $('#country-input').val(currentCountries.join(', '));
                }
                $('#country-dropdown').hide();
            });

            // Close dropdowns when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#brand-plus, #brand-dropdown').length) {
                    $('#brand-dropdown').hide();
                }
                if (!$(e.target).closest('#country-plus, #country-dropdown').length) {
                    $('#country-dropdown').hide();
                }
            });

            // Search filter functionality
            $('input[name="query"]').on('input', function() {
                const query = $(this).val().trim();
                const filter = $('select[name="filter"]').val() || '';

                $.ajax({
                    url: 'index.php',
                    type: 'GET',
                    data: {
                        page: 'productlist',
                        query: query,
                        filter: filter
                    },
                    success: function(response) {
                        const updatedTable = $(response).find('#product-table').html();
                        $('#product-table').html(updatedTable);

                        const updatedPagination = $(response).find('#pagination').html();
                        $('#pagination').html(updatedPagination);
                    },
                    error: function(xhr) {
                        console.error("Error fetching filtered results:", xhr.responseText);
                    }
                });
            });

            // Reset filters and reload the page
            $('#reset-filters').on('click', function() {
                $('input[name="query"]').val('');
                $('select[name="filter"]').val('');
                window.location.href = 'index.php?page=productlist';
            });

            // Filter dropdown change event
            $('select[name="filter"]').on('change', function() {
                const query = $('input[name="query"]').val().trim();
                const filter = $(this).val();

                $.ajax({
                    url: 'index.php',
                    type: 'GET',
                    data: {
                        page: 'productlist',
                        query: query,
                        filter: filter
                    },
                    success: function(response) {
                        const updatedTable = $(response).find('#product-table').html();
                        $('#product-table').html(updatedTable);

                        const updatedPagination = $(response).find('#pagination').html();
                        $('#pagination').html(updatedPagination);
                    },
                    error: function(xhr) {
                        console.error("Error fetching filtered results:", xhr.responseText);
                    }
                });
            });

            // Function to fetch filtered results
            function fetchFilteredResults() {
                const query = $('input[name="query"]').val().trim();
                const customPrice = $('#custom-price').val();
                const priceFilter = $('input[name="price_filter"]:checked').val();
                const priceMoreThan = $('input[name="price_more_than"]').val();
                const priceBelow = $('input[name="price_below"]').val();
                const releaseDate = $('#release-date').val();
                const releaseFrom = $('input[name="release_from"]').val();
                const releaseTo = $('input[name="release_to"]').val();
                const brandFilter = $('#brand-input').val();
                const countryFilter = $('#country-input').val();
                const alphabeticalOrder = $('input[name="alphabetical_order"]:checked').val();
                const materialFilter = $('input[name="material[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
                const glassTypeFilter = $('input[name="glass_type[]"]:checked').map(function() {
                    return $(this).val();
                }).get();

                $.ajax({
                    url: 'index.php',
                    type: 'GET',
                    data: {
                        page: 'productlist',
                        query: query,
                        custom_price: customPrice,
                        price_filter: priceFilter,
                        price_more_than: priceMoreThan,
                        price_below: priceBelow,
                        release_date: releaseDate,
                        release_from: releaseFrom,
                        release_to: releaseTo,
                        brand_filter: brandFilter,
                        country_filter: countryFilter,
                        alphabetical_order: alphabeticalOrder,
                        material: materialFilter,
                        glass_type: glassTypeFilter
                    },
                    success: function(response) {
                        const updatedTable = $(response).find('#product-table').html();
                        $('#product-table').html(updatedTable);

                        const updatedPagination = $(response).find('#pagination').html();
                        $('#pagination').html(updatedPagination);
                    },
                    error: function(xhr) {
                        console.error("Error fetching filtered results:", xhr.responseText);
                    }
                });
            }

            // Apply filter button functionality
            $('#filter-overlay form').on('submit', function(e) {
                e.preventDefault();
                fetchFilteredResults();
                $('#filter-overlay').hide();
            });

            // Reset filters and reload the page
            $('#reset-filters').on('click', function() {
                $('input[name="query"]').val('');
                $('#custom-price').val('');
                $('input[name="price_filter"]').prop('checked', false);
                $('input[name="price_more_than"]').val('');
                $('input[name="price_below"]').val('');
                $('#release-date').val('');
                $('input[name="release_from"]').val('');
                $('input[name="release_to"]').val('');
                $('#brand-input').val('');
                $('#country-input').val('');
                $('input[name="alphabetical_order"]').prop('checked', false);
                $('input[name="material[]"]').prop('checked', false);
                $('input[name="glass_type[]"]').prop('checked', false);
                window.location.href = 'index.php?page=productlist';
            });

            // Close filter overlay
            $('#close-filter-overlay').on('click', function() {
                $('#filter-overlay').hide();
            });

            // Show filter overlay
            $('#filter-products').on('click', function() {
                $('#filter-overlay').show();
            });
        });

        // Add event listeners for sidebar buttons
        const homeButton = document.getElementById('home');
        if (homeButton) {
            homeButton.addEventListener('click', function() {
                window.location.href = 'homepage.php'; // Redirect to homepage.php
            });
        }

        const prodListsButton = document.getElementById('prod-lists');
        if (prodListsButton) {
            prodListsButton.addEventListener('click', function() {
                window.location.href = 'productlist.php'; // Redirect to product list
            });
        }

        const usersListsButton = document.getElementById('users-lists');
        if (usersListsButton) {
            usersListsButton.addEventListener('click', function() {
                window.location.href = 'index.php?page=userlists';
            });
        }

        const statusButton = document.getElementById('status');
        if (statusButton) {
            statusButton.addEventListener('click', function() {
                window.location.href = 'index.php?page=shippingstatus';
            });
        }

        const statisticButton = document.getElementById('statistic');
        if (statisticButton) {
            statisticButton.addEventListener('click', function() {
                window.location.href = 'index.php?page=statistic';
            });
        }
    </script>
</body>
</html>
