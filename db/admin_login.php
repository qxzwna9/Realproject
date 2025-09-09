<?php
session_start();
require_once 'connectdb.php';

$response = ['status' => 'error', 'message' => 'Invalid credentials or not an admin.'];
$data = json_decode(file_get_contents("php://input"));

if (isset($data->email) && isset($data->password)) {
    $sql = "SELECT user_id, name, password, user_type FROM users WHERE email = ? AND user_type = 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $data->email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($data->password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['user_type'] = $user['user_type'];

            $response['status'] = 'success';
            $response['message'] = 'Login successful!';
            $response['user'] = [
                'id' => $user['user_id'],
                'name' => $user['name'],
                'user_type' => $user['user_type']
            ];
        }
    }
    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>