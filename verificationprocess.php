<?php
    $code = $_POST["code"];
    $email = $_POST["email"];

    $db = new SQLite3('database.db');


    $stmt = $db->prepare("SELECT verifaction FROM users WHERE email = :email");
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
    
    if (!$result) { 
        echo "<script>
        alert('Incorrect email');
        window.location.href = 'verification.php';
         </script>";
        exit; 
    }

    $storedCode = $result['verifaction'];

    if ($storedCode == $code && $storedCode != 1) {
        $stmt = $db->prepare("UPDATE users SET verifaction = 1 WHERE email = :email");
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->execute();
        header("Location: main.php");
    } else if($storedCode == 1){
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