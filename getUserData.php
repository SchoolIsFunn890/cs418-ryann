<?php
session_start();

if (!isset($_SESSION['email'])) {
    echo "Not logged in!";
    exit;
}

$connection = new mysqli(
    "sql202.infinityfree.com",   
    "if0_41571960",              
    "athEOCnHsx9DWn",            
    "if0_41571960_database"     
);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$email = $_SESSION['email'];

$stmt = $connection->prepare("SELECT first, last, email, uin FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if ($result) {
    echo json_encode([
        $result['first'],
        $result['last'],
        $result['email'],
        $result['uin']
    ]);
} else {
    echo json_encode(["error" => "User not found"]);
}

?>