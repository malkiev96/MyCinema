
<div class="pre-header">
    <div class="container">
        <div class="right-align">
            <?php
            if ($user==null){
                echo "<a class='textHref' href='#login'>Войти</a>";
                echo " | ";
                echo "<a class='textHref' href='/reg.php'>Регистрация</a>";

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
                <li><a href="/"><img id="myLogo" src="/includes/img/logo.png"></a></li>
                <li class="li-navigation"><a href="/">Главная</a></li>
                <li class="li-navigation"><a href="/films">Фильмы</a></li>
<!--                <li class="li-navigation"><a href="/">Новости</a></li>-->
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
        <div class="label">Логин: </div>
        <div><input name="login" class="inputLogin" placeholder="login" type="text"></div>

        <div class="label">Пароль: </div>
        <div><input name="password" class="inputLogin" placeholder="password" type="password"></div>
        <button type="submit" class="login">Войти</button>
    </form>

    <a class="close" title="Закрыть" href=""></a>
</div>

