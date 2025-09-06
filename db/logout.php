<?php
// db/logout.php
require_once 'connectdb.php'; // เรียกใช้ไฟล์เชื่อมต่อกลางเพื่อจัดการ Headers

session_start();

// ล้างค่าทั้งหมดใน session
$_SESSION = array();

// ทำลาย session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// ทำลาย session อย่างสมบูรณ์
session_destroy();

// ส่งการตอบกลับว่าสำเร็จเป็น JSON
echo json_encode(['status' => 'success', 'message' => 'Logged out successfully.']);
?>