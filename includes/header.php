
<div class="pre-header">
    <div class="container">
        <div class="right-align">
            <?php
            if ($user==null){
                echo "<a class='textHref' href='#login'>Войти</a>";
                echo " | ";
                echo "<a class='textHref' href='#signup'>Регистрация</a>";

            }else{
                echo "<a style='font-size: 1.1em' class='textHref' href='/cabinet'>".$user['login']."</a>";
            }
            ?>
        </div>
    </div>
</div>

<div class="header">
    <div class="container">
        <div class="header-navigation">
            <ul>
                <li><a href="/">Главная</a></li>
                <li><a href="/films">Фильмы</a></li>
                <li><a href="/">Новости</a></li>
            </ul>
        </div>
        <div class="header-search"></div>
    </div>
</div>

<a href="" class="overlay" id="login"></a>
<div class="popup" id="popup-login">
    <h1>Авторизация</h1>
    <hr>
    <form method="POST" action="/login.php">
        <div>
            <div class="label">Логин: </div>
            <div><input name="login" type="text"></div>
        </div>
        <div>
            <div class="label">Пароль: </div>
            <div><input name="password" type="password"></div>
        </div>
        <hr>
        <div>
            <button class="button-close" style="margin-right: 5px" id="button-close">Закрыть</button>
            <button type="submit" class="login">Войти</button>
        </div>
    </form>

    <a class="close" title="Закрыть" href=""></a>
</div>

<script>

    $(document).ready(function () {

    });

    $('#button-close').click(function () {
        window.location.hash = '';
    });
</script>