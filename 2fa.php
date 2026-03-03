<?php
    include "2fa.html";

    $db = new SQLite3('database.db');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $code = rand(100000, 999999);

        $email = $_POST['email']; 
        $stmt = $db->prepare("SELECT email FROM users WHERE email = :email");
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

            if (!$result) { 
            echo 
            "<script>
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
