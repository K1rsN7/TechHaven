<!-- Start Banner Area -->
<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Контакты</h1>
					<nav class="d-flex align-items-center">
						<a href="index.html">О магазине<span class="lnr lnr-arrow-right"></span></a>
						<a href="category.html">Контакты</a>
					</nav>
				</div>
			</div>
			
		</div>
	</section>
<!-- End Banner Area -->

<!--- Start about dream --->
<section>
<div class="container mt-5">
    <h1 class="text-center">О компании Tech Haven</h1>
    <p class="lead text-center">Мы объединены общей страстью к созданию лучших компьютеров и комплектующих.</p>
    <img class="img-fluid d-block mx-auto" src="./img/team.jpg" alt="Команда Tech Haven">

    <div class="row align-items-center mt-4">
        <div class="col-md-6">
            <h2>Компания Tech Haven</h2>
            <p>Компания Tech Haven была основана 15 лет назад с целью предоставить клиентам качественные технологии и решения по доступным ценам. Мы являемся надежным партнером для всех, кто ищет высокопроизводительные компьютеры и комплектующие.</p>
            <p>Главный офис Tech Haven и производственный центр расположены в Нижнем Новгороде. Шоурум с компьютерами и периферией находятся в Нижнем Новгороде. Мы осуществляем доставку компьютеров по всей России и миру. Наша компания работает как с частными, так и с юридическими лицами.</p>
        </div>
        <div class="col-md-6 text-center">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="./img/office.jpg" class="d-block w-100" alt="Офис Tech Haven">
                    </div>
                    <div class="carousel-item">
                        <img src="./img/office2.jpg" class="d-block w-100" alt="Офис Tech Haven">
                    </div>
                    <div class="carousel-item">
                        <img src="./img/office3.jpg" class="d-block w-100" alt="Офис Tech Haven">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Предыдущий</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Следующий</span>
                </a>
            </div>
        </div>
    </div>
	<div class="container mt-5">
    <div class="row">
		<div class="col-md-4 text-center">
            <img src="./img/avtor.jpg" alt="Компания Tech Haven - Основатель Кирилл Сухоруков" class="img-fluid" width="330">
        </div>
        <div class="col-md-8">
            <h2 >Здравствуйте, я – Сухоруков Кирилл,<br> основатель компании Tech Haven</h2>
            <ul class="list-unstyled mt-8">
                <li>
                    <p>Я прошел все этапы работы: сам продавал, собирал и доставлял компьютеры клиентам.<br> Как никто другой знаю, как это делать правильно.</p>
                </li>
                <li>
                    <p>За 15 лет работы мы построили компанию №1 среди<br> производителей компьютеров среднего класса в России.</p>
                </li>
                <li>
                    <p>За это время мы собрали мощную команду единомышленников,<br> с которыми дружим и работаем с самого основания компании.</p>
                </li>
            </ul>
        </div>
    </div>
    </div>
    <br>
    <br>
    <div id="testimonialCarousel" class="carousel slide" data-ride="carousel">
        <h2 align="center">Отзывы наших покупателей</h2>
        <div class="carousel-inner">
            <?php
                $flag = true;
                foreach ($sql_review as $review){
                    $activeClass = $flag ? 'active' : '';
                    echo '<div class="carousel-item '.$activeClass.'">
                            <div class="review_item">
                                <div class="media">
                                    <div class="d-flex">
                                        <img src="./img/people/'.$review["image_user"].'" class="rounded-circle" style="width: 70px; height: 70px;" alt="">
                                    </div>
                                    <div class="media-body">
                                        <h4>'.$review['username'].'</h4>';
                                        for ($i = 0; $i < 5; $i++) {
                                            if ($i < $review["rating"]) {
                                                echo '<i class="fa fa-star"></i>'; // Полная звезда
                                            } else {
                                                echo '<i class="fa fa-star-o"></i>'; // Пустая звезда
                                            }
                                        }
                                    echo '</div>
                                </div>
                                <p>'.$review['comment'].'</p>
                            </div>
                        </div>';
                        $flag = false;
                }
            ?>
            <a class="carousel-control-prev" href="#testimonialCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Предыдущий</span>
                </a>
                <a class="carousel-control-next" href="#testimonialCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Следующий</span>
                </a>
            <!-- Добавьте дополнительные отзывы по аналогии -->
    </div>
   <br>
    <!-- Контролы для навигации -->
</div>


<br>

</section>
<!--- End about dream --->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
