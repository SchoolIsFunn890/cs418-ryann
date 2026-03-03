<?php
    session_start();

    $db = new SQLite3('database.db');
    $email = $_SESSION['email'];


    $stmt = $db->prepare("SELECT first, last, email, uin FROM users WHERE email = :email");
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);


    $thing = $result['first'];
    $thing1 = $result['last'];
    $thing2 = $result['email'];
    $thing3 = $result['uin'];

    echo json_encode([$thing, $thing1, $thing2, $thing3]);

?>
