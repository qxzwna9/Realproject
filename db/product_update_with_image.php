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

// 2. ตรวจสอบว่ามีข้อมูล Product ID ส่งมาหรือไม่
if (!isset($_POST['product_id'])) {
    http_response_code(400);
    $response['message'] = 'Product ID is required.';
    echo json_encode($response);
    exit();
}

$product_id = $_POST['product_id'];
$image_to_update = null;

// 3. ตรวจสอบว่ามีการอัปโหลดไฟล์รูปภาพใหม่หรือไม่
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    // ดึงชื่อไฟล์รูปเก่าเพื่อลบออกจากเซิร์ฟเวอร์
    $stmt_old = $conn->prepare("SELECT image FROM products WHERE product_id = ?");
    $stmt_old->bind_param("i", $product_id);
    $stmt_old->execute();
    $result_old = $stmt_old->get_result();
    if ($row_old = $result_old->fetch_assoc()) {
        $old_image_path = "../images/" . $row_old['image'];
        if (file_exists($old_image_path) && !is_dir($old_image_path)) { // ตรวจสอบว่าเป็นไฟล์ ไม่ใช่โฟลเดอร์
            unlink($old_image_path); // ลบไฟล์เก่า
        }
    }
    $stmt_old->close();

    // จัดการอัปโหลดไฟล์ใหม่
    $target_dir = "../images/";
    $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $unique_filename = uniqid('prod_') . '_' . time() . '.' . $file_extension;
    $target_file = $target_dir . $unique_filename;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_to_update = $unique_filename;
    } else {
        http_response_code(500);
        $response['message'] = 'Failed to upload new image.';
        echo json_encode($response);
        exit();
    }
}

// 4. อัปเดตข้อมูลในฐานข้อมูล
$product_name = $_POST['product_name'];
$description = $_POST['description'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$category_id = $_POST['category_id'];


if ($image_to_update) {
    // ถ้ามีรูปใหม่ ให้อัปเดตชื่อไฟล์รูปด้วย
    $sql = "UPDATE products SET product_name=?, description=?, price=?, stock=?, category_id=?, image=? WHERE product_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdiisi", $product_name, $description, $price, $stock, $category_id, $image_to_update, $product_id);
} else {
    // ถ้าไม่มีรูปใหม่ ไม่ต้องอัปเดตคอลัมน์ image
    $sql = "UPDATE products SET product_name=?, description=?, price=?, stock=?, category_id=? WHERE product_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdiii", $product_name, $description, $price, $stock, $category_id, $product_id);
}

if ($stmt->execute()) {
    $response['status'] = 'success';
    $response['message'] = 'Product updated successfully!';
} else {
    http_response_code(500);
    $response['message'] = 'Failed to update product details.';
}

$stmt->close();
$conn->close();
echo json_encode($response);

?>