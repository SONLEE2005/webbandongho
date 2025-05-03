<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["email"])) {
    header("Location: ../login_admin.php");
    exit();
}
?>

<style>
    .filter-form {
        margin: 20px 0;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-form input,
    .filter-form select,
    .filter-form button {
        padding: 5px 10px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    .filter-form button {
        background-color: #333;
        color: white;
        border: none;
        cursor: pointer;
    }

    .filter-form button:hover {
        background-color: #555;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th,
    td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
    }

    th {
        background-color: #f0f0f0;
    }

    tr:nth-child(even) {
        background-color: #fafafa;
    }

    form {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: center;
        background-color: #f5f5f5;
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    form label {
        font-weight: bold;
        min-width: 100px;
    }

    form input[type="date"],
    form input[type="text"],
    form select {
        padding: 6px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        min-width: 180px;
    }

    button {
        padding: 8px 16px;
        background-color: #1e90ff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #0d6efd;
    }

    .tab-menu {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 20px 0;
        border-bottom: 2px solid #ccc;
    }

    .tab-menu .tab-item {
        padding: 10px 20px;
        cursor: pointer;
        background-color: #eee;
        border: 1px solid #ccc;
        border-bottom: none;
        margin-right: 5px;
        border-radius: 4px 4px 0 0;
    }

    .tab-menu .tab-item.active {
        background-color: #fff;
        font-weight: bold;
        border-bottom: 2px solid white;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }
</style>

<h2>Thống Kê</h2>

<form method="get" id="form_filter_statistic" style="margin: 20px 0;">
    <label for="">Từ Ngày:</label>
    <input type="date" name="form_date" id="">

    <label for="">Đến Ngày:</label>
    <input type="date" name="to_date" id="">

    <label for="">Sắp Xếp Theo Tổng Tiền:</label>
    <select name="sort" id="">
        <option value="0">Giảm Dần</option>
        <option value="1">Tăng Dần</option>
    </select>

    <button type="submit">Thống Kê</button>

    <button type="button" id="btnRefresh" onclick="resetFilter();">Làm Mới</button>
</form>

<ul class="tab-menu">
    <li class="tab-item " data-tab="customer">Thống Kê Khách Hàng</li>
    <li class="tab-item active" data-tab="order">Thống Kê Đơn Hàng</li>
</ul>

<div id="customer" class="tab-content ">
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <th>Mã Khách Hàng</th>
            <th>Tên Khách Hàng</th>
            <!-- <th>Tổng Số Đơn Hàng</th> -->
            <th>Tổng Tiền Mua Hàng</th>
        </thead>

        <tbody id="tableCustomer">

        </tbody>

    </table>
</div>

<div id="order" class="tab-content active">
    <table>
        <thead>
            <th>Mã Đơn Hàng</th>
            <th>Tên Khách Hàng</th>
            <th>Ngày Mua Hàng</th>
            <th>Tổng Giá Trị Mua Hàng</th>
            <th>Xem Chi Tiết</th>
        </thead>

        <tbody id="tableOrder"></tbody>

    </table>
</div>

<div id="orderDetailModel" style="display: none; position: fixed; top: 10%; left: 50%; transform: translateX(-50%);
     background-color: white; padding: 20px; border: 1px solid #ccc; box-shadow: 0 5px 15px rgba(0,0,0,0.3); z-index: 999;width: 90%;
    max-height: 80vh;
    overflow-y: auto;
    border-radius: 8px;">
    <h2>Chi Tiết Đơn Hàng</h2>
    <table>
        <thead>
            <th>Mã Đơn Hàng</th>
            <th>Tên Khách Hàng</th>
            <th>Tên Sản Phẩm</th>
            <th>Số Lượng</th>
            <th>Ngày Đặt</th>
            <th>Địa Chỉ</th>
            <th>Tổng Tiền</th>
            <th>Phương Thức Vận Chuyển</th>
            <th>Khuyến Mãi</th>
            <th>Trạng Thái</th>
        </thead>

        <tbody id="orderDetailContent"></tbody>
    </table>
    <button onclick="closeOrderDetail()" style="margin: 20px 10px 10px 10px;">Đóng</button>
</div>

<script>
    document.querySelectorAll(".tab-item").forEach(item => {
        item.addEventListener("click", function(){
            document.querySelectorAll(".tab-item").forEach(tab => tab.classList.remove("active"));
            document.querySelectorAll(".tab-content").forEach(content => content.classList.remove("active"));

            this.classList.add("active");
            document.getElementById(this.dataset.tab).classList.add("active");
        });
    });

    function fetchStatisticCustomer() {
        const form = document.getElementById("form_filter_statistic");
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();

        fetch("statistic_customer_process.php?" + params)
            .then(response => response.text())
            .then(html => {
                document.getElementById("tableCustomer").innerHTML = html;
            });
    }

    function fetchStatisticOrder() {
        const form = document.getElementById("form_filter_statistic");
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();

        fetch("statistic_order_process.php?" + params)
            .then(response => response.text())
            .then(html => {
                document.getElementById("tableOrder").innerHTML = html;
            })
    }

    document.getElementById("form_filter_statistic").addEventListener("submit", function(e) {
        e.preventDefault();
        const activeTab = document.querySelector(".tab-item.active").dataset.tab;
        if (activeTab === "customer") {
            fetchStatisticCustomer();
        } else if (activeTab === "order") {
            fetchStatisticOrder();
        }
    })

    function resetFilter() {
        document.getElementById("form_filter_statistic").reset();
        const activeTab = document.querySelector(".tab-item.active").dataset.tab;
        if (activeTab === "customer") {
            fetchStatisticCustomer();
        } else if (activeTab === "order") {
            fetchStatisticOrder();
        }
    }

    function showOrderDetail(maDH){
        fetch('order_detail.php?MaDH=' + maDH)
            .then(response => response.text())
            .then(html => {
                document.getElementById("orderDetailContent").innerHTML = html;
                document.getElementById("orderDetailModel").style.display = "block";
            })
            .catch(error => {
                alert("Lỗi Tải Chi Tiết Đơn Hàng");
                console.error(error);
            })
    }

    function closeOrderDetail(){
        document.getElementById("orderDetailModel").style.display = "none";
    }

    window.addEventListener("DOMContentLoaded", fetchStatisticOrder);
    window.addEventListener("DOMContentLoaded", fetchStatisticCustomer);
</script>