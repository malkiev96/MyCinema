<?php

require "../../includes/config.php";

if ($user==null) header('Location: /login.php');


?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$config['title']?></title>
    <link rel="stylesheet" href="/includes/css/style.css">
    <link rel="stylesheet" href="/includes/css/style-modal.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        label,input{
            display: inline-block;
            width: 150px;
            text-align: left;
        }
    </style>
</head>
<body>
<?include "../../includes/header.php";?>
<div class="main">
    <div class="container">
    <h1>Добавить сеанс</h1>
    <?php



    if ($user['role_id']==3){

        if ($_POST){
            $film_id = $_POST['film_id'];
            $hall_id = $_POST['hall_id'];
            $date = $_POST['date'];
            $time = $_POST['time'];
            $price = $_POST['price'];
            $format = $_POST['format'];

            $dateNow = date('Y-m-d');

            //валидация формы
            if (!empty($film_id) && !empty($hall_id) && !empty($date) && !empty($time) && !empty($price) && $price>=0 && $date>=$dateNow){

                //проверка значений в базе данных
                $films_query = mysqli_query($connection,"SELECT * FROM film WHERE id = ".$film_id);
                $halls_query = mysqli_query($connection,"SELECT * FROM hall WHERE id = ".$hall_id);

                $film = mysqli_fetch_assoc($films_query);
                $hall = mysqli_fetch_assoc($halls_query);

                $is3D = false;//проверяем возможность 3D в выбранном зале
                if ($format!=null){
                    $is3D = true;
                    $format = '3D';
                } else {
                    $format = '2D';
                    $is3D = false;
                }

                if ($film['id']==$film_id && $hall['id']==$hall_id){



                    $timeEnd = date('H:i',strtotime($time." + ".$film['length']." min"));

                    //проверяем, нет ли сеансов в выбранное время
                    $dateBd = date('Y-m-d',strtotime($date));
                    $session_query = mysqli_query($connection,"SELECT * FROM session WHERE id_hall='$hall_id' AND date = '$dateBd' AND time = t$timeime");// AND time >= '$time' AND time <= '$timeEnd'
                    $ses = mysqli_fetch_assoc($session_query);

                    if ($ses==null){
                        mysqli_query($connection,"INSERT INTO session (id_film, id_hall, date, time, price, format) VALUES ('$film_id','$hall_id',d$dateate,t$timeime,'$price','$format')");

                    }else{
                        //в данное время есть сеанс
                        echo "<p style='color: red'>В выбранное время зал занят</p>";
                    }
                } else echo "<p style='color: red'>Ошибка валидации формы 1</p>";
            } else echo "<p style='color: red'>Ошибка валидации формы 2</p>";
        }




        ?>
        <form method="post">
            <p>
                <label for="film_id">Выберите фильм</label>
                <select name="film_id" id="film_id">
                    <?php
                    $films_query = mysqli_query($connection,"SELECT id, name FROM film");
                    while ($film = mysqli_fetch_assoc($films_query)){
                        echo "<option value=".$film['id'].">".$film['name']."</option>";
                    }
                    ?>
                </select>
            </p>

            <p>
                <label for="hall_id">Выберите зал</label>
                <select name="hall_id" id="hall_id">
                    <?php
                    $halls_query = mysqli_query($connection,"SELECT id, name,is3D FROM hall");
                    while ($hall = mysqli_fetch_assoc($halls_query)){
                        echo "<option value=".$hall['id'].">".$hall['name']."</option>";
                    }
                    ?>
                </select>
            </p>

            <p>
                <label for="date">Дата сеанса</label>
                <input type="date" id="date" name="date">
            </p>
            <p>
                <label for="time">Время сеанса </label>
                <input type="time" id="time" name="time">
            </p>
            <p>
                <label for="price">Цена сеанса</label>
                <input type="text" id="price" name="price">
            </p>
            <p>
                <label for="format">Сеанс в 3D?</label>
                <input type="checkbox" id="format" name="format">
            </p>
            <p>
                <button type="submit">Добавить</button>
            </p>
        </form>
    <?php

    }else print "Access denied";

    ?>
</div>
</div>

<?include "../../includes/footer.php";?>

</body>
</html>