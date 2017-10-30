<?php
session_start();
require_once "../../includes/config.php";
if (isset($_REQUEST['mail'])){

    $mail = $_REQUEST['mail'];
    $_SESSION['pay']['mail'] = $mail;
    $validation = filter_var($mail, FILTER_VALIDATE_EMAIL);
    if ($validation){
        echo $mail;
    }else{
        echo "error";
    }
}

if (isset($_REQUEST['number']) && isset($_REQUEST['month']) && isset($_REQUEST['year']) && isset($_REQUEST['cvc'])){
    $number = $_REQUEST['number'];
    $month = $_REQUEST['month'];
    $year = $_REQUEST['year'];
    $cvc = $_REQUEST['cvc'];

    if (strlen($number)!=14 && !is_int($number)){
        echo "number";
    }elseif ($month<=0 || $month>12){
        echo "month";
    }elseif (strlen($year)!=2){
        echo "year";
    }elseif (strlen($cvc)!=3){
        echo "cvc";
    }else{
        //если ошибок нет
        if (empty($_SESSION['pay']['user_id']) ||
            empty($_SESSION['pay']['session_id']) ||
            empty($_SESSION['pay']['seats_id']) ||
            empty($_SESSION['pay']['time']) ||
            empty($_SESSION['pay']['mail'])){
            header('Location:/');
        }

        $time = $_SESSION['pay']['time'];
        $date = $_SESSION['pay']['date'];
        $user_id = $_SESSION['pay']['user_id'];
        $session_id = $_SESSION['pay']['session_id'];
        $seats = $_SESSION['pay']['seats_id'];
        $hashMas = $_SESSION['pay']['hash'];
        $timeNow = date('H:i:s');
        $mail = $_SESSION['pay']['mail'];

        if((strtotime($timeNow)-strtotime($time)>=600)){
            header('Location:/');
        }
        if($user_id=='guest') $user_id = 0;

        for($i = 0 ; $i<count($seats);$i++){
            $mysqliTicket = mysqli_query($connection,"UPDATE ticket SET isPay='1', mail='$mail' WHERE id =".$hashMas[$i]);
            if ($mysqliTicket==false){
                echo "error";
                exit();
                break;
            }
        }
        echo "ok";

        $_SESSION['ticket'] = $_SESSION['pay']['pay_id'];
        unset($_SESSION['pay']);

    }

}

