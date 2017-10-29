<?php

if (isset($_REQUEST['mail'])){
    session_start();
    $mail = $_REQUEST['mail'];
    $_SESSION['pay']['mail'] = $mail;
    $validation = filter_var($mail, FILTER_VALIDATE_EMAIL);
    if ($validation){
        echo $mail;
    }else{
        echo "error";
    }
}

if (isset($_REQUEST['number']) && isset($_REQUEST['year']) && isset($_REQUEST['cvc'])){
    $number = $_REQUEST['number'];
    $year = $_REQUEST['year'];
    $cvc = $_REQUEST['cvc'];


}

