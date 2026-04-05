<?php
include "2fa.html";

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

    $code = rand(100000, 999999);
    $email = $_POST['email'];

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

    $to = $email;
    $subject = $code;
    $message = "The code is $code";
    $headers = "From: noreply@gmail.com";

    mail($to, $subject, $message, $headers);
}
?>