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


$getTerm = $connection->prepare(
    "SELECT current_term FROM students WHERE email = ?"
);
$getTerm->bind_param("s", $email);
$getTerm->execute();
$result = $getTerm->get_result();
$row = $result->fetch_assoc();
$getTerm->close();

if (!$row) {
    echo "Could not find current term for this user.";
    exit;
}

$currentTerm = $row["current_term"];

$check = $connection->prepare(
    "SELECT status FROM terms WHERE email = ? AND term = ?"
);
$check->bind_param("ss", $email, $currentTerm);
$check->execute();
$checkResult = $check->get_result();
$existing = $checkResult->fetch_assoc();
$check->close();

if ($existing && $existing["status"] === "Approved" || $existing["status"] === "Rejected") {
    echo "TERM IS APPROVED/REJECTED CAN NOT EDIT";
    exit;
}

$date = date("Y-m-d");
$status = "Pending";

$insertTerm = $connection->prepare(
    "INSERT INTO terms (email, date_submitted, term, status)
     VALUES (?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE
        date_submitted = VALUES(date_submitted),
        status = VALUES(status)"
);

$insertTerm->bind_param("ssss", $email, $date, $currentTerm, $status);
$insertTerm->execute();
$insertTerm->close();


$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["courses"]) || !is_array($data["courses"])) {
    echo "Invalid data format";
    exit;
}

$courses = $data["courses"];

$delete = $connection->prepare(
    "DELETE FROM student_planned_courses WHERE email = ?"
);
$delete->bind_param("s", $email);
$delete->execute();
$delete->close();

$insert = $connection->prepare(
    "INSERT INTO student_planned_courses (email, course_id)
     VALUES (?, ?)"
);

foreach ($courses as $courseID) {
    $insert->bind_param("si", $email, $courseID);
    $insert->execute();
}

$insert->close();
$connection->close();

echo "Current term courses saved successfully!";
?>
