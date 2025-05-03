<?php
include 'databases/db_connection.php';

// Get the search query and filters from the URL
$query = isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '';
$sort = isset($_GET['sort']) ? htmlspecialchars($_GET['sort']) : '';
$role = isset($_GET['role']) ? htmlspecialchars($_GET['role']) : '';

// Pagination logic
$itemsPerPage = 20;
$currentPage = isset($_GET['pagenum']) ? max(1, intval($_GET['pagenum'])) : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

// Modify SQL query to include search and filters
$sql = "SELECT * FROM admin WHERE 1=1";
if ($query) {
    $sql .= " AND (HoTen LIKE '%" . $conn->real_escape_string($query) . "%' OR Email LIKE '%" . $conn->real_escape_string($query) . "%')";
}
if ($role) {
    $sql .= " AND VaiTro LIKE '%" . $conn->real_escape_string($role) . "%'";
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
$admins = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $admins[] = $row;
    }
}

// Get total admin count
$countResult = $conn->query("SELECT COUNT(*) as total FROM admin WHERE 1=1");
$totalAdmins = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalAdmins / $itemsPerPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin List</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        body {
            font-family: Arial, sans-serif;
        }
        #adminlist {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f1f1f1;
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
        #add-admin {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        #add-admin:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        #add-admin:active {
            background-color: #3e8e41;
            transform: scale(1);
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="adminlist">
        <h1>Admin List</h1>
        <form method="GET" action="index.php" style="display: flex; gap: 10px;">
            <input type="hidden" name="page" value="adminlist">
            <input type="text" name="query" placeholder="Search admins..." value="<?php echo htmlspecialchars($query); ?>" style="padding: 5px; border-radius: 5px; border: 1px solid #ccc;">
            <select name="sort" style="padding: 5px; border-radius: 5px; border: 1px solid #ccc;">
                <option value="">Sort by</option>
                <option value="recent" <?php echo $sort === 'recent' ? 'selected' : ''; ?>>Recent</option>
                <option value="name_asc" <?php echo $sort === 'name_asc' ? 'selected' : ''; ?>>Name (A-Z)</option>
                <option value="name_desc" <?php echo $sort === 'name_desc' ? 'selected' : ''; ?>>Name (Z-A)</option>
            </select>
            <select name="role" style="padding: 5px; border-radius: 5px; border: 1px solid #ccc;">
                <option value="">Filter by Role</option>
                <option value="Admin" <?php echo $role === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="Moderator" <?php echo $role === 'Moderator' ? 'selected' : ''; ?>>Moderator</option>
            </select>
            <button type="submit" style="padding: 5px 10px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Apply</button>
            <button type="button" id="reset-filters" style="padding: 5px 10px; background-color: red; color: white; border: none; border-radius: 5px; cursor: pointer;">Reset</button>
        </form>
        <button id="add-admin">Add Admin</button>
    </div>
    <table id="admin-table">
        <tr>
            <th>Admin ID</th>
            <th>Username</th> <!-- Display TenDangNhap -->
            <th>Full Name</th>
            <th>Email</th>
            <th>Password</th> <!-- New Password column -->
            <th>Role</th>
            <th>Locked</th>
            <th>Creation Date</th>
            <th>Last Update</th>
            <th>Action</th>
        </tr>
        <?php foreach ($admins as $admin): ?>
            <tr>
                <td><?php echo htmlspecialchars($admin['MaAdmin']); ?></td>
                <td><?php echo htmlspecialchars($admin['TenDangNhap']); ?></td> <!-- Display username -->
                <td><?php echo htmlspecialchars($admin['HoTen']); ?></td>
                <td><?php echo htmlspecialchars($admin['Email']); ?></td>
                <td><?php echo htmlspecialchars($admin['MatKhau']); ?></td> <!-- Display password -->
                <td><?php echo htmlspecialchars($admin['VaiTro']); ?></td>
                <td><?php echo $admin['DaKhoa'] ? 'Yes' : 'No'; ?></td>
                <td><?php echo htmlspecialchars($admin['NgayTao']); ?></td>
                <td><?php echo htmlspecialchars($admin['NgayCapNhat']); ?></td>
                <td>
                    <button class="edit-btn" data-id="<?php echo htmlspecialchars($admin['MaAdmin']); ?>" 
                            data-username="<?php echo htmlspecialchars($admin['TenDangNhap']); ?>" 
                            data-fullname="<?php echo htmlspecialchars($admin['HoTen']); ?>" 
                            data-email="<?php echo htmlspecialchars($admin['Email']); ?>" 
                            data-role="<?php echo htmlspecialchars($admin['VaiTro']); ?>">Edit</button>
                    <?php if ($admin['DaKhoa']): ?>
                        <button class="unlock-btn" data-id="<?php echo htmlspecialchars($admin['MaAdmin']); ?>">Unlock</button>
                    <?php else: ?>
                        <button class="lock-btn" data-id="<?php echo htmlspecialchars($admin['MaAdmin']); ?>">Lock</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div id="pagination" style="text-align: center; margin: 20px 0;">
        <?php if ($currentPage > 1): ?>
            <button onclick="window.location.href='index.php?page=adminlist&pagenum=<?php echo $currentPage - 1; ?>'" style="margin-right: 10px;">Previous</button>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <button onclick="window.location.href='index.php?page=adminlist&pagenum=<?php echo $i; ?>'" style="margin: 0 5px; <?php echo $i == $currentPage ? 'font-weight: bold;' : ''; ?>">
                <?php echo $i; ?>
            </button>
        <?php endfor; ?>
        <?php if ($currentPage < $totalPages): ?>
            <button onclick="window.location.href='index.php?page=adminlist&pagenum=<?php echo $currentPage + 1; ?>'" style="margin-left: 10px;">Next</button>
        <?php endif; ?>
    </div>

    <!-- Overlay for Add/Edit/Lock Admin -->
    <div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000;">
        <div id="overlay-content" style="position: relative; margin: 10% auto; padding: 20px; background: white; width: 50%; border-radius: 10px;">
            <button id="close-overlay" style="position: absolute; top: 10px; right: 10px; background: red; color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer;">X</button>
            <form id="admin-form" method="POST" action="admin_actions.php">
                <h2 id="overlay-title">Add Admin</h2>
                <input type="hidden" name="action" id="form-action" value="add">
                <div style="margin-bottom: 10px;">
                    <label for="admin-username">Admin Username (TenDangNhap):</label>
                    <input type="text" id="admin-username" name="admin_username" required style="width: 100%; padding: 5px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label for="admin-password">Password (MatKhau):</label>
                    <input type="password" id="admin-password" name="admin_password" required style="width: 100%; padding: 5px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label for="admin-fullname">Full Name (HoTen):</label>
                    <input type="text" id="admin-fullname" name="admin_fullname" required style="width: 100%; padding: 5px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label for="admin-email">Email:</label>
                    <input type="email" id="admin-email" name="admin_email" required style="width: 100%; padding: 5px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label for="admin-role">Role (VaiTro):</label>
                    <select id="admin-role" name="admin_role" required style="width: 100%; padding: 5px;">
                        <option value="Admin">Admin</option>
                        <option value="Moderator">Moderator</option>
                    </select>
                </div>
                <div style="margin-bottom: 10px;">
                    <label for="user-id">User ID (MaKH):</label>
                    <input type="text" id="user-id" name="user_id" style="width: 100%; padding: 5px;">
                    <button type="button" id="fetch-user" style="margin-top: 5px; padding: 5px 10px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Fetch User</button>
                </div>
                <button type="submit" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Submit</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#add-admin').on('click', function() {
                $('#overlay-title').text('Add Admin');
                $('#form-action').val('add');
                $('#admin-id').val('');
                $('#admin-username').val('');
                $('#admin-password').val('');
                $('#admin-fullname').val('');
                $('#admin-email').val('');
                $('#admin-role').val('Admin');
                $('#user-id').val('');
                $('#overlay').fadeIn();
            });

            $('.edit-btn').on('click', function() {
                $('#overlay-title').text('Edit Admin');
                $('#form-action').val('edit');
                $('#admin-id').val($(this).data('id'));
                $('#admin-username').val($(this).data('username'));
                $('#admin-password').val('');
                $('#admin-fullname').val('');
                $('#admin-email').val($(this).data('email'));
                $('#admin-role').val($(this).data('role'));
                $('#overlay').fadeIn();
            });

            $('.lock-btn').on('click', function() {
                const adminId = $(this).data('id');
                if (confirm('Are you sure you want to lock this admin?')) {
                    $.post('lockadmin.php', { admin_id: adminId, action: 'lock' }, function(response) {
                        alert(response);
                        location.reload();
                    });
                }
            });

            $('.unlock-btn').on('click', function() {
                const adminId = $(this).data('id');
                if (confirm('Are you sure you want to unlock this admin?')) {
                    $.post('lockadmin.php', { admin_id: adminId, action: 'unlock' }, function(response) {
                        alert(response);
                        location.reload();
                    });
                }
            });

            $('#fetch-user').on('click', function() {
                const userId = $('#user-id').val();
                if (!userId) {
                    alert('Please enter a User ID (MaKH).');
                    return;
                }

                $.ajax({
                    url: 'fetch_user.php',
                    method: 'POST',
                    data: { user_id: userId },
                    success: function(response) {
                        console.log('Server Response:', response); // Log server response for debugging
                        if (response.success) {
                            $('#admin-fullname').val(response.data.HoTen);
                            $('#admin-email').val(response.data.Email);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', xhr.responseText); // Log detailed error response
                        alert('An error occurred while fetching user details.');
                    }
                });
            });

            $('#close-overlay').on('click', function() {
                $('#overlay').fadeOut();
            });

            $('#reset-filters').on('click', function() {
                window.location.href = 'index.php?page=adminlist';
            });
        });
    </script>
</body>
</html>
