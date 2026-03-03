<?php
try{
        $email = "admin@email.com";
        $password = "123";
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $db = new SQLite3('database.db');


        $stmt = $db->prepare("INSERT INTO admin (email, password, verifaction) VALUES (:email, :password, :verifaction)");
        $db->enableExceptions(true);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':password', $hash, SQLITE3_TEXT);
        $stmt->bindValue(':verifaction', 1, SQLITE3_TEXT);

        $stmt->execute();

    }catch(SQLite3Exception $e){
        echo "<script>
        alert('Email already exists!');
        window.location.href = 'index.php';
         </script>";
    }

?>
