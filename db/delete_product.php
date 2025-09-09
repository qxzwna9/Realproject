<?php
require_once 'connectdb.php';

$data = json_decode(file_get_contents("php://input"));

if(isset($data->product_id)) {
    $sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $data->product_id);
    if($stmt->execute()){
        echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete product.']);
    }
    $stmt->close();
}

$conn->close();
?>