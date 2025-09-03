<?php
include 'connectdb.php';

$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$description = $_POST['description'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$category_id = $_POST['category_id'];

$sql = "UPDATE products 
        SET product_name=?, description=?, price=?, stock=?, category_id=? 
        WHERE product_id=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdiii", $product_name, $description, $price, $stock, $category_id, $product_id);

if ($stmt->execute()) {
    echo "แก้ไขข้อมูลสินค้าเรียบร้อยแล้ว";
} else {
    echo "เกิดข้อผิดพลาด: " . $conn->error;
}

$conn->close();
?>
