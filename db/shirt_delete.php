<?php
include 'connectdb.php';

$product_id = $_GET['product_id'];

$sql = "DELETE FROM products WHERE product_id=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    echo "ลบสินค้าสำเร็จ";
} else {
    echo "เกิดข้อผิดพลาด: " . $conn->error;
}

$conn->close();
?>
