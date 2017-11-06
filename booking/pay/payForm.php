<?php
session_start();
require_once "../../includes/config.php";

if ($_POST){
    $sessionId = htmlspecialchars($_POST['session_id']);
    $seats = htmlspecialchars($_POST['seats']);
    $seats = explode(',',$seats);//Восстанавливаем массив из переданной строки

    if (isset($_POST['checkbox'])){
        $mysqliType = mysqli_query($connection,"SELECT coef FROM type WHERE id=4");
        $mysqliType = mysqli_fetch_assoc($mysqliType);
        $discount = $mysqliType['coef']/100;
    }else{
        $discount = 0;
    }

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
                $mysqliTicket = mysqli_query($connection,"SELECT id FROM ticket WHERE id_session=$sessionId AND id_place=".$assoc['id']);
                $ticket = mysqli_fetch_assoc($mysqliTicket);
                if ($ticket['id']!=null){
                    print_r("Error selecting place");
                    exit();
                }
                $mySeats[]= $assoc['id'];
                $myHash[] = rand(100000000,999999999);
            }else{
                header('Location: /index.php');
            }
        }
        //проверяем, не занято ли выбранное место


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
        $_SESSION['pay']['discount'] = $discount;

        header('Location: /booking/pay');

    }else  header('Location: /index.php');

}else  header('Location: /index.php');