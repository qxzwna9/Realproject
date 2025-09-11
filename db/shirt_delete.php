<?php
session_start();
require_once 'connectdb.php';

$response = ['status' => 'error', 'message' => 'An error occurred.'];

// 1. ตรวจสอบสิทธิ์ว่าเป็น Admin หรือไม่
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(403);
    $response['message'] = 'Forbidden';
    echo json_encode($response);
    exit();
}

// 2. รับข้อมูล product_id จาก JSON body (POST request)
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->product_id)) {
    http_response_code(400);
    $response['message'] = 'Product ID is required.';
    echo json_encode($response);
    exit();
}

$product_id = $data->product_id;

// เริ่ม Transaction เพื่อให้แน่ใจว่าทุกอย่างทำงานสำเร็จทั้งหมด
$conn->begin_transaction();

try {
    // 3. (สำคัญ) ลบข้อมูลที่เกี่ยวข้องทั้งหมดก่อน
    // เพื่อปลดล็อคข้อจำกัดของฐานข้อมูล (Foreign Key Constraint)

    // ลบจากตาราง order_items
    $sql_order_items = "DELETE FROM order_items WHERE product_id = ?";
    $stmt_order_items = $conn->prepare($sql_order_items);
    $stmt_order_items->bind_param("i", $product_id);
    $stmt_order_items->execute();
    $stmt_order_items->close();

    // ---- เพิ่มส่วนนี้ ----
    // ลบจากตาราง product_sizes
    $sql_sizes = "DELETE FROM product_sizes WHERE product_id = ?";
    $stmt_sizes = $conn->prepare($sql_sizes);
    $stmt_sizes->bind_param("i", $product_id);
    $stmt_sizes->execute();
    $stmt_sizes->close();

    // ---- เพิ่มส่วนนี้ ----
    // ลบจากตาราง reviews
    $sql_reviews = "DELETE FROM reviews WHERE product_id = ?";
    $stmt_reviews = $conn->prepare($sql_reviews);
    $stmt_reviews->bind_param("i", $product_id);
    $stmt_reviews->execute();
    $stmt_reviews->close();

    // 4. ลบสินค้าออกจากตาราง `products`
    $sql_products = "DELETE FROM products WHERE product_id = ?";
    $stmt_products = $conn->prepare($sql_products);
    $stmt_products->bind_param("i", $product_id);

    if ($stmt_products->execute()) {
        // ตรวจสอบว่ามีแถวที่ถูกลบจริงหรือไม่
        if ($stmt_products->affected_rows > 0) {
            $conn->commit(); // ยืนยันการเปลี่ยนแปลงทั้งหมด
            $response['status'] = 'success';
            $response['message'] = 'Product deleted successfully.';
        } else {
            throw new Exception('Product not found or already deleted.');
        }
    } else {
        throw new Exception($conn->error);
    }
    $stmt_products->close();

} catch (Exception $e) {
    $conn->rollback(); // หากมีข้อผิดพลาด ให้ย้อนกลับการเปลี่ยนแปลงทั้งหมด
    http_response_code(500);
    $response['message'] = 'Failed to delete product: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response);
?>