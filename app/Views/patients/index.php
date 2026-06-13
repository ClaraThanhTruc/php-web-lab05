<?php ob_start(); ?>
<div class="header-action">
    <h1>👥 Quản Lý Hồ Sơ Bệnh Nhân</h1>
    <a class="btn primary" href="/patients/create">➕ Thêm Bệnh Nhân Mới</a>
</div>

<form method="get" action="/patients" class="toolbar">
    <input type="hidden" name="page" value="1">
    <input type="text" name="q" value="<?= e($q) ?>" placeholder="Tìm kiếm theo tên, email hoặc số điện thoại...">
    <input type="hidden" name="sort" value="<?= e($sort) ?>">
    <input type="hidden" name="direction" value="<?= e($direction) ?>">
    <button type="submit" class="btn primary">🔍 Tìm Kiếm</button>
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th><a href="/patients?<?= e(query_string(['sort' => 'fullname', 'direction' => $direction === 'asc' ? 'desc' : 'asc'])) ?>">Họ và Tên ↕️</a></th>
            <th>Email</th>
            <th>Số Điện Thoại</th>
            <th>Giới Tính</th>
            <th><a href="/patients?<?= e(query_string(['sort' => 'created_at', 'direction' => $direction === 'asc' ? 'desc' : 'asc'])) ?>">Ngày Tạo ↕️</a></th>
            <th>Thao Tác</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($patients)): ?>
            <tr><td colspan="7" style="text-align: center; color: #64748b;">Không tìm thấy dữ liệu bệnh nhân nào.</td></tr>
        <?php else: ?>
            <?php foreach ($patients as $patient): ?>
            <tr>
                <td><?= e($patient['id']) ?></td>
                <td><strong><?= e($patient['fullname']) ?></strong></td>
                <td><?= e($patient['patient_email']) ?></td>
                <td><?= e($patient['phone']) ?></td>
                <td><span class="badge"><?= e($patient['gender'] === 'male' ? 'Nam' : ($patient['gender'] === 'female' ? 'Nữ' : 'Khác')) ?></span></td>
                <td><?= e($patient['created_at']) ?></td>
                <td>
                    <a class="link" href="/patients/edit?id=<?= e($patient['id']) ?>">✏️ Sửa</a> | 
                    <form method="post" action="/patients/delete" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa hồ sơ bệnh nhân này?')">
                        <input type="hidden" name="id" value="<?= e($patient['id']) ?>">
                        <button type="submit" class="link danger">🗑️ Xóa</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="/patients?<?= e(query_string(['page' => $page - 1])) ?>">◀️ Trang trước</a>
    <?php endif; ?>
    <span class="page-info">Trang <?= e($page) ?> / <?= e($totalPages) ?> (Tổng số: <?= e($total) ?> bệnh nhân)</span>
    <?php if ($page < $totalPages): ?>
        <a href="/patients?<?= e(query_string(['page' => $page + 1])) ?>">Trang sau ▶️</a>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
$title = 'Danh Sách Bệnh Nhân';
require __DIR__ . '/../layout.php';