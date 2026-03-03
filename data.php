<?php
try{
        $first = $_POST["first"];
        $last = $_POST["last"];
        $email = $_POST["email"];
        $uin = $_POST["uin"];
        $password = $_POST["password"];
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $code = rand(100000, 999999);

        $db = new SQLite3('database.db');


        $stmt = $db->prepare("INSERT INTO users (first, last, email, uin, password, verifaction) VALUES (:first, :last, :email, :uin, :password, :verifaction)");
        $db->enableExceptions(true);
        $stmt->bindValue(':first', $first, SQLITE3_TEXT);
        $stmt->bindValue(':last', $last, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':uin', $uin, SQLITE3_TEXT);
        $stmt->bindValue(':password', $hash, SQLITE3_TEXT);
        $stmt->bindValue(':verifaction', $code, SQLITE3_TEXT);

        $stmt->execute();

        $to = $email;
        $subject = $code;
        $message = "The code is your $code";
        $headers = "From: noreply@gmail.com";

        mail($to, $subject, $message, $headers);


        header("Location: verification.php");
        exit;
    }catch(SQLite3Exception $e){
        echo "<script>
        alert('Email already exists!');
        window.location.href = 'index.php';
         </script>";
    }

?>
