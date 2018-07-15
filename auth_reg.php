<?php

require_once 'functions.php';

session_start();

$login = (string)($_POST['login']);
$password = (string)($_POST['password']);

if (isset($_POST['register'])) {
    if(empty($login)) {
        $err[] = 'Не введен Логин';
    }
    
    if(empty($password)) {
        $err[] = 'Не введен Пароль';
    }

    if(count($err) > 0) {
        echo showErrorMessage($err);
    } else {
        $query = "SELECT id FROM `user` WHERE login = '".$login."'";
        $statement = $pdo->prepare($query);
        $statement->execute([$login]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!empty($row['id'])) {
           $err[] = 'Такой Логин уже зарегистрирован';
        } 

        if(count($err) > 0) {
            echo showErrorMessage($err);
        } else {
            $query = "INSERT INTO `user` (login, password) VALUES (?, ?)";
            $login = $login;
            $password = md5($password);

            $statement = $pdo->prepare($query);
            $statement->execute([$login, $password]);
            exit("Вы зарегистрированы! <a href=\"register.php\">Войти.</a>"); 
        }
    }         
}

if (isset($_POST['sign_in'])) {
    if(empty($login)) {
        $err[] = 'Не введен Логин';
    }
    
    if(empty($password)) {
        $err[] = 'Не введен Пароль';
    }

    if(count($err) > 0) {
        echo showErrorMessage($err);
    } else {
        $query = "SELECT id FROM `user` WHERE login = '".$login."'";
        
        $statement = $pdo->prepare($query);
        $statement->execute([$login]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        $query1 = "SELECT id FROM `user` WHERE password = ?";
        $password = md5($password);

        $statement = $pdo->prepare($query1);
        $statement->execute([$password]);
        $row1 = $statement->fetch(PDO::FETCH_ASSOC);
        
        if (empty($row['id']) || ($row['id'] !== $row1['id'])) {
            $err[] = 'Неверный логин или пароль';
        } 

        if(count($err) > 0) {
            echo showErrorMessage($err);
        } else {
            $_SESSION['user'] = $login;
            $_SESSION['id'] = $row['id'];
            redirect('index');
        }
    }
}