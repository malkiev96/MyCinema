<?php
require "includes/config.php"

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$config['title']?></title>
    <link rel="stylesheet" href="includes/css/style.css">
    <link rel="stylesheet" href="includes/css/style-modal.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<?include "includes/header.php";?>

<div class="main">
    <div class="container">
            <?php
            if ($_GET){

            }else{//показываем сеансы на сегодня

                ?>
                <h1>Сегодня в прокате</h1>
        <div class="film_list">
                <?php

                $sessionsToday = mysqli_query($connection,"SELECT * FROM session WHERE date=CURRENT_DATE()");
                if (mysqli_num_rows($sessionsToday)==0){
                    echo "Сегодня сеансов нет.";
                }
                else {
                    $usedFilms = array();
                    //А это комментарий..
                    while ($session = mysqli_fetch_assoc($sessionsToday)) {
                        $id_film = $session['id_film'];
                        if ($usedFilms[$id_film] == false) {
                            $usedFilms[$id_film] = true;
                            $filmQuery = mysqli_query($connection, "SELECT * FROM film WHERE id=$id_film");
                            $film = mysqli_fetch_assoc($filmQuery);
                            ?>
                            <div class="film-item" id="<?echo 'film-item-'.$film["id"]?>"
                                 onmouseover="showItem(id)" onmouseout="hideItem(id)"
                                 style="background: url(<?php echo $film['logo']; ?>); background-size: 100%;">

                                <div class="layout-film-item" style="visibility: hidden; width: 267px; height: 380px;
                                color: white; background-color:rgba(0,0,0,.5);" id="<?echo 'layout-film-item-'.$film["id"]?>">
                                    <div style="width: 237px; height: 350px; padding: 25px; ">
                                        <div class="filmName"><?=$film['name']?></div>
                                        <div class="filmAge"><?=$film['age']?>+</div>
                                        <div style="position: absolute; bottom: 120px;"><a href="/films/?id=<?=$film['id']?>" class="button-info">Подробнее</a></div>
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
        <h1>Скоро в прокате</h1>
    </div>
</div>

<?include "includes/footer.php";?>


<script type="text/javascript">
      function showItem(id) {
          var item = document.getElementById('layout-'+id);
          item.style.visibility = "visible";
          console.log(id+ 'enter');

      }

      function hideItem(id) {
          var item = document.getElementById('layout-'+id);
          item.style.visibility = "hidden";
          console.log(id+' end');
      }
</script>

</body>
</html>