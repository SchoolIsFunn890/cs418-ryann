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

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["course_id"])) {
    echo "Missing course_id";
    exit;
}

$email = $_SESSION['email'];
$courseID = intval($data["course_id"]);

$stmt = $connection->prepare(
    "DELETE FROM student_planned_courses 
     WHERE email = ? AND course_id = ?"
);

$stmt->bind_param("si", $email, $courseID);
$stmt->execute();

$stmt->close();
$connection->close();

echo "Course deleted successfully!";
?>
