<!doctype html>
<html>
<head>
    <?php
    require_once "includes/config.php"
    ?>
    <meta charset="UTF-8">
    <title><?=$config['title']?></title>
    <link rel="stylesheet" href="includes/css/style.css">
    <link rel="stylesheet" href="includes/css/style-modal.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="/includes/js/myScript.js"></script>
</head>
<body>
<?include "includes/header.php";?>

<div class="main">
    <div class="container">
            <?php
            if (isset($_GET['date'])){//показываем сеансы на выбранное число

            }else{
                //показываем сеансы на сегодня


                ?>

        <h1>Сегодня в прокате</h1>
        <div class="film_list">
                <?php

                $maxShowFilm = 4;

                $sessionsToday = mysqli_query($connection,"SELECT * FROM session WHERE date=CURRENT_DATE() AND time>CURRENT_TIME()");
                if (mysqli_num_rows($sessionsToday)==0){
                    echo "Сегодня сеансов нет.";
                }
                else {
                    $usedFilms = array();//записываем в массив фильмы без повторений
                    //А это комментарий..
                    while ($session = mysqli_fetch_assoc($sessionsToday)) {
                        $id_film = $session['id_film'];
                        if ($usedFilms[$id_film] == false) {
                            $usedFilms[$id_film] = true;
                            if (count($usedFilms)>$maxShowFilm){
                                //Если количество фильмов больше чем можно показать
                                echo "<div style='margin: auto; width: 20%;'><a href='/films' class='button-info'>Больше фильмов</a></div>";
                                break;
                            }
                            $filmQuery = mysqli_query($connection, "SELECT * FROM film WHERE id=$id_film");
                            $film = mysqli_fetch_assoc($filmQuery);
                            ?>
                            <div class="film-item" id="<?echo 'film-item-'.$film["id"]?>"
                                 onmouseover="showItem(id)" onmouseout="hideItem(id)"
                                 style="background: url(<?php echo '/files/photos/'.$film['logo']; ?>); background-size: 100%;">

                                <div class="layout-film-item" style="visibility: hidden; width: 267px; height: 380px;
                                color: white; background-color:rgba(0,0,0,.5);" id="<?echo 'layout-film-item-'.$film["id"]?>">
                                    <div style="width: 237px; height: 350px; padding: 25px; ">
                                        <div class="filmName"><?=$film['name']?></div>
                                        <div class="filmAge"><?=$film['age']?>+</div>
                                        <div><a href="/films/?id=<?=$film['id']?>" class="button-info">Подробнее</a></div>
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
        <?php

        //Скоро в прокате
        $sessionsMore = mysqli_query($connection,"SELECT * FROM session WHERE date>CURRENT_DATE()");
        echo " <h1>Скоро в прокате</h1>";
        if (mysqli_num_rows($sessionsMore)!=0){

            $usedFilms = array();
            while ($session = mysqli_fetch_assoc($sessionsMore)) {
                $id_film = $session['id_film'];

                //Проверяем, нет ли показа фильма сегодня, и если есть, не показываем в скором прокате
                $filmQuery = mysqli_query($connection, "SELECT id FROM session WHERE id_film = '$id_film' AND date=CURRENT_DATE() AND time>CURRENT_TIME()");

                if ($usedFilms[$id_film] == false && mysqli_num_rows($filmQuery)==0) {
                    $usedFilms[$id_film] = true;
                    $filmQuery = mysqli_query($connection, "SELECT * FROM film WHERE id=$id_film");
                    $film = mysqli_fetch_assoc($filmQuery);
                    ?>
                    <div class="film-item" id="<?echo 'more-item-'.$film["id"]?>"
                         onmouseover="showMore(id)" onmouseout="hideMore(id)"
                         style="background: url(<?php echo "/files/photos/".$film['logo']; ?>); background-size: 100%;">

                        <div class="layout-film-item" style="visibility: hidden; width: 267px; height: 380px;
                                color: white; background-color:rgba(0,0,0,.5);" id="<?echo 'layout-more-item-'.$film["id"]?>">
                            <div style="width: 237px; height: 350px; padding: 25px; ">
                                <div class="filmName"><?=$film['name']?></div>
                                <div class="filmAge"><?=$film['age']?>+</div>
                                <div><a href="/films/?id=<?=$film['id']?>" class="button-info">Подробнее</a></div>
                            </div>
                        </div>
                    </div>

                    <?php
                }
            }
        }else{
            echo "В ближайшее время сеансы не запланированы";
        }
        ?>
    </div>
</div>

<?include "includes/footer.php";?>

</body>
</html>