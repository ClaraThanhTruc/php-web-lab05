<?php

class PatientController
{
    private function repository(): PatientRepository {
        $config = require __DIR__ . '/../../config/database.php';
        $pdo = (new Database($config))->getConnection();
        return new PatientRepository($pdo);
    }

    // Hiển thị danh sách bệnh nhân + Tìm kiếm + Phân trang + Sort an toàn
    public function index(): void {
        $q = trim($_GET['q'] ?? '');
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 5; // Chia nhỏ 5 bản ghi/trang để dễ test phân trang mượt mà
        $sort = $_GET['sort'] ?? 'created_at';
        $direction = $_GET['direction'] ?? 'desc';

        $repo = $this->repository();
        $total = $repo->countAll($q);
        $totalPages = (int) ceil($total / $perPage);
        if ($totalPages < 1) { $totalPages = 1; }

        // BẢO VỆ ĐƯỜNG DẪN: Xử lý triệt để nếu user nhập số trang âm hoặc quá lớn
        if ($page < 1) { $page = 1; }
        if ($page > $totalPages) { $page = $totalPages; }
        $offset = ($page - 1) * $perPage;

        $patients = $repo->getPaginated($q, $perPage, $offset, $sort, $direction);
        view('patients/index', compact('patients', 'q', 'page', 'perPage', 'total', 'totalPages', 'sort', 'direction'));
    }

    // Hiển thị form tạo mới bệnh nhân
    public function create(): void {
        $errors = [];
        $old = ['fullname' => '', 'patient_email' => '', 'phone' => '', 'gender' => 'male', 'medical_history' => ''];
        view('patients/create', compact('errors', 'old'));
    }

    // Tiếp nhận dữ liệu, validate và thực hiện lưu trữ hồ sơ bệnh nhân
    public function store(): void {
        $data = $this->validate($_POST);
        $errors = $data['errors'];
        $old = $data['values'];

        if (!empty($errors)) {
            view('patients/create', compact('errors', 'old'));
            return;
        }

        try {
            $this->repository()->create($old);
            flash_set('success', 'Hồ sơ bệnh nhân mới đã được thiết lập thành công.');
            redirect('/patients'); // PRG Pattern: Điều hướng trang lập tức để tránh F12 tạo trùng
        } catch (DuplicateRecordException $e) {
            // Hứng lỗi duy nhất từ Unique Key Database gửi qua form
            $errors['patient_email'] = 'Địa chỉ Email này đã tồn tại trong hệ thống phòng khám.';
            view('patients/create', compact('errors', 'old'));
        } catch (Exception $e) {
            $this->logError($e);
        }
    }

    // Mở form chỉnh sửa hồ sơ dựa theo ID bệnh nhân
    public function edit(): void {
        $id = (int) ($_GET['id'] ?? 0);
        $patient = $this->repository()->findById($id);
        if (!$patient) { redirect('/patients'); }

        $errors = [];
        $old = $patient;
        view('patients/edit', compact('errors', 'old', 'id'));
    }

    // Xử lý cập nhật thay đổi dữ liệu bệnh nhân
    public function update(): void {
        $id = (int) ($_POST['id'] ?? 0);
        $data = $this->validate($_POST);
        $errors = $data['errors'];
        $old = $data['values'];

        if (!empty($errors)) {
            view('patients/edit', compact('errors', 'old', 'id'));
            return;
        }

        try {
            $this->repository()->update($id, $old);
            flash_set('success', 'Cập nhật thông tin hồ sơ bệnh nhân thành công.');
            redirect('/patients');
        } catch (DuplicateRecordException $e) {
            $errors['patient_email'] = 'Địa chỉ Email này đang được sử dụng bởi một bệnh nhân khác.';
            view('patients/edit', compact('errors', 'old', 'id'));
        } catch (Exception $e) {
            $this->logError($e);
        }
    }

    // Tiếp nhận yêu cầu xóa bệnh nhân bằng phương thức POST an toàn
    public function delete(): void {
        $id = (int) ($_POST['id'] ?? 0);
        $this->repository()->delete($id);
        flash_set('success', 'Xóa thành công hồ sơ bệnh nhân ra khỏi hệ thống.');
        redirect('/patients');
    }

    // Bộ lọc kiểm định dữ liệu đầu vào (Validation) mẫu mực
    private function validate(array $input): array {
        $values = [
            'fullname'        => trim($input['fullname'] ?? ''),
            'patient_email'   => trim($input['patient_email'] ?? ''),
            'phone'           => trim($input['phone'] ?? ''),
            'gender'          => trim($input['gender'] ?? 'male'),
            'medical_history' => trim($input['medical_history'] ?? ''),
        ];
        $errors = [];

        if ($values['fullname'] === '') { $errors['fullname'] = 'Vui lòng điền họ tên bệnh nhân.'; }
        if ($values['patient_email'] === '') { 
            $errors['patient_email'] = 'Vui lòng cung cấp địa chỉ Email bệnh nhân.'; 
        } elseif (!filter_var($values['patient_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['patient_email'] = 'Cấu trúc địa chỉ định dạng Email không chính xác.';
        }
        if ($values['phone'] === '') { $errors['phone'] = 'Vui lòng điền số điện thoại liên lạc.'; }

        return ['values' => $values, 'errors' => $errors];
    }

    // Ghi nhận log lỗi hệ thống ẩn danh (Production Mindset)
    private function logError(Exception $e): void {
        error_log($e->getMessage() . "\n", 3, __DIR__ . '/../../storage/logs/app.log');
        $appConfig = require __DIR__ . '/../../config/app.php';
        http_response_code(500);
        if ($appConfig['environment'] === 'production') {
            view('errors/500');
        } else {
            echo "<h1>Database Technical Error:</h1><p>" . htmlspecialchars($e->getMessage()) . "</p>";
        }
        exit;
    }
}