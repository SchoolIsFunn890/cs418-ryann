<?php
session_start();

if (!isset($_SESSION['email'])) {
    echo "Not logged in!";
    exit;
}

$db = new SQLite3('database.db');

$email = $_SESSION['email'];


//Needs more
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $first = $_POST['first'];
    $last = $_POST['last'];
    $uin= $_POST['uin'];
    $stmt = $db->prepare("UPDATE users SET 
    password = COALESCE(:password, password),
    first = COALESCE(:first, first),    
    last = COALESCE(:last, last), 
    uin = COALESCE(:uin, uin)
    WHERE email = :email");

    if($password !== ""){
        $hash = password_hash($password, PASSWORD_BCRYPT);
    }

    $stmt->bindValue(':password', $hash, SQLITE3_TEXT);
    $stmt->bindValue(':first',    $first    !== "" ? $first    : null, SQLITE3_TEXT);
    $stmt->bindValue(':last',     $last     !== "" ? $last     : null, SQLITE3_TEXT);
    $stmt->bindValue(':uin',      $uin     !== "" ? $uin     : null, SQLITE3_TEXT);
    $stmt->bindValue(':email',    $email, SQLITE3_TEXT);
    $stmt->execute();
    header("Location: user.php");
}

include "user.html";
?>