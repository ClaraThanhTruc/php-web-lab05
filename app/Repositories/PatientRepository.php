<?php

class PatientRepository
{
    public function __construct(private PDO $db) {}

    // Đếm tổng số bệnh nhân để phục vụ thuật toán phân trang
    public function countAll(string $keyword = ''): int {
        $sql = "SELECT COUNT(*) AS total FROM patients";
        $params = [];

        if ($keyword !== '') {
            $sql .= " WHERE fullname LIKE :keyword OR patient_email LIKE :keyword OR phone LIKE :keyword";
            $params['keyword'] = '%' . $keyword . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) ($stmt->fetch()['total'] ?? 0);
    }

    // Lấy danh sách bệnh nhân có tìm kiếm, phân trang và sắp xếp an toàn
    public function getPaginated(string $keyword, int $limit, int $offset, string $sort, string $direction): array {
        // WHITELIST: Chống SQL Injection qua tham số sắp xếp cột trên URL
        $allowedSorts = ['id', 'fullname', 'patient_email', 'phone', 'created_at'];
        $allowedDirections = ['asc', 'desc'];

        if (!in_array($sort, $allowedSorts, true)) { $sort = 'created_at'; }
        if (!in_array(strtolower($direction), $allowedDirections, true)) { $direction = 'desc'; }

        $sql = "SELECT id, fullname, patient_email, phone, gender, created_at FROM patients";
        $params = [];

        if ($keyword !== '') {
            $sql .= " WHERE fullname LIKE :keyword OR patient_email LIKE :keyword OR phone LIKE :keyword";
            $params['keyword'] = '%' . $keyword . '%';
        }

        $sql .= " ORDER BY {$sort} {$direction} LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }
        // Bắt buộc bind kiểu số nguyên (PARAM_INT) cho LIMIT và OFFSET
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Tìm kiếm một bệnh nhân cụ thể bằng ID để nạp vào form sửa
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM patients WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    // Lưu bệnh nhân mới vào hệ thống
    public function create(array $data): bool {
        $sql = "INSERT INTO patients (fullname, patient_email, phone, gender, medical_history) 
                VALUES (:fullname, :patient_email, :phone, :gender, :medical_history)";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'fullname'        => $data['fullname'],
                'patient_email'   => $data['patient_email'],
                'phone'           => $data['phone'],
                'gender'          => $data['gender'],
                'medical_history' => $data['medical_history'] ?: null,
            ]);
        } catch (PDOException $e) {
            // Kiểm tra mã lỗi 1062 - Vi phạm ràng buộc UNIQUE của MySQL (Trùng Email)
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Email hồ sơ bệnh nhân này đã tồn tại.');
            }
            throw $e;
        }
    }

    // Cập nhật thông tin hồ sơ bệnh nhân
    public function update(int $id, array $data): bool {
        $sql = "UPDATE patients 
                SET fullname = :fullname, patient_email = :patient_email, phone = :phone, 
                    gender = :gender, medical_history = :medical_history, updated_at = NOW() 
                WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id'              => $id,
                'fullname'        => $data['fullname'],
                'patient_email'   => $data['patient_email'],
                'phone'           => $data['phone'],
                'gender'          => $data['gender'],
                'medical_history' => $data['medical_history'] ?: null,
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Email hồ sơ bệnh nhân này đã tồn tại.');
            }
            throw $e;
        }
    }

    // Xóa vĩnh viễn hồ sơ bệnh nhân bằng ID
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM patients WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}