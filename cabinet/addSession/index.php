<?php

require "../../includes/config.php";

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
        input,select{
            width: 25%;
            padding: 5px;

        }
    </style>
</head>
<body>
<?include "../../includes/header.php";?>
<div class="main">
    <div class="container">
    <h1>Добавить сеанс</h1>

        <form method="post" action="form.php" id="formSession">

            <div class="label">Выберите фильм</div>

                <div><select name="film_id" id="film_id">
                    <?php
                    $films_query = mysqli_query($connection,"SELECT id, name FROM film");
                    while ($film = mysqli_fetch_assoc($films_query)){
                        echo "<option value=".$film['id'].">".$film['name']."</option>";
                    }
                    ?>
                    </select></div>

            <div class="label">Выберите зал</div>
                <div><select name="hall_id" id="hall_id">
                    <?php
                    $halls_query = mysqli_query($connection,"SELECT id, name,is3D FROM hall");
                    while ($hall = mysqli_fetch_assoc($halls_query)){
                        echo "<option value=".$hall['id'].">".$hall['name']."</option>";
                    }
                    ?>
                </select></div>

            <div class="label">Дата сеанса</div>
            <div><input type="date" id="date" name="date"></div>

            <div class="label">Время сеанса, шаг 30 минут</div>
            <div><input type="time" id="time" name="time"></div>

            <div class="label">Цена сеанса</div>
            <div><input type="text" id="price" name="price"></div>

            <div class="label">Сеанс в 3D?</div>
            <div><input type="checkbox" id="format" name="format"></div>

            <div><button type="button" id="formButton" class="button-info">Добавить</button></div>
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
        var date = $('#date').val();
        var time = $('#time').val();
        var price = $('#price').val();

        if (date=="") inputError('#date');
        else if (time=="") inputError('#time');
        else if (price=="" || price<=0) inputError('#price');
        else {
            $('#formSession').submit();
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