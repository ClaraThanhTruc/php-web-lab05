<?php ob_start(); ?>
<h1>✏️ Chỉnh Sửa Hồ Sơ Bệnh Nhân</h1>
<form method="post" action="/patients/update" class="card form-card">
    <input type="hidden" name="id" value="<?= e($id) ?>">

    <div class="form-group">
        <label>Họ và Tên bệnh nhân <span class="danger">*</span></label>
        <input type="text" name="fullname" value="<?= e($old['fullname'] ?? '') ?>">
        <?php if (!empty($errors['fullname'])): ?><p class="error"><?= e($errors['fullname']) ?></p><?php endif; ?>
    </div>

    <div class="form-group">
        <label>Địa chỉ Email <span class="danger">*</span></label>
        <input type="text" name="patient_email" value="<?= e($old['patient_email'] ?? '') ?>">
        <?php if (!empty($errors['patient_email'])): ?><p class="error"><?= e($errors['patient_email']) ?></p><?php endif; ?>
    </div>

    <div class="form-group">
        <label>Số điện thoại liên lạc <span class="danger">*</span></label>
        <input type="text" name="phone" value="<?= e($old['phone'] ?? '') ?>">
        <?php if (!empty($errors['phone'])): ?><p class="error"><?= e($errors['phone']) ?></p><?php endif; ?>
    </div>

    <div class="form-group">
        <label>Giới tính</label>
        <select name="gender">
            <option value="male" <?= ($old['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Nam</option>
            <option value="female" <?= ($old['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Nữ</option>
            <option value="other" <?= ($old['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Khác</option>
        </select>
    </div>

    <div class="form-group">
        <label>Tiền sử bệnh án (Nếu có)</label>
        <textarea name="medical_history" rows="4"><?= e($old['medical_history'] ?? '') ?></textarea>
    </div>

    <div style="margin-top: 10px;">
        <button class="btn primary" type="submit">🔄 Cập Nhật</button>
        <a class="btn" href="/patients" style="background: #e2e8f0; color: #334155;">Hủy bỏ</a>
    </div>
</form>
<?php
$content = ob_get_clean();
$title = 'Sửa Hồ Sơ Bệnh Nhân';
require __DIR__ . '/../layout.php';