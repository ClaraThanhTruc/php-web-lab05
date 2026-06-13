<?php

class AppointmentRepository
{
    public function __construct(private PDO $db) {}

    // Đếm tổng số lịch hẹn để tính toán số trang phân trang
    public function countAll(string $keyword = ''): int {
        $sql = "SELECT COUNT(*) AS total FROM appointments";
        $params = [];

        if ($keyword !== '') {
            $sql .= " WHERE appointment_code LIKE :keyword OR patient_name LIKE :keyword OR patient_email LIKE :keyword";
            $params['keyword'] = '%' . $keyword . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) ($stmt->fetch()['total'] ?? 0);
    }

    // Lấy danh sách lịch hẹn khám tích hợp tìm kiếm và phân trang mượt mà
    public function getPaginated(string $keyword, int $limit, int $offset, string $sort, string $direction): array {
        // WHITELIST: Chỉ cho phép sắp xếp theo các trường dữ liệu hợp lệ của bảng lịch hẹn
        $allowedSorts = ['id', 'appointment_code', 'patient_name', 'patient_email', 'booking_date', 'status'];
        $allowedDirections = ['asc', 'desc'];

        if (!in_array($sort, $allowedSorts, true)) { $sort = 'booking_date'; }
        if (!in_array(strtolower($direction), $allowedDirections, true)) { $direction = 'desc'; }

        $sql = "SELECT id, appointment_code, patient_name, patient_email, booking_date, status FROM appointments";
        $params = [];

        if ($keyword !== '') {
            $sql .= " WHERE appointment_code LIKE :keyword OR patient_name LIKE :keyword OR patient_email LIKE :keyword";
            $params['keyword'] = '%' . $keyword . '%';
        }

        $sql .= " ORDER BY {$sort} {$direction} LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Tìm lịch hẹn theo ID để chỉnh sửa
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM appointments WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    // Đặt lịch hẹn khám bệnh mới (Bắt lỗi trùng mã lịch hẹn Appointment Code)
    public function create(array $data): bool {
        $sql = "INSERT INTO appointments (appointment_code, patient_name, patient_email, booking_date, status, doctor_note) 
                VALUES (:appointment_code, :patient_name, :patient_email, :booking_date, :status, :doctor_note)";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'appointment_code' => $data['appointment_code'],
                'patient_name'     => $data['patient_name'],
                'patient_email'    => $data['patient_email'],
                'booking_date'     => $data['booking_date'],
                'status'           => $data['status'],
                'doctor_note'      => $data['doctor_note'] ?: null,
            ]);
        } catch (PDOException $e) {
            // Hứng lỗi trùng lặp mã Unique Key của lịch hẹn
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Mã lịch hẹn (Appointment Code) này đã tồn tại trên hệ thống.');
            }
            throw $e;
        }
    }

    // Cập nhật chi tiết lịch hẹn hoặc cập nhật trạng thái/ghi chú của bác sĩ
    public function update(int $id, array $data): bool {
        $sql = "UPDATE appointments 
                SET appointment_code = :appointment_code, patient_name = :patient_name, 
                    patient_email = :patient_email, booking_date = :booking_date, 
                    status = :status, doctor_note = :doctor_note 
                WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id'               => $id,
                'appointment_code' => $data['appointment_code'],
                'patient_name'     => $data['patient_name'],
                'patient_email'    => $data['patient_email'],
                'booking_date'     => $data['booking_date'],
                'status'           => $data['status'],
                'doctor_note'      => $data['doctor_note'] ?: null,
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Mã lịch hẹn (Appointment Code) này đã tồn tại trên hệ thống.');
            }
            throw $e;
        }
    }

    // Xóa hoặc hủy bỏ lịch hẹn khám bệnh
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM appointments WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}