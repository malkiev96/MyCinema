<?php
session_start();
require "../../includes/config.php";

if (empty($_SESSION['pay']['user_id']) ||
    empty($_SESSION['pay']['session_id']) ||
    empty($_SESSION['pay']['seats_id']) ||
    empty($_SESSION['pay']['time'])){
    header('Location:/');
}

$time = $_SESSION['pay']['time'];
$date = $_SESSION['pay']['date'];
$user_id = $_SESSION['pay']['user_id'];
$session_id = $_SESSION['pay']['session_id'];
$seats = $_SESSION['pay']['seats_id'];
$hashMas = $_SESSION['pay']['hash'];
$timeNow = date('H:i:s');

if((strtotime($timeNow)-strtotime($time)>=600)){
    header('Location:/');
}

if($user_id=='guest') $user_id = 0;

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?=$config['title']?></title>
    <link rel="stylesheet" href="../../includes/css/style.css">
    <link rel="stylesheet" href="../../includes/css/style-modal.css">
    <link rel="stylesheet" href="../../includes/css/style-booking.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        button{
            cursor: pointer;
        }

        input:focus{
            outline: 0;
            outline-offset: 0;
        }
    </style>
</head>
<body>
<?include "../../includes/header.php";?>
<div class="main">
    <div class="container">
        <h1></h1>

        <?php

        $mysqliSession = mysqli_query($connection, "SELECT * FROM session WHERE id=".$session_id);
        $session = mysqli_fetch_assoc($mysqliSession);
        $dateTime = date('d.m.Y',strtotime($session['date'])). " ". date('H:i',strtotime($session['time']));

        $mysqliFilm = mysqli_query($connection,"SELECT * FROM film WHERE id=".$session['id_film']);
        $film = mysqli_fetch_assoc($mysqliFilm);

        $mysqliHall = mysqli_query($connection,"SELECT name FROM hall WHERE id =" . $session['id_hall']);
        $hallName = mysqli_fetch_assoc($mysqliHall);
        $hallName = $hallName['name'];


        //таймер
        $timer = date('h:i:s',strtotime($timeNow)-strtotime($time));
        $timer = date('h:i:s',600-strtotime($timer));
        $timer = date('i:s',strtotime($timer));// от 10мин к 0
        $timeI = substr($timer,0,2);
        $timeS = substr($timer, 3,2);

        //считаем цену
        $price = 0;
        $priceMas;
        foreach ($seats as $seatId){
            $mysqliPlace = mysqli_query($connection,"SELECT * FROM place WHERE id='$seatId'");
            $place = mysqli_fetch_assoc($mysqliPlace);
            if ($place['type']==1){
                $price += $session['price']*0.8;
                $priceMas[] = $session['price']*0.8;
            }elseif ($place['type']==2){
                $price += $session['price'];
                $priceMas[] = $session['price'];
            }elseif ($place['type']==3){
                $price += $session['price']*1.4;
                $priceMas[] = $session['price']*1.4;
            }
        }

        //бронируем места
        for ($i = 0; $i<count($hashMas); $i++){
            $ticketSQL = mysqli_query($connection,"INSERT INTO ticket(id, id_session, id_place, id_user, dateBooking, timeBooking, isStudent, isPay, lastname, firstname, timePay, price) VALUES('$hashMas[$i]','$session_id','$seats[$i]','$user_id','$date','$time','0','0','','','','$priceMas[$i]')");
        }


        ?>

        <div class="seat-view">
            <div id="show-seat"  class='seat-info'>
                Вы выбрали <?=count($seats)?><span id="seat-count"> места</span>
                <?php
                echo "(";
                foreach ($seats as $seatId){
                    $mysqliPlace = mysqli_query($connection,"SELECT * FROM place WHERE id='$seatId'");
                    $place = mysqli_fetch_assoc($mysqliPlace);
                    echo "ряд:".$place['row']."-место:".$place['seat']." ";
                    if ($place['type']==1){
                        $price += $session['price']*0.8;
                        $priceMas[] = $session['price']*0.8;
                    }elseif ($place['type']==2){
                        $price += $session['price'];
                        $priceMas[] = $session['price'];
                    }elseif ($place['type']==3){
                        $price += $session['price']*1.4;
                        $priceMas[] = $session['price']*1.4;
                    }
                }
                echo ")";
            echo "</div>";
            echo "<div class='seat-info'>Итого к оплате: ".$price." руб</div>";
            echo "<div class='seat-info'>Завершите оплату в течение <span id='timeI'>$timeI</span><span>:</span><span id='timeS'>$timeS</span></div><hr>";
            ?>

            <h2>Онлайн оплата</h2>

                <div id="enter-email">
                    <h4>Куда прислать билеты</h4>
                    <input type="email" name="mail" id="input-email">
                    <h4>Способ оплаты</h4>
                    <div id="payMethod">Банковская карта</div>
                    <button class="button-info" style="width: 240px" id="button-email">Перейти к оплате</button>
                </div>

                <div hidden id="enter-cart">
<!--                    <form method="post" name="payForm">-->
                        <div id="divCart">
                        <h4>Банковская карта</h4>
                        <div><input type="text" id="formNumber" name="number" placeholder="Введите номер карты"></div>
                        <div>
                            <input type="text" id="formYear" name="year" placeholder="ММ/ГГ">
                            <input type="text" id="formCVC" name="cvc" placeholder="CVC">
                        </div>
                        <button class="button-info" id="buttonPay" style="width: 350px">Оплатить <?=$price?> &#8381;</button>
                        </div>
<!--                    </form>-->
                </div>

        </div>

        <div class="seat-film">
            <?php
            echo "<div><img class='seat-img' src=/files/photos/".$film['logo']."></div>";
            echo "<div>";
            echo "<h2>".$film['name']."</h2>";
            echo "<div class='seat-info'>Ограничение: ".$film['age']."+</div>";
            echo "<div class='seat-info'>Жанр: ".$film['genre']."</div>";
            echo "<div class='seat-info'>Продолжительность фильма: ".$film['length']." мин.</div>";
            echo "</div>";
            echo "<div class='seat-info'>Дата и время: ".$dateTime."</div>";
            echo "<div class='seat-info'>Зал: ".$hallName."</div>";
            echo "<div id='count-selected' style='margin-top: 5px; margin-bottom: 5px'></div>";
            echo "<div id='show-price'></div>";
            ?>
        </div>

        <div style="clear: left"></div>
    </div>
</div>

<?include "../../includes/footer.php";?>


<script type="text/javascript">

    var timeOut = false;
    var timer;
    $(document).ready(function () {
       countSeat();

       $('#button-email').click(function () {
           postMail();
       });
        

       $('#buttonPay').click(function () {
           postCard();
       });

       timer = setInterval(showTime, 1000); // использовать функцию
    });


    function postCard() {
        var number = $('#formNumber').val();
        var year = $('#formYear').val();
        var cvc = $('#formCVC').val();

        $.ajax({
            url: 'mail.php',
            type: 'POST',
            data: {
                number: number,
                year: year,
                cvc: cvc
            },
            success: function (data) {
                console.log(data);
            }
        });
    }


    function postMail() {
        var mail = $('#input-email').val();
        $.ajax({
            url: 'mail.php',
            type: 'POST',
            data: 'mail='+mail,
            success: function (data) {
                if (data=='error'){
                    $('#input-email').css({
                        border: 'solid 2px #c00107'
                    });
                    $('#input-email').attr('placeholder','Некоректный e-mail');

                }else {
                    $('#enter-email').html('');
                    $('#enter-cart').show();
                }
            }
        });
    }


    function countSeat() {
        var count = <?=count($seats)?>;
        if (count==1){
            $('#seat-count').html(' место');
        }else if (count==2 || count==3 || count==4){
            $('#seat-count').html(' места');
        }else if (count==5){
            $('seat-count').html(' мест');
        }
    }

    function showTime() {
        var i = $('#timeI').html();
        var s = $('#timeS').html();
        if (s>10)s--;
        else if (s>0 && s<=10) {
            s--;
            s = '0'+s;
        }
        else if (s==0 && i!=0){
            s=60;
            i--;
            i = '0'+i;
        }
        if (i==0 && s==0){
            timeOut = true;
            clearInterval(timer);
        }
        $('#timeI').text(i);
        $('#timeS').text(s);

    }
</script>

</body>
</html>