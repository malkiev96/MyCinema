<?php
require "../includes/config.php";
if ($_GET['session']==null){
    header('Location:/');
}else{
    $mysqliSession = mysqli_query($connection, "SELECT * FROM session WHERE id=".htmlspecialchars($_GET['session']));
    $session = mysqli_fetch_assoc($mysqliSession);
    if ($session['id']==null){
        header('Location:/');
    }

    $dateTime = date('d.m.Y',strtotime($session['date'])). " ". date('H:i',strtotime($session['time']));

    $mysqliFilm = mysqli_query($connection,"SELECT * FROM film WHERE id=".$session['id_film']);
    $film = mysqli_fetch_assoc($mysqliFilm);

    $mysqliPlace = mysqli_query($connection,"SELECT * FROM place WHERE Id_hall=".$session['id_hall']);

    $mysqliType = mysqli_query($connection, "SELECT * FROM type WHERE id=4");
    $type = mysqli_fetch_assoc($mysqliType);
    $discount = $type['coef'];

    $mysqliType = mysqli_query($connection, "SELECT * FROM type");
    while ($type = mysqli_fetch_assoc($mysqliType)){
        if ($type['id']==1){
            $placeDiscount[1] = $type['coef'];
        }
        if ($type['id']==2){
            $placeDiscount[2] = $type['coef'];
        }
        if ($type['id']==3){
            $placeDiscount[3] = $type['coef'];
        }
    }


}

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?=$config['title']?></title>
    <link rel="stylesheet" href="../includes/css/style.css">
    <link rel="stylesheet" href="../includes/css/style-modal.css">
    <link rel="stylesheet" href="../includes/css/style-booking.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        .checkbox + label {
            cursor: pointer;
        }

        /* Далее идет оформление чекбокса в современных браузерах, а также IE9 и выше.
        Благодаря тому, что старые браузеры не поддерживают селекторы :not и :checked,
        в них все нижеследующие стили не сработают. */

        /* Прячем оригинальный чекбокс. */
        .checkbox:not(checked) {
            position: absolute;
            opacity: 0;
        }
        .checkbox:not(checked) + label {
            position: relative; /* будем позиционировать псевдочекбокс относительно label */
            padding: 0 0 0 60px; /* оставляем слева от label место под псевдочекбокс */
        }
        /* Оформление первой части чекбокса в выключенном состоянии (фон). */
        .checkbox:not(checked) + label:before {
            content: '';
            position: absolute;
            top: -4px;
            left: 0;
            width: 50px;
            height: 26px;
            border-radius: 13px;
            background: #CDD1DA;
            box-shadow: inset 0 2px 3px rgba(0,0,0,.2);
        }
        /* Оформление второй части чекбокса в выключенном состоянии (переключатель). */
        .checkbox:not(checked) + label:after {
            content: '';
            position: absolute;
            top: -2px;
            left: 2px;
            width: 22px;
            height: 22px;
            border-radius: 10px;
            background: #FFF;
            box-shadow: 0 2px 5px rgba(0,0,0,.3);
            transition: all .2s; /* анимация, чтобы чекбокс переключался плавно */
        }
        /* Меняем фон чекбокса, когда он включен. */
        .checkbox:checked + label:before {
            background: #9FD468;
        }
        /* Сдвигаем переключатель чекбокса, когда он включен. */
        .checkbox:checked + label:after {
            left: 26px;
        }
        /* Показываем получение фокуса. */
        .checkbox:focus + label:before {
            box-shadow: 0 0 0 3px rgba(255,255,0,.5);
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
                                        echo "<span class='has-seat econ-seat'  id='seat$seat-row$row' title='".$session['price']*$placeDiscount[1]."rub seat $seat row $row'></span>";
                                        echo "<input type='text' hidden name='seat$seat-row$row' value='".$session['price']*$placeDiscount[1]."'>";
                                    }else if ($value['type']==2){
                                        echo "<span class='has-seat norm-seat' id='seat$seat-row$row' title='".$session['price']*$placeDiscount[2]."rub seat $seat row $row'></span>";
                                        echo "<input type='text' hidden name='seat$seat-row$row' value='".$session['price']*$placeDiscount[2]."'>";
                                    }else if ($value['type']==3){
                                        echo "<span class='has-seat vip-seat' id='seat$seat-row$row' title='".$session['price']*$placeDiscount[3]."rub seat $seat row $row'></span>";
                                        echo "<input type='text' hidden name='seat$seat-row$row' value='".$session['price']*$placeDiscount[3]."'>";
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
            echo "<div id='myCheckbox' hidden style='padding-top: 15px; padding-bottom: 15px;'><input type='checkbox' name='checkbox'  class='checkbox' id='checkbox' /><label for='checkbox'>Студент / пенсионер</label></div>";
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
            $('#myCheckbox').show();
        }else if (count==2 || count==3 || count==4){
            $('#count-selected').html('Вы выбрали '+count+' места');
            $('#go-payment').show();
            $('#myCheckbox').show();
        }else if (count==5){
            $('#count-selected').html('Вы выбрали 5 мест');
            $('#go-payment').show();
            $('#myCheckbox').show();
        }else if (count==0){
            $('#count-selected').html('');
            $('#go-payment').hide();
            $('#myCheckbox').hide();
        }
    }


    $('#checkbox').change(function () {
        showPrice();
    });



    function showPrice() {

        var sum = 0;
        var arr = $('.sell-seat');
        var id;
        for (var i = 0; i< arr.length;i++){
            id = $(arr[i]).attr('id');
            sum += Number($('input[name='+id+']').val());

        }

        if ($('#checkbox').is(':checked')){
            sum -= sum* <?=$discount?> / 100;
        }

        if (sum!=0){
                $('#show-price').html('Итоговая сумма: '+sum+' руб');
            }
            else $('#show-price').html('');
    }

</script>

</body>
</html>