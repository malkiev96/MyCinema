<?php

require "../includes/config.php";

?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$config['title']?></title>
    <link rel="stylesheet" href="../includes/css/style.css">
</head>
<body>
<?include "../includes/header.php";?>
<div id="content">
    <?php
    if ($_GET){
        if (isset($_GET['id'])){

            $id = $_GET['id'];
            $filmQuery = mysqli_query($connection, "SELECT * FROM film WHERE id=$id");
            if (mysqli_num_rows($filmQuery)!=null){
                $film = mysqli_fetch_assoc($filmQuery);
                echo "<h1>".$film['name']."</h1>";
                if ($film['logo']!=null){
                    echo "<img src=".$film['logo']." width=300><br>";
                }
                echo "Возрастные ограничения: ".$film['age']."+<br>";
                echo "Страна: ".$film['country']."<br>";
                echo "Продолжительность фильма: ".$film['length']." минут<br>";
                echo "<h2>Описание</h2>";
                echo $film['description'];
                echo "<h2>Сеансы</h2>";
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
                            echo $name[0]." ". $session['format']." ". substr($session['time'],0,5)."<hr>";

                        }
                    }

                }else echo "Сегодня сеансов нет";

            }

        }
    }

    ?>
</div>

<?include "../includes/footer.php";?>

</body>
</html>