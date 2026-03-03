<?php
    include "forgot.html";

        $db = new SQLite3('database.db');



    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email']; 
        $password = $_POST['password'];
        $stmt = $db->prepare("SELECT email, password FROM users WHERE email = :email");
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

        $hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $db->prepare("UPDATE users SET password = :password WHERE email = :email");
        $stmt->bindValue(':password', $hash, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->execute();

        echo "<script>
            alert('Password updated successfully!'); 
            window.location.href='main.php';
        </script>";

    }
?>