<?php
require_once 'connectdb.php';

$data = json_decode(file_get_contents("php://input"));

if(isset($data->user_id)) {
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $data->user_id);
    if($stmt->execute()){
        echo json_encode(['status' => 'success', 'message' => 'User deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete user.']);
    }
    $stmt->close();
}

$conn->close();
?>