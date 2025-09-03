<?php
// Start session at the very top
session_start();

// --- Headers for API ---
header("Access-Control-Allow-Origin: http://localhost:5173"); // **สำคัญ: แก้ Port 5173 ให้ตรงกับ Vue dev server ของคุณ**
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true"); // **สำคัญ: อนุญาตให้ส่งคุกกี้ (สำหรับ Session)**
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


// --- Database Connection ---
$conn = new mysqli("127.0.0.1", "root", "", "shirt_shop");
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit();
}
$conn->set_charset("utf8mb4");

$response = ['status' => 'error', 'message' => 'Invalid credentials.'];
$data = json_decode(file_get_contents("php://input"));

if (isset($data->email) && isset($data->password)) {
    $sql = "SELECT user_id, name, password, user_type FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $data->email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // --- Verify the hashed password ---
        if (password_verify($data->password, $user['password'])) {
            // --- Set session variables on successful login ---
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['user_type'] = $user['user_type'];

            $response['status'] = 'success';
            $response['message'] = 'Login successful!';
            // Send user data back to Vue
            $response['user'] = [
                'name' => $user['name'],
                'user_type' => $user['user_type']
            ];
        } else {
            $response['message'] = 'Incorrect password.';
        }
    } else {
        $response['message'] = 'Email not found.';
    }
    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>