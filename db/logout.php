<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Credentials: true");

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

echo json_encode(['status' => 'success', 'message' => 'Logged out successfully.']);
?>