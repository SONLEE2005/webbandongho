-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 21, 2025 lúc 11:16 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `shop_dong_ho`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `MaAdmin` int(11) NOT NULL,
  `TenDangNhap` varchar(50) DEFAULT NULL,
  `MatKhau` varchar(8) DEFAULT NULL,
  `HoTen` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `VaiTro` varchar(50) DEFAULT 'Admin',
  `DaKhoa` TINYINT(1) DEFAULT 0,
  `NgayTao` datetime DEFAULT current_timestamp(),
  `NgayCapNhat` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`MaAdmin`, `TenDangNhap`, `MatKhau`, `HoTen`, `Email`, `VaiTro`, `NgayTao`, `NgayCapNhat`) VALUES
(1, 'admin01', 'matkhau1', 'Nguyễn Văn A', 'admin01@email.com', 'Admin', '2025-04-14 13:29:42', '2025-04-21 16:15:07'),
(2, 'admin02', 'matkhau4', 'Trần Thị B', 'admin02@email.com', 'Admin', '2025-04-14 13:29:42', '2025-04-21 16:15:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `MaCTDH` int(11) NOT NULL,
  `MaDH` int(11) DEFAULT NULL,
  `MaSP` int(11) DEFAULT NULL,
  `SoLuong` int(11) DEFAULT NULL,
  `DonGia` decimal(10,2) DEFAULT NULL,
  `ThanhTien` decimal(10,2) GENERATED ALWAYS AS (`SoLuong` * `DonGia`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietphieunhap`
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
-- Cấu trúc bảng cho bảng `danhmuc`
--

CREATE TABLE `danhmuc` (
  `MaDanhMuc` int(11) NOT NULL,
  `TenDanhMuc` varchar(100) DEFAULT NULL,
  `MoTa` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danhmuc`
--

INSERT INTO `danhmuc` (`MaDanhMuc`, `TenDanhMuc`, `MoTa`) VALUES
(1, 'Đồng hồ nam', 'Các mẫu đồng hồ dành cho nam giới'),
(2, 'Đồng hồ nữ', 'Các mẫu đồng hồ dành cho nữ giới');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donhang`
--

CREATE TABLE `donhang` (
  `MaDH` int(11) NOT NULL,
  `MaKH` int(11) DEFAULT NULL,
  `NgayDat` datetime DEFAULT current_timestamp(),
  `TongTien` decimal(10,2) DEFAULT NULL,
  `TrangThai` varchar(50) DEFAULT 'Chờ xử lý',
  `MaKM` int(11) DEFAULT NULL,
  `MaVC` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giohang`
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
-- Cấu trúc bảng cho bảng `khachhang`
--

CREATE TABLE `khachhang` (
  `MaKH` int(11) NOT NULL,
  `HoTen` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `MatKhau` varchar(255) DEFAULT NULL,
  `SoDienThoai` varchar(15) DEFAULT NULL,
  `DiaChi` text DEFAULT NULL,
  `DaKhoa` TINYINT(1) DEFAULT 0,
  `NgayTao` datetime DEFAULT current_timestamp(),
  `NgayCapNhat` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `khachhang`
--

INSERT INTO `khachhang` (`MaKH`, `HoTen`, `Email`, `MatKhau`, `SoDienThoai`, `DiaChi`, `NgayTao`) VALUES
(1, 'Lê Văn C', 'le.c@email.com', 'pass123', '0901234567', '123 Nguyễn Trãi, Hà Nội', '2025-04-14 13:29:42'),
(2, 'Phạm Thị D', 'pham.d@email.com', 'pass456', '0987654321', '456 Trần Hưng Đạo, TP.HCM', '2025-04-14 13:29:42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khuyenmai`
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
-- Đang đổ dữ liệu cho bảng `khuyenmai`
--

INSERT INTO `khuyenmai` (`MaKM`, `MaCode`, `MoTa`, `ChietKhauPhanTram`, `NgayBatDau`, `NgayKetThuc`, `KichHoat`) VALUES
(1, 'TET2025', 'Khuyến mãi Tết 2025', 10.00, '2025-01-15', '2025-02-15', 1),
(2, 'SUMMER25', 'Giảm giá hè 2025', 15.50, '2025-06-01', '2025-06-30', 1),
(3, 'BLACKFRI', 'Khuyến mãi Black Friday', 25.00, '2025-11-25', '2025-11-30', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieunhap`
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
-- Cấu trúc bảng cho bảng `sanpham`
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
  `NgayTao` datetime DEFAULT current_timestamp(),
  `NgayCapNhat` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
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
-- Cấu trúc bảng cho bảng `thanhtoan`
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
-- Cấu trúc bảng cho bảng `vanchuyen`
--

CREATE TABLE `vanchuyen` (
  `MaVC` int(11) NOT NULL,
  `TenPhuongThuc` varchar(100) DEFAULT NULL,
  `PhiVanChuyen` decimal(10,2) DEFAULT NULL,
  `SoNgayDuKien` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`MaAdmin`),
  ADD UNIQUE KEY `TenDangNhap` (`TenDangNhap`);

--
-- Chỉ mục cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`MaCTDH`),
  ADD KEY `MaDH` (`MaDH`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Chỉ mục cho bảng `chitietphieunhap`
--
ALTER TABLE `chitietphieunhap`
  ADD PRIMARY KEY (`MaChiTietPhieuNhap`),
  ADD KEY `MaPhieuNhap` (`MaPhieuNhap`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Chỉ mục cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`MaDanhMuc`);

--
-- Chỉ mục cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`MaDH`),
  ADD KEY `MaKH` (`MaKH`),
  ADD KEY `MaKM` (`MaKM`),
  ADD KEY `MaVC` (`MaVC`);

--
-- Chỉ mục cho bảng `giohang`
--
ALTER TABLE `giohang`
  ADD PRIMARY KEY (`MaGio`),
  ADD KEY `MaKH` (`MaKH`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Chỉ mục cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`MaKH`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Chỉ mục cho bảng `khuyenmai`
--
ALTER TABLE `khuyenmai`
  ADD PRIMARY KEY (`MaKM`),
  ADD UNIQUE KEY `MaCode` (`MaCode`);

--
-- Chỉ mục cho bảng `phieunhap`
--
ALTER TABLE `phieunhap`
  ADD PRIMARY KEY (`MaPhieuNhap`),
  ADD KEY `MaAdmin` (`MaAdmin`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`MaSP`),
  ADD KEY `MaDanhMuc` (`MaDanhMuc`);

--
-- Chỉ mục cho bảng `thanhtoan`
--
ALTER TABLE `thanhtoan`
  ADD PRIMARY KEY (`MaTT`),
  ADD KEY `MaDH` (`MaDH`);

--
-- Chỉ mục cho bảng `vanchuyen`
--
ALTER TABLE `vanchuyen`
  ADD PRIMARY KEY (`MaVC`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin`
--
ALTER TABLE `admin`
  MODIFY `MaAdmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  MODIFY `MaCTDH` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `chitietphieunhap`
--
ALTER TABLE `chitietphieunhap`
  MODIFY `MaChiTietPhieuNhap` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  MODIFY `MaDanhMuc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `donhang`
--
ALTER TABLE `donhang`
  MODIFY `MaDH` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `giohang`
--
ALTER TABLE `giohang`
  MODIFY `MaGio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  MODIFY `MaKH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `khuyenmai`
--
ALTER TABLE `khuyenmai`
  MODIFY `MaKM` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `phieunhap`
--
ALTER TABLE `phieunhap`
  MODIFY `MaPhieuNhap` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `MaSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT cho bảng `thanhtoan`
--
ALTER TABLE `thanhtoan`
  MODIFY `MaTT` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `vanchuyen`
--
ALTER TABLE `vanchuyen`
  MODIFY `MaVC` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `chitietdonhang_ibfk_1` FOREIGN KEY (`MaDH`) REFERENCES `donhang` (`MaDH`),
  ADD CONSTRAINT `chitietdonhang_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Các ràng buộc cho bảng `chitietphieunhap`
--
ALTER TABLE `chitietphieunhap`
  ADD CONSTRAINT `chitietphieunhap_ibfk_1` FOREIGN KEY (`MaPhieuNhap`) REFERENCES `phieunhap` (`MaPhieuNhap`),
  ADD CONSTRAINT `chitietphieunhap_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Các ràng buộc cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`MaKH`) REFERENCES `khachhang` (`MaKH`),
  ADD CONSTRAINT `donhang_ibfk_2` FOREIGN KEY (`MaKM`) REFERENCES `khuyenmai` (`MaKM`),
  ADD CONSTRAINT `donhang_ibfk_3` FOREIGN KEY (`MaVC`) REFERENCES `vanchuyen` (`MaVC`);

--
-- Các ràng buộc cho bảng `giohang`
--
ALTER TABLE `giohang`
  ADD CONSTRAINT `giohang_ibfk_1` FOREIGN KEY (`MaKH`) REFERENCES `khachhang` (`MaKH`),
  ADD CONSTRAINT `giohang_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Các ràng buộc cho bảng `phieunhap`
--
ALTER TABLE `phieunhap`
  ADD CONSTRAINT `phieunhap_ibfk_1` FOREIGN KEY (`MaAdmin`) REFERENCES `admin` (`MaAdmin`);

--
-- Các ràng buộc cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`MaDanhMuc`) REFERENCES `danhmuc` (`MaDanhMuc`);

--
-- Các ràng buộc cho bảng `thanhtoan`
--
ALTER TABLE `thanhtoan`
  ADD CONSTRAINT `thanhtoan_ibfk_1` FOREIGN KEY (`MaDH`) REFERENCES `donhang` (`MaDH`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
