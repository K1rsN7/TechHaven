<?php
session_start();

$id_add = $_GET['id_product'];
$quantity = isset($_GET['qty']) ? (int)$_GET['qty'] : 1; // Получаем количество из URL, по умолчанию 1

if (!isset($_SESSION['cart'][$id_add])) {
    $_SESSION['cart'][$id_add] = $quantity; // Добавляем товар с указанным количеством
} else {
    $_SESSION['cart'][$id_add] += $quantity; // Увеличиваем количество товара в корзине

    // Ограничение максимального количества товара в корзине до 12 включительно (изменяется в зависимости от реальной ограничения)
    if ($_SESSION['cart'][$id_add] > 12) {
      $_SESSION['cart'][$id_add] = 12; // Ограничиваем до 12
  }
}

// print_r($_SESSION['cart']);

$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'redirect-form.html';
header("Location: $redirect");
exit();
?>
