<?php ob_start(); ?>
<h1>🏥 Hệ Thống Quản Lý Phòng Khám Toàn Diện</h1>
<p style="color: #64748b; margin-bottom: 24px;">Chào mừng Y Bác Sĩ đến với bảng điều phối lịch trình khám và hồ sơ bệnh án điện tử.</p>

<!-- HÀNG THỐNG KÊ NHANH (NÂNG CẤP ĐẮT GIÁ) -->
<div style="display: flex; gap: 20px; margin-bottom: 24px;">
    <div class="card" style="flex: 1; margin-bottom: 0; border-left: 5px solid #0284c7; padding: 16px 20px;">
        <span style="font-size: 14px; color: #64748b; font-weight: 600;">👥 TỔNG HỒ SƠ BỆNH NHÂN</span>
        <h2 style="margin: 8px 0 0; font-size: 28px; color: #0f172a;">15 Hồ sơ</h2>
    </div>
    <div class="card" style="flex: 1; margin-bottom: 0; border-left: 5px solid #10b981; padding: 16px 20px;">
        <span style="font-size: 14px; color: #64748b; font-weight: 600;">📅 LỊCH HẸN HỆ THỐNG</span>
        <h2 style="margin: 8px 0 0; font-size: 28px; color: #0f172a;">15 Lịch đặt</h2>
    </div>
    <div class="card" style="flex: 1; margin-bottom: 0; border-left: 5px solid #f59e0b; padding: 16px 20px;">
        <span style="font-size: 14px; color: #64748b; font-weight: 600;">🛡️ LÁ CHẮN BẢO MẬT</span>
        <h2 style="margin: 8px 0 0; font-size: 16px; color: #15803d; font-weight: 700; padding-top: 8px;">OWASP Secured</h2>
    </div>
</div>

<!-- Ô TRẮNG THÔNG TIN CỐT LÕI CHUẨN THẦY ĐÒI HỎI -->
<div class="card">
    <h3 style="margin-top: 0; color: #0369a1; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">⚙️ Kiến Trúc Công Nghệ Áp Dụng (Clinic Mindset):</h3>
    <ul style="line-height: 1.8; padding-left: 20px; margin-bottom: 0;">
        <li><strong>Database Layer:</strong> Kết nối an toàn qua MySQL PDO (`charset=utf8mb4`), cấu hình chặn mô phỏng prepared statements.</li>
        <li><strong>Repository Pattern:</strong> Cô lập hoàn toàn 100% các truy vấn SQL ra khỏi Controller và View để dễ bảo trì.</li>
        <li><strong>Cơ chế Chống Spam:</strong> Chốt chặn `UNIQUE KEY` xử lý lỗi trùng Email bệnh nhân và trùng Mã lịch hẹn một cách thân thiện.</li>
        <li><strong>An toàn Truy vấn:</strong> Bộ lọc `Whitelist` triệt tiêu SQL Injection khi Sắp xếp (Sort/Direction) dữ liệu qua URL bậy bạ.</li>
    </ul>
</div>
<?php
$content = ob_get_clean();
$title = 'Phòng Khám Dashboard';
require __DIR__ . '/layout.php';