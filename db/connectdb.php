<?php
// db/connectdb.php

// --- การตั้งค่าการเชื่อมต่อฐานข้อมูล ---
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "shirt_shop";

// --- ส่วนแก้ไข CORS ---
// **สำคัญ:** แก้ไข http://localhost:3000 ให้ตรงกับ Port ของ Nuxt dev server ของคุณ (ปกติคือ 3000)
header("Access-Control-Allow-Origin: http://localhost:3000"); 
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// **สำคัญ:** ส่วนนี้สำหรับจัดการ Preflight Request (OPTIONS) ที่เบราว์เซอร์ส่งมาถามก่อน
// เมื่อเบราว์เซอร์ส่ง OPTIONS request มา ให้เราตอบกลับไปว่า "อนุญาต" แล้วจบการทำงานเลย
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit(0);
}
// --- จบส่วนแก้ไข CORS ---


// --- สร้างการเชื่อมต่อ ---
$conn = new mysqli($servername, $username, $password, $dbname);

// --- ตรวจสอบการเชื่อมต่อ ---
if ($conn->connect_error) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// ตั้งค่า character set
$conn->set_charset("utf8mb4");
?>