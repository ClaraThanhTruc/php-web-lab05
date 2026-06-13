<?php ob_start(); ?>
<div style="text-align: center; padding: 40px 0;">
    <h1 style="font-size: 64px; color: #0284c7; margin-bottom: 10px;">404</h1>
    <h2>Không tìm thấy trang yêu cầu</h2>
    <p style="color: #64748b; margin-bottom: 20px;">Đường dẫn bạn truy cập không tồn tại trong hệ thống phòng khám.</p>
    <a href="/" class="btn primary">Quay về Trang Chủ Dashboard</a>
</div>
<?php
$content = ob_get_clean(); $title = '404 Not Found'; require __DIR__ . '/../layout.php';