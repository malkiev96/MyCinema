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
<div id="content">
    <h1>Кабинет</h1>
    <div>
    <?php
    if ($user['role_id']==3){
        echo "<a href='addFilm'>Добавить новый фильм</a><br>";
        echo "<a href='addSession'>Добавить новый сеанс</a>";
    }


    ?>
    </div>
</div>

<?include "../includes/footer.php";?>

</body>
</html>