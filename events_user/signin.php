<?php
//  Вход
session_start();
require('../db.php');
unset($_SESSION['message']);
$login=$_POST['login'];
$password=$_POST['password'];

$password_hash=md5($password);

//Проверка есть ли пользователь с таким логином и паролем

$sql_user=$link->query("SELECT * FROM user WHERE login = '$login' AND password = '$password_hash' ");

if (mysqli_num_rows($sql_user)==0) {
	$_SESSION['message']='Неверный логин и пароль';
	$redirect = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']:'redirect-form.html';
	header("Location: $redirect");
	exit();
}else{

    $user=mysqli_fetch_assoc($sql_user);

    $_SESSION['user']=[
        "id_user"=>$user['id_user'],
        "login"=>$user['login'],
        "password"=>$user['password'],
        "email"=>$user['email'],
        "role"=>$user['role'],
        "username"=>$user['username'],
    ];
if ($_SESSION['user']['role']=="customer") {
    header("Location: ../index.php?page=user");
}else{
    header("Location: ../index.php?page=admin");
}

}

?>