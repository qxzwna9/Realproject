<?php
// --- Headers for API ---
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// --- Database Connection ---
$conn = new mysqli("127.0.0.1", "root", "", "shirt_shop");
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit();
}
$conn->set_charset("utf8mb4");

$response = ['status' => 'error', 'message' => 'Invalid input.'];

// --- Get data from Vue.js ---
$data = json_decode(file_get_contents("php://input"));

// --- Validate input ---
if (!isset($data->name) || !isset($data->email) || !isset($data->password) || empty($data->name) || empty($data->email) || empty($data->password)) {
    $response['message'] = 'Please fill in all required fields.';
} else {
    // --- Check if email already exists ---
    $check_sql = "SELECT user_id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $data->email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $response['message'] = 'This email is already registered.';
    } else {
        // --- Hash the password for security ---
        $hashed_password = password_hash($data->password, PASSWORD_BCRYPT);

        // --- Insert new user ---
        $insert_sql = "INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, 'customer')";
        $stmt_insert = $conn->prepare($insert_sql);
        // 'sss' means three string parameters
        $stmt_insert->bind_param("sss", $data->name, $data->email, $hashed_password);

        if ($stmt_insert->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Registration successful!';
        } else {
            $response['message'] = 'Registration failed. Please try again.';
        }
        $stmt_insert->close();
    }
    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>