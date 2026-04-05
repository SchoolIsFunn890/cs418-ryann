<?php
session_start();

if (!isset($_SESSION['email'])) {
    echo json_encode(["error" => "Not logged in"]);
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

$stmt = $connection->prepare(
    "SELECT date_submitted, term, status 
     FROM terms 
     WHERE email = ?"
);

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$rows = [];

while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

echo json_encode($rows);
?>