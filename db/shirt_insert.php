<?php
include 'connectdb.php';

$product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';
$price = isset($_POST['price']) ? $_POST['price'] : '';
$stock = isset($_POST['stock']) ? $_POST['stock'] : '';
$category_id = isset($_POST['category_id']) ? $_POST['category_id'] : '';

$sql = "INSERT INTO products (product_name, description, price, stock, category_id)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdii", $product_name, $description, $price, $stock, $category_id);

if ($stmt->execute()) {
    echo "เพิ่มสินค้าเรียบร้อยแล้ว";
} else {
    echo "เกิดข้อผิดพลาด: " . $conn->error;
}

$conn->close();
?>
