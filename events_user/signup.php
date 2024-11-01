<?php
//  Регистрация
session_start();
require('../db.php');
unset($_SESSION['message']);
$login=$_POST['login'];
$password=$_POST['password'];
$password2=$_POST['password2'];
$email=$_POST['email'];
$username = $_POST['username'];

//Проверка есть ли в БД пользователь с таким логином
$sql_user=$link->query("SELECT * FROM user WHERE login = '$login'");

if (mysqli_num_rows($sql_user)>0) {
	$_SESSION['message']='Пользователь с таким логином существует';
	$redirect = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']:'redirect-form.html';
	header("Location: $redirect");
	exit();
}else{
	if ($password===$password2) {
	//Хеширование пароля
	$password_hash=md5($password);
	mysqli_query($link, "INSERT INTO `user`(`login`, `password`, `email`, `username`) VALUES ('$login', '$password_hash', '$email', '$username')");
	$_SESSION['message']='Регистрация прошла успешно';
	$redirect = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']:'redirect-form.html';
	header("Location: $redirect");
	exit();

	}else{
		$_SESSION['message']='Пароли не совпадают';
		$redirect = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']:'redirect-form.html';
		header("Location: $redirect");
		exit();

	}
}


?>