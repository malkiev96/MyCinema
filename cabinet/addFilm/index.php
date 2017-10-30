<?php


require_once "../../includes/config.php";

if ($user==null && $user['role_id']!=3) {
    print("Access denied");
    exit();
}

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

        <h1>Добавить фильм</h1>

        <form method="post" action="form.php" id="formFilm" enctype="multipart/form-data">
            <div class="label">Название фильма</div>
            <div><input type="text" id="name" name="name"></div>

            <div class="label">Краткое описание фильма</div>
            <div><input type="text" id="description" name="description"></div>

            <div class="label">Жанр фильма</div>
            <div><input type="text" id="genre" name="genre"></div>

            <div class="label">В ролях</div>
            <div><input type="text" id="role" name="role"></div>

            <div class="label">Страна фильма</div>
            <div><input type="text" id="country" name="country"></div>

            <div class="label">Год фильма</div>
            <div><input type="text" id="year" name="year"></div>

            <div class="label">Возрастное ограничение</div>
            <div><input type="text" id="age" name="age"></div>

            <div class="label">Продолжительность фильма, мин</div>
            <div><input type="text" id="length" name="length"></div>

            <div class="label">Дата выхода в прокат</div>
            <div><input type="date" id="dateStart" name="dateStart"></div>

            <div class="label">Дата конца проката</div>
            <div><input type="date" id="dateStop" name="dateStop"></div>

            <div class="label">Фильм доступен в 3D?</div>
            <div><input type="checkbox" id="format" name="format"></div>

            <div class="label">Картинка к фильму</div>
            <div><input type="file" id="logo" name="logo"></div>

            <div><button class="button-info" id="formButton" type="button">Добавить</button></div>
        </form>
    </div>
</div>

<?include "../../includes/footer.php";?>

<script type="text/javascript">

    $(document).ready(function () {
        $('#formButton').click(function () {
            validateForm();
        });

        $('input').keyup(function () {
            $(this).css({
                borderWidth: '2px',
            borderStyle: 'inset',
            borderColor: 'initial',
            borderImage: 'initial'
            })
        });

    });
    
    function validateForm() {
        var name = $('#name').val();
        var description = $('#description').val();
        var genre = $('#genre').val();
        var role = $('#role').val();
        var country = $('#country').val();
        var year = $('#year').val();
        var age = $('#age').val();
        var length = $('#length').val();
        var dateStart = $('#dateStart').val();
        var dateStop = $('#dateStop').val();
        var format = $('#dateStop').val();
        var logo = $('#logo').val();
        if (name=="") inputError('#name');
        else if (description=="") inputError('#description');
        else if (genre=="") inputError('#genre');
        else if (role=="") inputError('#role');
        else if (country=="") inputError('#country');
        else if (year=="") inputError('#year');
        else if (age=="") inputError('#age');
        else if (length=="") inputError('#length');
        else if (dateStart=="") inputError('#dateStart');
        else if (dateStop=="") inputError('#dateStop');
        else {

            $('#formFilm').submit();
            /*$.ajax({
                url: 'form.php',
                type: 'POST',
                data:{
                    name: name,
                    description: description,
                    genre: genre,
                    role: role,
                    country: country,
                    year: year,
                    age: age,
                    length: length,
                    dateStart: dateStart,
                    dateStop: dateStop,
                    format: format,
                    logo: logo
                },
                success: function (data) {
                    console.log(data);
                }
            })*/
        }

        
    }
    
    function inputError(input) {
        $(input).css({
            border: 'solid 2px #c00107'
        });
    }

</script>

</body>
</html>