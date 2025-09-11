<?php
// db/cancel_order.php
session_start();
require_once 'connectdb.php';

$response = ['status' => 'error', 'message' => 'An error occurred.'];
$data = json_decode(file_get_contents("php://input"));

// 1. ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    http_response_code(401);
    $response['message'] = 'You must be logged in to cancel an order.';
    echo json_encode($response);
    exit();
}

// 2. ตรวจสอบว่ามี order_id ส่งมาหรือไม่
if (!isset($data->order_id)) {
    http_response_code(400);
    $response['message'] = 'Order ID is required.';
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $data->order_id;

// เริ่ม Transaction
$conn->begin_transaction();

try {
    // 3. ตรวจสอบว่าออเดอร์เป็นของผู้ใช้จริงและสถานะเป็น 'pending'
    $sql_check = "SELECT status FROM orders WHERE order_id = ? AND user_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $order_id, $user_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $order_to_cancel = $result_check->fetch_assoc();

    if (!$order_to_cancel) {
        throw new Exception('Order not found or you do not have permission to cancel it.');
    }

    if ($order_to_cancel['status'] !== 'pending') {
        throw new Exception('Only orders with "pending" status can be cancelled.');
    }
    $stmt_check->close();

    // 4. อัปเดตสถานะออเดอร์เป็น 'cancelled'
    $sql_update = "UPDATE orders SET status = 'cancelled' WHERE order_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("i", $order_id);
    
    if ($stmt_update->execute()) {
        // (ส่วนนี้สำคัญ) คืนสินค้ากลับเข้าสต็อก
        $sql_items = "SELECT product_id, quantity FROM order_items WHERE order_id = ?";
        $stmt_items = $conn->prepare($sql_items);
        $stmt_items->bind_param("i", $order_id);
        $stmt_items->execute();
        $result_items = $stmt_items->get_result();
        
        $sql_stock = "UPDATE products SET stock = stock + ? WHERE product_id = ?";
        $stmt_stock = $conn->prepare($sql_stock);

        while ($item = $result_items->fetch_assoc()) {
            $stmt_stock->bind_param("ii", $item['quantity'], $item['product_id']);
            $stmt_stock->execute();
        }
        
        $stmt_items->close();
        $stmt_stock->close();
        
        // ยืนยันการเปลี่ยนแปลงทั้งหมด
        $conn->commit();
        $response['status'] = 'success';
        $response['message'] = 'Order has been cancelled successfully.';

    } else {
        throw new Exception('Failed to update order status.');
    }

    $stmt_update->close();

} catch (Exception $e) {
    // หากมีข้อผิดพลาด ให้ย้อนกลับ
    $conn->rollback();
    http_response_code(500);
    $response['message'] = $e->getMessage();
}

$conn->close();
echo json_encode($response);
?>