<?
require_once "../../includes/config.php"
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<!--    <meta http-equiv="refresh" content="3; url=/cabinet/addSession"/>-->
    <title></title>
    <link rel="stylesheet" href="/includes/css/style.css">
    <link rel="stylesheet" href="/includes/css/style-modal.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        input{
            width: 25%;
            padding: 5px;

        }
    </style>
</head>
<body>
<?include "../../includes/header.php";?>
<div class="main">
    <div class="container">

        <?php

        if ($_POST && $user['role_id']==3){
            $film_id = $_POST['film_id'];
            $hall_id = $_POST['hall_id'];
            $date = $_POST['date'];
            $time = $_POST['time'];
            $price = $_POST['price'];
            $format = $_POST['format'];
            $dateNow = date('Y-m-d');

            //валидация формы
            if (!empty($film_id) && !empty($hall_id) && !empty($date) && !empty($time) && !empty($price) && $price>=0 && $date>=$dateNow){

                $timeMin = substr($time,3,4);
                if ($timeMin=='00' || $timeMin=='30'){

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

                    if (($hall['is3D']==0 && $is3D)){
                        echo "<p style='color: red'>Сеанс в 3D невозможен</p>";
                    }else{
                        if ($film['id']==$film_id && $hall['id']==$hall_id){
                            $length = $film['length']*60-180*60;
                            $length = date('H:i:s',$length);
                            $query = "SELECT * FROM session WHERE date='$date' AND id_hall='$hall_id' AND time_to_sec(time) <= (time_to_sec('$time')+time_to_sec('$length')) AND (time_to_sec(time)+time_to_sec(length)) >= time_to_sec('$time')";
                            $testSession = mysqli_query($connection,$query);

                            if (mysqli_num_rows($testSession)==0){
                                $insertSession = mysqli_query($connection,"INSERT INTO session (id_film, id_hall, date, time,length, price, format) VALUES ('$film_id','$hall_id','$date','$time','$length','$price','$format')");
                                echo "<div style='padding: 15px;margin:0;'>Сеанс успешно добавлен</div>";
                            }else echo "<div style='color: red;padding: 15px; margin: 0;'>В данное время зал занят</div>";
                        }else echo "<p style='color: red;padding: 15px; margin: 0;'>Ошибка валидации формы</p>";
                    }
                }else echo "<p style='color: red; padding: 15px; margin: 0;'>Время сеанса должно быть с шагом 30 минут</p>";
            } else echo "<p style='color: red; padding: 15px; margin: 0;'>Ошибка валидации формы</p>";
        }



        ?>

    </div>
</div>

<?include "../../includes/footer.php";?>

</body>
</html>

