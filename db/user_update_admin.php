<?php
session_start();
require_once 'connectdb.php';

$response = ['status' => 'error', 'message' => 'An error occurred.'];

// 1. ตรวจสอบสิทธิ์ว่าเป็น Admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(403);
    $response['message'] = 'Forbidden';
    echo json_encode($response);
    exit();
}

// 2. รับข้อมูลจาก JSON body ที่ส่งมาจาก Vue
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->user_id) || !isset($data->name) || !isset($data->user_type)) {
    http_response_code(400);
    $response['message'] = 'Required fields are missing.';
    echo json_encode($response);
    exit();
}

// 3. เตรียมข้อมูลและอัปเดตฐานข้อมูล
$user_id = $data->user_id;
$name = $data->name;
$user_type = $data->user_type;

// (หมายเหตุ: เราไม่อนุญาตให้แก้ไขอีเมล)
$sql = "UPDATE users SET name = ?, user_type = ? WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $name, $user_type, $user_id);

if ($stmt->execute()) {
    $response['status'] = 'success';
    $response['message'] = 'User updated successfully!';
} else {
    http_response_code(500);
    $response['message'] = 'Failed to update user.';
}

$stmt->close();
$conn->close();
echo json_encode($response);
?>