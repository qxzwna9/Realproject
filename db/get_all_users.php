<?php
// db/get_all_users.php
require_once 'connectdb.php';
session_start();

// Basic admin check
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Forbidden']);
    exit();
}

$sql = "SELECT user_id, name, email, user_type, created_at FROM users";
$result = $conn->query($sql);

$users = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

echo json_encode(['status' => 'success', 'users' => $users]);
$conn->close();
?>