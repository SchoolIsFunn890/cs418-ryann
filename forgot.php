<?php
include "forgot.html";

$connection = new mysqli(
    "sql202.infinityfree.com",   
    "if0_41571960",              
    "athEOCnHsx9DWn",            
    "if0_41571960_database"      
);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $connection->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if (!$result) {
        echo "<script>
            alert('Incorrect email');
            window.location.href = 'forgot.php';
        </script>";
        exit;
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $connection->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hash, $email);
    $stmt->execute();

    echo "<script>
        alert('Password updated successfully!');
        window.location.href='main.php';
    </script>";
    exit;
}
?>