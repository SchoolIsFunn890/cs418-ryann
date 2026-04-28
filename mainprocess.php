<?php
    header("X-Frame-Options: DENY");
header("Content-Security-Policy: frame-ancestors 'none';");
session_start();

$email = $_POST["email"];
$password = $_POST["password"];

$recaptcha = $_POST['g-recaptcha-response'];

$connection = new mysqli(
    "sql202.infinityfree.com",  
    "if0_41571960",             
    "athEOCnHsx9DWn",            
    "if0_41571960_database"      
);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if (!$recaptcha) {
    echo "<script>
            alert('reCAPTCHA NEEDS TO BE CLICKED!');
            window.location.href = 'main.php';
        </script>";
}

$secret = "6LfPLc8sAAAAAK9NYpewbvnPhA-ID0vXr9QgmomI";

$verify = file_get_contents(
    "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$recaptcha}"
);

$response = json_decode($verify);

$stmt = $connection->prepare("SELECT email, password FROM admin WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultForAdmin = $stmt->get_result()->fetch_assoc();

if ($resultForAdmin) {

    $hash = $resultForAdmin['password'];

    if (password_verify($password, $hash)) {
        $_SESSION['email'] = $resultForAdmin['email'];
        header("Location: admin.php");
        exit;
    } else {
        echo "<script>
            alert('Incorrect Password A');
            window.location.href = 'main.php';
        </script>";
        exit;
    }
}


$stmt = $connection->prepare("SELECT email, password, verifaction FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    echo "<script>
        alert('Incorrect email');
        window.location.href = 'main.php';
    </script>";
    exit;
}

$hash = $result['password'];
$verifaction = intval($result['verifaction']);

if (password_verify($password, $hash) && $verifaction === 1) {

    $_SESSION['email'] = $result['email'];

    echo "<script>
        window.location.href = 'user.php';
    </script>";
    exit;

} else {
    echo "<script>
        alert('Incorrect Password Or account is not verified');
        window.location.href = 'main.php';
    </script>";
    exit;
}

?>