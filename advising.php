<?php
    header("X-Frame-Options: DENY");
header("Content-Security-Policy: frame-ancestors 'none';");
include "advising.html";


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

$adminCheck = $connection->prepare("SELECT email FROM admin WHERE email = ?");
$adminCheck->bind_param("s", $email);
$adminCheck->execute();
$result = $adminCheck->get_result();

if ($row = $result->fetch_assoc()) {
    if ($row['email'] === $email) {
        echo "admin";
    }
}else{


$lastterm = $_POST["lastterm"];
$lastgpa  = $_POST["lastgpa"];
$curterm  = $_POST["curterm"];

if (empty($lastterm) || empty($lastgpa) || empty($curterm)) {
    exit;
}

$stmt = $connection->prepare(
    "INSERT INTO students (email, last_term, last_gpa, current_term)
     VALUES (?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE
        last_term = VALUES(last_term),
        last_gpa = VALUES(last_gpa),
        current_term = VALUES(current_term)"
);

$stmt->bind_param("ssds", $email, $lastterm, $lastgpa, $curterm);
$stmt->execute();

$stmt->close();
$connection->close();

echo "Header information saved successfully!";
}
?>
