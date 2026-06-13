<?php ob_start(); ?>
<div class="header-action">
    <h1>📅 Quản Lý Lịch Hẹn Khám Bệnh</h1>
    <a class="btn primary" href="/appointments/create">📅 Đặt Lịch Hẹn Mới</a>
</div>

<form method="get" action="/appointments" class="toolbar">
    <input type="hidden" name="page" value="1">
    <input type="text" name="q" value="<?= e($q) ?>" placeholder="Tìm theo mã lịch hẹn, tên bệnh nhân, email...">
    <input type="hidden" name="sort" value="<?= e($sort) ?>">
    <input type="hidden" name="direction" value="<?= e($direction) ?>">
    <button type="submit" class="btn primary">🔍 Bộ Lọc</button>
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Mã Lịch Hẹn</th>
            <th>Tên Bệnh Nhân</th>
            <th>Email</th>
            <th><a href="/appointments?<?= e(query_string(['sort' => 'booking_date', 'direction' => $direction === 'asc' ? 'desc' : 'asc'])) ?>">Ngày Hẹn Khám ↕️</a></th>
            <th>Trạng Thái</th>
            <th>Thao Tác</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($appointments)): ?>
            <tr><td colspan="7" style="text-align: center; color: #64748b;">Không có lịch hẹn khám nào được ghi nhận.</td></tr>
        <?php else: ?>
            <?php foreach ($appointments as $apt): ?>
            <tr>
                <td><?= e($apt['id']) ?></td>
                <td><code style="background: #e2e8f0; padding: 4px 8px; border-radius: 4px; font-weight: bold;"><?= e($apt['appointment_code']) ?></code></td>
                <td><strong><?= e($apt['patient_name']) ?></strong></td>
                <td><?= e($apt['patient_email']) ?></td>
                <td><?= e($apt['booking_date']) ?></td>
                <td>
                    <?php 
                        $statusText = ['pending' => '⏳ Chờ khám', 'confirmed' => '✅ Đã xác nhận', 'completed' => '🏥 Hoàn thành', 'cancelled' => '❌ Đã hủy'];
                        echo '<span class="badge" style="background: #f1f5f9; color: #1e293b; border: 1px solid #cbd5e1;">' . e($statusText[$apt['status']] ?? $apt['status']) . '</span>';
                    ?>
                </td>
                <td>
                    <a class="link" href="/appointments/edit?id=<?= e($apt['id']) ?>">✏️ Sửa</a> | 
                    <form method="post" action="/appointments/delete" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn hủy bỏ lịch hẹn khám này?')">
                        <input type="hidden" name="id" value="<?= e($apt['id']) ?>">
                        <button type="submit" class="link danger">🗑️ Hủy</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="/appointments?<?= e(query_string(['page' => $page - 1])) ?>">◀️</a>
    <?php endif; ?>
    <span class="page-info">Trang <?= e($page) ?> / <?= e($totalPages) ?></span>
    <?php if ($page < $totalPages): ?>
        <a href="/appointments?<?= e(query_string(['page' => $page + 1])) ?>">▶️</a>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
$title = 'Danh Sách Lịch Hẹn';
require __DIR__ . '/../layout.php';