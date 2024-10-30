<?php
session_start();

// Удаляем все товары из корзины
if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
}

// Перенаправление обратно на страницу корзины или на главную
$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
header("Location: $redirect");
exit();
?>
