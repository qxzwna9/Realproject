<?php
require_once 'connectdb.php';
session_start();

$response = ['status' => 'error', 'message' => 'An error occurred.'];
$data = json_decode(file_get_contents("php://input"));

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(403);
    $response['message'] = 'Forbidden';
    echo json_encode($response);
    exit();
}

// ตรวจสอบว่ามีข้อมูลที่จำเป็นส่งมาหรือไม่
if (isset($data->product_id) && isset($data->product_name) && isset($data->price) && isset($data->stock)) {

    $sql = "UPDATE products
            SET product_name = ?, description = ?, price = ?, stock = ?
            WHERE product_id = ?";

    $stmt = $conn->prepare($sql);
    // s = string, d = double, i = integer
    $stmt->bind_param(
        "ssdii",
        $data->product_name,
        $data->description,
        $data->price,
        $data->stock,
        $data->product_id
    );

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Product updated successfully!';
    } else {
        http_response_code(500);
        $response['message'] = 'Failed to update product.';
    }
    $stmt->close();
} else {
    http_response_code(400); // Bad Request
    $response['message'] = 'Incomplete product data.';
}

$conn->close();
echo json_encode($response);
?>