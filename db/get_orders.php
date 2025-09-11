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

// Step 1: Fetch all main order details
$sql = "SELECT o.order_id, o.total, o.status, o.order_date as created_at, u.name as customer_name
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.user_id
        ORDER BY o.order_date DESC";

$result = $conn->query($sql);

if ($result) {
    $orders = [];
    if ($result->num_rows > 0) {
        // Prepare a statement for fetching items to use inside the loop
        $sql_items = "SELECT oi.quantity, oi.price, oi.size, p.product_name
                      FROM order_items oi
                      JOIN products p ON oi.product_id = p.product_id
                      WHERE oi.order_id = ?";
        $stmt_items = $conn->prepare($sql_items);

        while($row = $result->fetch_assoc()) {
            // Step 2: For each order, fetch its items
            $stmt_items->bind_param("i", $row['order_id']);
            $stmt_items->execute();
            $result_items = $stmt_items->get_result();
            
            $items = [];
            while ($item_row = $result_items->fetch_assoc()) {
                $items[] = $item_row;
            }
            
            // Add the items array to the order object
            $row['items'] = $items; 
            
            $orders[] = $row;
        }
        $stmt_items->close();
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