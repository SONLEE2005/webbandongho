-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2025 at 09:41 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop_dong_ho`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `MaAdmin` int(11) NOT NULL,
  `TenDangNhap` varchar(50) DEFAULT NULL,
  `MatKhau` varchar(8) DEFAULT NULL,
  `HoTen` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `VaiTro` varchar(50) DEFAULT 'Admin',
  `DaKhoa` tinyint(1) DEFAULT 0,
  `NgayTao` datetime DEFAULT current_timestamp(),
  `NgayCapNhat` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`MaAdmin`, `TenDangNhap`, `MatKhau`, `HoTen`, `Email`, `VaiTro`, `DaKhoa`, `NgayTao`, `NgayCapNhat`) VALUES
(1, 'admin01', 'matkhau1', 'Nguyễn Văn A', 'admin01@email.com', 'Admin', 0, '2025-04-14 13:29:42', '2025-04-21 16:15:07'),
(2, 'admin02', 'matkhau4', 'Trần Thị B', 'admin02@email.com', 'Admin', 0, '2025-04-14 13:29:42', '2025-04-21 16:15:07'),
(3, 'admin03', 'matkhau3', 'viet_admin', 'viet_admin@email.com', 'Admin', 0, '2025-05-01 13:36:14', '2025-05-01 13:36:14');

-- --------------------------------------------------------

--
-- Table structure for table `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `MaCTDH` int(11) NOT NULL,
  `MaDH` int(11) DEFAULT NULL,
  `MaSP` int(11) DEFAULT NULL,
  `SoLuong` int(11) DEFAULT NULL,
  `DonGia` decimal(10,2) DEFAULT NULL,
  `ThanhTien` decimal(10,2) GENERATED ALWAYS AS (`SoLuong` * `DonGia`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`MaCTDH`, `MaDH`, `MaSP`, `SoLuong`, `DonGia`) VALUES
(5, 3, 13, 1, 3200000.00),
(6, 4, 22, 1, 3500000.00),
(7, 7, 37, 1, 3200000.00),
(8, 5, 36, 2, 3200000.00),
(9, 6, 14, 2, 3200000.00),
(13, 8, 15, 2, 3450000.00),
(14, 9, 22, 1, 3500000.00),
(16, 10, 17, 1, 3200000.00);

-- --------------------------------------------------------

--
-- Table structure for table `chitietphieunhap`
--

CREATE TABLE `chitietphieunhap` (
  `MaChiTietPhieuNhap` int(11) NOT NULL,
  `MaPhieuNhap` int(11) DEFAULT NULL,
  `MaSP` int(11) DEFAULT NULL,
  `SoLuong` int(11) DEFAULT NULL,
  `DonGia` decimal(10,2) DEFAULT NULL,
  `ThanhTien` decimal(10,2) GENERATED ALWAYS AS (`SoLuong` * `DonGia`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danhmuc`
--

CREATE TABLE `danhmuc` (
  `MaDanhMuc` int(11) NOT NULL,
  `TenDanhMuc` varchar(100) DEFAULT NULL,
  `MoTa` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `danhmuc`
--

INSERT INTO `danhmuc` (`MaDanhMuc`, `TenDanhMuc`, `MoTa`) VALUES
(1, 'Đồng hồ nam', 'Các mẫu đồng hồ dành cho nam giới'),
(2, 'Đồng hồ nữ', 'Các mẫu đồng hồ dành cho nữ giới');

-- --------------------------------------------------------

--
-- Table structure for table `donhang`
--

CREATE TABLE `donhang` (
  `MaDH` int(11) NOT NULL,
  `MaKH` int(11) DEFAULT NULL,
  `NgayDat` datetime DEFAULT current_timestamp(),
  `DiaChi` text NOT NULL,
  `TongTien` decimal(10,2) DEFAULT NULL,
  `TrangThai` int(11) DEFAULT 0,
  `MaKM` int(11) DEFAULT NULL,
  `MaVC` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donhang`
--

INSERT INTO `donhang` (`MaDH`, `MaKH`, `NgayDat`, `DiaChi`, `TongTien`, `TrangThai`, `MaKM`, `MaVC`) VALUES
(3, 1, '2025-04-14 14:00:00', '123 Nguyễn Trãi, Hà Nội', 500000.00, 1, 2, 6),
(4, 2, '2025-04-14 14:05:00', '456 Trần Hưng Đạo, TP.HCM', 750000.00, 3, 3, 7),
(5, 1, '2025-05-04 02:51:26', '19 Hoàng Thành, Thăng Long, Hà Nội', 6400000.00, 0, 3, 7),
(6, 8, '2025-05-04 02:51:31', '54 Điện Biên Phủ, Quận Bình Thạnh, TPHCM', 6400000.00, 0, 3, 7),
(7, 4, '2025-05-04 02:52:39', '27B An Dương Vương, Quận 5, TPHCM', 3200000.00, 2, 2, 7),
(8, 1, '2025-05-04 02:52:39', '24 Võ Thị Sáu, Quận 3, TPHCM', 6900000.00, 1, 2, 6),
(9, 6, '2025-05-04 02:52:44', '238 Lý Thường Kiệt, Quận 10, TPHCM', 3500000.00, 0, 2, 7),
(10, 1, '2025-05-04 02:52:44', '24 Võ Thị Sáu, Quận 3, TPHCM', 3200000.00, 1, 3, 6);

-- --------------------------------------------------------

--
-- Table structure for table `giohang`
--

CREATE TABLE `giohang` (
  `MaGio` int(11) NOT NULL,
  `MaKH` int(11) DEFAULT NULL,
  `MaSP` int(11) DEFAULT NULL,
  `SoLuong` int(11) DEFAULT NULL,
  `NgayThem` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `khachhang`
--

CREATE TABLE `khachhang` (
  `MaKH` int(11) NOT NULL,
  `HoTen` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `MatKhau` varchar(255) DEFAULT NULL,
  `SoDienThoai` varchar(15) DEFAULT NULL,
  `DiaChi` text DEFAULT NULL,
  `DaKhoa` tinyint(1) DEFAULT 0,
  `NgayTao` datetime DEFAULT current_timestamp(),
  `NgayCapNhat` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khachhang`
--

INSERT INTO `khachhang` (`MaKH`, `HoTen`, `Email`, `MatKhau`, `SoDienThoai`, `DiaChi`, `DaKhoa`, `NgayTao`, `NgayCapNhat`) VALUES
(1, 'Lê Văn C', 'le.c@email.com', 'pass123', '0901234567', '123 Nguyễn Trãi, Hà Nội', 0, '2025-04-14 13:29:42', '2025-04-30 17:58:52'),
(2, 'Phạm Thị D', 'pham.d@email.com', 'pass456', '0987654321', '456 Trần Hưng Đạo, TP.HCM', 0, '2025-04-14 13:29:42', '2025-04-30 17:58:52'),
(3, 'viet', 'viet123@email.com', '$2y$10$i3K23vaxmgmAyaOv7kYhB.umQ4X3eXXJ0hQ1phKGBWeWbm0df5VPO', '0987654321', '19 Hoàng Thành, Thăng Long', 0, '2025-05-01 03:21:31', '2025-05-01 03:21:31'),
(4, 'Nguyễn Thị T', 'thit@email.com', 'matkhau1', '0923758397', '27B An Dương Vương, Quận 5, TPHCM', 0, '2025-05-04 07:51:13', '2025-05-04 07:51:13'),
(6, 'Cao Văn M', 'Vanm@email.com', 'matkhau2', '0823758392', '238 Lý Thường Kiệt, Quận 10, TPHCM', 0, '2025-05-04 07:53:13', '2025-05-04 07:53:13'),
(8, 'Lý K', 'Lyk@email.com', 'matkhau3', '0123582348', '54 Điện Biên Phủ, Quận Bình Thạnh, TPHCM', 0, '2025-05-04 07:55:57', '2025-05-04 07:55:57');

-- --------------------------------------------------------

--
-- Table structure for table `khuyenmai`
--

CREATE TABLE `khuyenmai` (
  `MaKM` int(11) NOT NULL,
  `MaCode` varchar(50) DEFAULT NULL,
  `MoTa` text DEFAULT NULL,
  `ChietKhauPhanTram` decimal(5,2) DEFAULT NULL,
  `NgayBatDau` date DEFAULT NULL,
  `NgayKetThuc` date DEFAULT NULL,
  `KichHoat` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khuyenmai`
--

INSERT INTO `khuyenmai` (`MaKM`, `MaCode`, `MoTa`, `ChietKhauPhanTram`, `NgayBatDau`, `NgayKetThuc`, `KichHoat`) VALUES
(1, 'TET2025', 'Khuyến mãi Tết 2025', 10.00, '2025-01-15', '2025-02-15', 1),
(2, 'SUMMER25', 'Giảm giá hè 2025', 15.50, '2025-06-01', '2025-06-30', 1),
(3, 'BLACKFRI', 'Khuyến mãi Black Friday', 25.00, '2025-11-25', '2025-11-30', 0);

-- --------------------------------------------------------

--
-- Table structure for table `phieunhap`
--

CREATE TABLE `phieunhap` (
  `MaPhieuNhap` int(11) NOT NULL,
  `NgayNhap` datetime DEFAULT current_timestamp(),
  `MaAdmin` int(11) DEFAULT NULL,
  `TongTien` decimal(10,2) DEFAULT NULL,
  `GhiChu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `MaSP` int(11) NOT NULL,
  `TenSP` varchar(150) DEFAULT NULL,
  `MaDanhMuc` int(11) DEFAULT NULL,
  `ThuongHieu` varchar(100) DEFAULT NULL,
  `Gia` decimal(10,2) DEFAULT NULL,
  `MoTa` text DEFAULT NULL,
  `HinhAnh` varchar(255) DEFAULT NULL,
  `SoLuongTon` int(11) DEFAULT NULL,
  `NgayTao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`MaSP`, `TenSP`, `MaDanhMuc`, `ThuongHieu`, `Gia`, `MoTa`, `HinhAnh`, `SoLuongTon`, `NgayTao`) VALUES
(2, 'Daniel Wellington Petite Melrose', 2, 'DW', 3200000.00, 'Đồng hồ nữ thời trang dây kim loại', 'DW_DW001_Nam.jpg', 15, '2025-04-14 13:29:42'),
(4, 'Orient Star Nam RE-AT0018S00B', 2, 'Orient', 3200000.00, 'Đồng hồ nữ thời trang dây kim loại', 'Orient_Star_Nam.jpg\r\n', 15, '2025-04-20 23:10:35'),
(5, 'Orient Nữ RA-AG0729S30B (RA-AG0726S00B)', 2, 'Orient', 3200000.00, 'Đồng hồ nữ thời trang dây kim loại', 'Orient_RA_Nu.jpg', 15, '2025-04-20 23:10:35'),
(6, 'Orient Sun&Moon SK RA-AA0B02R39B', 2, 'Orient', 2990000.00, 'Đồng hồ nữ thời trang dây kim loại', 'Orient_Sun&Moon_Nam.jpg', 15, '2025-04-20 23:10:35'),
(10, 'Citizen Nam NJ0151-53W', 2, 'Citizen', 3200000.00, 'Đồng hồ nữ thời trang dây kim loại', 'Citizen_NJ0151_Nam.jpg', 15, '2025-04-20 23:10:35'),
(11, 'Citizen Nữ EU6060-55D', 2, 'Citizen', 1900000.00, 'Đồng hồ nữ thời trang dây kim loại', 'Citizen_EM0320_Nu.jpg', 15, '2025-04-20 23:10:35'),
(12, 'Olym Pianus Nam OP990-45ADGS-GL-D', 2, 'Olym Pianus', 3200000.00, 'Đồng hồ nữ thời trang dây kim loại', 'OlymPianus_OP990_Nam.jpg', 15, '2025-04-20 23:10:35'),
(13, 'Olym Pianus Nữ OP990-45DGS-GL-D', 2, 'Olym Pianus', 3200000.00, 'Đồng hồ nữ thời trang dây kim loại', 'OlymPianus_OP990_Nu.jpg', 15, '2025-04-20 23:10:35'),
(14, 'Olym Pianus Nam OP990-45.24ADGS-GL-T', 2, 'Olym Pianus', 3200000.00, 'Đồng hồ nữ thời trang dây kim loại', 'OlymPianus_OP990_Nam.jpg', 15, '2025-04-20 23:10:35'),
(15, 'Bentley Nu BL1869-101MWBB', 2, 'Bentley', 3450000.00, 'Đồng hồ nữ thời trang dây kim loại', 'Bentley_BL1869_Nu.jpg', 15, '2025-04-20 23:10:35'),
(16, 'Casio Nam EQS1784-252KBB-S2-M', 2, 'DW', 3200000.00, 'Đồng hồ nữ thời trang dây kim loại', 'Casio_EQS_Nam.jpg', 15, '2025-04-20 23:10:35'),
(17, 'Bentley Nam BL1832-25MKWD', 2, 'DW', 3200000.00, 'Đồng hồ nữ thời trang dây kim loại', 'Bentley_BL1764_Nam.jpg', 15, '2025-04-20 23:10:35'),
(18, 'Casio G-Shock Nam BL2096-152KBI-S', 2, 'DW', 2550000.00, 'Đồng hồ nữ thời trang dây kim loại', 'Casio_G-Shock_Nam.jpg\r\n', 15, '2025-04-20 23:10:35'),
(22, 'Citizen Eco-Drive Nam BM8475-26E', 1, 'Citizen', 3500000.00, 'Đồng hồ nam năng lượng ánh sáng', 'Citizen_BI5000_Nam.jpg', 25, '2025-04-20 23:10:35'),
(23, 'Olympianus Nữ OP89322-71AGS-T', 1, 'Olympianus', 2800000.00, 'Đồng hồ nam dây da, chống nước', 'OlymPianus_OP130_Nu.jpg', 20, '2025-04-20 23:10:35'),
(24, 'Casio Mtp Nam MTP-1374L-7AVDF', 1, 'Casio', 1250000.00, 'Đồng hồ nam dây da, thiết kế cổ điển', 'Casio_MTP_Nam.jpg', 20, '2025-04-21 15:19:42'),
(34, 'Olym Pianus Nữ OP990-45DGS-GL-D', 2, 'Olym Pianus', 3200000.00, 'Đồng hồ nữ thời trang dây kim loại', 'OlymPianus_OP990_Nu.jpg', 15, '2025-04-21 15:19:42'),
(35, 'Olym Pianus Nam OP990-45.24ADGS-GL-T', 2, 'Olym Pianus', 3200000.00, 'Đồng hồ nữ thời trang dây kim loại', 'OlymPianus_OP990_Nam.jpg', 15, '2025-04-21 15:19:42'),
(36, 'Bentley Nam BL1869-101MWBB', 2, 'DW', 3200000.00, 'Đồng hồ nữ thời trang dây kim loại', 'Bentley_BL1869_Nam.jpg', 15, '2025-04-21 15:19:42'),
(37, 'Bentley Nam BL1784-252KBB-S2-M', 2, 'DW', 3200000.00, 'Đồng hồ nữ thời trang dây kim loại', 'Bentley_BL2416_Nam.jpg', 15, '2025-04-21 15:19:42'),
(38, 'Bentley Nam BL1832-25MKWD', 2, 'DW', 3200000.00, 'Đồng hồ nữ thời trang dây kim loại', 'Bentley_BL1764_Nam.jpg', 15, '2025-04-21 15:19:42'),
(39, 'Bentley Nam BL2096-152KBI-S', 2, 'DW', 3200000.00, 'Đồng hồ nữ thời trang dây kim loại', 'Bentley_BL2096_Nam.jpg', 15, '2025-04-21 15:19:42'),
(40, 'Casio G-Shock', 2, 'DW', 3200000.00, 'Đồng hồ nam thời trang dây cao su', 'Casio_G-Shock1_Nam.jpg', 15, '2025-04-21 15:19:42'),
(41, 'Casio G-Shock EFV-540D-1AVUDF', 1, 'Casio', 2500000.00, 'Đồng hồ nam thể thao, dây kim loại', 'Casio_G-Shock2_Nam.jpg', 30, '2025-04-21 15:19:42'),
(42, 'Casio Vintage Nam SRPB41J1', 1, 'Seiko', 4500000.00, 'Đồng hồ nam cơ khí, thiết kế sang trọng', 'Casio_Vintage_Nam.jpg', 10, '2025-04-21 15:19:42');

-- --------------------------------------------------------

--
-- Table structure for table `thanhtoan`
--

CREATE TABLE `thanhtoan` (
  `MaTT` int(11) NOT NULL,
  `MaDH` int(11) DEFAULT NULL,
  `PhuongThucTT` varchar(50) DEFAULT NULL,
  `NgayThanhToan` datetime DEFAULT current_timestamp(),
  `SoTien` decimal(10,2) DEFAULT NULL,
  `TrangThaiTT` varchar(50) DEFAULT 'Chưa thanh toán'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vanchuyen`
--

CREATE TABLE `vanchuyen` (
  `MaVC` int(11) NOT NULL,
  `TenPhuongThuc` varchar(100) DEFAULT NULL,
  `PhiVanChuyen` decimal(10,2) DEFAULT NULL,
  `SoNgayDuKien` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vanchuyen`
--

INSERT INTO `vanchuyen` (`MaVC`, `TenPhuongThuc`, `PhiVanChuyen`, `SoNgayDuKien`) VALUES
(6, 'Giao hàng tiêu chuẩn', 20000.00, 3),
(7, 'Giao hàng nhanh', 40000.00, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`MaAdmin`),
  ADD UNIQUE KEY `TenDangNhap` (`TenDangNhap`);

--
-- Indexes for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`MaCTDH`),
  ADD KEY `MaDH` (`MaDH`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Indexes for table `chitietphieunhap`
--
ALTER TABLE `chitietphieunhap`
  ADD PRIMARY KEY (`MaChiTietPhieuNhap`),
  ADD KEY `MaPhieuNhap` (`MaPhieuNhap`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Indexes for table `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`MaDanhMuc`);

--
-- Indexes for table `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`MaDH`),
  ADD KEY `MaKH` (`MaKH`),
  ADD KEY `MaKM` (`MaKM`),
  ADD KEY `MaVC` (`MaVC`);

--
-- Indexes for table `giohang`
--
ALTER TABLE `giohang`
  ADD PRIMARY KEY (`MaGio`),
  ADD KEY `MaKH` (`MaKH`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Indexes for table `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`MaKH`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  ADD PRIMARY KEY (`MaKM`),
  ADD UNIQUE KEY `MaCode` (`MaCode`);

--
-- Indexes for table `phieunhap`
--
ALTER TABLE `phieunhap`
  ADD PRIMARY KEY (`MaPhieuNhap`),
  ADD KEY `MaAdmin` (`MaAdmin`);

--
-- Indexes for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`MaSP`),
  ADD KEY `MaDanhMuc` (`MaDanhMuc`);

--
-- Indexes for table `thanhtoan`
--
ALTER TABLE `thanhtoan`
  ADD PRIMARY KEY (`MaTT`),
  ADD KEY `MaDH` (`MaDH`);

--
-- Indexes for table `vanchuyen`
--
ALTER TABLE `vanchuyen`
  ADD PRIMARY KEY (`MaVC`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `MaAdmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  MODIFY `MaCTDH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `chitietphieunhap`
--
ALTER TABLE `chitietphieunhap`
  MODIFY `MaChiTietPhieuNhap` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `danhmuc`
--
ALTER TABLE `danhmuc`
  MODIFY `MaDanhMuc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `donhang`
--
ALTER TABLE `donhang`
  MODIFY `MaDH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `giohang`
--
ALTER TABLE `giohang`
  MODIFY `MaGio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `khachhang`
--
ALTER TABLE `khachhang`
  MODIFY `MaKH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  MODIFY `MaKM` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `phieunhap`
--
ALTER TABLE `phieunhap`
  MODIFY `MaPhieuNhap` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `MaSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `thanhtoan`
--
ALTER TABLE `thanhtoan`
  MODIFY `MaTT` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vanchuyen`
--
ALTER TABLE `vanchuyen`
  MODIFY `MaVC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `chitietdonhang_ibfk_1` FOREIGN KEY (`MaDH`) REFERENCES `donhang` (`MaDH`),
  ADD CONSTRAINT `chitietdonhang_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Constraints for table `chitietphieunhap`
--
ALTER TABLE `chitietphieunhap`
  ADD CONSTRAINT `chitietphieunhap_ibfk_1` FOREIGN KEY (`MaPhieuNhap`) REFERENCES `phieunhap` (`MaPhieuNhap`),
  ADD CONSTRAINT `chitietphieunhap_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Constraints for table `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`MaKH`) REFERENCES `khachhang` (`MaKH`),
  ADD CONSTRAINT `donhang_ibfk_2` FOREIGN KEY (`MaKM`) REFERENCES `khuyenmai` (`MaKM`),
  ADD CONSTRAINT `donhang_ibfk_3` FOREIGN KEY (`MaVC`) REFERENCES `vanchuyen` (`MaVC`);

--
-- Constraints for table `giohang`
--
ALTER TABLE `giohang`
  ADD CONSTRAINT `giohang_ibfk_1` FOREIGN KEY (`MaKH`) REFERENCES `khachhang` (`MaKH`),
  ADD CONSTRAINT `giohang_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Constraints for table `phieunhap`
--
ALTER TABLE `phieunhap`
  ADD CONSTRAINT `phieunhap_ibfk_1` FOREIGN KEY (`MaAdmin`) REFERENCES `admin` (`MaAdmin`);

--
-- Constraints for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`MaDanhMuc`) REFERENCES `danhmuc` (`MaDanhMuc`);

--
-- Constraints for table `thanhtoan`
--
ALTER TABLE `thanhtoan`
  ADD CONSTRAINT `thanhtoan_ibfk_1` FOREIGN KEY (`MaDH`) REFERENCES `donhang` (`MaDH`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
