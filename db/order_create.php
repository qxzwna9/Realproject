<?php
// db/order_create.php
session_start();
require_once 'connectdb.php';

$response = ['status' => 'error', 'message' => 'An error occurred.'];
$data = json_decode(file_get_contents("php://input"));

// 1. ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    $response['message'] = 'You must be logged in to place an order.';
    echo json_encode($response);
    exit();
}

// 2. ตรวจสอบว่ามีข้อมูลที่จำเป็นครบถ้วนหรือไม่
if (!isset($data->shipping_details) || !isset($data->cart) || empty($data->cart)) {
    http_response_code(400); // Bad Request
    $response['message'] = 'Shipping details and cart items are required.';
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user_id'];
$shipping = $data->shipping_details;
$cart = $data->cart;

// เริ่ม Transaction เพื่อให้แน่ใจว่าทุกอย่างสำเร็จทั้งหมดหรือล้มเหลวทั้งหมด
$conn->begin_transaction();

try {
    // 3. คำนวณราคารวมจากฝั่ง Server เพื่อความปลอดภัย
    $total_price = 0;
    $product_ids = array_map(function($item) { return $item->product_id; }, $cart);
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));

    $sql_prices = "SELECT product_id, price, stock FROM products WHERE product_id IN ($placeholders)";
    $stmt_prices = $conn->prepare($sql_prices);
    $types = str_repeat('i', count($product_ids));
    $stmt_prices->bind_param($types, ...$product_ids);
    $stmt_prices->execute();
    $result_prices = $stmt_prices->get_result();
    $server_products = [];
    while ($row = $result_prices->fetch_assoc()) {
        $server_products[$row['product_id']] = $row;
    }
    $stmt_prices->close();

    foreach ($cart as $item) {
        if (!isset($server_products[$item->product_id])) {
            throw new Exception("Product with ID {$item->product_id} not found.");
        }
        if ($server_products[$item->product_id]['stock'] < $item->quantity) {
             throw new Exception("Not enough stock for product ID {$item->product_id}.");
        }
        $total_price += $server_products[$item->product_id]['price'] * $item->quantity;
    }

    // 4. บันทึกข้อมูลลงตาราง `orders`
    $sql_order = "INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'pending')";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("id", $user_id, $total_price);
    $stmt_order->execute();
    $order_id = $stmt_order->insert_id;
    $stmt_order->close();
    
    // 5. บันทึกรายการสินค้าลง `order_items` และตัดสต็อก (เพิ่มคอลัมน์ size)
    $sql_item = "INSERT INTO order_items (order_id, product_id, size, quantity, price) VALUES (?, ?, ?, ?, ?)";
    $stmt_item = $conn->prepare($sql_item);

    $sql_stock = "UPDATE products SET stock = stock - ? WHERE product_id = ?";
    $stmt_stock = $conn->prepare($sql_stock);

    foreach ($cart as $item) {
        $product_price = $server_products[$item->product_id]['price'];
        // เพิ่มตัวแปร s (string) สำหรับ size และส่งค่า $item->size เข้าไป
        $stmt_item->bind_param("iisid", $order_id, $item->product_id, $item->size, $item->quantity, $product_price);
        $stmt_item->execute();
        
        $stmt_stock->bind_param("ii", $item->quantity, $item->product_id);
        $stmt_stock->execute();
    }
    $stmt_item->close();
    $stmt_stock->close();
    
    // 6. ถ้าทุกอย่างสำเร็จ ให้ Commit transaction
    $conn->commit();
    
    http_response_code(201);
    $response['status'] = 'success';
    $response['message'] = 'Order created successfully!';
    $response['order_id'] = $order_id;

} catch (Exception $e) {
    // 7. หากมีข้อผิดพลาด ให้ Rollback transaction
    $conn->rollback();
    http_response_code(500);
    $response['message'] = 'Failed to create order: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response);
?>