<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Информация о продукте</h1>
					<nav class="d-flex align-items-center">
						<a href="index.php">О магазине<span class="lnr lnr-arrow-right"></span></a>
						<a href="index.php?page=shop">Каталог товаров<span class="lnr lnr-arrow-right"></span></a>
						<a href="#">Информация о продукте</a>
					</nav>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->


<!--================Single Product Area =================-->
<div class="product_image_area">
		<div class="container">
			<div class="row s_product_inner">
				<div class="col-lg-6">
					<div class="s_Product_carousel">
						<div class="single-prd-item">
							<img class="img-fluid" src="<?php echo "./img/".$product_id['image'] ?>" alt="">
						</div>
						<div class="single-prd-item">
							<img class="img-fluid" src="<?php echo "./img/".$product_id['image'] ?>" alt="">
						</div>
					</div>
				</div>
				<div class="col-lg-5 offset-lg-1">
					<div class="s_product_text">
						<h3><?php echo $product_id['name_product'] ?></h3>
						<h2><?php echo $product_id['price'] ?> ₽</h2>
						<ul class="list">
							<!-- TODO: поправить ссылки -->
							<li><a class="active" href="index.php?page=sort&id_cat=<?php echo $product_id['id_category']?>"><span>Категории:</span> <?php echo $product_id['name_category'] ?></a></li>
							<li><a class="active" href="index.php?page=sort&b_ids[]=<?php echo $product_id['id_brand']?>"><span>Бренд:</span> <?php echo $product_id['name_brand']?></a></li>
						</ul>
						<p>
							<?php echo $product_id['description']?>
						</p>
						<div class="product_count">
							<label for="qty">Количество:</label>
							<input type="number" name="qty" id="sst" value="1" min="1" max="12" title="Количество:" class="input-text qty" onkeydown="return false;">
							<button onclick="var result = document.getElementById('sst'); var sst = parseInt(result.value); if (!isNaN(sst) && sst < 12) result.value = sst + 1; return false;"
								class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
							<button onclick="var result = document.getElementById('sst'); var sst = parseInt(result.value); if (!isNaN(sst) && sst > 1) result.value = sst - 1; return false;"
								class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
						</div>

						<div class="card_area d-flex align-items-center">
							<button class="primary-btn" onclick="addToCart(<?php echo $product_id['id_product']; ?>)">Добавить в корзину</button>
						</div>



						<br><br><br>

					</div>
				</div>
			</div>
		</div>
	</div>
	<!--================End Single Product Area =================-->

	<script>
function addToCart(productId) {
    var qty = document.getElementById('sst').value;
    window.location.href = 'events_cart/add_cart.php?id_product=' + productId + '&qty=' + qty;
}
</script>