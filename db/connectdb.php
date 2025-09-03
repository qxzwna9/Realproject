<?php
$host = 'localhost';
$dbname = 'shirt_shop';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // ตั้งค่า PDO ให้แสดง error เป็น exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'เชื่อมต่อฐานข้อมูลล้มเหลว: ' . $e->getMessage()]);
    exit;
}
?>
