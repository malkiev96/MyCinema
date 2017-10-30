<?php
session_start();
require "../../includes/config.php";
$payId = $_SESSION['ticket'];

if (!isset($payId)){
    header('Location:/');
}

$mysqliTicket = mysqli_query($connection,"SELECT * FROM ticket WHERE payId = '$payId' AND isPay = 1");
if (mysqli_num_rows($mysqliTicket)==0) {
    header('Location:/');
}

$ticket = mysqli_fetch_assoc($mysqliTicket);

$price = 0;
$count = 0;
$seats = "";
$mysqliTicket = mysqli_query($connection,"SELECT * FROM ticket WHERE payId = '$payId' AND isPay = 1");
while ($tick = mysqli_fetch_assoc($mysqliTicket)){
    $count++;
    $price+=$tick['price'];
    $mysqliPlace = mysqli_query($connection,"SELECT * FROM place WHERE id =".$tick['id_place']);
    $place = mysqli_fetch_assoc($mysqliPlace);
    $seats .= "ряд ".$place['row'].", место ". $place['seat']."; ";
}




$mysqliSession = mysqli_query($connection,"SELECT * FROM session WHERE id=".$ticket['id_session']);
$session = mysqli_fetch_assoc($mysqliSession);
$mysqliFilm = mysqli_query($connection,"SELECT * FROM film WHERE id = ". $session['id_film']);
$film = mysqli_fetch_assoc($mysqliFilm);
$mysqliHall = mysqli_query($connection,"SELECT * FROM hall WHERE id =".$session['id_hall']);
$hall = mysqli_fetch_assoc($mysqliHall);
$dateTime = date('d.m.Y',strtotime($session['date'])). " ". date('H:i',strtotime($session['time']));

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?=$config['title']?></title>
    <link rel="stylesheet" href="../../includes/css/style.css">
    <link rel="stylesheet" href="../../includes/css/style-modal.css">
    <link rel="stylesheet" href="../../includes/css/style-booking.css">
    <link rel="stylesheet" href="../../includes/css/style-ticket.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<?include "../../includes/header.php";?>
<div class="main">
    <div class="container">
        <h2>Вы успешно купили билеты</h2>

        <div class="ticket">
            <div id="qr">
                <img src="/includes/img/qr.png">
            </div>
            <div class="number">
                <div id="numberInfo">Номер билета</div>
                <div id="number"><?=$payId?></div>
            </div>
            <div id="info">
                <div><span class="info">Фильм: </span><span class="info-val"><?=$film['name']?></span></div>
                <div><span class="info">Зал: </span><span class="info-val"><?=$hall['name']?></span></div>
                <div><span class="info">Сеанс: </span><span class="info-val"><?=$dateTime?></span></div>
                <div><span class="info">Места: </span><span class="info-val"><?=$seats?></span></div>
                <div><span class="info">Стоимость билетов: </span><span class="info-val"><?=$price?> руб.</span></div>
            </div>

        </div>
    </div>
</div>

    <?include "../../includes/footer.php";?>
</body>
</html>