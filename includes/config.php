<?php
$config = array(
    'title' => 'Кинотеатр "Рассветный"',

    'db' => array(
        'server' => 'localhost:3306',
        'username' => 'root',
        'password' => 'root',
        'name' => 'cinema'
        )
);
require "db.php";

if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])){
    $query_user = mysqli_query($connection, "SELECT * FROM users WHERE user_id=".intval($_COOKIE['id']));
    $user_data = mysqli_fetch_assoc($query_user);
    $query_role = mysqli_query($connection, "SELECT * FROM user_roles WHERE user_id=".$user_data['user_id']);

    if (($user_data['user_hash'] !== $_COOKIE['hash']) or ($user_data['user_id'] !== $_COOKIE['id'])) {
        $user = null;
    }else{
        $user_roles = mysqli_fetch_assoc($query_role);
        $user = array(
            'id' => $user_data['user_id'],
            'login' => $user_data['user_login'],
            'role_id' => $user_roles['role_id'],
            'name' => $user_data['user_name']
        );
    }
}

//проверяем неоплаченные билеты и удаляем записи
$timeNow = date('H:i:s');
$dateNow = date('Y-m-d');
$timeStart = date("H:i:s",strtotime($timeNow.' - 10 min'));
$ticketMysqli = mysqli_query($connection,"SELECT id FROM ticket WHERE isPay=0 AND dateBooking='$dateNow' AND time_to_sec(timeBooking)<time_to_sec('$timeStart')");
while ($id = mysqli_fetch_assoc($ticketMysqli)){
    mysqli_query($connection,"DELETE FROM ticket WHERE id = ".$id['id']);
}

