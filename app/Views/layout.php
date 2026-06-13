<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title><?= e($title ?? 'Clinic Portal Lab05') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<nav class="navbar">
    <a href="/">🏥 Clinic Dashboard</a>
    <a href="/patients">👥 Quản Lý Bệnh Nhân</a>
    <a href="/patients/create">➕ Thêm Bệnh Nhân</a>
    <a href="/appointments">📅 Lịch Hẹn Khám</a>
    <a href="/appointments/create">➕ Đặt Lịch Mới</a>
    <a href="/health">⚙️ Hệ Thống Health</a>
</nav>
<main class="container">
    <?php if ($success = flash_get('success')): ?>
        <div class="alert success"><?= e($success) ?></div>
    <?php endif; ?>
    
    <?= $content ?? '' ?>
</main>
</body>
</html>