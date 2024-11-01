<!-- Start Banner Area -->
<section class="banner-area organic-breadcrumb">
  <div class="container">
   <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
    <div class="col-first">
     <h1>Авторизация</h1>
     <nav class="d-flex align-items-center">
      <a href="index.php">О магазине<span class="lnr lnr-arrow-right"></span></a>
      <a href="#">Авторизация</a>
     </nav>
    </div>
   </div>
  </div>
 </section>
 <!-- End Banner Area -->
<!--================Login Box Area =================-->
<section class="login_box_area section_gap">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="login_box_img">
						<img class="img-fluid" src="img/login.jpg" alt="">
						<div class="hover">
						<h4>Впервые на нашем сайте?</h4>
						<p>Добро пожаловать в мир технологий! У нас вы найдете все необходимое для создания идеального компьютера: от комплектующих до готовых решений.</p>
						<a class="primary-btn" href="index.php?page=registration">Создать аккаунт</a>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="login_form_inner">
						<br>
						<br>
						<br>
						<br>
						<h3>Вход</h3>
						<form class="row login_form" action="events_user/signin.php" method="post" id="contactForm" novalidate="novalidate">
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" id="login" name="login" placeholder="Логин" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Логин'">
							</div>
							<div class="col-md-12 form-group">
								<input type="password" class="form-control" id="password" name="password" placeholder="Пароль" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Пароль'">
							</div>
							<div class="col-md-12 form-group">
								<div class="creat_account">
									<?php
									if (isset($_SESSION['message'])) {
										echo $_SESSION['message'];
									}
									 ?>
								</div>
							</div>
							<div class="col-md-12 form-group">
								<button type="submit" value="submit" class="primary-btn">Войти</button>
								
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->