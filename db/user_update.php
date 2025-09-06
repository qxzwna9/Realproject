<?php
// db/user_update.php
session_start();
require_once 'connectdb.php';

$response = ['status' => 'error', 'message' => 'An error occurred.'];
$data = json_decode(file_get_contents("php://input"));

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    $response['message'] = 'You must be logged in to update your profile.';
    echo json_encode($response);
    exit();
}

if (isset($data->name) && isset($data->email)) {
    $user_id = $_SESSION['user_id'];
    $name = $data->name;
    $email = $data->email;
    $phone = $data->phone ?? null; // ใช้ Null Coalescing Operator
    $address = $data->address ?? null;

    // Optional: ตรวจสอบว่าอีเมลใหม่ซ้ำกับของคนอื่นหรือไม่
    $check_email_sql = "SELECT user_id FROM users WHERE email = ? AND user_id != ?";
    $stmt_check = $conn->prepare($check_email_sql);
    $stmt_check->bind_param("si", $email, $user_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        http_response_code(409); // Conflict
        $response['message'] = 'This email is already in use by another account.';
    } else {
        $sql = "UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $email, $phone, $address, $user_id);

        if ($stmt->execute()) {
            // อัปเดตข้อมูลใน Session ด้วย
            $_SESSION['name'] = $name;
            
            $response['status'] = 'success';
            $response['message'] = 'Profile updated successfully!';
        } else {
            http_response_code(500);
            $response['message'] = 'Failed to update profile.';
        }
        $stmt->close();
    }
    $stmt_check->close();
} else {
    http_response_code(400); // Bad Request
    $response['message'] = 'Name and email are required.';
}

$conn->close();
echo json_encode($response);
?>