<?php
header("Content-Type: application/json; charset=utf-8");

require 'connectdb.php';

try {
    $sql = "SELECT 
                p.product_id,
                p.product_name,
                p.description,
                p.price,
                p.stock,
                p.image,    
                c.category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'status' => 'success',
        'data' => $products
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'ไม่สามารถดึงข้อมูลได้: ' . $e->getMessage()
    ]);
}
?>
