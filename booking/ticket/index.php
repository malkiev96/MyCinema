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
    <h2 style="margin: 0; padding: 15px 0;">Вы успешно купили билеты</h2>

        <?php
        while ($ticket = mysqli_fetch_assoc($mysqliTicket)){
            $mysqliSession = mysqli_query($connection,"SELECT * FROM session WHERE id=".$ticket['id_session']);
            $session = mysqli_fetch_assoc($mysqliSession);
            $mysqliFilm = mysqli_query($connection,"SELECT * FROM film WHERE id = ". $session['id_film']);
            $film = mysqli_fetch_assoc($mysqliFilm);
            $mysqliHall = mysqli_query($connection,"SELECT * FROM hall WHERE id =".$session['id_hall']);
            $hall = mysqli_fetch_assoc($mysqliHall);
            $mysqliPlace = mysqli_query($connection, "SELECT * FROM place WHERE id =".$ticket['id_place']);
            $place = mysqli_fetch_assoc($mysqliPlace);
            $dateTime = date('d.m.Y',strtotime($session['date'])). " ". date('H:i',strtotime($session['time']));


            ?>
        <div class="ticket">
            <div id="ticket-main">
                <div style="padding-bottom: 5px"><span id="info-film"><?=$film['name']?></span></div>
                <div style="padding-bottom: 5px"><span id="info-numb">№ <?=$ticket['id']?></span></div>
                <div style="padding-bottom: 3px"><span class="info">Зал: </span><span class="info-val"><?=$hall['name']?></span></div>
                <div style="padding-bottom: 3px"><span class="info">Сеанс: </span><span class="info-val"><?=$dateTime?></span></div>
                <div style="padding-bottom: 3px"><span class="info">Место: </span><span class="info-val">Ряд <?=$place['row']?> место <?=$place['seat']?></span></div>
                <div style="padding-bottom: 3px"><span class="info">Стоимость: </span><span class="info-val"><?=$ticket['price']?> руб.</span></div>
            </div>
            <div id="ticket-logo">

            </div>
        </div>

            <?php
        }

        session_destroy();
        ?>

        <div>
            <button onclick="javascript:print();">Распечатать билеты</button>
        </div>

    </div>

</div>

    <?include "../../includes/footer.php";?>
</body>
</html>