<?php ob_start(); ?>
<div style="text-align: center; padding: 40px 0;">
    <h1 style="font-size: 64px; color: #ef4444; margin-bottom: 10px;">⚠️ Lỗi 500</h1>
    <h2>Có lỗi kỹ thuật hệ thống xảy ra</h2>
    <p style="color: #64748b; margin-bottom: 20px;">Yêu cầu không thể xử lý. Lỗi đã được ghi lại trong tệp log an toàn để phục vụ quản trị viên bảo mật.</p>
    <a href="/" class="btn primary">Quay về Trang Chủ</a>
</div>
<?php
$content = ob_get_clean(); $title = 'Internal Server Error'; require __DIR__ . '/../layout.php';