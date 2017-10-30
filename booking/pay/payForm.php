<?php
session_start();
require_once "../../includes/config.php";

if ($_POST){
    $sessionId = htmlspecialchars($_POST['session_id']);
    $seats = htmlspecialchars($_POST['seats']);
    $seats = explode(',',$seats);//Восстанавливаем массив из переданной строки

    $mySeats;
    $myHash;

    $mysqliSession = mysqli_query($connection, "SELECT * FROM session WHERE id='$sessionId'");
    $session = mysqli_fetch_assoc($mysqliSession);
    //проверяем верность id и переданных мест и записываем места в массив
    if ($session['id']==$sessionId){
        foreach ($seats as $place){
            //обрабатываем строки и вытаскиваем ряд-место
            $place = str_replace('seat','',$place);
            $place = str_replace('row','',$place);
            $pos = strpos($place,'-');

            $seat = substr($place,0,$pos);
            $row = substr($place,$pos+1);

            //проверяем переданные места с БД
            $mysqliPlace = mysqli_query($connection,"SELECT * FROM place WHERE seat='$seat' AND row='$row' AND Id_hall=".$session['id_hall']);
            $assoc = mysqli_fetch_assoc($mysqliPlace);
            if ($seat == $assoc['seat'] && $row==$assoc['row']){
                $mySeats[]= $assoc['id'];
                $myHash[] = rand(100000000,999999999);
            }else{
                header('Location: /index.php');
            }
        }
        //Добавить сессии php
        if ($user['id']==null){
            $_SESSION['pay']['user_id'] = 'guest';
        }else{
            $_SESSION['pay']['user_id'] = $user['id'];
        }
        $_SESSION['pay']['session_id'] = $sessionId;
        $_SESSION['pay']['seats_id'] = $mySeats;
        $_SESSION['pay']['time'] = date('H:i:s');
        $_SESSION['pay']['hash'] = $myHash;
        $_SESSION['pay']['date'] = date('Y-m-d');
        $myPayId = rand(100000000,999999999);
        $_SESSION['pay']['pay_id'] = $myPayId;

        header('Location: /booking/pay');

    }else  header('Location: /index.php');

}else  header('Location: /index.php');