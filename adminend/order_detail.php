<?php
if(!isset($_GET["MaDH"]) || !is_numeric($_GET["MaDH"])){
    echo "Không Có Mã Đơn Hàng";
    exit();
}

$MaDH = $_GET["MaDH"];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_dong_ho";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
    die("Kết Nối Thất Bại: " . $conn->connect_error);
}

$sql = "SELECT kh.HoTen, dh.NgayDat, dh.TongTien, dh.TrangThai, ctdh.SoLuong, sp.TenSP, vc.TenPhuongThuc, dh.MaKM, dh.DiaChi
FROM donhang dh
JOIN khachhang kh ON dh.MaKH = kh.MaKH
JOIN chitietdonhang ctdh ON ctdh.MaDH = dh.MaDH 
JOIN sanpham sp ON sp.MaSP = ctdh.MaSP
JOIN vanchuyen vc ON vc.MaVC = dh.MaVC
-- JOIN khuyenmai km ON km.MaKM = dh.MaKM
WHERE dh.MaDH = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $MaDH);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows() > 0){
    $stmt->bind_result($HoTen, $NgayDat, $TongTien, $TrangThai, $SoLuong, $TenSP, $PTVC, $MaKM, $DiaChi);
    while($stmt->fetch()){

        echo "
            <tr>
                <td>{$MaDH}</td>
                <td>{$HoTen}</td>
                <td>{$TenSP}</td>
                <td>{$SoLuong}</td>
                <td>{$NgayDat}</td>
                <td>{$DiaChi}</td>
                <td>{$TongTien}</td>
                <td>{$PTVC}</td>
                <td>{$MaKM}</td>
                <td>{$TrangThai}</td>
            </tr>
        ";
    }
} else {
    echo "Không Tìm Thấy Chi Tiết Đơn Hàng";
}
$stmt->close();
$conn->close();

?>