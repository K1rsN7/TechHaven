<!--================Cart Area =================-->
<section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Корзина</h1>
                    <nav class="d-flex align-items-center">
                        <a href="index.php">О магазине<span class="lnr lnr-arrow-right"></span></a>
                        <a href="#">Корзина</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>

<section class="cart_area">
        <div class="container">
            <div class="cart_inner">
                 <?php
            if (empty($_SESSION['cart'])) {
                echo '<h2 align="center">Корзина пуста</h2><br></div></div></section>';
                return;
            }
            ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Продукт</th>
                                <th scope="col">Цена</th>
                                <th scope="col">Количество</th>
                                <th scope="col">Сумма</th>
                            </tr>
                        </thead>
                        <tbody>

            <?php
            // Продукты из корзины
            $sql_product_m= $_SESSION['all_products'];
            $add_cart = $_SESSION['cart'];
            $total_sum = 0; // Переменная для хранения итоговой суммы

            foreach ($add_cart as $key => $value):
                $a = $key;
                $col = $value;
                foreach ($sql_product_m as $product_m) {
                    if ($product_m['id_product'] == $a) {
                        $cart_prod = $product_m;
                        break;
                    }
                }
                $product_price = $cart_prod['price'];
                $product_total = $product_price * $col; // Сумма для текущего продукта
                $total_sum += $product_total; // Добавляем к общей сумме
            ?>
            <tr>
                <td>
                    <div class="media">
                        <div class="d-flex">
                            <img src="./img/<?php echo $cart_prod['image'] ?>" alt="" width=100px>
                        </div>
                        <div class="media-body">
                            <p><?php echo $cart_prod['name_product'] ?></p>
                        </div>
                    </div>
                </td>
                <td>
                    <h5 id="price_<?php echo $cart_prod['id_product']; ?>"><?php echo number_format($product_price, 2, '.', ''); ?> ₽</h5>
                </td>
                <td>
                    <div class="product_count">
                        <input type="text" name="qty" id="<?php echo $cart_prod['id_product']?>" maxlength="12" value="<?php echo $col?>" title="Quantity:" class="input-text qty" onkeydown="return false;" 
                               oninput="updateQuantity('<?php echo $cart_prod['id_product']; ?>', this.value)">
                        <button type="button" onclick="
                            var result = document.getElementById('<?php echo $cart_prod['id_product']?>');
                            var sst = parseInt(result.value);
                            if (!isNaN(sst) && sst < 12) result.value++;
                            updateQuantity('<?php echo $cart_prod['id_product']; ?>', result.value);
                            return false;" class="increase items-count"><i class="lnr lnr-chevron-up"></i></button>
                        <button type="button" onclick="
                            var result = document.getElementById('<?php echo $cart_prod['id_product']?>');
                            var sst = parseInt(result.value);
                            if (!isNaN(sst) && sst > 0) result.value--;
                            updateQuantity('<?php echo $cart_prod['id_product']; ?>', result.value);
                            return false;" class="reduced items-count"><i class="lnr lnr-chevron-down"></i></button>
                    </div>
                </div>
                </td>
                <td>
                    <h5 class="end_price_product" id="<?php echo $cart_prod['id_product']."1"?>"><?php echo number_format($product_total, 2, '.', ''); ?> ₽</h5>
                </td>
            </tr>
            <?php endforeach; ?>

            <tr>
                
                <td></td>
                <td></td>
                <td><h5>Итоговая сумма</h5></td>
                <td><h5 id="total_sum"><?php echo number_format($total_sum, 2, '.', ''); ?> ₽</h5></td> <!-- Итоговая сумма -->
            </tr>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == "customer"): ?>
                        <tr>
                            <td colspan="3"></td>
                            <td>
                                <form action="events_cart/checkout.php" method="POST">
                                    <input type="hidden" name="total" value="<?php echo number_format($total_sum, 2, '.', ''); ?>">
                                    <button type="submit" class="btn btn-primary">Оформить заказ</button> 
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
            <div class="card_area d-flex align-items-center">
                <form action="events_cart/clear_cart.php" method="POST">
                    <button type="submit" class="btn btn-danger">Очистить корзину</button>
                </form>
            </div>
                        </tbody>
                        
                    </table>
                    
                </div>
            </div>
        </div>
    </section>
    <!--================End Cart Area =================-->

    <!-- JavaScript для обновления суммы -->
    <script type="text/javascript">

    function updateOverallTotal() {
        var totalSum = 0;
        
        // Получаем все элементы h5 с классом end_price_product
        var sumElements = document.querySelectorAll('h5.end_price_product');

        sumElements.forEach(function(sumElement) {
            var sumValue = parseFloat(sumElement.innerText.replace(' ₽', '').replace(',', '.')); // Убираем символы и преобразуем в число
            totalSum += sumValue;
        });

        // Обновляем отображение итоговой суммы
        document.getElementById('total_sum').innerText = totalSum.toFixed(2) + ' ₽';
    }

    function updateQuantity(productId, qty) {
        // Проверяем, что количество является числом и не меньше 0
        if (isNaN(qty) || qty < 0) {
            qty = 0; // Устанавливаем количество в 0
        }

        // Отправляем AJAX-запрос на сервер для обновления количества
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "events_cart/update_cart.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // Обновляем сумму для текущего продукта
                var priceValue = parseFloat(document.getElementById("price_" + productId).innerText.replace(' ₽', '').replace(',', '.'));
                var sumElement = document.getElementById(productId + "1");
                var productTotal = qty * priceValue;

                // Обновляем сумму для текущего продукта
                sumElement.innerText = productTotal.toFixed(2) + ' ₽';

                // Обновляем общую сумму
                updateOverallTotal();
            }
        };

        xhr.send("id_product=" + productId + "&qty=" + qty);
    }


    </script>