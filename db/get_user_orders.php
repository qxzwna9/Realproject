<?php
// db/get_user_orders.php
session_start();
require_once 'connectdb.php';

header("Content-Type: application/json; charset=UTF-8");

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    http_response_code(401);
    $response['message'] = 'You must be logged in to view your orders.';
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT order_id, total, status, order_date as created_at
        FROM orders
        WHERE user_id = ?
        ORDER BY order_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

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

$stmt->close();
$conn->close();
echo json_encode($response);
?>