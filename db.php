<?php 

// Подключение к БД
$host = '127.0.0.1:3306'; // Или 'localhost'
$user = 'root';
$password = 'root';
$db_name = 'computer_shop';

$link=mysqli_connect($host,$user,$password,$db_name);
$link->set_charset('utf8');
?>
