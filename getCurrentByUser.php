<?php
    header("X-Frame-Options: DENY");
header("Content-Security-Policy: frame-ancestors 'none';");
if (!isset($_POST['email'])) {
    echo json_encode(["error" => "No email provided"]);
    exit;
}

$email = $_POST['email'];

$connection = new mysqli(
    "sql202.infinityfree.com",
    "if0_41571960",
    "athEOCnHsx9DWn",
    "if0_41571960_database"
);

if ($connection->connect_error) {
    die(json_encode(["error" => "Connection failed"]));
}

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
