<?php
require "includes/config.php";

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
    // Вытаскиваем из БД запись, у которой логин равняеться введенному
    $query = mysqli_query($connection,"SELECT user_id, user_password FROM users WHERE user_login='".mysqli_real_escape_string($connection,$_POST['login'])."' LIMIT 1");
    $data = mysqli_fetch_assoc($query);

    // Сравниваем пароли
    if($data['user_password'] === md5(md5($_POST['password'])))
    {
        // Генерируем случайное число и шифруем его
        $hash = md5(generateCode(10));

//        if(!empty($_POST['not_attach_ip']))
//        {
//            // Если пользователя выбрал привязку к IP
//            // Переводим IP в строку
//            $insip = ", user_ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."')";
//        }

        // Записываем в БД новый хеш авторизации и IP
        mysqli_query($connection, "UPDATE users SET user_hash='".$hash."' ".$insip." WHERE user_id='".$data['user_id']."'");


        // Ставим куки
        setcookie("id", $data['user_id'], time()+60*60*24*30);
        setcookie("hash", $hash, time()+60*60*24*30,null,null,null,true); // httponly !!!

        header("Location: ". $_SERVER["HTTP_REFERER"]); exit();

    }
    else
    {
        echo "incorrect login or password";
    }
}
?>


