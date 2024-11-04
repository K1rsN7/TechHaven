<section class="banner-area organic-breadcrumb">
  <div class="container">
   <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
    <div class="col-first">
     <h1>Личный кабинет</h1>
     <nav class="d-flex align-items-center">
      <a href="index.php">О магазине<span class="lnr lnr-arrow-right"></span></a>
      <a href="#">Личный кабинет</a>
     </nav>
    </div>
   </div>
  </div>
</section>
<section>
    <br><br><br>
    
    <div class="container">
        <h2 align="center">Ваши заказы</h2>
        <div class="text-center">
            <form action="events_user/logout.php" method="post">
                <button type="submit" class="btn btn-danger">Выйти из профиля</button>
            </form>
        </div>
        <br>

        <?php if ($_SESSION['my_orders']->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Номер заказа</th>
                        <th>Дата заказа</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $_SESSION['my_orders']->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['id_order']); ?></td>
                            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($order['total'], 2, '.', '')); ?> ₽</td>
                            <td>
                            <?php
                            switch ($order['status']) {
                                case 'pending':
                                    echo 'В ожидании';
                                    break;
                                case 'completed':
                                    echo 'Завершен';
                                    break;
                                case 'canceled':
                                    echo 'Отменён';
                                    break;
                                case 'on the way':
                                    echo 'В пути';
                                    break;
                                }
                            ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p align="center">У вас нет заказов.</p>
        <?php endif; ?>
    </div>
    <br><br><br>
</section>
