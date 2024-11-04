<?php
session_start();
include '../db.php'; // Подключение к базе данных

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, что пользователь авторизован и является клиентом
    if (isset($_SESSION['user']) && $_SESSION['user']['role'] == "customer") {
        // Получаем данные из формы
        $user_id = $_SESSION['user']['id_user'];
        $total = $_POST['total'];

        // Создаем новый заказ
        $stmt = $link->prepare("INSERT INTO `order` (id_user, total) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $total);
        
        if ($stmt->execute()) {
            // Получаем ID созданного заказа
            $order_id = $link->query("SELECT * FROM `order` WHERE `id_user` = '$user_id' AND `total` = '$total' ORDER BY `id_order` DESC")->fetch_assoc()['id_order'];
            // Добавляем товары в заказ
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                // Получаем цену продукта
                $product_query = "SELECT price FROM product WHERE id_product = ?";
                $product_stmt = $link->prepare($product_query);
                $product_stmt->bind_param("i", $product_id);
                $product_stmt->execute();
                $result = $product_stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $product_data = $result->fetch_assoc();
                    
                    // Вставляем товар в order_items
                    $price = (float)$product_data['price'];
                    $insert_item_query = "INSERT INTO order_items (id_order, id_product, quantity, price) VALUES (?, ?, ?, ?)";
                    $insert_item_stmt = $link->prepare($insert_item_query);
                    
                    // Привязываем параметры
                    $insert_item_stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
                    
                    // Выполняем запрос на вставку
                    if (!$insert_item_stmt->execute()) {
                        echo "Ошибка при добавлении товара: " . $insert_item_stmt->error;
                    }
                } else {
                    echo "Товар с ID {$product_id} не найден.";
                }
            }

            // Очистка корзины после оформления заказа
            unset($_SESSION['cart']);
            $_SESSION['id_order'] = $order_id;

            header("Location: ../index.php?page=success"); // Перенаправление на страницу успеха или подтверждения заказа
            exit();
        } else {
            // Обработка ошибки создания заказа (необязательно)
        }
    } else {
        header("Location: ../login.php"); // Перенаправление на страницу входа если не авторизован
        exit();
    }
}
?>
