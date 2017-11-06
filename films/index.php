<?php
require_once "../includes/config.php";
if (isset($_GET['id'])) {

    $id = $_GET[ 'id' ];
    $filmQuery = mysqli_query($connection, "SELECT * FROM film WHERE id=$id");
    if (mysqli_num_rows($filmQuery) == null) {
        header('Location: /error');
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="/includes/js/myScript.js"></script>
    <style>
        .table-th{
            text-align: left;
            font-size: larger;
            padding-bottom: 10px;
            padding-right: 50px;
            border-bottom: 1px solid #bdbdbd;
        }
        .table-td{
            padding-top: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #bdbdbd;
        }
    </style>
</head>
<body>
<?include "../includes/header.php";?>

<div class="main">
    <div class="container">

    <?php

    //показываем фильм
    if (isset($_GET['id'])){

        $id = $_GET['id'];
        $filmQuery = mysqli_query($connection, "SELECT * FROM film WHERE id=$id");
        if (mysqli_num_rows($filmQuery)!=null){
            $film = mysqli_fetch_assoc($filmQuery);

            echo "<div class='film-info'>";
            echo "<img id='film-logo' src=  /files/photos/".$film['logo']." width=300><br>";

            if ($film['format']=='3D'){
                echo "<h1>".$film['name']." 3D</h1>";
            }else{
                echo "<h1>".$film['name']."</h1>";
            }

            echo "<table>";
            echo "<tr><td class='td-info'>Возрастные ограничения</td><td>".$film['age']."+</td></tr>";
            if ($film['genre']!=null){
                echo "<tr><td class='td-info'>Жанр</td><td>".$film['genre']."</td></tr>";
            }
            echo "<tr><td class='td-info'>Страна</td><td>".$film['country']."</td></tr>";
            echo "<tr><td class='td-info'>Продолжительность фильма</td><td>".$film['length']." минут</td></tr>";
            if ($film['role']!=null){
                echo "<tr><td class='td-info'>В ролях</td><td>".$film['role']."</td></tr>";
            }
            echo "</table>";

            echo "<h2>Описание</h2>";
            echo $film['description'];
            echo "</div>";
            $sessions = mysqli_query($connection,"SELECT * FROM session WHERE id_film='$id' AND date=CURRENT_DATE() AND time_to_sec(time)>(time_to_sec(CURRENT_TIME())+30*60) OR date>CURRENT_DATE() AND id_film='$id'");


            //получаем сеансы
            if (mysqli_num_rows($sessions)!=null){
                echo "<div id='session'>";
                echo "<h2 id='sessions'>Сеансы</h2>";
                echo "<table cellspacing='0' class='session'>";
                echo "<tr>";
                echo "<th class='table-th'>Дата</th>";
                echo "<th class='table-th'>Время</th>";
                echo "<th class='table-th'>Формат</th>";
                echo "<th class='table-th'>Цена</th>";
                echo "<th class='table-th'>Зал</th>";
                echo "<th class='table-th'></th>";
                if ($user['role_id']>=2){
                    echo "<th class='table-th'></th>";
                }
                echo "</tr>";
                while ($session = mysqli_fetch_assoc($sessions)){
                    $dateToday = date("d-m-Y");
                    $timeNow = date("H:i:00");
                    $date = $session['date'];
                    $date = date('d-m-Y',strtotime($date));
                    $timeStart = $session['time'];
                    $time = $session['time'];
                    $timeStart = date("H:i",strtotime($timeNow.' + 15 min'));



                    //Сверяем, не устарел ли сеанс
                    //if ((strtotime($date)>strtotime($dateToday)) || ((strtotime($timeStart)<strtotime($time)) && (strtotime($date)==strtotime($dateToday)))){
                    $hall = mysqli_query($connection,"SELECT name FROM hall WHERE id = ".$session['id_hall']);
                    $id = $session['id'];
                    $name = mysqli_fetch_row($hall);
                    echo "<tr>";
                    echo "<td class='table-td'>".$date."</td>";
                    echo "<td class='table-td'>".substr($session['time'],0,5)."</td>";
                    echo "<td class='table-td'>".$session['format']."</td>";
                    echo "<td class='table-td'>".$session['price']." р.</td>";
                    echo "<td class='table-td'>".$name[0]."</td>";
                    echo "<td class='table-td'><a href='/booking/?session=$id'><div class='buy-ticket'>Купить</div></a></td>";
                    if ($user['role_id']>=2){
                        echo "<td class='table-td'><a href='/films/seats.php?session=$id'><div class='buy-ticket'>Просмотр мест</div></a></td></tr>";
                    }

                }
                echo "</table>";

            }else echo "<h2>Сеансов нет</h2>";
            echo "</div>";
        }
    } else if(empty($_GET)){
    //показываем все фильмы
        ?>

        <h1>В прокате</h1>
        <div class="film_list">
                <?php

                $sessionsToday = mysqli_query($connection,"SELECT * FROM session WHERE date>CURRENT_DATE() OR date=CURRENT_DATE() AND time_to_sec(time)>(time_to_sec(CURRENT_TIME())+30*60)");
                if (mysqli_num_rows($sessionsToday)==0){
                    echo "В ближайшее время сеансы не запланированы.";
                }
                else {
                    $usedFilms = array();//записываем в массив фильмы без повторений
                    //гвнкд
                    while ($session = mysqli_fetch_assoc($sessionsToday)) {
                        $id_film = $session['id_film'];
                        if ($usedFilms[$id_film] == false) {
                            $usedFilms[$id_film] = true;
                            $filmQuery = mysqli_query($connection, "SELECT * FROM film WHERE id=$id_film");
                            $film = mysqli_fetch_assoc($filmQuery);
                            ?>
                            <div class="film-item" id="<?echo 'film-item-'.$film["id"]?>"
                                 onmouseover="showItem(id)" onmouseout="hideItem(id)"
                                 style="background: url(<?php echo '/files/photos/'.$film['logo']; ?>); background-size: 100%;">

                                <div class="layout-film-item" style="visibility: hidden; width: 267px; height: 380px;
                                color: white; background-color:rgba(0,0,0,.5);" id="<?echo 'layout-film-item-'.$film["id"]?>">
                                    <div style="position: relative; width: 237px; height: 350px; padding: 25px; ">
                                        <?php
                                        if ($film['format'] == '3D'){
                                            echo "<div class=\"filmName\">".$film['name']." 3D</div>";
                                        }else echo "<div class=\"filmName\">".$film['name']."</div>";
                                        ?>
                                        <div style="padding-top: 15px; margin: 0;"><?=$film['genre']?></div>
                                        <div style="padding-top: 5px;"><?=$film['age']?>+</div>
                                       <a href="/films/?id=<?=$film['id']?>" style="position: absolute; top: 75%;" class="button-info">Подробнее</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }
    }

    ?>
    </div>
</div>

<?include "../includes/footer.php";?>

</body>
</html>