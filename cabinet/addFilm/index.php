<?php

require "../../includes/config.php";
require "../../includes/uploadFile.php";


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
        label{
            display: inline-block;
            width: 200px;
            text-align: left;
        }
    </style>
</head>
<body>
<?include "../../includes/header.php";?>
<div class="main">
    <div class="container">

        <?php

        function clean($value = "") {
            $value = trim($value);
            $value = stripslashes($value);
            $value = strip_tags($value);
            $value = htmlspecialchars($value);

            return $value;
        }

        if ($user['role_id']==3){
            if ($_POST){

                $name = $_POST['name'];
                $description = $_POST['description'];
                $genre = $_POST['genre'];
                $role = $_POST['role'];
                $country = $_POST['country'];
                $year = $_POST['year'];
                $age = $_POST['age'];
                $length = $_POST['length'];
                $dateStart = $_POST['dateStart'];
                $dateStop = $_POST['dateStop'];
                $format = $_POST['format'];
                $trailer = $_POST['trailer'];


                if (!empty($name) &&
                    !empty($description) &&
                    !empty($age) &&
                    !empty($length) &&
                    !empty($dateStart) &&
                    !empty($dateStop) &&
                    $dateStart<$dateStop) {


                    if ($format != null) {
                        $format = "3D";
                    } else $format = "2D";
                    if ($year == null) $year = date("Y");

                    if (isset($_FILES['logo'])){
                        $result = upload_file($_FILES['logo']);
                        if(isset($result['error'])){
                            echo "<p style='color: red'>Ошибка валидации формы 1</p>";
                        }else{
                            $logo = $result['filename'];
                            mysqli_query($connection, "INSERT INTO film(name, description, year, country, format, length, age, dateStart, dateStop, genre, role, logo, trailer) VALUES ('$name','$description','$year','$country','$format','$length','$age','$dateStart','$dateStop','$genre','$role','$logo','$trailer')");
                            echo "<p style='color: green; padding-top: 25px'>Фильм успешно добавлен</p>";
                        }
                    }else {
                        echo "<p style='color: red'>Ошибка валидации формы 2</p>";
                    }

                } else echo "<p style='color: red'>Ошибка валидации формы 3</p>";
            }


            ?>


            <h1>Добавить фильм</h1>
            <form method="post" action="index.php" enctype="multipart/form-data">
                <p>
                    <label for="name">Название фильма</label>
                    <input type="text" id="name" name="name">
                </p>
                <p>
                    <label for="description">Краткое описание фильма</label>
                    <input type="text" id="description" name="description">
                </p>
                <p>
                    <label for="genre">Жанр фильма</label>
                    <input type="text" id="genre" name="genre">
                </p>
                <p>
                    <label for="role">В ролях</label>
                    <input type="text" id="role" name="role">
                </p>
                <p>
                    <label for="country">Страна фильма</label>
                    <input type="text" id="country" name="country">
                </p>
                <p>
                    <label for="year">Год фильма</label>
                    <input type="text" id="year" name="year">
                </p>
                <p>
                    <label for="age">Возрастное ограничение</label>
                    <input type="text" id="age" name="age">
                </p>
                <p>
                    <label for="length">Продолжительность фильма в минутах</label>
                    <input type="text" id="length" name="length">
                </p>
                <p>
                    <label for="dateStart">Дата выхода в прокат</label>
                    <input type="date" id="dateStart" name="dateStart">
                </p>
                <p>
                    <label for="dateStop">Дата конца проката</label>
                    <input type="date" id="dateStop" name="dateStop">
                </p>
                <p>
                    <label for="format">Фильм в 3D?</label>
                    <input type="checkbox" id="format" name="format">
                </p>
                <p>
                    <label for="logo">Картинка к фильму</label>
                    <input type="file" id="logo" name="logo">
                </p>
                <p>
                    <label for="trailer">HTML код трейлера</label>
                    <input type="text" id="trailer" name="trailer">
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