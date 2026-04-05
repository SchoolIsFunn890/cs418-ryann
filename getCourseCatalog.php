<?php
session_start();

$connection = new mysqli(
    "sql202.infinityfree.com",
    "if0_41571960",
    "athEOCnHsx9DWn",
    "if0_41571960_database"
);

if ($connection->connect_error) {
    die(json_encode(["error" => "Connection failed"]));
}

$result = $connection->query("SELECT course_id, level, course_name FROM courses");

$courses = [];

while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

echo json_encode($courses);
?>
