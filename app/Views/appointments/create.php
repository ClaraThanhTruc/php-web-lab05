<?php ob_start(); ?>
<h1>📅 Đăng Ký Lịch Hẹn Khám Mới</h1>
<form method="post" action="/appointments/store" class="card form-card">
    <div class="form-group">
        <label>Mã lịch hẹn độc quyền (Unique Code) <span class="danger">*</span></label>
        <input type="text" name="appointment_code" value="<?= e($old['appointment_code'] ?? '') ?>">
        <?php if (!empty($errors['appointment_code'])): ?><p class="error"><?= e($errors['appointment_code']) ?></p><?php endif; ?>
    </div>

    <div class="form-group">
        <label>Tên bệnh nhân đến khám <span class="danger">*</span></label>
        <input type="text" name="patient_name" value="<?= e($old['patient_name'] ?? '') ?>">
        <?php if (!empty($errors['patient_name'])): ?><p class="error"><?= e($errors['patient_name']) ?></p><?php endif; ?>
    </div>

    <div class="form-group">
        <label>Email liên hệ nhận lịch <span class="danger">*</span></label>
        <input type="text" name="patient_email" value="<?= e($old['patient_email'] ?? '') ?>">
        <?php if (!empty($errors['patient_email'])): ?><p class="error"><?= e($errors['patient_email']) ?></p><?php endif; ?>
    </div>

    <div class="form-group">
        <label>Chọn Ngày & Giờ khám <span class="danger">*</span></label>
        <input type="datetime-local" name="booking_date" value="<?= e($old['booking_date'] ?? '') ?>">
        <?php if (!empty($errors['booking_date'])): ?><p class="error"><?= e($errors['booking_date']) ?></p><?php endif; ?>
    </div>

    <div class="form-group">
        <label>Trạng thái ban đầu</label>
        <select name="status">
            <option value="pending" <?= ($old['status'] ?? '') === 'pending' ? 'selected' : '' ?>>⏳ Chờ khám</option>
            <option value="confirmed" <?= ($old['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>✅ Đã xác nhận</option>
        </select>
    </div>

    <div class="form-group">
        <label>Ghi chú lâm sàng / Lý do khám</label>
        <textarea name="doctor_note" rows="3"><?= e($old['doctor_note'] ?? '') ?></textarea>
    </div>

    <div style="margin-top: 10px;">
        <button class="btn primary" type="submit">📅 Chốt Lịch Hẹn</button>
        <a class="btn" href="/appointments" style="background: #e2e8f0; color: #334155;">Quay về</a>
    </div>
</form>
<?php
$content = ob_get_clean();
$title = 'Đặt Lịch Hẹn';
require __DIR__ . '/../layout.php';