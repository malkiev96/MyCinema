<?php

require "../includes/config.php";

if ($user!=null){

}else header('Location: /login.php');

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
        <h1>Личный кабинет</h1>
        <div>
            <?php
            echo "<h3>Привет, ".$user['name']."</h3>";

            if ($user['role_id']==3){
                echo "<a class='textHref' href='addFilm'>Добавить новый фильм</a><br>";
                echo "<a class='textHref' href='addSession'>Добавить новый сеанс</a><br>";
            }

            echo "<a class='textHref' href='/exit.php'>Выход</a>";
            ?>
    </div>
    </div>
</div>

<?include "../includes/footer.php";?>

</body>
</html>