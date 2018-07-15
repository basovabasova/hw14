<?php

    require_once 'auth_reg.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Регистрация</title>
</head>
<body>
  <p>Введите данные для регистрации или войдите, если уже регистрировались:</p>

  <form action="" method="POST">
    <input type="text" name="login" placeholder="Логин">
    <input type="password" name="password" placeholder="Пароль">
    <input type="submit" name="sign_in" value="Вход">
    <input type="submit" name="register" value="Регистрация">
  </form>
</body>
</html>