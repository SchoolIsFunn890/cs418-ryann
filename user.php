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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $password = $_POST['password'];
    $first = $_POST['first'];
    $last = $_POST['last'];
    $uin = $_POST['uin'];

    // Build dynamic update query
    $fields = [];
    $params = [];
    $types = "";

    if ($password !== "") {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $fields[] = "password = ?";
        $params[] = $hash;
        $types .= "s";
    }

    if ($first !== "") {
        $fields[] = "first = ?";
        $params[] = $first;
        $types .= "s";
    }

    if ($last !== "") {
        $fields[] = "last = ?";
        $params[] = $last;
        $types .= "s";
    }

    if ($uin !== "") {
        $fields[] = "uin = ?";
        $params[] = $uin;
        $types .= "i";
    }

    // Only update if something was provided
    if (!empty($fields)) {
        $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE email = ?";
        $stmt = $connection->prepare($sql);

        // Add email to parameters
        $params[] = $email;
        $types .= "s";

        // Bind parameters dynamically
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
    }

    header("Location: user.php");
    exit;
}

include "user.html";
?>