<!doctype html>
<html>
<head>
    <?php
    require_once "../includes/config.php";

    require "../includes/config.php";
    if ($_GET['session']==null){
        header('Location:/');
    }else{
        $mysqliSession = mysqli_query($connection, "SELECT * FROM session WHERE id=".htmlspecialchars($_GET['session']));
        $session = mysqli_fetch_assoc($mysqliSession);
        $dateTime = date('d.m.Y',strtotime($session['date'])). " ". date('H:i',strtotime($session['time']));

        $mysqliFilm = mysqli_query($connection,"SELECT * FROM film WHERE id=".$session['id_film']);
        $film = mysqli_fetch_assoc($mysqliFilm);

        $mysqliPlace = mysqli_query($connection,"SELECT * FROM place WHERE Id_hall=".$session['id_hall']);

        if ($session['id']==null){
            header('Location:/');
        }
    }

    ?>

    <meta charset="UTF-8">
    <title><?=$config['title']?></title>
    <link rel="stylesheet" href="../includes/css/style.css">
    <link rel="stylesheet" href="../includes/css/style-modal.css">
    <link rel="stylesheet" href="../includes/css/style-booking.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="/includes/js/myScript.js"></script>
    <style>
        span.has-seat {
            cursor: auto;
        }
    </style>
</head>
<body>
<?include "../includes/header.php";?>

<div class="main">
    <div class="container">
        <h1>Покупка билета</h1>
        <div class="seat-view">
            <div id="legend">
                <span>Эконом</span><span class="econom"></span>
                <span>Обычное</span><span class="norm"></span>
                <span>Vip</span><span class="vip"></span>
                <span>Недоступно</span><span class="unavailable"></span>
            </div>
            <div class="seats">

                <?php
                $places = array();
                $maxSeat = 0;
                $maxRow = 0;

                //Получаем ряд место зала
                while ($place = mysqli_fetch_assoc($mysqliPlace)){
                    $places[] = $place;
                    $maxSeat = max($maxSeat,$place['seat']);
                    $maxRow = max($maxRow,$place['row']);
                }

                //показываем выбор места
                for ($row = 1; $row<=$maxRow; $row++){
                    echo "<div class='row'>";
                    echo "<div class='seat-col'>$row</div>";
                    for ($seat = 1 ; $seat <=$maxSeat;$seat++){
                        foreach ($places as $value){
                            if ($value['row']==$row && $value['seat']==$seat){
                                $mysqliTicket = mysqli_query($connection,"SELECT * FROM ticket WHERE id_session=".$session['id']." AND id_place=".$value['id']);
                                $ticket = mysqli_fetch_assoc($mysqliTicket);
                                if ($ticket['id']==null){
                                    if ($value['type']==1){
                                        echo "<span class='has-seat econ-seat'  id='seat$seat-row$row' title='seat $seat row $row'></span>";
                                    }else if ($value['type']==2){
                                        echo "<span class='has-seat norm-seat' id='seat$seat-row$row' title='seat $seat row $row'></span>";
                                    }else if ($value['type']==3){
                                        echo "<span class='has-seat vip-seat' id='seat$seat-row$row' title='seat $seat row $row'></span>";
                                    }
                                }else{
                                    echo "<span class='lock-seat'></span>";
                                }
                            }
                        }
                    }
                    echo "</div>";
                }
                ?>
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
            echo "<div style='clear: left'></div><hr>";
            echo "<div class='seat-info'>Дата и время: ".$dateTime."</div>";
            echo "<div class='seat-info'>Цена сеанса: ".$session['price']." руб</div>";
            echo "<div style='clear: left'></div><hr>";
            ?>
        </div>
        <div style="clear: left"></div>
    </div>
</div>

    <?include "../includes/footer.php";?>

</body>
</html>