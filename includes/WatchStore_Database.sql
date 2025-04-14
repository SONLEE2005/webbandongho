
USE SHOP_DONG_HO;

CREATE TABLE Admin (
    MaAdmin INT PRIMARY KEY AUTO_INCREMENT,
    TenDangNhap VARCHAR(50) UNIQUE,
    MatKhau VARCHAR(255),
    HoTen VARCHAR(100),
    Email VARCHAR(100),
    VaiTro VARCHAR(50) DEFAULT 'Admin',
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE KhachHang (
    MaKH INT PRIMARY KEY AUTO_INCREMENT,
    HoTen VARCHAR(100),
    Email VARCHAR(100) UNIQUE,
    MatKhau VARCHAR(255),
    SoDienThoai VARCHAR(15),
    DiaChi TEXT,
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP
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
    MoTa TEXT,
    HinhAnh VARCHAR(255),
    SoLuongTon INT,
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (MaDanhMuc) REFERENCES DanhMuc(MaDanhMuc)
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

INSERT INTO SanPham (TenSP, MaDanhMuc, ThuongHieu, Gia, MoTa, HinhAnh, SoLuongTon)
VALUES 
(N'Casio MTP-V002L-7B3UDF', 1, N'Casio', 1250000, N'Đồng hồ nam dây da, thiết kế cổ điển', 'casio1.jpg', 20),
(N'Daniel Wellington Petite Melrose', 2, N'DW', 3200000, N'Đồng hồ nữ thời trang dây kim loại', 'dw1.jpg', 15);

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

