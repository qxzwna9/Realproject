<?php
// --- การตั้งค่าการเชื่อมต่อฐานข้อมูล ---
$servername = "127.0.0.1"; // หรือ "localhost"
$username = "root";       // Username ของ XAMPP/WAMP โดยปกติ
$password = "";           // Password ของ XAMPP/WAMP โดยปกติ
$dbname = "shirt_shop";   // ชื่อฐานข้อมูลที่คุณสร้างไว้

// --- ตั้งค่า Headers สำหรับ API ---
// อนุญาตให้เข้าถึงได้จากทุกโดเมน (สำคัญมากสำหรับการพัฒนา Vue กับ PHP)
header("Access-Control-Allow-Origin: *");
// บอกให้ Browser รู้ว่าข้อมูลที่ส่งกลับไปเป็นประเภท JSON
header("Content-Type: application/json; charset=UTF-8");

// --- สร้างการเชื่อมต่อ ---
$conn = new mysqli($servername, $username, $password, $dbname);

// --- ตรวจสอบการเชื่อมต่อ ---
if ($conn->connect_error) {
    // หากเชื่อมต่อไม่ได้ ให้ส่ง JSON error กลับไป
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit(); // จบการทำงานทันที
}

// ตั้งค่า character set เป็น utf8mb4 เพื่อรองรับภาษาไทย
$conn->set_charset("utf8mb4");

// --- คำสั่ง SQL สำหรับดึงข้อมูล ---
// เราจะ JOIN ตาราง products กับ categories เพื่อเอาชื่อหมวดหมู่มาด้วย
$sql = "
    SELECT 
        p.product_id, 
        p.product_name, 
        p.description, 
        p.price, 
        p.stock, 
        p.image, 
        c.category_name 
    FROM 
        products p
    LEFT JOIN 
        categories c ON p.category_id = c.category_id
    ORDER BY
        p.product_id ASC
";

$result = $conn->query($sql);

$products = array(); // สร้าง Array ว่างๆ เพื่อรอเก็บข้อมูล

// --- วนลูปเพื่อดึงข้อมูลทีละแถว ---
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // เพิ่มข้อมูลแต่ละแถวเข้าไปใน Array
        $products[] = $row;
    }
}

// --- ส่งข้อมูลกลับไปเป็น JSON ---
echo json_encode($products);

// --- ปิดการเชื่อมต่อ ---
$conn->close();
?>