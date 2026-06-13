<?php
session_start();

// Nạp toàn bộ Core Files nền tảng
require __DIR__ . '/../app/Core/helpers.php';
require __DIR__ . '/../app/Core/Router.php';
require __DIR__ . '/../app/Core/Database.php';
require __DIR__ . '/../app/Core/DuplicateRecordException.php';

// Nạp toàn bộ Repositories xử lý dữ liệu SQL
require __DIR__ . '/../app/Repositories/PatientRepository.php';
require __DIR__ . '/../app/Repositories/AppointmentRepository.php';

// Nạp toàn bộ các bộ điều phối Controllers
require __DIR__ . '/../app/Controllers/HomeController.php';
require __DIR__ . '/../app/Controllers/HealthController.php';
require __DIR__ . '/../app/Controllers/PatientController.php';
require __DIR__ . '/../app/Controllers/AppointmentController.php';

$router = new Router();

// Khai báo định tuyến trang chủ và trang theo dõi trạng thái hệ thống
$router->get('/', [HomeController::class, 'index']);
$router->get('/health', [HealthController::class, 'index']);

// Khai báo định tuyến module A (Quản Lý Bệnh Nhân)
$router->get('/patients', [PatientController::class, 'index']);
$router->get('/patients/create', [PatientController::class, 'create']);
$router->post('/patients/store', [PatientController::class, 'store']);
$router->get('/patients/edit', [PatientController::class, 'edit']);
$router->post('/patients/update', [PatientController::class, 'update']);
$router->post('/patients/delete', [PatientController::class, 'delete']);

// Khai báo định tuyến module B (Quản Lý Lịch Hẹn Khám)
$router->get('/appointments', [AppointmentController::class, 'index']);
$router->get('/appointments/create', [AppointmentController::class, 'create']);
$router->post('/appointments/store', [AppointmentController::class, 'store']);
$router->get('/appointments/edit', [AppointmentController::class, 'edit']);
$router->post('/appointments/update', [AppointmentController::class, 'update']);
$router->post('/appointments/delete', [AppointmentController::class, 'delete']);

// Thực thi bóc tách URL và kích hoạt Controller tương ứng
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);