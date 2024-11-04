<section class="banner-area organic-breadcrumb">
    <div class="container">
        <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
            <div class="col-first">
                <h1>Спасибо за покупку!</h1>
                <nav class="d-flex align-items-center">
                    <a href="index.html">О магазине<span class="lnr lnr-arrow-right"></span></a>
                    <a href="#">Спасибо за покупку</a>
                </nav>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container justify-content-center d-flex">
        <div class="thank-you-message text-center">
            <br><br><br>
            <h2>Ваш заказ №<?php echo $_SESSION['id_order'] ?> успешно оформлен!</h2>
            <p>Мы благодарим вас за покупку. Ваш заказ будет обработан в ближайшее время.</p>
            <img src="./img/thank-you.jpg" alt="Спасибо за покупку" />
            <br><br><br>
            <?php unset($_SESSION['id_order'])?>
        </div>
    </div>
</section>
