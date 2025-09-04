<?php
// db/user_get.php
session_start();
require_once 'connectdb.php';

$response = ['status' => 'error', 'message' => 'Not logged in'];

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // ดึงข้อมูลล่าสุดจากฐานข้อมูล (ยกเว้นรหัสผ่าน)
    $sql = "SELECT user_id, name, email, phone, address FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        $response['status'] = 'success';
        $response['message'] = 'User data fetched successfully.';
        $response['user'] = $user;
    } else {
        http_response_code(404);
        $response['message'] = 'User not found.';
    }
    $stmt->close();
} else {
    http_response_code(401); // Unauthorized
}

$conn->close();
echo json_encode($response);
?>