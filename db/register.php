<?php
require_once 'connectdb.php'; // <-- แก้ไขเป็น connectdb.php

$response = ['status' => 'error', 'message' => 'Invalid input.'];
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->name) || !isset($data->email) || !isset($data->password) || empty($data->name) || empty($data->email) || empty($data->password)) {
    $response['message'] = 'กรุณากรอกข้อมูลให้ครบทุกช่อง';
} else {
    $check_sql = "SELECT user_id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $data->email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        http_response_code(409);
        $response['message'] = 'อีเมลนี้มีผู้ใช้งานแล้ว';
    } else {
        $hashed_password = password_hash($data->password, PASSWORD_BCRYPT);
        $insert_sql = "INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, 'customer')";
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bind_param("sss", $data->name, $data->email, $hashed_password);

        if ($stmt_insert->execute()) {
            http_response_code(201);
            $response['status'] = 'success';
            $response['message'] = 'สมัครสมาชิกสำเร็จ!';
        } else {
            http_response_code(500);
            $response['message'] = 'การสมัครสมาชิกล้มเหลว';
        }
        $stmt_insert->close();
    }
    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>