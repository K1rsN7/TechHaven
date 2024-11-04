<?php
//Добавление заказа
session_start();
unset($_SESSION['message_order']);
require('../db.php');



if (isset($_SESSION['user'])){
  $add_order=$_SESSION['cart'];
  $id_user=(int)$_SESSION['user']['id_user'];

  foreach ($add_order as $key => $value) {
    $id_tovar=$key;
    $col_tovar=$value;
    mysqli_query($link, "INSERT INTO `orders`( `id_tovar`, `col_tovar`,  `id_user`) VALUES ('$id_tovar', '$col_tovar', '$id_user')");
    //после оформления
    unset($_SESSION['cart']);
    if (isset($_SESSION['user'])) {
      if ($_SESSION['user']['role']==0) {
        header("Location: ../index.php?page=user");
      }else{
        header("Location: ../index.php?page=admin");
      }

  }

  }

}else{
  $_SESSION['message_order']='Для оформления заказа необходимо зарегистрироваться';
	$redirect = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']:'redirect-form.html';
	header("Location: $redirect");
	exit();
}
?>