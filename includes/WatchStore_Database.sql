
USE SHOP_DONG_HO;

CREATE TABLE Admin (
    MaAdmin INT PRIMARY KEY AUTO_INCREMENT,
    TenDangNhap VARCHAR(50) UNIQUE,
    MatKhau VARCHAR(255),
    HoTen VARCHAR(100),
    Email VARCHAR(100),
    VaiTro VARCHAR(50) DEFAULT 'Admin',
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP
);


USE SHOP_DONG_HO;

CREATE TABLE Admin (
    MaAdmin INT PRIMARY KEY AUTO_INCREMENT,
    TenDangNhap VARCHAR(50) UNIQUE,
    MatKhau VARCHAR(255),
    HoTen VARCHAR(100),
    Email VARCHAR(100),
    VaiTro VARCHAR(50) DEFAULT 'Admin',
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE KhachHang (
    MaKH INT PRIMARY KEY AUTO_INCREMENT,
    HoTen VARCHAR(100),
    Email VARCHAR(100) UNIQUE,
    MatKhau VARCHAR(255),
    SoDienThoai VARCHAR(15),
    DiaChi TEXT,
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE DanhMuc (
    MaDanhMuc INT PRIMARY KEY AUTO_INCREMENT,
    TenDanhMuc VARCHAR(100),
    MoTa TEXT
);

CREATE TABLE SanPham (
    MaSP INT PRIMARY KEY AUTO_INCREMENT,
    TenSP VARCHAR(150),
    MaDanhMuc INT,
    ThuongHieu VARCHAR(100),
    Gia DECIMAL(10,2),
    Xuatxu VARCHAR(20),
    MoTa TEXT,
    HinhAnh VARCHAR(255),
    SoLuongTon INT,
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (MaDanhMuc) REFERENCES DanhMuc(MaDanhMuc)
);

CREATE TABLE ChiTietSanPham (
    MaCTSP INT PRIMARY KEY AUTO_INCREMENT,
    MaSP INT,
    ThuongHieu VARCHAR(100),
    XuatXu VARCHAR(100),
    DoiTuong VARCHAR(50),
    DongSanPham VARCHAR(100),
    KhangNuoc VARCHAR(50),
    LoaiMay VARCHAR(100),
    ChatLieuKinh VARCHAR(100),
    ChatLieuDay VARCHAR(100),
    SizeMat VARCHAR(20),
    DoDay VARCHAR(20),
    Series VARCHAR(100),
    TienIch TEXT,
    FOREIGN KEY (MaSP) REFERENCES SanPham(MaSP)
);

CREATE TABLE KhuyenMai (
    MaKM INT PRIMARY KEY AUTO_INCREMENT,
    MaCode VARCHAR(50) UNIQUE,
    MoTa TEXT,
    ChietKhauPhanTram DECIMAL(5,2),
    NgayBatDau DATE,
    NgayKetThuc DATE,
    KichHoat BOOLEAN DEFAULT TRUE
);

CREATE TABLE VanChuyen (
    MaVC INT PRIMARY KEY AUTO_INCREMENT,
    TenPhuongThuc VARCHAR(100),
    PhiVanChuyen DECIMAL(10,2),
    SoNgayDuKien INT
);

CREATE TABLE DonHang (
    MaDH INT PRIMARY KEY AUTO_INCREMENT,
    MaKH INT,
    NgayDat DATETIME DEFAULT CURRENT_TIMESTAMP,
    TongTien DECIMAL(10,2),
    TrangThai VARCHAR(50) DEFAULT 'Chờ xử lý',
    MaKM INT,
    MaVC INT,
    FOREIGN KEY (MaKH) REFERENCES KhachHang(MaKH),
    FOREIGN KEY (MaKM) REFERENCES KhuyenMai(MaKM),
    FOREIGN KEY (MaVC) REFERENCES VanChuyen(MaVC)
);

CREATE TABLE ChiTietDonHang (
    MaCTDH INT PRIMARY KEY AUTO_INCREMENT,
    MaDH INT,
    MaSP INT,
    SoLuong INT,
    DonGia DECIMAL(10,2),
    ThanhTien DECIMAL(10,2) GENERATED ALWAYS AS (SoLuong * DonGia) STORED,
    FOREIGN KEY (MaDH) REFERENCES DonHang(MaDH),
    FOREIGN KEY (MaSP) REFERENCES SanPham(MaSP)
);

CREATE TABLE GioHang (
    MaGio INT PRIMARY KEY AUTO_INCREMENT,
    MaKH INT,
    MaSP INT,
    SoLuong INT,
    NgayThem DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (MaKH) REFERENCES KhachHang(MaKH),
    FOREIGN KEY (MaSP) REFERENCES SanPham(MaSP)
);

CREATE TABLE ThanhToan (
    MaTT INT PRIMARY KEY AUTO_INCREMENT,
    MaDH INT,
    PhuongThucTT VARCHAR(50),
    NgayThanhToan DATETIME DEFAULT CURRENT_TIMESTAMP,
    SoTien DECIMAL(10,2),
    TrangThaiTT VARCHAR(50) DEFAULT 'Chưa thanh toán',
    FOREIGN KEY (MaDH) REFERENCES DonHang(MaDH)
);

-- Bảng phiếu nhập
CREATE TABLE PhieuNhap (
    MaPhieuNhap INT PRIMARY KEY AUTO_INCREMENT,
    NgayNhap DATETIME DEFAULT CURRENT_TIMESTAMP,
    MaAdmin INT,
    TongTien DECIMAL(10,2),
    GhiChu TEXT,
    FOREIGN KEY (MaAdmin) REFERENCES Admin(MaAdmin)
);

-- Bảng chi tiết phiếu nhập
CREATE TABLE ChiTietPhieuNhap (
    MaChiTietPhieuNhap INT PRIMARY KEY AUTO_INCREMENT,
    MaPhieuNhap INT,
    MaSP INT,
    SoLuong INT,
    DonGia DECIMAL(10,2),
    ThanhTien DECIMAL(10,2) GENERATED ALWAYS AS (SoLuong * DonGia) STORED,
    FOREIGN KEY (MaPhieuNhap) REFERENCES PhieuNhap(MaPhieuNhap),
    FOREIGN KEY (MaSP) REFERENCES SanPham(MaSP)
);


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

INSERT INTO Admin (TenDangNhap, MatKhau, HoTen, Email, VaiTro)
VALUES 
('admin01', 'matkhau123', N'Nguyễn Văn A', 'admin01@email.com', 'Admin'),
('admin02', 'matkhau456', N'Trần Thị B', 'admin02@email.com', 'Admin');

INSERT INTO DanhMuc (TenDanhMuc, MoTa)
VALUES 
(N'Đồng hồ nam', N'Các mẫu đồng hồ dành cho nam giới'),
(N'Đồng hồ nữ', N'Các mẫu đồng hồ dành cho nữ giới');

INSERT INTO KhachHang (HoTen, Email, MatKhau, SoDienThoai, DiaChi)
VALUES 
(N'Lê Văn C', 'le.c@email.com', 'pass123', '0901234567', N'123 Nguyễn Trãi, Hà Nội'),
(N'Phạm Thị D', 'pham.d@email.com', 'pass456', '0987654321', N'456 Trần Hưng Đạo, TP.HCM');

INSERT INTO KhuyenMai (MaCode, MoTa, ChietKhauPhanTram, NgayBatDau, NgayKetThuc, KichHoat)
VALUES 
('TET2025', N'Khuyến mãi Tết 2025', 10.00, '2025-01-15', '2025-02-15', TRUE),
('SUMMER25', N'Giảm giá hè 2025', 15.50, '2025-06-01', '2025-06-30', TRUE),
('BLACKFRI', N'Khuyến mãi Black Friday', 25.00, '2025-11-25', '2025-11-30', FALSE);

INSERT INTO ChiTietSanPham (
    MaSP, ThuongHieu, XuatXu, DoiTuong, DongSanPham,
    KhangNuoc, LoaiMay, ChatLieuKinh, ChatLieuDay,
    SizeMat, DoDay, Series, TienIch
)
VALUES (
    1, 'Casio', 'Nhật', 'Nam', 'Casio MTP',
    '5atm', 'Pin/Quartz', 'Kính Khoáng', 'Dây Da',
    '43.5mm', '10.4mm', 'Casio MTP 1374',
    N'Dạ quang, Lịch thứ, Lịch ngày, Giờ, phút, giây, Lịch 24 giờ'
),
(17, 'Casio', 'Nhật', 'Nam', 'Casio Edifice',
    '10atm', 'Pin/Quartz', 'Kính Cứng', 'Dây Kim Loại',
    '45mm', '12mm', 'Edifice EFV-540D',
    N'Chống nước, Lịch ngày, Đồng hồ bấm giờ'),
(18, 'Seiko', 'Nhật', 'Nam', 'Seiko Presage',
    '5atm', 'Cơ khí', 'Kính Sapphire', 'Dây Da',
    '40mm', '11mm', 'Presage SRPB41J1',
    N'Chống nước, Lịch ngày, Lịch tuần, Lịch tháng'),
(19, 'Citizen', 'Nhật', 'Nam', 'Citizen Eco-Drive',
    '10atm', 'Năng lượng ánh sáng', 'Kính Sapphire', 'Dây Kim Loại',
    '42mm', '10mm', 'Eco-Drive BM8475',
    N'Chống nước, Lịch ngày, Giờ thế giới'),
(20, 'Olympianus', 'Nhật', 'Nam', 'Olympianus OP89322',
    '5atm', 'Pin/Quartz', 'Kính Khoáng', 'Dây Da',
    '44mm', '12mm', 'OP89322-71AGS',
    N'Chống nước, Lịch ngày, Lịch tuần');


