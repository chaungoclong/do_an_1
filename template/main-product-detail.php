//lấy các ảnh của sản phẩm, thể loại, hãng
	<?php 
	if(!empty($product)) {
		$getImgProSQL = "SELECT img_url FROM db_image WHERE pro_id = ? LIMIT 4";
		$listImg = db_get($getImgProSQL, 0, [$proID], "i");
		
		$getCatProSQL = "SELECT cat_name FROM db_category WHERE cat_id = ?";
		$category = s_cell($getCatProSQL, [$product['cat_id']], "i");
		
		$getBraProSQL = "SELECT bra_name FROM db_brand WHERE bra_id = ?";
		$brand = s_cell($getBraProSQL, [$product['bra_id']], "i");
	}
	?>
	
	<div style="padding-left: 85px; padding-right: 85px;" class="">
		<h1 class="text-center mt-3">PRODUCT DETAIL</h1>
		<section>
			<div class="row p-0 m-0 bg-light">
				<!-- product image wrapper -->
				<div id="product_image" class="col-6 p-3">
					<!-- product image content -->
					<div class="card text-center py-3 h-100">
						<div class="row p-0 m-0">
							<div class="col-12 mb-3">
								<img src="<?= $product['pro_img']; ?>" alt="" class="big_img card-img-top w-75" style="border-radius: 5px;">
							</div>
							<div class="col-12">
								<div class="row">
									<?php if (!empty($listImg)): ?>
										<?php foreach ($listImg as $key => $img): ?>
											<div class="col-3">
												<img src="<?= $img['img_url']; ?>" class="small_img img-fluid" alt="" style="border-radius: 5px;">
											</div>
										<?php endforeach ?>
									<?php endif ?>
								</div>
							</div>
						</div>
					</div>
					<!-- /product image content -->
				</div>
				<!-- /product image wrapper -->
				<div class="col-6 p-3">
					<div class="card h-100">
						<div id="notice" class="card-header text-center"></div>
						<div class="card-body">
							<!-- product name -->
							<h2 style="color:blue;" class="card-title">
								<strong>
									<?= ucwords(strtolower($product['pro_name'])); ?>
								</strong>
							</h2>
							<div class="rateAvg"></div>
							<hr>
							<h3 class="card-text my-4" style="color:red;">
								<strong>
									<?= !empty($product['pro_price']) ?
									number_format($product['pro_price'], 0, ",", ".") : ""; ?>
									&#8363;
								</strong>
							</h3>
							<div class="table-responsive mb-3">
								<table class="table table-sm table-borderless mb-0">
									<tr>
										<th class="pl-0 w-25" scope="row"><strong>Loại sản phẩm:</strong></th>
										<td><?= !empty($category) ? $category : ""; ?></td>
									</tr>
									<tr>
										<th class="pl-0 w-25" scope="row"><strong>Hãng sản xuất:</strong></th>
										<td><?= !empty($brand) ? $brand : ""; ?></td>
									</tr>
									<tr>
										<th class="pl-0 w-25" scope="row"><strong>Màu:</strong></th>
										<td>
											<?= !empty($product['pro_color']) ?
											$product['pro_color'] : ""; ?>
										</td>
									</tr>
									<tr>
										<th class="pl-0 w-25" scope="row"><strong>Tình trạng:</strong></th>
										<td>
											<?= $product['pro_qty'] ?
											"còn hàng(" . $product['pro_qty'] . " sản phẩm)": "hết hàng"; ?>
										</td>
									</tr>
								</table>
							</div>
							<hr>
							<p>
								<?= !empty($product['pro_short_desc']) ?
								$product['pro_short_desc'] : ""; ?>
							</p>
							<hr>
							<div class="action border-1">
								<div class="get_quantity d-flex mb-3">
									<button class="minus btn"><i class="fas fa-minus"></i></button>
									<input type="number" min="0" name="quantity" value="1" class="quantity text-center">
									<button class="plus btn"><i class="fas fa-plus"></i></button>
								</div>
								<button class="btn_add_cart btn btn-primary"><strong>THÊM VÀO GIỎ</strong></button>
								<button class="btn_wishlist btn btn-danger"><strong><i class="fas fa-heart"></i></strong></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		
		<!-- tab info -->
		<section id="product_tab" class="border p-3 my-5">
			<!-- tab index -->
			<ul class="nav nav-justified nav-tabs">
				<li class="nav-item">
					<a href="#desc" class="nav-link active" data-toggle="tab">MÔ TẢ</a>
				</li>
				<li class="nav-item">
					<a href="#info" class="nav-link" data-toggle="tab">THÔNG SỐ KỸ THUẬT</a>
				</li>
				<li class="nav-item">
					<a href="#rate" class="nav-link" data-toggle="tab">ĐÁNH GIÁ (<span class="qtyRate"></span>)</a>
				</li>
			</ul>
			<!-- tab content -->
			<div class="tab-content">
				<div id="desc" class="active tab-pane p-3">
					<h6><strong>MÔ TẢ</strong></h6>
					<?= !empty($product['pro_desc']) ? $product['pro_desc'] : "";  ?>
				</div>
				<div id="info" class="tab-pane fadt p-3">
					<h6><strong>THÔNG SỐ KĨ THUẬT</strong></h6>
				</div>
				<div id="rate" class="tab-pane fade py-3">
					<!-- đánh giá trung bình-->
					<div class="rate_title row m-0 mb-5">
						<div class="col-12 p-0 mb-3">
							<h5 class="text-uppercase">có <span class="qtyRate"></span> đánh giá cho <?= $product['pro_name']; ?></h5>
						</div>
						<div class="col-12 p-0 rateAvg">
						</div>

					</div>

					<!-- tạo đánh giá -->
					<div class= "send_rate row m-0 mb-5 bg-white shadow">

						<div class="col-4 text-center py-3">
							<p class="p-0">
								<strong>Bạn đánh giá sản phẩm này bao nhiêu sao?</strong>
							</p>
							<!-- chọn số sao -->
							<div class="choose_star">
								<button class="btn starr m-1" data-rate="1" id="star_rate_1">
									<i class="fas fa-star"></i>
								</button>
								<button class="btn starr m-1" data-rate="2" id="star_rate_2">
									<i class="fas fa-star"></i>
								</button>
								<button class="btn  starr m-1" data-rate="3" id="star_rate_3">
									<i class="fas fa-star"></i>
								</button>
								<button class="btn  starr m-1" data-rate="4" id="star_rate_4">
									<i class="fas fa-star"></i>
								</button>
								<button class="btn  starr m-1" data-rate="5" id="star_rate_5">
									<i class="fas fa-star"></i>
								</button>
							</div>
						</div>

						<!-- viết bình luận -->
						<div class="create_rate col-8 py-3">
							<form action="" id="formRate" class="w-100 position-relative">
								<div class="form-group">
									<textarea class="form-control" rows="3" cols="55" id="rateContent" name="rateContent"></textarea>
									<input type="hidden" id="rateValue" name="rateValue">
								</div>

								<button class="btn btn-primary position-absolute" id="sendRate" type="button"
								style="top: 20%; right: 10px;">SEND</button>
							</form>
						</div>
					</div>
					<!-- /tạo đánh giá -->
					
					<!-- hiển thị các đánh giá -->
					<div class="show_rate">
						<!-- display rate -->
					</div>
					<!-- /hiển thị các đánh giá -->
				</div>
			</div>
		</section>
		<!-- /tab info -->

		<!-- product -->
		<?php
		$getRelatedProSQL = "
		SELECT * FROM db_product
		WHERE cat_id = ? AND pro_id != ?
		";
		$listRelatedPro = db_get(
			$getRelatedProSQL,
			0,
			[$product['cat_id'], $product['pro_id']],
			"ii"
		);
		//vd($listRelatedPro);
		?>
		<section class="product py-5">
			<h2 class="text-center mb-3">SẢN PHẨM LIÊN QUAN</h2>
			<div class="list_product_body">
				<!-- list products bar -->
				<div class="product_bar bg-info px-2 py-2 d-flex justify-content-between">
					<span class="badge  bg-faded">
						<?= !empty($listRelatedPro) ? count($listRelatedPro) : "0"; ?>
						sản phẩm liên quan
					</span>
					<a href="
					<?php
					echo create_link(
					base_url("product.php"),
					['cat' => $product['cat_id']]
					);
					?>
					"
					class="badge badge-pill bg-danger">Xem tất cả</a>
				</div>
				<!-- list products -->
				<div class="card-group">
					<?php if (!empty($listRelatedPro)): ?>
						<?php
						$limit = 4;
						$count = 0;
						?>
						<?php foreach ($listRelatedPro as $key => $relatedPro): ?>
							<div class="card text-center" style="max-width: 25%;">
								<?php if (empty($relatedPro['pro_qty'])): ?>
									<span class="product_status badge badge-pill badge-warning">
										bán hết
									</span>
								<?php endif ?>
								<a href="
								<?php
								echo create_link(
								base_url("product_detail.php"),
								['proid' => $relatedPro['pro_id']]
								);
								?>
								">
								<img src="<?= $relatedPro['pro_img']; ?>" alt="" class="card-img-top">
							</a>
							<div class="card-body">
								<h5 class="card-title text-uppercase">
									<a href="
									<?php
									echo create_link(
									base_url("product_detail.php"),
									["proid" => $relatedPro['pro_id']]
									);
									?>
									">
									<?= $relatedPro['pro_name']; ?>
								</a>
							</h5>
							<p class="text-uppercase card-subtitle">
								<?= $category; ?>
							</p>
							<h6 class="text-danger">
								<strong>
									<?= number_format($relatedPro['pro_price'], 0, ",", "."); ?>
									&#8363;
								</strong>
							</h6>
							<hr>
							<!-- thêm vào giỏ hàng -->
							<?php if ($relatedPro['pro_qty']): ?>

								<a class="btn_add_cart_out btn btn-success text-light" data-pro-id="<?= $relatedPro['pro_id']; ?>"
									data-toggle="tooltip" data-placement="top" title="Thêm vào giỏ hàng"
									>
									<i class="fas fa-cart-plus fa-lg"></i>
								</a>

							<?php endif ?>

							<!-- xem chi tiết sản phẩm -->
							<a href='<?= create_link(base_url("product_detail.php"), ["proid"=> $relatedPro["pro_id"]]); ?>' class="btn btn-default btn-primary" data-toggle="tooltip" data-placement="top" title="chi tiết sản phẩm">
								<i class="far fa-eye fa-lg"></i>
							</a>

							<!-- danh sách yêu thích -->
							<a href='<?= create_link(base_url("wishlist.php"), ["proid"=> $relatedPro["pro_id"]]); ?>' class="btn btn-default btn-danger"
								data-toggle="tooltip" data-placement="top" title="Thêm vào danh sách yêu thích">
								<i class="far fa-heart fa-lg"></i>
							</a>
						</div>
					</div>
					<?php
					++$count;
					if($count === $limit) {
						break;
					}
					?>
				<?php endforeach ?>
			<?php endif ?>
		</div>
	</div>
</section>
<!-- /product -->
</div>