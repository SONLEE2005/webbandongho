<?php
    include 'databases/db_connection.php';

    // Get the search query and filters from the URL
    $query = isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '';
    $sort = isset($_GET['sort']) ? htmlspecialchars($_GET['sort']) : '';
    $province = isset($_GET['province']) ? htmlspecialchars($_GET['province']) : '';

    // Pagination logic
    $itemsPerPage = 20;
    $currentPage = isset($_GET['pagenum']) ? max(1, intval($_GET['pagenum'])) : 1;
    $offset = ($currentPage - 1) * $itemsPerPage;

    // Modify SQL query to include search and filters
    $sql = "SELECT * FROM khachhang WHERE 1=1";
    if ($query) {
        $sql .= " AND HoTen LIKE '%" . $conn->real_escape_string($query) . "%'";
    }
    if ($province) {
        $sql .= " AND DiaChi LIKE '%" . $conn->real_escape_string($province) . "%'";
    }
    if ($sort === 'name_asc') {
        $sql .= " ORDER BY HoTen ASC";
    } elseif ($sort === 'name_desc') {
        $sql .= " ORDER BY HoTen DESC";
    } elseif ($sort === 'recent') {
        $sql .= " ORDER BY NgayTao DESC";
    }
    $sql .= " LIMIT $itemsPerPage OFFSET $offset";

    $result = $conn->query($sql);
    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    // Get total user count
    $countResult = $conn->query("SELECT COUNT(*) as total FROM khachhang WHERE 1=1");
    $totalUsers = $countResult->fetch_assoc()['total'];
    $totalPages = ceil($totalUsers / $itemsPerPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: 100%; /* Ensure the body takes the full height of the viewport */
            display: flex;
            flex-direction: column; /* Make the body a flex container */
        }
        body {
            font-family: Arial, sans-serif;
        }
        #userlist{
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        #overlay-content{
            display: none; /* Initially hidden */
            position: fixed; /* Fixed position to cover the entire screen */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            z-index: 10; /* Ensure it appears above other content */
            padding-top: 60px; /* Space for the close button */
        }
        #overlay-content>div>div{
            display: flex;
            justify-content: space-between;
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
            min-width: 160px;
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
        #filter-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 10;
            padding-top: 60px;
        }
        #filter-overlay > div {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
        }
        #filter-overlay button {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        #lock-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 10;
            padding-top: 60px;
        }
        #lock-overlay > div {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow */
            width: 80%;
            max-width: 400px;
            text-align: center; /* Center-align content */
        }
        #lock-overlay h2 {
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #333;
        }
        #lock-overlay p {
            margin-bottom: 20px;
            font-size: 1.1em;
            color: #555;
        }
        #lock-overlay button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            margin: 5px;
        }
        #lock-overlay button:hover {
            background-color: #45a049;
        }
        #lock-overlay button.cancel {
            background-color: red;
        }
        #lock-overlay button.cancel:hover {
            background-color: darkred;
        }
        #pagination button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }
        #pagination button:hover {
            background-color: #45a049;
        }
        #pagination button[style*="font-weight: bold;"] {
            background-color: #333;
            color: white;
            font-weight: bold;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="userlist">
        <h1>User List</h1>
        <form method="GET" action="index.php" style="display: flex; gap: 10px;">
            <input type="hidden" name="page" value="userlists">
            <input type="text" name="query" placeholder="Search users..." value="<?php echo htmlspecialchars($query); ?>" style="padding: 5px; border-radius: 5px; border: 1px solid #ccc;">
            <select name="sort" style="padding: 5px; border-radius: 5px; border: 1px solid #ccc;">
                <option value="">Sort by</option>
                <option value="recent" <?php echo $sort === 'recent' ? 'selected' : ''; ?>>Recent</option>
                <option value="name_asc" <?php echo $sort === 'name_asc' ? 'selected' : ''; ?>>Name (A-Z)</option>
                <option value="name_desc" <?php echo $sort === 'name_desc' ? 'selected' : ''; ?>>Name (Z-A)</option>
            </select>
            <select name="province" style="padding: 5px; border-radius: 5px; border: 1px solid #ccc;">
                <option value="">Filter by Province</option>
                <option value="Hanoi" <?php echo $province === 'Hanoi' ? 'selected' : ''; ?>>Hanoi</option>
                <option value="Ho Chi Minh" <?php echo $province === 'Ho Chi Minh' ? 'selected' : ''; ?>>Ho Chi Minh</option>
                <option value="Da Nang" <?php echo $province === 'Da Nang' ? 'selected' : ''; ?>>Da Nang</option>
                <!-- Add more provinces as needed -->
            </select>
            <button type="submit" style="padding: 5px 10px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Apply</button>
            <button type="button" id="reset-filters" style="padding: 5px 10px; background-color: red; color: white; border: none; border-radius: 5px; cursor: pointer;">Reset</button>
        </form>
        <button id="filter-users">Filter</button>
        <button id="add-user">Add User</button>
    </div>
    <div id="overlay-content" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:10; padding-top: 60px;">
        <div style="background-color:#fff; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 600px;">
            <div>
                <h2>Add User</h2>
                <button onclick="document.getElementById('overlay-content').style.display='none'">Close</button>
            </div>
            <form action="adduser.php" method="post">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username"><br><br>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email"><br><br>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password"><br><br>
                <label for="phonenumber">Phone number:</label><br>
                <input type="text" id="phonenumber" name="phonenumber"><br><br>
                <label for="address">Address:</label><br>
                <input type="text" id="address" name="address"><br><br>
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>    
    <div id="edit-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:10; padding-top: 60px;">
        <div style="background-color:#fff; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 600px;">
            <div>
                <h2>Edit User</h2>
                <button onclick="document.getElementById('edit-overlay').style.display='none'">Close</button>
            </div>
            <form id="edit-user-form" action="edituser.php" method="post">
                <input type="hidden" id="edit-user-id" name="user_id">
                <label for="edit-username">Username:</label><br>
                <input type="text" id="edit-username" name="username"><br><br>
                <label for="edit-email">Email:</label><br>
                <input type="email" id="edit-email" name="email"><br><br>
                <label for="edit-phonenumber">Phone number:</label><br>
                <input type="text" id="edit-phonenumber" name="phonenumber"><br><br>
                <label for="edit-address">Address:</label><br>
                <input type="text" id="edit-address" name="address"><br><br>
                <input type="submit" value="Save Changes">
            </form>
        </div>
    </div>
    <div id="lock-overlay">
        <div>
            <h2>Lock User</h2>
            <p>Are you sure you want to lock this user?</p>
            <form id="lock-user-form" action="lockuser.php" method="post">
                <input type="hidden" id="lock-user-id" name="user_id">
                <button type="submit">Confirm</button>
                <button type="button" class="cancel" onclick="document.getElementById('lock-overlay').style.display='none'">Cancel</button>
            </form>
        </div>
    </div>
    <div id="filter-overlay">
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h1>Filter Users</h1>
                <button id="close-filter-overlay">X</button>
            </div>
            <form method="GET" action="index.php">
                <input type="hidden" name="page" value="userlists">
                <label for="query">Search:</label>
                <input type="text" id="query" name="query" placeholder="Search users..." value="<?php echo htmlspecialchars($query); ?>">
                <button type="submit" style="background-color: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer;">Apply</button>
            </form>
        </div>
    </div>
    <table id="user-table">
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Password</th>
            <th>Phone number</th>
            <th>Address</th>
            <th>Locked</th> <!-- Display DaKhoa column -->
            <th>Creation date</th>
            <th>Last update</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['MaKH']); ?></td>
                <td><?php echo htmlspecialchars($user['HoTen']); ?></td>
                <td><?php echo htmlspecialchars($user['Email']); ?></td>
                <td><?php echo htmlspecialchars($user['MatKhau']); ?></td>
                <td><?php echo htmlspecialchars($user['SoDienThoai']); ?></td>
                <td><?php echo htmlspecialchars($user['DiaChi']); ?></td>
                <td><?php echo $user['DaKhoa'] ? 'Yes' : 'No'; ?></td> <!-- Display lock status -->
                <td><?php echo htmlspecialchars($user['NgayTao']); ?></td>
                <td><?php echo htmlspecialchars($user['NgayCapNhat']); ?></td>
                <td>
                    <button class="edit-btn" data-id="<?php echo htmlspecialchars($user['MaKH']); ?>" 
                            data-username="<?php echo htmlspecialchars($user['HoTen']); ?>" 
                            data-email="<?php echo htmlspecialchars($user['Email']); ?>" 
                            data-phonenumber="<?php echo htmlspecialchars($user['SoDienThoai']); ?>" 
                            data-address="<?php echo htmlspecialchars($user['DiaChi']); ?>">Edit</button>
                    <?php if ($user['DaKhoa']): ?>
                        <button class="unlock-btn" data-id="<?php echo htmlspecialchars($user['MaKH']); ?>">Unlock</button>
                    <?php else: ?>
                        <button class="lock-btn" data-id="<?php echo htmlspecialchars($user['MaKH']); ?>">Lock</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Pagination controls -->
    <div id="pagination" style="text-align: center; margin: 20px 0;">
        <?php if ($currentPage > 1): ?>
            <button onclick="window.location.href='index.php?page=userlists&pagenum=<?php echo $currentPage - 1; ?>'" style="margin-right: 10px;">Previous</button>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <button onclick="window.location.href='index.php?page=userlists&pagenum=<?php echo $i; ?>'" style="margin: 0 5px; <?php echo $i == $currentPage ? 'font-weight: bold;' : ''; ?>">
                <?php echo $i; ?>
            </button>
        <?php endfor; ?>
        <?php if ($currentPage < $totalPages): ?>
            <button onclick="window.location.href='index.php?page=userlists&pagenum=<?php echo $currentPage + 1; ?>'" style="margin-left: 10px;">Next</button>
        <?php endif; ?>
    </div>

    <script>
        $(document).ready(function() {
            // Add User button functionality
            $('#add-user').on('click', function() {
                $('#overlay-content').css('display', 'block');
            });

            // Close Add User overlay
            $('#overlay-content button').on('click', function() {
                $('#overlay-content').css('display', 'none');
            });

            // Edit button functionality
            $(document).on('click', '.edit-btn', function() {
                const userId = $(this).data('id');
                const username = $(this).data('username');
                const email = $(this).data('email');
                const phonenumber = $(this).data('phonenumber');
                const address = $(this).data('address');

                $('#edit-user-id').val(userId);
                $('#edit-username').val(username);
                $('#edit-email').val(email);
                $('#edit-phonenumber').val(phonenumber);
                $('#edit-address').val(address);

                $('#edit-overlay').css('display', 'block');
            });

            // Submit Edit User form
            $('#edit-user-form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                const formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: 'edituser.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        alert(response); // Show the server's exact message
                        if (response.includes("successfully")) {
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("Error: " + xhr.responseText); // Show detailed server error
                    }
                });
            });

            // Lock button functionality
            $(document).on('click', '.lock-btn', function() {
                const userId = $(this).data('id');
                if (confirm('Are you sure you want to lock this user?')) {
                    $.post('lockuser.php', { user_id: userId, action: 'lock' }, function(response) {
                        alert(response);
                        location.reload();
                    });
                }
            });

            // Unlock button functionality
            $(document).on('click', '.unlock-btn', function() {
                const userId = $(this).data('id');
                if (confirm('Are you sure you want to unlock this user?')) {
                    $.post('lockuser.php', { user_id: userId, action: 'unlock' }, function(response) {
                        alert(response);
                        location.reload();
                    });
                }
            });

            // Show Filter overlay
            $('#filter-users').on('click', function() {
                $('#filter-overlay').css('display', 'block');
            });

            // Close Filter overlay
            $('#close-filter-overlay').on('click', function() {
                $('#filter-overlay').css('display', 'none');
            });

            // Reset filters and search
            $('#reset-filters').on('click', function() {
                window.location.href = 'index.php?page=userlists';
            });

            $('.pagination-btn').on('click', function() {
                const page = $(this).data('page');
                $.ajax({
                    url: 'userlists.php',
                    type: 'GET',
                    data: { page: page },
                    success: function(response) {
                        const html = $(response).find('#user-table').html();
                        $('#user-table').html(html);

                        const paginationHtml = $(response).find('#pagination').html();
                        $('#pagination').html(paginationHtml);
                    }
                });
            });
        });
    </script>
</body>
</html>
