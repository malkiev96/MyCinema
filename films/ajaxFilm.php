<?php

require "../includes/config.php";
if (isset($_POST['date'])){

    $date = $_POST['date'];
    $date = date("Y-m-d",strtotime($date));
    ?>

<div class="film_list">
    <?php

    $maxShowFilm = 8;

    $sessionsToday = mysqli_query($connection,"SELECT * FROM session WHERE date='$date'");
    if (mysqli_num_rows($sessionsToday)==0){
        //echo "В выбранный день сеансов нет";
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
                <div class="film-item" id="<?echo 'more-item-'.$film["id"]?>"
                     onmouseover="showItem(id)" onmouseout="hideItem(id)"
                     style="background: url(<?php echo '/files/photos/'.$film['logo']; ?>); background-size: 100%;">

                    <div class="layout-more-item" style="visibility: hidden; width: 267px; height: 380px;
                                color: white; background-color:rgba(0,0,0,.5);" id="<?echo 'layout-more-item-'.$film["id"]?>">
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