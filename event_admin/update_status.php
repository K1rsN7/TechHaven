<?php
session_start();
require('../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_order = $_POST['id_order'];
    $new_status = $_POST['status'];

    // Обновление статуса заказа в базе данных
    $query = "UPDATE `order` SET status = ? WHERE id_order = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("si", $new_status, $id_order);
    date_default_timezone_set('Europe/Moscow');
    $_SESSION['message_admin_time'] = date('Y-m-d H:i:s');
    if ($stmt->execute()) {
        $_SESSION['message_admin'] = "Статус заказа успешно обновлён.";
    } else {
        $_SESSION['message_admin'] = "Ошибка при обновлении статуса заказа.";
    }

    $stmt->close();
}

// Перенаправление обратно на страницу с заказами
header("Location: ../index.php?page=admin"); // Замените на вашу страницу с заказами
exit();
?>
