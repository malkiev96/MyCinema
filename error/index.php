<!doctype html>
<html>
<head>
    <?php
    require_once "../includes/config.php"
    ?>
    <meta charset="UTF-8">
    <title><?=$config['title']?></title>
    <link rel="stylesheet" href="../includes/css/style.css">
    <link rel="stylesheet" href="../includes/css/style-modal.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="/includes/js/myScript.js"></script>
</head>
<body>
<?include "../includes/header.php";?>

<div class="main">
    <div class="container">
        <h1>Кажется, что-то пошло не так</h1>
<!--        <a href="/"><button class="button-info">Вернуться на главную</button></a>-->
    </div>
</div>

<?include "../includes/footer.php";?>

</body>
</html>