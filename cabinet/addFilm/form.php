<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3; url=/cabinet/addFilm"/>
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

        require_once "../../includes/config.php";
        require "../../includes/uploadFile.php";



        if ($_POST) {

            $name = $_POST[ 'name' ];
            $description = $_POST[ 'description' ];
            $genre = $_POST[ 'genre' ];
            $role = $_POST[ 'role' ];
            $country = $_POST[ 'country' ];
            $year = $_POST[ 'year' ];
            $age = $_POST[ 'age' ];
            $length = $_POST[ 'length' ];
            $dateStart = $_POST[ 'dateStart' ];
            $dateStop = $_POST[ 'dateStop' ];
            $format = $_POST[ 'format' ];


            if (!empty($name) &&
                !empty($description) &&
                !empty($age) &&
                !empty($length) &&
                !empty($dateStart) &&
                !empty($dateStop) &&
                $dateStart < $dateStop) {


                if ($format != null) {
                    $format = "3D";
                } else $format = "2D";
                if ($year == null) $year = date("Y");

                if (isset($_FILES[ 'logo' ])) {
                    $result = upload_file($_FILES[ 'logo' ]);
                    if (isset($result[ 'error' ])) {
                        echo "<p style='color: red'>Ошибка при добавлении фильма</p>$result";
                    } else {
                        $logo = $result[ 'filename' ];
                        mysqli_query($connection, "INSERT INTO film(name, description, year, country, format, length, age, dateStart, dateStop, genre, role, logo) VALUES ('$name','$description','$year','$country','$format','$length','$age','$dateStart','$dateStop','$genre','$role','$logo')");
                        echo "<p style='color: green; padding-top: 25px'>Фильм успешно добавлен</p>";
                    }
                } else {
                    echo "<p style='color: red'>Ошибка при добавлении фильма</p>";
                }

            } else echo "<p style='color: red'>Ошибка при добавлении фильма</p>";
        }

        ?>

        </div>
</div>

<?include "../../includes/footer.php";?>

</body>
</html>

