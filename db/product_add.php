<?php
// เพิ่ม session_start() และ require_once 'connectdb.php'
session_start();
require_once 'connectdb.php';

$response = ['status' => 'error', 'message' => 'Invalid request.'];

// --- 1. ตรวจสอบสิทธิ์ว่าเป็น Admin ---
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(403); // Forbidden
    $response['message'] = 'You are not authorized to perform this action.';
    echo json_encode($response);
    exit();
}

// --- 2. ตรวจสอบว่ามีข้อมูลส่งมาแบบ POST หรือไม่ ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --- 3. ตรวจสอบข้อมูลและไฟล์ที่ส่งมา ---
    if (isset($_POST['product_name']) && isset($_FILES['image'])) {
        
        // --- 4. จัดการการอัปโหลดรูปภาพ ---
        $target_dir = "../images/"; // โฟลเดอร์สำหรับเก็บรูป (อยู่นอกโฟลเดอร์ db)
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        // สร้างชื่อไฟล์ใหม่เพื่อป้องกันการซ้ำกัน
        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $unique_filename = 'prod_' . uniqid() . '_' . time() . '.' . $file_extension;
        $target_file = $target_dir . $unique_filename;
        
        // ย้ายไฟล์ที่อัปโหลดไปยังโฟลเดอร์ images
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            
            // --- 5. เตรียมข้อมูลเพื่อบันทึกลงฐานข้อมูล ---
            $product_name = $_POST['product_name'];
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0.00;
            $stock = $_POST['stock'] ?? 0;
            $category_id = $_POST['category_id'] ?? null;
            $image_filename = $unique_filename; // ใช้ชื่อไฟล์ใหม่ที่สร้างขึ้น

            // --- 6. บันทึกข้อมูล (ใช้ Prepared Statement เพื่อความปลอดภัย) ---
            $sql = "INSERT INTO products (product_name, description, price, stock, image, category_id) VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            // 'ssdssi' คือชนิดของข้อมูล: s=string, d=double, i=integer
            $stmt->bind_param("ssdssi", $product_name, $description, $price, $stock, $image_filename, $category_id);
            
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Product added successfully!';
            } else {
                http_response_code(500);
                $response['message'] = 'Failed to save product to database.';
            }
            $stmt->close();
        } else {
            http_response_code(500);
            $response['message'] = 'Failed to upload image.';
        }
    } else {
        http_response_code(400);
        $response['message'] = 'Product name and image are required.';
    }
}

$conn->close();
echo json_encode($response);
?>