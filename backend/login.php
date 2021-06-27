<?php
    if (isset($_POST["logbutton"])) {
        if (isset($_POST["login"]) && isset($_POST["password"])) { //&& $_POST['g-recaptcha-response']) {
            include('./includes/dbconnect.inc.php');
            try{
                $login = htmlspecialchars($_POST["login"]);
                $password = htmlspecialchars($_POST["password"]);
                $stmt = $dbh->prepare("SELECT * FROM users WHERE login = :login");
                $stmt->execute([':login' => $login]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if($user && password_verify($password, $user['password'])) {
                    session_id($user['id']);
                    session_start();
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['login'] = $user['login'];
                    session_write_close();
                    header("Location: https://s113.labagh.pl/index.html?page=main&mess=loginsuccess");
                }else{
                    header("Location: https://s113.labagh.pl/index.html?page=main&mess=wronglogpass");
                }
            } catch (PDOException $e) {
                header("Location: https://s113.labagh.pl/index.html?page=main&mess=error");
            }  
        } else {
            header("Location: https://s113.labagh.pl/index.html?page=main&mess=formnotfilled");
        }
    }