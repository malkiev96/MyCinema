<?php

require "../includes/config.php";

if ($user!=null){
    //$user_id = $user['id'];

    //$mysqliTicket = mysqli_query($connection,"SELECT ticket.* FROM ticket,session WHERE id_user=$user_id AND isPay=1 AND ticket.id_session=session.id AND ((session.date=CURRENT_DATE AND session.time>CURRENT_TIME) OR session.date>CURRENT_DATE)");

}else header('Location: /login.php');

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет - <?=$config['title']?></title>
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
            echo "<h3 style='margin: 0; padding-bottom: 5px'>Привет, ".$user['name']."</h3>";
            if ($user['role_id']==1){
                echo "<h4>Вы вошли как пользователь</h4>";
            }elseif ($user['role_id']==2){
                echo "<h4>Вы вошли как сотрудник</h4>";
            }elseif ($user['role_id']==3){
                echo "<h4 style='margin: 10px 0;'>Вы вошли как администратор</h4>";
            }

            echo "<a class='textHref' href='myTicket'>Мои билеты</a><br>";

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