<?php
require_once 'connectdb.php';

$sql = "SELECT product_id, product_name, price, stock FROM products";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

echo json_encode(['status' => 'success', 'products' => $products]);
$conn->close();
?>