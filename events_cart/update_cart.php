<?php
session_start();

$id_add = $_POST['id_product'];
$quantity = isset($_POST['qty']) ? (int)$_POST['qty'] : 1; // Получаем количество из формы

// Если количество равно 0, удаляем товар из корзины
if ($quantity <= 0) {
    unset($_SESSION['cart'][$id_add]); // Удаляем товар из корзины
} else {
    // Ограничение на максимальное количество товара
    if ($quantity > 12) {
        $quantity = 12; // Устанавливаем максимум на 12
    }
    
    // Обновляем количество товара в корзине
    $_SESSION['cart'][$id_add] = $quantity; 
}

// Перенаправление обратно на страницу корзины
$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
header("Location: $redirect");
exit();
?>
