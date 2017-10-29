<!doctype html>
<html>
<head>
    <?php
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
                <span>Ваш выбор</span><span class="chosen"></span>
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
                                        echo "<input type='text' hidden name='seat$seat-row$row' value='".$session['price']*0.8."'>";
                                    }else if ($value['type']==2){
                                        echo "<span class='has-seat norm-seat' id='seat$seat-row$row' title='seat $seat row $row'></span>";
                                        echo "<input type='text' hidden name='seat$seat-row$row' value='".$session['price']."'>";
                                    }else if ($value['type']==3){
                                        echo "<span class='has-seat vip-seat' id='seat$seat-row$row' title='seat $seat row $row'></span>";
                                        echo "<input type='text' hidden name='seat$seat-row$row' value='".$session['price']*1.4."'>";
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
            echo "<div>Выберите ваше место</div>";
            echo "<div id='seat-selected'></div>";
            echo "<div id='count-selected' style='margin-top: 5px; margin-bottom: 5px'></div>";
            echo "<div id='show-price'></div>";
            echo "<form method='post' action='pay/payForm.php' id='pay-form'>";
            echo "<button id='go-payment' hidden type='submit'>Перейти к оплате</button>";
            echo "</form>";
            ?>
        </div>
        <div style="clear: left"></div>
    </div>
</div>

<?include "../includes/footer.php";?>


<script>

    $('#pay-form').submit(function () {
        $(this).append("<input type='text' name='session_id' value='<?echo $session['id']?>' hidden >");

        var arr = $('.sell-seat');
        var id;
        var idArray=[];
        for (var i = 0; i< arr.length;i++){
            id = $(arr[i]).attr('id');
            idArray.push(id);
        }
        var textArray = idArray.join(',');

        $(this).append("<input type='text' name='seats' value='"+textArray+"' hidden >");
    });

    $('.has-seat').click(function () {
        onClickClass(this.id);
        showCountSelected();
        getPriceById(this.id);
        showPrice();
    });


    function onClickClass(id) {

        if ($('#'+id).hasClass('sell-seat')){
            $('#'+id).removeClass('sell-seat');
            delSelected(id);
        }else if ($('.sell-seat').length<5){
            $('#'+id).addClass('sell-seat');
            showSelected(id);
        }
    }

    function showSelected(id) {
        $('#seat-selected').append("<div class='selected' id='seat-"+id+"'>"+id+"</div>");
    }

    function delSelected(id) {
        $('#seat-'+id).remove();
    }

    function showCountSelected() {
        var count = $('.sell-seat').length;
        if (count==1){
            $('#count-selected').html('Вы выбрали 1 место');
            $('#go-payment').show();
        }else if (count==2 || count==3 || count==4){
            $('#count-selected').html('Вы выбрали '+count+' места');
            $('#go-payment').show();
        }else if (count==5){
            $('#count-selected').html('Вы выбрали 5 мест');
            $('#go-payment').show();
        }else if (count==0){
            $('#count-selected').html('');
            $('#go-payment').hide();
        }
    }

    function getPriceById(id) {
        var price = $('input[name='+id+']').val();
        return price;

    }

    function showPrice() {
        var sum = 0;
        var arr = $('.sell-seat');
        var id;
        for (var i = 0; i< arr.length;i++){
            id = $(arr[i]).attr('id');
            sum += Number($('input[name='+id+']').val());

        }

        if (sum!=0){
            $('#show-price').html('Итоговая сумма: '+sum+' руб');
        }else $('#show-price').html('');
    }

</script>

</body>
</html>