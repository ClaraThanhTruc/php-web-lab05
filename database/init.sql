-- 1. Tạo Database cho phòng khám nếu chưa tồn tại
CREATE DATABASE IF NOT EXISTS `clinic_lab05_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `clinic_lab05_db`;

-- 2. Tạo bảng Quản Lý Bệnh Nhân (Module A)
CREATE TABLE IF NOT EXISTS `patients` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `fullname` VARCHAR(255) NOT NULL,
    `patient_email` VARCHAR(255) NOT NULL UNIQUE,
    `phone` VARCHAR(50) NOT NULL,
    `gender` ENUM('male', 'female', 'other') DEFAULT 'male',
    `medical_history` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_patients_created` (`created_at`),
    INDEX `idx_patients_fullname` (`fullname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tạo bảng Quản Lý Lịch Hẹn Khám (Module B - Phần tự làm)
CREATE TABLE IF NOT EXISTS `appointments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `appointment_code` VARCHAR(100) NOT NULL UNIQUE,
    `patient_name` VARCHAR(255) NOT NULL,
    `patient_email` VARCHAR(255) NOT NULL,
    `booking_date` DATETIME NOT NULL,
    `status` ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    `doctor_note` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_appointments_date_status` (`booking_date`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Chèn dữ liệu mẫu (Seed dữ liệu 15 dòng cho mỗi bảng để test phân trang)
INSERT INTO `patients` (`fullname`, `patient_email`, `phone`, `gender`, `medical_history`) VALUES
('Nguyễn Văn A', 'bnhan01@gmail.com', '0901234567', 'male', 'Tiền sử cao huyết áp'),
('Trần Thị B', 'bnhan02@gmail.com', '0901234568', 'female', NULL),
('Lê Văn C', 'bnhan03@gmail.com', '0901234569', 'male', 'Dị ứng thuốc penicillin'),
('Phạm Minh D', 'bnhan04@gmail.com', '0901234570', 'male', NULL),
('Hoàng Lan E', 'bnhan05@gmail.com', '0901234571', 'female', 'Đột quỵ nhẹ năm 2024'),
('Vũ Đăng F', 'bnhan06@gmail.com', '0901234572', 'male', NULL),
('Đặng Thúy G', 'bnhan07@gmail.com', '0901234573', 'female', 'Tiểu đường tuýp 2'),
('Bùi Quang H', 'bnhan08@gmail.com', '0901234574', 'male', NULL),
('Đỗ Thùy I', 'bnhan09@gmail.com', '0901234575', 'female', NULL),
('Ngô Tiến J', 'bnhan10@gmail.com', '0901234576', 'male', 'Hen suyễn mãn tính'),
('Trịnh Kim K', 'bnhan11@gmail.com', '0901234577', 'female', NULL),
('Lý Triệu L', 'bnhan12@gmail.com', '0901234578', 'male', NULL),
('Dương Hồng M', 'bnhan13@gmail.com', '0901234579', 'female', 'Đang mang thai tuần 12'),
('Phan Hoàng N', 'bnhan14@gmail.com', '0901234580', 'male', NULL),
('Tống Khánh O', 'bnhan15@gmail.com', '0901234581', 'female', NULL);

INSERT INTO `appointments` (`appointment_code`, `patient_name`, `patient_email`, `booking_date`, `status`, `doctor_note`) VALUES
('APT-20260613-001', 'Nguyễn Văn A', 'bnhan01@gmail.com', '2026-06-15 08:00:00', 'pending', 'Khám định kỳ huyết áp'),
('APT-20260613-002', 'Trần Thị B', 'bnhan02@gmail.com', '2026-06-15 09:30:00', 'confirmed', 'Kiểm tra tổng quát'),
('APT-20260613-003', 'Lê Văn C', 'bnhan03@gmail.com', '2026-06-15 10:45:00', 'pending', 'Khám sàng lọc dị ứng'),
('APT-20260613-004', 'Phạm Minh D', 'bnhan04@gmail.com', '2026-06-15 14:00:00', 'pending', NULL),
('APT-20260613-005', 'Hoàng Lan E', 'bnhan05@gmail.com', '2026-06-16 08:30:00', 'confirmed', 'Theo dõi tim mạch'),
('APT-20260613-006', 'Vũ Đăng F', 'bnhan06@gmail.com', '2026-06-16 11:00:00', 'pending', NULL),
('APT-20260613-007', 'Đặng Thúy G', 'bnhan07@gmail.com', '2026-06-16 15:15:00', 'confirmed', 'Xét nghiệm chỉ số đường huyết'),
('APT-20260613-008', 'Bùi Quang H', 'bnhan08@gmail.com', '2026-06-17 09:00:00', 'pending', NULL),
('APT-20260613-009', 'Đỗ Thùy I', 'bnhan09@gmail.com', '2026-06-17 10:00:00', 'pending', NULL),
('APT-20260613-010', 'Ngô Tiến J', 'bnhan10@gmail.com', '2026-06-17 13:30:00', 'confirmed', 'Đo chức năng hô hấp'),
('APT-20260613-011', 'Trịnh Kim K', 'bnhan11@gmail.com', '2026-06-18 08:00:00', 'pending', NULL),
('APT-20260613-012', 'Lý Triệu L', 'bnhan12@gmail.com', '2026-06-18 14:30:00', 'pending', NULL),
('APT-20260613-013', 'Dương Hồng M', 'bnhan13@gmail.com', '2026-06-19 09:00:00', 'confirmed', 'Khám thai định kỳ'),
('APT-20260613-014', 'Phan Hoàng N', 'bnhan14@gmail.com', '2026-06-19 10:30:00', 'pending', NULL),
('APT-20260613-015', 'Tống Khánh O', 'bnhan15@gmail.com', '2026-06-19 16:00:00', 'pending', NULL);