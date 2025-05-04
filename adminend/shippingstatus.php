<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["hoTen"])) {
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
    select {
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


    .pagination {
        margin-top: 20px;
        text-align: center;
    }

    .page-btn {
        padding: 6px 12px;
        margin: 0 3px;
        background-color: #f0f0f0;
        border: 1px solid #999;
        cursor: pointer;
        color: #333;
    }

    .page-btn:hover {
        background-color: #ddd;
    }

    .active-page {
        background-color: #1e90ff;
        color: white;
        font-weight: bold;
        border-radius: 4px;
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

<div id="orderTable"></div>
<div id="pagination"></div>

<script>
    let currentPage = 1;

    function updateStatus(maDH, newStatus) {
        if (!confirm("Bạn Có Chắc Muốn Cập Nhật Trạng Thái Đơn Hàng ?")) return;

        fetch("update_status.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({id: maDH, status: newStatus})
            })
            .then(res => res.json())    
            .then(data => {
                // alert(data);
                fetchOrder(currentPage);
            });
    }

    function fetchOrder(page = 1) {
        currentPage = page;
        const form = document.getElementById("form_filter");
        const formData = new FormData(form);
        formData.append("page", page);
        const params = new URLSearchParams(formData).toString();
        fetch("load_orders.php?" + params)
            .then(response => response.json())
            .then(data => {
                document.getElementById("orderTable").innerHTML = data.table;
                document.getElementById("pagination").innerHTML = data.pagination;
            });
    }

    function goToPage(page) {
        fetchOrder(page);
    }

    document.getElementById("form_filter").addEventListener("submit", function(e) {
        e.preventDefault();
        fetchOrder(1);
    })

    function resetFilter() {
        document.getElementById("form_filter").reset();
        fetchOrder(1);
    }

    window.addEventListener("DOMContentLoaded", () => fetchOrder(1));
</script>