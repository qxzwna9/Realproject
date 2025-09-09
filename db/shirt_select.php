<?php
// เรียกใช้ไฟล์เชื่อมต่อกลางที่มีการตั้งค่า CORS และการเชื่อมต่อ DB ที่ถูกต้อง
require_once 'connectdb.php';

// --- คำสั่ง SQL สำหรับดึงข้อมูล ---
// ***** แก้ไข SQL โดยเพิ่ม p.category_id เข้ามาใน SELECT *****
$sql = "
    SELECT
        p.product_id,
        p.product_name,
        p.description,
        p.price,
        p.stock,
        p.image,
        p.category_id,
        c.category_name
    FROM
        products p
    LEFT JOIN
        categories c ON p.category_id = c.category_id
    ORDER BY
        p.product_id ASC
";

$result = $conn->query($sql);

$products = array();

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

echo json_encode($products);
$conn->close();
?>