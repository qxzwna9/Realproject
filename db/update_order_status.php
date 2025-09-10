<?php
// db/update_order_status.php
session_start();
require_once 'connectdb.php';

$response = ['status' => 'error', 'message' => 'An error occurred.'];

// 1. ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(403);
    $response['message'] = 'Forbidden';
    echo json_encode($response);
    exit();
}

// 2. รับข้อมูลจาก JSON body
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->order_id) || !isset($data->status)) {
    http_response_code(400);
    $response['message'] = 'Required fields are missing.';
    echo json_encode($response);
    exit();
}

// 3. เตรียมและอัปเดตข้อมูลในฐานข้อมูล
$order_id = $data->order_id;
$status = $data->status;

$sql = "UPDATE orders SET status = ? WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $order_id);

if ($stmt->execute()) {
    $response['status'] = 'success';
    $response['message'] = 'Order status updated successfully!';
} else {
    http_response_code(500);
    $response['message'] = 'Failed to update order status.';
}

$stmt->close();
$conn->close();
echo json_encode($response);
?>