<?php
$connection = new mysqli(
    "sql202.infinityfree.com", 
    "if0_41571960",             
    "athEOCnHsx9DWn",           
    "if0_41571960_database"     
);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$first = $_POST["first"];
$last = $_POST["last"];
$email = $_POST["email"];
$uin = $_POST["uin"];
$password = $_POST["password"];

$hash = password_hash($password, PASSWORD_BCRYPT);
$code = rand(100000, 999999);

$stmt = $connection->prepare(
    "INSERT INTO users (first, last, email, uin, password, verifaction)
     VALUES (?, ?, ?, ?, ?, ?)"
);

$stmt->bind_param("sssisi", $first, $last, $email, $uin, $hash, $code);

if ($stmt->execute()) {

    $to = $email;
    $subject = $code;
    $message = "The code is your $code";
    $headers = "From: noreply@gmail.com";

    mail($to, $subject, $message, $headers);

    header("Location: verification.php");
    exit;

} else {
    echo "<script>
        alert('Email already exists or database error!');
        window.location.href = 'index.php';
    </script>";
}

$stmt->close();
$connection->close();
?>