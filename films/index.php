<?php

require "../includes/config.php";

?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$config['title']?></title>
    <link rel="stylesheet" href="../includes/css/style.css">
    <link rel="stylesheet" href="../includes/css/style-modal.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<?include "../includes/header.php";?>

<div class="main">
    <div class="container">

    <?php
    if ($_GET){
        if (isset($_GET['id'])){

            $id = $_GET['id'];
            $filmQuery = mysqli_query($connection, "SELECT * FROM film WHERE id=$id");
            if (mysqli_num_rows($filmQuery)!=null){
                $film = mysqli_fetch_assoc($filmQuery);

                echo "<div class='film-info'>";
                echo "<img id='film-logo' src=  ".$film['logo']." width=300><br>";

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
                echo "<h2 id='sessions'>Сеансы</h2>";
                $sessions = mysqli_query($connection,"SELECT * FROM session WHERE id_film=$id");
                //получаем сеансы
                if (mysqli_num_rows($sessions)!=null){
                    while ($session = mysqli_fetch_assoc($sessions)){
                        $dateToday = date("Y-m-d");
                        $timeNow = date("H:i:00");
                        if ($session['date']==$dateToday){
                            //echo date('H:i:00')."<br>";
                            $hall = mysqli_query($connection,"SELECT name FROM hall WHERE id = ".$session['id_hall']);
                            $name = mysqli_fetch_row($hall);
                            if (strtotime($session['time']) > (strtotime($timeNow)+1800)){
                                echo $name[0]." ". $session['format']." ". substr($session['time'],0,5);
                                echo "<hr>";
                            }
                        }
                    }

                }else echo "Сегодня сеансов нет";
                echo "</div>";
            }
        }
    }

    ?>
</div>
</div>

<?include "../includes/footer.php";?>

</body>
</html>