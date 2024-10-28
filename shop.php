<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Каталог товаров</h1>
					<nav class="d-flex align-items-center">
						<a href="index.php?page=content">О магазине<span class="lnr lnr-arrow-right"></span></a>
						<?php 
							$id_cat=$_GET['id_cat'];
							if (isset($id_cat)){
								foreach ($sql_category as $category){
									if ($category['id_category'] == $id_cat){
										$name_category = $category['name_category'];
										break;
									}
								}
								echo '<a href="index.php?page=shop">Каталог товаров<span class="lnr lnr-arrow-right"></span</a> <a href="page=sort&id_cat='.$id_cat.'">'.$name_category.'</a>';
							}
							else{
								echo '<a href="index.php?page=shop">Каталог товаров</a>';
							}
						?>
					</nav>
				</div>
			</div>
		</div>
</section>

<div class="container">
		<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-5">
				<div class="sidebar-categories">
					<div class="head">Категории</div>
					<ul class="main-categories">

					<?php
						foreach ($sql_category as $cat):
							?>
						<li class="main-nav-list">
							<a href="index.php?page=sort&id_cat=<?php echo $cat['id_category'] ?>"><?php echo $cat['name_category'] ?><span class="number">(<?php echo $cat['count']?>)</span></a>
						</li>
					<?php
						endforeach;
					?>
					</ul>
				</div>
				<div class="sidebar-filter mt-50">
					<div class="top-filter-head">Фильтры</div>
					<div class="common-filter">
						<div class="head">Срок доставки</div>
						<form action="#">
							<!-- TODO: сделать взаимодействие для изменения списка -->
							<ul>
							<?php
								foreach ($sql_delivery as $delivery):
									?>
									<li class="filter-list"><input class="pixel-checkbox" 
								type="checkbox" id="black" name="color"><label 
								for="black"><?php echo "За ".$delivery['delivery_days']." дней" ?><span> (<?php echo $delivery['count']?>)</span></label></li>
								<?php
									endforeach;
								?>
							</ul>
						</form>
					</div>
					<div class="common-filter">
						<div class="head">Бренды</div>
						<form action="#">
							<!-- TODO: поместить в крутящийся список -->
							<!-- TODO: сделать взаимодействие для изменения списка -->
							<ul>
							<?php
								foreach ($sql_brands as $brand):
									?>
									<li class="filter-list"><input class="pixel-checkbox" 
								type="checkbox" id="black" name="color"><label 
								for="black"><?php echo $brand['name_brand'] ?><span> (<?php echo $brand['count']?>)</span></label></li>
								<?php
									endforeach;
								?>
							</ul>
						</form>
					</div>
					<!-- TODO: удалить, если не надо будет -->
					<!-- <div class="common-filter">
						<div class="head">Цена</div>
						<div class="price-range-area">
							<div id="price-range"></div>
							<div class="value-wrapper d-flex">
								<div class="price">Цена</div>
								<span>₽</span>
								<div id="lower-value"></div>
								<div class="to">до</div>
								<span>₽</span>
								<div id="upper-value"></div>
							</div>
						</div>
					</div> -->
				</div>
			</div>
			<div class="col-xl-9 col-lg-8 col-md-7">
				<!-- Start Filter Bar -->
				<div class="filter-bar d-flex flex-wrap align-items-center">
					<div class="sorting">
						<select onchange="location=value">
							<option value="index.php?page=sort">По индексу товара</option>
							<option value="index.php?page=sort<?php if ($_GET['id_cat']) echo '&id_cat='.$_GET['id_cat']?>&id_sort=1"<?php if ($_GET["id_sort"] == 1) echo 'selected'?>>Название от А до Я</option>
							<option value="index.php?page=sort<?php if ($_GET['id_cat']) echo '&id_cat='.$_GET['id_cat']?>&id_sort=2"<?php if ($_GET["id_sort"] == 2) echo 'selected'?>>Название от Я до А</option>
							<option value="index.php?page=sort<?php if ($_GET['id_cat']) echo '&id_cat='.$_GET['id_cat']?>&id_sort=3"<?php if ($_GET["id_sort"] == 3) echo 'selected'?>>Цена по возрастанию</option>
							<option value="index.php?page=sort<?php if ($_GET['id_cat']) echo '&id_cat='.$_GET['id_cat']?>&id_sort=4"<?php if ($_GET["id_sort"] == 4) echo 'selected'?>>Цена по убыванию</option>
						</select>
					</div>
					<div class="sorting mr-auto">
				
					</div>
					<div class="pagination">
						<a href="#" class="prev-arrow"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
						<a href="#" class="active">1</a>
						<a href="#">2</a>
						<a href="#">3</a>
						<a href="#" class="dot-dot"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
						<a href="#">6</a>
						<a href="#" class="next-arrow"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
					</div>
				</div>
				<!-- End Filter Bar -->
				<!-- Start Best Seller -->
				<section class="lattest-product-area pb-40 category-list">
					<div class="row">
						<!-- single product -->
						<?php
							$count = 0;
							$current_page = isset($_GET['current_page']) ? (int)$_GET['current_page'] : 1;
							$products_per_page = $_SESSION['count_products_on_page']; 

							foreach ($sql_product as $product):
								$count++;
								if ($count > ($current_page - 1) * $products_per_page && $count <= $current_page * $products_per_page) {
						?>
						<div class="col-lg-4 col-md-6">
							<div class="single-product">
								<img class="img-fluid" src="./img/<?php echo $product['image'] ?>" alt="">
								<div class="product-details">
									<h6><?php echo $product['name_product'] ?></h6>
									<div class="price">
										<br>
										<h4><?php echo $product['price'] ?>₽</h4>
										<br>
										<h7><?php 
										echo "Бренд: ".$product['name_brand'];
										?></h7>
										<br>
										<h7><?php echo 'Время доставки: '.$product['delivery_days'].' (дней)'?></h7>
									</div>
									<div class="prd-bottom">
										<a href="events_cart/add_cart.php?id_product=<?php echo $product['id_product'] ?>" class="social-info">
										<span class="ti-bag"></span>
											<p class="hover-text">В корзину</p>
										</a>
										<a href="index.php?page=single-product&id_product=<?php echo $product['id_product'] ?>" class="social-info">
											<span class="lnr lnr-move"></span>
											<p class="hover-text">Подробнее</p>
										</a>
									</div>
								</div>
							</div>
						</div>
						<?php
								}
								endforeach;
						?>
					</div>
				</section>
				<!-- End Best Seller -->
				<!-- Start Filter Bar -->
				<div class="filter-bar d-flex flex-wrap align-items-center">
					<div class="sorting mr-auto">
						<select onchange="window.location.href=this.value">
							<option value="index.php?page=sort<?php if ($_GET['id_cat']) echo '&id_cat='.$_GET['id_cat']?><?php if ($_GET["id_sort"]) echo "&id_sort=".$_GET["id_sort"]?>&count_products_on_page=6" <?php if($_SESSION['count_products_on_page'] == 6) echo 'selected'?>>Показать 6</option>
							<option value="index.php?page=sort<?php if ($_GET['id_cat']) echo '&id_cat='.$_GET['id_cat']?><?php if ($_GET["id_sort"]) echo "&id_sort=".$_GET["id_sort"]?>&count_products_on_page=12" <?php if($_SESSION['count_products_on_page'] == 12) echo 'selected'?>>Показать 12</option>
							<option value="index.php?page=sort<?php if ($_GET['id_cat']) echo '&id_cat='.$_GET['id_cat']?><?php if ($_GET["id_sort"]) echo "&id_sort=".$_GET["id_sort"]?>&count_products_on_page=18" <?php if($_SESSION['count_products_on_page'] == 18) echo 'selected'?>>Показать 18</option>
							<option value="index.php?page=sort<?php if ($_GET['id_cat']) echo '&id_cat='.$_GET['id_cat']?><?php if ($_GET["id_sort"]) echo "&id_sort=".$_GET["id_sort"]?>&count_products_on_page=24" <?php if($_SESSION['count_products_on_page'] == 24) echo 'selected'?>>Показать 24</option>
						</select>
					</div>
					<div class="pagination">
						<a href="#" class="prev-arrow"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
						<a href="#" class="active">1</a>
						<a href="#">2</a>
						<a href="#">3</a>
						<a href="#" class="dot-dot"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
						<a href="#">6</a>
						<a href="#" class="next-arrow"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
					</div>
				</div>
				<!-- End Filter Bar -->
			</div>
		</div>
	</div>