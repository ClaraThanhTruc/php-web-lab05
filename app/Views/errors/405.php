<?php ob_start(); ?>
<div style="text-align: center; padding: 40px 0;">
    <h1 style="font-size: 64px; color: #ea580c; margin-bottom: 10px;">405</h1>
    <h2>Phương thức không được hỗ trợ (Method Not Allowed)</h2>
    <p style="color: #64748b; margin-bottom: 20px;">Hệ thống bảo vệ từ chối xử lý dữ liệu thông qua phương thức GET trái phép.</p>
    <a href="/" class="btn primary">Quay về Trang Chủ Dashboard</a>
</div>
<?php
$content = ob_get_clean(); $title = '405 Method Not Allowed'; require __DIR__ . '/../layout.php';