<?php

class AppointmentController
{
    private function repository(): AppointmentRepository {
        $config = require __DIR__ . '/../../config/database.php';
        $pdo = (new Database($config))->getConnection();
        return new AppointmentRepository($pdo);
    }

    // Hiển thị danh sách lịch hẹn đặt trước + Bộ lọc tìm kiếm + Sắp xếp ngày hẹn
    public function index(): void {
        $q = trim($_GET['q'] ?? '');
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 5;
        $sort = $_GET['sort'] ?? 'booking_date';
        $direction = $_GET['direction'] ?? 'desc';

        $repo = $this->repository();
        $total = $repo->countAll($q);
        $totalPages = (int) ceil($total / $perPage);
        if ($totalPages < 1) { $totalPages = 1; }

        if ($page < 1) { $page = 1; }
        if ($page > $totalPages) { $page = $totalPages; }
        $offset = ($page - 1) * $perPage;

        $appointments = $repo->getPaginated($q, $perPage, $offset, $sort, $direction);
        view('appointments/index', compact('appointments', 'q', 'page', 'perPage', 'total', 'totalPages', 'sort', 'direction'));
    }

    // Mở form đặt lịch hẹn khám bệnh mới
    public function create(): void {
        $errors = [];
        // Tự sinh mã lịch hẹn thông minh dạng APT-YYYYMMDD-Random số để làm gợi ý cho form
        $old = [
            'appointment_code' => 'APT-' . date('Ymd') . '-' . rand(100, 999), 
            'patient_name' => '', 
            'patient_email' => '', 
            'booking_date' => '', 
            'status' => 'pending', 
            'doctor_note' => ''
        ];
        view('appointments/create', compact('errors', 'old'));
    }

    // Lưu thông tin lịch hẹn, bắt chặt lỗi trùng mã Appointment Code
    public function store(): void {
        $data = $this->validate($_POST);
        $errors = $data['errors'];
        $old = $data['values'];

        if (!empty($errors)) { 
            view('appointments/create', compact('errors', 'old')); 
            return; 
        }

        try {
            $this->repository()->create($old);
            flash_set('success', 'Lịch đặt hẹn khám lâm sàng mới đã được kích hoạt thành công.');
            redirect('/appointments');
        } catch (DuplicateRecordException $e) {
            $errors['appointment_code'] = 'Mã lịch hẹn này đã có trên hệ thống, vui lòng đổi mã khác.';
            view('appointments/create', compact('errors', 'old'));
        } catch (Exception $e) {
            $this->logError($e);
        }
    }

    // Mở form chỉnh sửa và cập nhật trạng thái khám của bệnh nhân
    public function edit(): void {
        $id = (int) ($_GET['id'] ?? 0);
        $appointment = $this->repository()->findById($id);
        if (!$appointment) { redirect('/appointments'); }

        $errors = [];
        $old = $appointment;
        // Định dạng thời gian tương thích chính xác với thẻ input datetime-local của HTML
        $old['booking_date'] = date('Y-m-d\TH:i', strtotime($old['booking_date']));
        view('appointments/edit', compact('errors', 'old', 'id'));
    }

    // Xử lý cập nhật thay đổi thông tin hoặc trạng thái lịch hẹn
    public function update(): void {
        $id = (int) ($_POST['id'] ?? 0);
        $data = $this->validate($_POST);
        $errors = $data['errors'];
        $old = $data['values'];

        if (!empty($errors)) { 
            view('appointments/edit', compact('errors', 'old', 'id')); 
            return; 
        }

        try {
            $this->repository()->update($id, $old);
            flash_set('success', 'Cập nhật trạng thái và thông tin lịch hẹn thành công.');
            redirect('/appointments');
        } catch (DuplicateRecordException $e) {
            $errors['appointment_code'] = 'Mã lịch hẹn bị trùng với một bản ghi lịch đặt khác.';
            view('appointments/edit', compact('errors', 'old', 'id'));
        } catch (Exception $e) {
            $this->logError($e);
        }
    }

    // Tiếp nhận lệnh hủy bỏ lịch hẹn khám qua phương thức POST an toàn
    public function delete(): void {
        $id = (int) ($_POST['id'] ?? 0);
        $this->repository()->delete($id);
        flash_set('success', 'Hủy bỏ và giải phóng lịch hẹn thành công.');
        redirect('/appointments');
    }

    // Bộ quy tắc Validate lọc dữ liệu đầu vào của lịch hẹn
    private function validate(array $input): array {
        $values = [
            'appointment_code' => trim($input['appointment_code'] ?? ''),
            'patient_name'     => trim($input['patient_name'] ?? ''),
            'patient_email'    => trim($input['patient_email'] ?? ''),
            'booking_date'     => trim($input['booking_date'] ?? ''),
            'status'           => trim($input['status'] ?? 'pending'),
            'doctor_note'      => trim($input['doctor_note'] ?? ''),
        ];
        $errors = [];

        if ($values['appointment_code'] === '') { $errors['appointment_code'] = 'Mã lịch hẹn bắt buộc phải điền.'; }
        if ($values['patient_name'] === '') { $errors['patient_name'] = 'Vui lòng nhập tên bệnh nhân hẹn khám.'; }
        if ($values['patient_email'] === '') { 
            $errors['patient_email'] = 'Vui lòng cung cấp Email bệnh nhân.'; 
        } elseif (!filter_var($values['patient_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['patient_email'] = 'Địa chỉ Email liên hệ không đúng cấu trúc.';
        }
        if ($values['booking_date'] === '') { $errors['booking_date'] = 'Vui lòng ấn chọn ngày và giờ hẹn khám cụ thể.'; }

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