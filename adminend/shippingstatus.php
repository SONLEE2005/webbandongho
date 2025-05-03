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

    form button {
        padding: 8px 16px;
        background-color: #1e90ff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    form button:hover {
        background-color: #0d6efd;
    }
</style>


<h2>Danh Sách Đơn Hàng</h2>

<form method="GET" id="form_filter" style="margin: 20px 0">
    <label for="status">Trạng Thái:</label>
    <select name="status" id="status">
        <option value="">Tất Cả</option>
        <option value="0">Chưa Xác Nhận</option>
        <option value="1">Đã Xác Nhận</option>
        <option value="2">Giao Hàng Thành Công</option>
        <option value="3">Đã Hủy Đơn</option>
    </select>

    <label for="form_date">Từ Ngày:</label>
    <input type="date" name="form_date" id="form_date">

    <label for="to_date">Đến Ngày:</label>
    <input type="date" name="to_date" id="to_date">

    <label for="location">Địa Điểm:</label>
    <input type="text" name="location" id="location" placeholder="Quận, Huyện, TP,...">

    <button type="submit">Lọc</button>

    <button type="button" onclick="resetFilter();">Làm Mới</button>
</form>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Mã Đơn Hàng</th>
            <th>Tên Khách Hàng</th>
            <th>Địa Chỉ</th>
            <th>Ngày Đặt</th>
            <th>Trạng Thái</th>
            <th>Hành Động</th>
        </tr>
    </thead>

    <tbody id="orderTable">

    </tbody>
</table>

<script>
    function updateStatus(maDH, newStatus) {
        if (!confirm("Bạn Có Chắc Muốn Cập Nhật Trạng Thái Đơn Hàng ?")) return;

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "update_status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            alert(this.responseText);
            location.reload();
        }
        xhr.send("id=" + maDH + "&status=" + newStatus);
    }

    function fetchOrder(){
        const form = document.getElementById("form_filter");
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();

        fetch("load_orders.php?" + params)
            .then(response => response.text())
            .then(html => {
                document.getElementById("orderTable").innerHTML = html;
            });
    }

    document.getElementById("form_filter").addEventListener("submit", function(e){
        e.preventDefault();
        fetchOrder();
    })

    function resetFilter() {
        document.getElementById("form_filter").reset();
        fetchOrder();
    }

    window.addEventListener("DOMContentLoaded", fetchOrder);
</script>