<?php
require_once 'common.php';

if(!is_login() || is_admin()) {
	redirect("login_form.php");
}

require_once 'include/header.php';
require_once 'include/navbar.php';


?>

<main>
	<div class="" style="padding-left: 85px; padding-right: 85px;">
		<div class="card my-5 shadow" id="shopping-cart">
			<div class="card-header text-center">
				<h3><strong>GIỎ HÀNG</strong></h3>
			</div>
			<div class="card-body">
				<table class="cart_table table table-hover table-borderless">
					<tr class="cart_table_title bg-info">
						<th width="5%"><strong>ID</strong></th>
						<th width="45%"  colspan="2"><strong>SẢN PHẨM</strong></th>
						<th width="15%" ><strong>GIÁ</strong></th>
						<th width="10%" ><strong>SỐ LƯỢNG</strong></th>
						<th width="15%" ><strong>TỔNG</strong></th>
						<th width="10%" class="text-center" ><strong>TÙY CHỌN</strong></th>
					</tr>
					<!-- 
						/**
						 * #nếu tồn tại giỏ hàng: lặp in ra các sản phẩm
						 * $_SESSION['cart'] là 1 mảng 1 chiều với key = pro_id, value = số lượng sản phẩm
						 * có id = pro_id
						 * note: nếu chưa đăng nhập giỏ hàng sẽ bị xóa ở phần header
						 */
					 -->
					<?php if (!empty($_SESSION['cart'])): ?>
						<?php 
							$total = 0; 
							$totalItem = 0;
						?>
						<?php foreach ($_SESSION['cart'] as $pro_id => $qty): ?>
							<?php
								$getOneProSQL = "SELECT * FROM db_product
								WHERE pro_id  = ?
								";
								$product = s_row($getOneProSQL, [$pro_id]);

								$limit = 10;
								$limit = ($limit > $product['pro_qty']) ? $product['pro_qty'] : $limit;

								if($limit == 0) {
									unset($_SESSION['cart'][$pro_id]);
									continue;
								}

								if($limit < $qty) {
									$qty = $limit;
									$_SESSION['cart'][$pro_id] = $qty;
								}
							?>
							<tr class="cart_table_body">
								<td><?= $product['pro_id']; ?></td>
								<td width="8%">
									<a href="
									<?=
									create_link(
									base_url('product_detail.php'), 
									['proid' => $product['pro_id']]
									); 
									?>
									">
									<img src="image/<?= $product['pro_img']; ?>" alt="" width="100%" class="img-thumbnail">
								</a>
							</td>
							<td>
								<h5>
									<a href=" 
									<?=
									create_link(
									base_url('product_detail.php'), 
									['proid' => $product['pro_id']]
									); 
									?> 
									">
									<?= $product['pro_name']; ?>
								</a>
							</h5>
							<h6><?= $product['pro_color']; ?></h6>
						</td>
						<td>
							<?= number_format($product['pro_price'], 0, ",", "."); ?>
							<span class="unit">&#8363;</span>
						</td>
						<td>
							<!-- ô thay đổi số lượng -->
							<select name="quantity" value="<?= $qty; ?>" class="quantity custom-select-sm" data-pro-id="<?= $product['pro_id']; ?>">
								<?php 
									/**
									 * #Giới hạn số lượng sản phẩm được
									 * @var $limit: giới hạn
									 * #Giới hạn sản phẩm được chọn > số lượng sản phẩm hiện tại
									 * => giới hạn = số lượng sản phẩm hiện tại , ngược lại
									 * #In lần lượt các option từ 1 -> $limit
									 * #nếu option có giá trị = số lượng của sản phẩm trong giỏ hàng
									 * =>selected option đó
									 * 
									 */
									for ($i = 1; $i <= $limit ; $i++) { 
										if($i == $qty) {
											echo "	 
												<option value='$i' selected>$i</option>
											";
										} else {
											echo "	 
												<option value='$i'>$i</option>
											";
										}
									}
								 ?>
							</select>
						</td>

						<td>
							<?=number_format($product['pro_price'] * $qty, 0, ",", "."); ?>
							<span class="unit">&#8363;</span>
						</td>
						<td class="text-center">
							<button class="delete btn btn-danger" id="<?= $product['pro_id']; ?>" data-pro-id="<?= $product['pro_id']; ?>">
								<i class="far fa-trash-alt"></i>
							</button>
						</td>
					</tr>
					<?php 
						$total     += $product['pro_price'] * $qty;
						$totalItem += $qty;
					?>
				<?php endforeach ?>

				<tr class="all_total">
					<td colspan="5" class="text-right"><strong>TỔNG SỐ LƯỢNG:</strong></td>
					<td id="totalItem">
						
					</td>
					<td></td>
				</tr>
				<tr class="all_total">
					<td colspan="5" class="text-right"><strong>TỔNG TIỀN:</strong></td>
					<td>
						<?= number_format($total, 0, ",", "."); ?>
						<span class="unit">&#8363;</span>
					</td>
					<td></td>
				</tr>
				<?php else: ?>
					<tr>
						<td class='text-center' colspan='7'>
							<h5>GIỎ HÀNG TRỐNG</h5>
						</td>
					</tr>
				<?php endif ?>
			</table>
		</div>

		<div class="card-footer d-flex justify-content-between">
			<a class="btn_buy_more btn btn-danger" href="<?= base_url('product.php') ?>">
				<strong>MUA THÊM</strong>
			</a>
			<?php if (!empty($_SESSION['cart'])): ?>
				<a class="btn_check_out btn btn-warning">
					<strong>CHECK OUT</strong>
				</a>
			<?php endif ?>
		</div>

	</div>
	<script>
		/**
		 * kết quả trả về từ cart.php gồm thuộc tính tổng số sản phẩm trong giỏ hàng
		 * -> cập nhật giá trị hiển thị trên icon giỏ hàng
		 */
		$(function() {
			 fetch_cart();

			/**
			 * giá trị hàng tổng số lượng  trong bảng giỏ hàng lấy từ chỉ số của
			 * giỏ hàng trên thanh menu:
			 * vì chỉ số của giỏ hàng trên thanh menu thay đổi ngay lập tức khi giỏ hàng
			 * có sự thay đổi
			 */
			$('#totalItem').text($('#shoppingCartIndex').text() + " sản phẩm");

			$(document).on('click', '.btn_check_out', function() {
				let checkOutOK = sendAJax(
					'get_cart.php',
					'post',
					'text',
					{action:"check_out"}
				);
				if(checkOutOK == '1') {
					window.location = "checkout.php";
				} else {
					alert("CÓ SẢN PHẨM TRONG GIỎ ĐÃ HẾT HÀNG HOẶC SỐ LƯỢNG TỒN KHO KHÔNG ĐỦ");
					fetch_cart();
				}
			});

			//thay đổi số lượng sản phẩm
			$(document).on('change', '.quantity', function(e) {
				let proID = $(this).data('pro-id');
				let quantity = $(this).val();
				let action = "change";
				let data = {proid:proID, quantity:quantity, action:action};
				if(proID && quantity) {
					let change_qty = $.ajax({
						url: "cart.php",
						data: data,
						method: "POST",
						dataType: "json"
					});
						//thành công
						change_qty.done(function(res) {
							if(res.notice != "") {
								alert(res.notice);
							}
							$('#shopping-cart .card-body').html(res.html);
							if(res.totalItem > 0) {
								$('#shoppingCartIndex').text(res.totalItem);
								$('#modal_cart').show().find('.badge').text(res.totalItem);
							} else {
								$('#shoppingCartIndex').text(0);
							}
						});
						//thất bại
						change_qty.fail(function(a, b, c) {
							console.log(a, b, c);
						});
					}
				});
				//xóa sản phẩm
				$(document).on('click', '.delete', function() {
					let proID = $(this).data('pro-id');
					console.log(proID);
					let action = "delete";
					let data = {proid:proID, action:action};
					if(proID && action && confirm("bạn có muốn xóa")) {
						let del = $.ajax({
							url: "cart.php",
							method: "POST",
							data: data,
							dataType: "json"
						});
						//thành công'
						del.done(function(res) {
							console.log(res.html);
							$('#shopping-cart .card-body').html(res.html);
							if(res.totalItem > 0) {
								$('#shoppingCartIndex').text(res.totalItem);
								$('#modal_cart').show().find('.badge').text(res.totalItem);
								$('.btn_check_out').show();
							} else {
								$('#shoppingCartIndex').text(0);
								$('.btn_check_out').hide();
								$('#modal_cart').hide();
							}
						});
						//thất bại
						del.fail(function(a, b, c) {
							console.log(a, b, c);
						});
					}
				})
			});
		</script>
	</div>
</main>

<?php require_once 'include/footer.php'; ?>