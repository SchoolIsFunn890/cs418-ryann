<?php
session_start();

if (!isset($_SESSION['email'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$email = $_SESSION['email'];

$connection = new mysqli(
    "sql202.infinityfree.com",
    "if0_41571960",
    "athEOCnHsx9DWn",
    "if0_41571960_database"
);

if ($connection->connect_error) {
    die(json_encode(["error" => "DB connection failed"]));
}

$stmt = $connection->prepare(
    "SELECT last_term, last_gpa, current_term FROM students WHERE email = ?"
);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();
$connection->close();

echo json_encode($row);
?>
