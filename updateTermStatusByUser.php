<?php
    header("X-Frame-Options: DENY");
header("Content-Security-Policy: frame-ancestors 'none';");
$connection = new mysqli(
    "sql202.infinityfree.com",
    "if0_41571960",
    "athEOCnHsx9DWn",
    "if0_41571960_database"
);

if ($connection->connect_error) {
    die(json_encode(["error" => "Connection failed"]));
}

$data = json_decode(file_get_contents("php://input"), true);

$email = $data["email"];
$term = $data["term"];
$status = $data["status"];
$message = $data["message"];

$stmt = $connection->prepare(
    "UPDATE terms 
     SET status = ?, message = ?
     WHERE email = ? AND term = ?"
);

$stmt->bind_param("ssss", $status, $message, $email, $term);
$stmt->execute();

echo json_encode(["success" => true]);
?>
