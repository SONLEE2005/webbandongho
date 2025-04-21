<?php
    include 'databases/db_connection.php';

    // Pagination logic
    $limit = 20;
    $page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1; // Ensure $page is at least 1
    $offset = ($page - 1) * $limit;

    $sql = "SELECT * FROM khachhang LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);
    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    // Get total user count
    $countResult = $conn->query("SELECT COUNT(*) as total FROM khachhang");
    $totalUsers = $countResult->fetch_assoc()['total'];
    $totalPages = ceil($totalUsers / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="userlist">
        <h1>User List</h1>
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
    <table id="user-table">
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Password</th>
            <th>Phone number</th>
            <th>Address</th>
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
                <td><?php echo htmlspecialchars($user['NgayTao']); ?></td>
                <td><?php echo htmlspecialchars($user['NgayCapNhat']); ?></td>
                <td><button>Edit</button> <button>Lock user</button></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div id="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <button class="pagination-btn" data-page="<?php echo $i; ?>"><?php echo $i; ?></button>
        <?php endfor; ?>
    </div>
    <script>
        $(document).ready(function() {
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

        document.getElementById('add-user').addEventListener('click', function() {
            document.getElementById('overlay-content').style.display = 'block';
        });
        document.getElementById('close-overlay').addEventListener('click', function() {
            document.getElementById('overlay-content').style.display = 'none';
        });
    </script>
</body>
</html>
