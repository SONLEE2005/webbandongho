<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["hoTen"])) {
    header("Location: ./login_admin.php");
    exit();
}

// include 'weblayout/admintopmenu.php';
include 'weblayout/topmenu.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">

    <title>
        <?php
        $page = $_GET['page'] ?? 'main';

        if ($page === 'productlist') {
            echo "Product List";
        } elseif ($page === 'userlists') {
            echo "User List";
        } elseif ($page === 'shippingstatus') {
            echo "Shipping Status";
        } elseif ($page === 'adminlist') {
            echo "Admin List";
        } elseif ($page === 'statistic') {
            echo "Statistic";
        } elseif ($page === 'details') {
            echo "Product Detail";
        } else {
            echo "Home";
        }
        ?>
    </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            /* Ensure the body takes the full height of the viewport */
            display: flex;
            flex-direction: column;
            /* Make the body a flex container */
        }

        body {
            font-family: Arial, sans-serif;
        }

        #header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }

        #header>div {
            margin-left: 100px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #header h1 {
            margin: 0;
        }

        #header>nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 50px;
            margin-bottom: 20px;
        }

        #header>nav>ul {
            margin-left: 50px;
            margin-right: 100px;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
        }

        li>input {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-right: 10px;
        }

        ul>button {
            padding: 5px 10px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
            border: 1px solid white;
            padding: 5px 10px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
        }

        /* #topmenu {
            display: flex;
            justify-content: center;
            background-color: rgb(240, 234, 39);
            padding: 10px 20px;
        }

        #topmenu ul {
            display: flex;
            list-style-type: none;
            flex-direction: row;
            gap: 15px;
            align-items: center;
            margin-left: 100px;
        } */

        #main-content {
            display: flex;
            margin-top: 0px;
            flex: 1;
            /* Allow the main content to grow and take available space */
        }

        #sidebar {
            /* position: absolute;
            top: 0;
            left: 0;
            width: 20%;
            height: 100%;
            padding: 20px;
            background-color: #f4f4f4;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: transform 0.3s ease;
            transform: translateX(-100%); */
            /* Initially hidden */
            /* Sidebar chung */

            position: fixed;
            top: 0;
            left: 0;
            width: 270px;
            height: 100vh;
            background: linear-gradient(135deg, #343a40, #222);
            color: white;
            padding: 20px;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: transform 0.3s ease;
            transform: translateX(-100%);
        }

        #sidebar.visible {
            transform: translateX(0);
            /* Slide in when visible */
        }

        #sidebar h2 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        #sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #sidebar>ul {
            list-style-type: none;
            text-align: center;
        }

        #sidebar>ul>li {
            margin-bottom: 16px;
        }

        #sidebar ul li button {
            width: 100%;
            padding: 12px;
            background-color: #555;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
            text-align: left;
        }

        #sidebar ul li button:hover {
            background-color: #777;
            transform: scale(1.05);
        }

        #content-area {
            position: relative;
            width: 100%;
            /* Ensure full width when sidebar is hidden */
            padding: 20px;
            background-color: #fff;
            overflow-y: auto;
            /* Allows scrolling if content overflows */
            height: calc(100vh - 200px);
            /* Adjust height to fit the viewport */
        }

        #item-lists {
            width: 80%;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            /* Allows wrapping to the next row */
            gap: 20px;
            /* Adds spacing between items */
        }

        .item {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
            flex: 1 1 calc(20% - 20px);
            /* Ensures 5 items per row */
            box-sizing: border-box;
        }

        .item img {
            width: 100%;
            /* Makes the image responsive */
            height: auto;
            /* Maintains aspect ratio */
            max-width: 100%;
            height: auto;
            display: block;
        }

        #footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
        }

        #toggle-sidebar {
            position: fixed;
            top: 15px;
            left: 15px;
            font-size: 16px;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1001;
        }

        #toggle-sidebar:hover {
            background-color: #218838;
        }

        #close-sidebar {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 8px 12px;
            background-color: #dc3545;
            color: white;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        #close-sidebar:hover {
            background-color: #c82333;
        }

        #sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0);
            /* Fully transparent initially */
            z-index: 999;
            /* Below the sidebar but above the content */
            display: none;
            /* Hidden by default */
            transition: background-color 0.3s ease;
            /* Smooth transition for background color */
        }

        #sidebar-overlay.visible {
            display: block;
            /* Show when the sidebar is visible */
            background-color: rgba(0, 0, 0, 0.5);
            /* Semi-transparent black */
            transition: background-color 0.3s ease;
            /* Smooth transition for background color */
        }

        #add-user {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        #add-user:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        #add-user:active {
            background-color: #3e8e41;
            transform: scale(1);
        }

        /* Cấu trúc container */
        .container {
            display: flex;
            justify-content: space-between;
            margin: 20px;
        }

        /* Cột bên trái chứa các ô thông tin */
        .left-column {
            width: 270px;
            display: flex;
            flex-direction: column;
        }

        /* Các ô thông tin */
        .info-box {
            width: 100%;
            min-height: 200px;
            line-height: 25px;
            padding: 20px;
            border-radius: 8px;
            background: #f8f9fa;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 10px 0;
            position: relative;
            transition: transform 0.3s ease;
        }

        /* Hiệu ứng hover trượt qua phải */
        .info-box:hover {
            transform: scale(1.05);
            /* phóng to 5% */
            box-shadow: 4px 8px 16px rgba(0, 0, 0, 0.2);
            /* đổ bóng mạnh hơn */
            z-index: 2;
        }

        /* Màu sắc cho các ô */
        .revenue {
            background: #28a745;
            color: white;
        }

        .orders {
            background: #ffc107;
            color: black;
        }

        /* Nút xem chi tiết */
        .detail-btn {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .detail-btn:hover {
            background-color: #0056b3;
        }

        /* Cột bên phải chứa biểu đồ */
        .right-column {
            flex: 1;
            padding: 20px;
        }


        #chartDT {
            width: 80%;
            height: 400px;
        }

        #chartST {
            width: 50%;
            height: 200px;
        }

        .chart-canvas {
            display: none;
            /* Mặc định ẩn tất cả biểu đồ */
        }

        .chart-canvas.active {
            display: block;
        }


        @media (max-width: 768px) {
            #sidebar {
                width: 60%;
                /* Adjust sidebar width for smaller screens */
            }

            #content-area {
                padding: 10px;
                height: calc(100vh - 150px);
                /* Adjust height for smaller screens */
            }

            #toggle-sidebar {
                padding: 8px 12px;
                font-size: 14px;
            }

            #close-sidebar {
                padding: 5px 10px;
                font-size: 12px;
            }

            #sidebar ul li button {
                font-size: 14px;
                padding: 10px;
            }
        }

        @media (max-width: 480px) {
            #sidebar {
                width: 80%;
                /* Further adjust sidebar width for very small screens */
            }

            #toggle-sidebar {
                padding: 6px 10px;
                font-size: 12px;
            }

            #close-sidebar {
                padding: 5px 8px;
                font-size: 12px;
            }

            #sidebar ul li button {
                font-size: 12px;
                padding: 8px;
            }

            #content-area {
                padding: 8px;
                height: calc(100vh - 120px);

                /* Further adjust height */
            }
        }
    </style>
</head>

<body>
    <div id="main-content">
        <button id="toggle-sidebar"><i class="fa-solid fa-bars"></i></button>
        <div id="sidebar-overlay">
            <div id="sidebar">
                <button id="close-sidebar">X</button>
                <div>
                    <h2>Dashboard</h2>
                </div>
                <ul>
                    <li><button id="home">Home</button></li>
                    <li><button id="prod-lists">Products List</button></li>
                    <li><button id="users-lists">Users Database</button></li>
                    <li><button id="admin-database">Admins Database</button></li>
                    <li><button id="status">Shipping Status</button></li>
                    <li><button id="statistic">Statistic</button></li>
                </ul>
            </div>
        </div>
        <div id="content-area">
            <div style="<?php echo isset($_GET['page']) ? 'display: none;' : ''; ?>">
                <div class="container">
                    <div class="left-column">
                        <div class="info-box revenue">
                            <h3>Tổng Doanh Thu</h3>
                            <p></p>
                            <button class="detail-btn" data-chart="chartDT" id="btnChartDT" onclick="renderChartDT()">Xem Chi Tiết</button>
                        </div>
                        <div class="info-box orders">


                        </div>
                    </div>

                    <div class="right-column">
                        <canvas id="chartDT" class="chart-canvas" style="width: 100%; height: 400px"></canvas>
                        <canvas id="chartST" class="chart-canvas" style="width: 100%; height: 400px"></canvas>
                        <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
                    </div>
                </div>


            </div>

            <?php
            $page = $_GET['page'] ?? 'main';

            // Extract the base page name before any query parameters
            if (strpos($page, '?') !== false) {
                $page = explode('?', $page)[0];
            }
            if ($page) {
                if ($page === 'productlist') {
                    include 'productlist.php';
                } elseif ($page === 'userlists') {
                    include 'userlists.php';
                } elseif ($page === 'shippingstatus') {
                    include 'shippingstatus.php';
                } elseif ($page === 'adminlist') {
                    include 'adminlist.php';
                } elseif ($page === 'statistic') {
                    include 'statistic.php';
                } elseif ($page === 'details') {
                    if (isset($_GET['id'])) {
                        include 'proddetails.php'; // Include proddetails.php and pass the id parameter
                    } else {
                        die("Product ID not provided.");
                    }
                }
            }
            ?>


        </div>
    </div>
    <div id="footer">
        <?php
        include "./weblayout/footer.php"
        ?>
    </div>
    <script>
        // JavaScript code can be added here for interactivity
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const home = document.getElementById('home');
        document.getElementById('home').addEventListener('click', function() {
            window.location.href = 'index.php';
        });
        document.getElementById('prod-lists').addEventListener('click', function() {
            home.style.display = "none";
            window.location.href = 'index.php?page=productlist';
        });
        document.getElementById('users-lists').addEventListener('click', function() {
            home.style.display = "none";
            window.location.href = 'index.php?page=userlists';
        });
        document.getElementById('admin-database').addEventListener('click', function() {
            home.style.display = "none";
            window.location.href = 'index.php?page=adminlist';
        });
        document.getElementById('status').addEventListener('click', function() {
            home.style.display = "none";
            window.location.href = 'index.php?page=shippingstatus';
        });
        document.getElementById('statistic').addEventListener('click', function() {
            home.style.display = "none";
            window.location.href = 'index.php?page=statistic';
        });

        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            console.log("Sidebar toggle clicked!");
            sidebar.classList.toggle('visible');
            sidebarOverlay.classList.toggle('visible');
        });
        document.getElementById('close-sidebar').addEventListener('click', function() {
            sidebar.classList.remove('visible');
            sidebarOverlay.classList.remove('visible');
        });
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('visible');
            sidebarOverlay.classList.remove('visible');
        });

        function loaddoanhthu() {
            fetch("get_doanhthu.php")
                .then(response => response.json())
                .then(data => {
                    console.log("Dữ liệu doanh thu nhận được:", data);
                    document.querySelector(".revenue p").textContent = `đ ${data["Doanh Thu"]}`;
                })
                .catch(error => console.error("Lỗi Tải Doanh Thu: ", error));
        }

        function loadstatus() {
            fetch("get_order_status.php")
                .then(response => response.json())
                .then(data => {
                    const statusContainer = document.querySelector(".orders");
                    statusContainer.innerHTML = "<h3>Trạng Thái Đơn Hàng</h3>";

                    data.forEach(item => {
                        statusContainer.innerHTML += `<p>${item.TrangThai}: ${item.SoLuong} đơn</p>`;
                    });
                    statusContainer.innerHTML += '<button class="detail-btn" data-chart="chartST" id="btnChartST" onclick="renderChartST()">Xem Chi Tiết</button>';
                })
                .catch(error => console.error("Lỗi tải trạng thái đơn hàng:", error));
        }

        window.addEventListener("DOMContentLoaded", () => {
            loaddoanhthu();
            loadstatus();
        });

        function chartDoanhThu() {
            fetch("chart_doanhthu.php")
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        console.error("Không có dữ liệu để hiển thị biểu đồ!");
                        return;
                    }
                    const labels = data.map(item => `Tháng ${parseInt(item.Thang)}`);
                    const values = data.map(item => parseFloat(item.DoanhThu));

                    new Chart(document.getElementById("chartDT"), {
                        type: "bar",
                        data: {
                            labels: labels,
                            datasets: [{
                                label: "Doanh Thu (VND)",
                                data: values,
                                backgroundColor: "rgba(30, 144, 255, 1)"
                            }]
                        },
                        options: {
                            responsive: false,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top'
                                },
                                title: {
                                    display: true,
                                    text: 'Doanh Thu Theo Tháng'
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error("Lỗi Tải Dữ Liệu: ", error));
        }

        function chartStatus() {
            fetch("chart_status.php") // Gọi file PHP
                .then(response => response.json())
                .then(data => {
                    const statusMap = {
                        0: "Chưa Xác Nhận",
                        1: "Đã xác nhận",
                        2: "Giao Hàng Thành Công",
                        3: "Đã Hủy Đơn"
                    };
                    const labels = data.map(item => statusMap[item.TrangThai]);
                    const values = data.map(item => item.SoLuong);
                    const colors = ["#1e90ff", "#FFA500", "#28A745", "#DC3545"];

                    new Chart(document.getElementById("chartST"), {
                        type: "doughnut",
                        data: {
                            labels: labels,
                            datasets: [{
                                label: "Trạng Thái Đơn Hàng",
                                data: values,
                                backgroundColor: colors
                            }]
                        },
                        options: {
                            responsive: false,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: "bottom"
                                },
                                title: {
                                    display: true,
                                    text: 'Trạng Thái Đơn Hàng'
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error("Lỗi tải dữ liệu:", error));
        }
        window.addEventListener("DOMContentLoaded", () => chartDoanhThu());

        // document.querySelectorAll('.detail-btn').forEach(button => {
        //     button.addEventListener('click', function() {
        //         console.log("Nút đã được click:", this);
        //         const chartId = this.getAttribute('data-chart');
        //         console.log("Nút bấm:", this); // Kiểm tra nút đã click
        //         console.log("Biểu đồ cần hiển thị:", chartId); // Kiểm tra ID biểu đồ

        //         // Kiểm tra nếu biểu đồ chưa được tìm thấy
        //         const targetChart = document.getElementById(chartId);
        //         if (!targetChart) {
        //             console.error("Không tìm thấy biểu đồ có ID:", chartId);
        //             return;
        //         }

        //         // Ẩn tất cả biểu đồ trước khi hiển thị biểu đồ được chọn
        //         document.querySelectorAll('.chart-canvas').forEach(canvas => {
        //             canvas.style.display = 'none'; // Dùng `display: none;` thay vì `visibility: hidden;`
        //         });

        //         targetChart.style.display = 'block';

        //         if (chartId === "chartDT") {
        //             chartDoanhThu();
        //         } else if (chartId === "chartST") {
        //             chartStatus();
        //         }
        //     });
        // });

        function renderChartDT() {
            const chartId = "chartDT";
            const targetChart = document.getElementById(chartId);
            if (!targetChart) {
                console.error("Không tìm thấy biểu đồ có ID:", chartId);
                return;
            }
            document.getElementById("chartST").style.display = "none";
            document.getElementById("chartDT").style.display = "block";
            chartDoanhThu();
        }

        function renderChartST() {
            const chartId = "chartST";
            const targetChart = document.getElementById(chartId);
            if (!targetChart) {
                console.error("Không tìm thấy biểu đồ có ID:", chartId);
                return;
            }
            document.getElementById("chartDT").style.display = "none";
            document.getElementById("chartST").style.display = "block";
            chartStatus();
        }
    </script>
</body>

</html>