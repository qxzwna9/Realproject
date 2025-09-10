<?php
// db/delete_order.php
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

if (!isset($data->order_id)) {
    http_response_code(400);
    $response['message'] = 'Order ID is required.';
    echo json_encode($response);
    exit();
}

$order_id = $data->order_id;

$conn->begin_transaction();

try {
    // ลบข้อมูลจากตาราง order_items ก่อน
    $sql_items = "DELETE FROM order_items WHERE order_id = ?";
    $stmt_items = $conn->prepare($sql_items);
    $stmt_items->bind_param("i", $order_id);
    $stmt_items->execute();
    $stmt_items->close();

    // ลบข้อมูลจากตาราง orders
    $sql_order = "DELETE FROM orders WHERE order_id = ?";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("i", $order_id);
    $stmt_order->execute();

    if ($stmt_order->affected_rows > 0) {
        $conn->commit();
        $response['status'] = 'success';
        $response['message'] = 'Order deleted successfully.';
    } else {
        throw new Exception('Order not found.');
    }
    $stmt_order->close();

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    $response['message'] = 'Failed to delete order: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response);
?>