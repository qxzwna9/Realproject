<?php
// db/check_session.php
session_start();
require_once 'connectdb.php';

$response = ['loggedin' => false, 'user' => null];

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['user_id'])) {
    $response['loggedin'] = true;
    // ส่งข้อมูลที่จำเป็นกลับไป ไม่ควรส่งรหัสผ่าน
    $response['user'] = [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['name'],
        'user_type' => $_SESSION['user_type']
    ];
}

$conn->close();
echo json_encode($response);
?>