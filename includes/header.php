
<header>
    <div id="userHeader">
        <div id="user">
            <ul>
                <?php
                if ($user==null){
                    echo "<li><a href='/login.php'>Войти</a></li>";
                    echo "<li><a href='/reg.php'>Регистрация</a></li>";
                }else{
                    echo "<li><a href='/cabinet'>Личный кабинет</a></li>";
                }
                ?>
            </ul>
        </div>
    </div>
    <div id="navHeader">
        <div id="nav">
            <ul>
                <li><a href="/">Главная</a></li>
                <li><a href="/">Фильмы</a></li>
            </ul>
        </div>
    </div>
</header>

<div id="search">
    <div id="searchContent">

    </div>
</div>