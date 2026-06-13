# 🏥 Dự Án Quản Lý Phòng Khám - PHP Thuần (MVC & Repository Pattern)

## 🚀 Tính Năng Chính
- **Quản lý bệnh nhân (Patients Module):** Xem danh sách, tìm kiếm, thêm mới, cập nhật hồ sơ, áp dụng cơ chế xóa mềm (Soft Delete).
- **Quản lý lịch hẹn (Appointments Module):** Đặt lịch khám, kiểm soát trùng mã khám (`appointment_code`), phân trang dữ liệu lớn.
- **Tối ưu Database:** Thiết lập Index đa cột (`booking_date`, `status`) cho bảng lịch hẹn.

## 🛠️ Hướng Dẫn Cài Đặt & Khởi Chạy

### 1. Cấu hình Cơ sở dữ liệu
- Mở **XAMPP**, kích hoạt **Apache** và **MySQL**.
- Truy cập `http://localhost/phpmyadmin`, tạo một database mới tên là: `clinic_lab05_db`.
- Import file cấu trúc dữ liệu `.sql` của dự án vào database vừa tạo.

### 2. Cấu hình Mã nguồn
- Bản sao dự án đặt trong thư mục `htdocs` của XAMPP.
- Kiểm tra cấu hình kết nối tại file `config/database.php` bảo đảm đúng thông tin User/Password của MySQL local.

### 3. Sinh dữ liệu mẫu (Seed Data) để test EXPLAIN & Phân trang
Mở Terminal tại thư mục gốc của dự án và chạy câu lệnh sau để tự động sinh 200 bản ghi lịch hẹn ngẫu nhiên:
```bash
php seed_data.php