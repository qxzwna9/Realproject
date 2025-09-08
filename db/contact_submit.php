<?php
require_once 'connectdb.php'; // เรียกใช้ไฟล์เชื่อมต่อกลาง

$response = ['status' => 'error', 'message' => 'Invalid request.'];
$data = json_decode(file_get_contents("php://input"));

// --- 1. ตรวจสอบว่ามีข้อมูลส่งมาหรือไม่ ---
if (isset($data->name) && isset($data->email) && isset($data->message)) {
    
    // --- 2. ตรวจสอบว่าข้อมูลไม่เป็นค่าว่าง ---
    if (!empty($data->name) && !empty($data->email) && !empty($data->message)) {
        
        // --- 3. เตรียมคำสั่ง SQL เพื่อบันทึกข้อมูล ---
        $sql = "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        // --- 4. Bind ค่าพารามิเตอร์และ Execute ---
        if ($stmt) {
            $stmt->bind_param("sss", $data->name, $data->email, $data->message);
            
            if ($stmt->execute()) {
                http_response_code(201); // Created
                $response['status'] = 'success';
                $response['message'] = 'ข้อความของคุณถูกส่งเรียบร้อยแล้ว';
            } else {
                http_response_code(500); // Internal Server Error
                $response['message'] = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
            }
            $stmt->close();
        } else {
            http_response_code(500);
            $response['message'] = 'เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL';
        }
        
    } else {
        http_response_code(400); // Bad Request
        $response['message'] = 'กรุณากรอกข้อมูลให้ครบทุกช่อง';
    }
} else {
    http_response_code(400);
    $response['message'] = 'ข้อมูลที่ส่งมาไม่ถูกต้อง';
}

$conn->close();
echo json_encode($response);
?>