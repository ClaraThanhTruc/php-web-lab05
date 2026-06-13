<?php
try {
    // Điền thông tin kết nối trực tiếp của dự án clinic_lab05_db
    $host = '127.0.0.1';
    $dbname = 'clinic_lab05_db';
    $username = 'root';
    $password = ''; // Nếu lúc nãy có dính số 6 thì điền 'root6', không thì để trống '' nha bồ

    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "--- BAT DAU SINH DU LIEU TU DONG (SEED DATA) ---\n";

    // Sử dụng Transaction để insert 200 dòng siêu tốc
    $pdo->beginTransaction();

    $sql = "INSERT INTO appointments (appointment_code, patient_name, patient_email, booking_date, status, doctor_note) 
            VALUES (:code, :name, :email, :booking_date, :status, :note)";
    $stmt = $pdo->prepare($sql);

    $statuses = ['pending', 'confirmed', 'cancelled'];
    $notes = ['Khám định kỳ', 'Kiểm tra tổng quát', 'Khám sàng lọc', 'Theo dõi tim mạch', 'Tái khám'];

    // Vòng lặp chạy sinh ra 200 bản ghi lịch hẹn ngẫu nhiên
    for ($i = 1; $i <= 200; $i++) {
        $rand_day = rand(15, 30);
        $rand_hour = rand(8, 16);
        
        $stmt->execute([
            ':code'         => 'APT-SEED-' . str_pad($i, 4, '0', STR_PAD_LEFT),
            ':name'         => 'Benh nhan ao ' . $i,
            ':email'        => 'patient_seed' . $i . '@gmail.com',
            ':booking_date' => "2026-06-{$rand_day} 0{$rand_hour}:00:00",
            ':status'       => $statuses[array_rand($statuses)],
            ':note'         => $notes[array_rand($notes)]
        ]);
    }

    $pdo->commit();
    echo "THANH CONG! Da tu dong them 200 lich hen vao bang appointments!\n";

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) { $pdo->rollBack(); }
    echo "LOI ROI BO OI: " . $e->getMessage() . "\n";
}