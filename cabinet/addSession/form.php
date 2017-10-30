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

                if (($hall['is3D']==0 && $is3D)){
                    echo "<p style='color: red'>Сеанс в 3D в невозможен</p>";
                }else{
                    if ($film['id']==$film_id && $hall['id']==$hall_id){
                        $timeEnd = date('H:i',strtotime($time." + ".$film['length']." min"));
                        $dateBd = date('Y-m-d',strtotime($date));
                        $length = $film['length'];

                        $qq = mysqli_query($connection,"INSERT INTO session (id_film, id_hall, date, time, price, format) VALUES ('$film_id','$hall_id','$date','$time','$price','$format')");
                        echo "Сеанс успешно добавлен";

                    }else echo "<p style='color: red'>Ошибка валидации формы 1</p>";
                }
            } else echo "<p style='color: red'>Ошибка валидации формы 1</p>";
        }



        ?>

    </div>
</div>

<?include "../../includes/footer.php";?>

</body>
</html>

