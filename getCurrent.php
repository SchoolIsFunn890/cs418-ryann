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
    die(json_encode(["error" => "Connection failed"]));
}

$email = $_SESSION['email'];

$stmt = $connection->prepare(
    "SELECT c.course_id, c.level, c.course_name
     FROM student_planned_courses s
     JOIN courses c ON s.course_id = c.course_id
     WHERE s.email = ?"
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
