<?php
require "includes/config.php";

if ($user!=null) header('Location: /');

// Страница регистрации нового пользователя

function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
        $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}

if($_POST) {
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

    if(strlen($_POST['password']) < 4 or strlen($_POST['password']) > 30)
    {
        $err[] = "Пароль должен быть не меньше 4-х символов и не больше 30";
    }

    if(strlen($_POST['firstname']) < 3 or strlen($_POST['lastname']) > 30)
    {
        $err[] = "Введите имя";
    }

    if(strlen($_POST['lastname']) < 3 or strlen($_POST['lastname']) > 30)
    {
        $err[] = "Введите фамилию";
    }



    // Если нет ошибок, то добавляем в БД нового пользователя
    if(count($err) == 0)
    {

        $login = $_POST['login'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];

        // Убераем лишние пробелы и делаем двойное шифрование
        $password = md5(md5(trim($_POST['password'])));

        mysqli_query($connection,"INSERT INTO users SET user_login='$login', user_password='$password', user_name='$firstname',user_lastname='$lastname'");
        $queryq = mysqli_query($connection,"SELECT user_id FROM users WHERE user_login='$login'");
        $user_id = mysqli_fetch_assoc($queryq);
        $id = $user_id['user_id'];
        mysqli_query($connection,"INSERT INTO user_roles SET user_id='$id', role_id='1'");

        // Генерируем случайное число и шифруем его
        $hash = md5(generateCode(10));

        if(!empty($_POST['not_attach_ip']))
        {
            // Если пользователя выбрал привязку к IP
            // Переводим IP в строку
            $insip = ", user_ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."')";
        }

        // Записываем в БД новый хеш авторизации и IP
        mysqli_query($connection, "UPDATE users SET user_hash='".$hash."' ".$insip." WHERE user_id='$id'");


        // Ставим куки
        setcookie("id", $id, time()+60*60*24*30);
        setcookie("hash", $hash, time()+60*60*24*30,null,null,null,true); // httponly !!!

        header("Location: /cabinet"); exit();
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
    <link rel="stylesheet" href="includes/css/style-modal.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        input{
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<?include "includes/header.php";?>

<div class="main">
    <div class="container">
    <h1>Регистрация</h1>
    <form method="POST">
            <div>Логин: </div>
            <div><input style="font: inherit; font-weight: normal; width: 193px; padding: 5px"  name="login" placeholder="login" type="text"></div>
            <div>Имя: </div>
            <div><input style="font: inherit; font-weight: normal; width: 193px; padding: 5px"  name="firstname" placeholder="firstname" type="text"></div>
            <div>Фамилия: </div>
            <div><input style="font: inherit; font-weight: normal; width: 193px; padding: 5px"  name="lastname" placeholder="lastname" type="text"></div>
            <div>Пароль: </div>
            <div><input style="font: inherit; font-weight: normal; width: 193px; padding: 5px" name="password" type="password" placeholder="password"></div>
            <button style="cursor: pointer; margin-top: 5px;" class="button-info" type="submit">Войти</button>
    </form>
    </div>
</div>
<?include "includes/footer.php";?>
</body>
</html>