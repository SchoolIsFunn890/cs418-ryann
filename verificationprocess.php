<?php
$code = $_POST["code"];
$email = $_POST["email"];

// MySQL connection
$connection = new mysqli(
    "sql202.infinityfree.com",  
    "if0_41571960",              
    "athEOCnHsx9DWn",            
    "if0_41571960_database"      
);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Get stored verification code
$stmt = $connection->prepare("SELECT verifaction FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    echo "<script>
        alert('Incorrect email');
        window.location.href = 'verification.php';
    </script>";
    exit;
}

$storedCode = $result['verifaction'];

if ($storedCode == $code && $storedCode != 1) {

    $stmt = $connection->prepare("UPDATE users SET verifaction = 1 WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    header("Location: main.php");
    exit;

} else if ($storedCode == 1) {

    echo "<script>
        alert('Already verified');
        window.location.href = 'main.php';
    </script>";

} else {

    echo "<script>
        alert('Incorrect verification code');
        window.location.href = 'verification.php';
    </script>";
}

?>