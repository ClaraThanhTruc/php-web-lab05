<?php ob_start(); ?>
<h1>✏️ Điều Chỉnh Lịch Hẹn Khám Bệnh</h1>
<form method="post" action="/appointments/update" class="card form-card">
    <input type="hidden" name="id" value="<?= e($id) ?>">

    <div class="form-group">
        <label>Mã lịch hẹn (Không được trùng) <span class="danger">*</span></label>
        <input type="text" name="appointment_code" value="<?= e($old['appointment_code'] ?? '') ?>">
        <?php if (!empty($errors['appointment_code'])): ?><p class="error"><?= e($errors['appointment_code']) ?></p><?php endif; ?>
    </div>

    <div class="form-group">
        <label>Tên bệnh nhân <span class="danger">*</span></label>
        <input type="text" name="patient_name" value="<?= e($old['patient_name'] ?? '') ?>">
        <?php if (!empty($errors['patient_name'])): ?><p class="error"><?= e($errors['patient_name']) ?></p><?php endif; ?>
    </div>

    <div class="form-group">
        <label>Email bệnh nhân <span class="danger">*</span></label>
        <input type="text" name="patient_email" value="<?= e($old['patient_email'] ?? '') ?>">
        <?php if (!empty($errors['patient_email'])): ?><p class="error"><?= e($errors['patient_email']) ?></p><?php endif; ?>
    </div>

    <div class="form-group">
        <label>Ngày & Giờ khám bệnh <span class="danger">*</span></label>
        <input type="datetime-local" name="booking_date" value="<?= e($old['booking_date'] ?? '') ?>">
        <?php if (!empty($errors['booking_date'])): ?><p class="error"><?= e($errors['booking_date']) ?></p><?php endif; ?>
    </div>

    <div class="form-group">
        <label>Trạng thái lịch trình</label>
        <select name="status">
            <option value="pending" <?= ($old['status'] ?? '') === 'pending' ? 'selected' : '' ?>>⏳ Chờ khám</option>
            <option value="confirmed" <?= ($old['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>✅ Đã xác nhận</option>
            <option value="completed" <?= ($old['status'] ?? '') === 'completed' ? 'selected' : '' ?>>🏥 Hoàn thành</option>
            <option value="cancelled" <?= ($old['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>❌ Đã hủy</option>
        </select>
    </div>

    <div class="form-group">
        <label>Chẩn đoán sơ bộ / Ghi chú của Bác Sĩ</label>
        <textarea name="doctor_note" rows="3"><?= e($old['doctor_note'] ?? '') ?></textarea>
    </div>

    <div style="margin-top: 10px;">
        <button class="btn primary" type="submit">🔄 Cập Nhật Lịch</button>
        <a class="btn" href="/appointments" style="background: #e2e8f0; color: #334155;">Hủy bỏ</a>
    </div>
</form>
<?php
$content = ob_get_clean();
$title = 'Cập Nhật Lịch Hẹn';
require __DIR__ . '/../layout.php';