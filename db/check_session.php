<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Credentials: true");

$response = ['loggedin' => false];

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $response = [
        'loggedin' => true,
        'user' => [
            'name' => $_SESSION['name'],
            'user_type' => $_SESSION['user_type']
        ]
    ];
}

echo json_encode($response);
?>