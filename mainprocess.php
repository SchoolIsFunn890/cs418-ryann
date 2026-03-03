<?php
    $email = $_POST["email"];
    $password = $_POST["password"];

    $checker = 0;

    $db = new SQLite3('database.db');
    //admin
    $stmt = $db->prepare("SELECT email, password FROM admin WHERE email = :email");
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $resultForAdmin = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if ($resultForAdmin) { 
        $hash = $resultForAdmin['password'];

        if(password_verify($password, $hash)){
            session_start();
            $_SESSION['email'] = $resultForAdmin['email'];
            header("Location: admin.php"); 
            exit;
        } else {
            echo 
            "<script>
                alert('Incorrect Password A');
                window.location.href = 'main.php';
            </script>";
        }

    }

   
    //user
    if (!$resultForAdmin) { 

        $stmt = $db->prepare("SELECT email, password, verifaction FROM users WHERE email = :email");
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':verifaction', $verifaction, SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        
        if (!$result) { 
            echo 
            "<script>
                alert('Incorrect email');
                window.location.href = 'main.php';
            </script>";
            exit; 
        }
        $verifaction = $result['verifaction'];
        $hash = $result['password'];

        if(password_verify($password, $hash) && $verifaction === 1){
            session_start();
            $_SESSION['email'] = $result['email'];
            echo 
            "<script>
                window.location.href = 'user.php';
            </script>";
        } else {
            echo 
            "<script>
                alert('Incorrect Password Or account is not verified');
                window.location.href = 'main.php';
            </script>";
        }
    }

    
?>