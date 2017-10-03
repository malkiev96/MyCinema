<?php
require "includes/config.php";

// Страница регистрации нового пользователя


if(isset($_POST['submit'])) {
    $err = array();
    // проверям логин
    if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
    {
        $err[] = "Логин может состоять только из букв английского алфавита и цифр";
    }

    if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
    {
        $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
    }

    // проверяем, не сущестует ли пользователя с таким именем
    $query = mysqli_query($connection,
        "SELECT user_id FROM users WHERE user_login='".mysqli_real_escape_string($connection, $_POST['login'])."'");
    if(mysqli_num_rows($query) > 0) {
        $err[] = "Пользователь с таким логином уже существует в базе данных";
    }

    // Если нет ошибок, то добавляем в БД нового пользователя
    if(count($err) == 0)
    {

        $login = $_POST['login'];

        // Убераем лишние пробелы и делаем двойное шифрование
        $password = md5(md5(trim($_POST['password'])));

        mysqli_query($connection,"INSERT INTO users SET user_login='$login', user_password='$password'");
        mysqli_query($connection,"INSERT INTO user_roles SET user_id=(SELECT user_id FROM users WHERE user_login=$login), role_id='1'");

        header("Location: login.php"); exit();
    }
    else
    {
        print "<b>При регистрации произошли следующие ошибки:</b><br>";
        foreach($err AS $error)
        {
            print $error."<br>";
        }
    }
}
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$config['title']?></title>
    <link rel="stylesheet" href="includes/css/style.css">
</head>
<body>
<?include "includes/header.php";?>

<div id="content">

    <form method="POST">
        Логин <input name="login" type="text"><br>
        Пароль <input name="password" type="password"><br>
        <input name="submit" type="submit" value="Зарегистрироваться">

    </form>
</div>

<?include "includes/footer.php";?>
</body>
</html>