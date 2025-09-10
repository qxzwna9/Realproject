<?php
// db/get_orders.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'connectdb.php';
session_start();

header("Content-Type: application/json; charset=UTF-8");

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(403);
    $response['message'] = 'Forbidden';
    echo json_encode($response);
    exit();
}

// แก้ไข SQL ให้ใช้ order_date และตั้งชื่อเล่น (alias) เป็น created_at
$sql = "SELECT o.order_id, o.total, o.status, o.order_date as created_at, u.name as customer_name
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.user_id
        ORDER BY o.order_date DESC";

$result = $conn->query($sql);

if ($result) {
    $orders = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    }
    $response['status'] = 'success';
    $response['orders'] = $orders;
} else {
    http_response_code(500);
    $response['message'] = 'Database query failed: ' . $conn->error;
}

$conn->close();
echo json_encode($response);
?>