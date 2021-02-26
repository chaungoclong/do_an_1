<?php
require_once '../common.php';
//check is login
if(!is_login() || is_admin()) {
	redirect("login_form.php");
} else {
	$user = getUserById($_SESSION['user_token']['id']);
}
require_once RF . '/user/include/header.php';
require_once RF . '/user/include/navbar.php';
?>
<style>
	#user_avatar_edit img {
		width: 100% !important;
	}
</style>
<main>
	<div class="" style="padding: 0px 85px;">
		<!-- row -->
		<div class="row m-0 py-3">
			<!-- column -->
			<?php require_once 'include/sidebar_user.php'; ?>
			<!-- /column -->
			<!-- colum -->
			<div class="col-9 bg-white py-3">
				<form action="	" method="POST" id="user_info_update_form">
					<div class="row m-0">
						<div class="col-12">
							<h5>HỒ SƠ CỦA TÔI</h5>
							<p class="mb-4">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
							<hr>
						</div>
					</div>

					<div class="row m-0">
						<div class="col-9">
							<div  id="backErr" class="alert-danger"></div>

							<!-- name -->
							<div class="form-group mb-3">
								<div class="input-group">
									<div class="input-group-prepend">
										<label for="name" class="input-group-text">
											<i class="fas fa-user fa-lg"></i>
										</label>
									</div>
									<input type="text" id="name" name="name" class="form-control" placeholder="name" value="<?= $user['cus_name']; ?>">
								</div>
								<div id="nameErr" class="alert-danger">
								</div>
							</div>

							<!-- id -->
							<input type="hidden" name="userID" value="<?= $user['cus_id']; ?>">

							<!-- date of birth -->
							<div class="form-group mb-3">
								<div class="input-group">
									<div class="input-group-prepend">
										<label for="dob" class="input-group-text">
											<i class="fas fa-calendar fa-lg"></i>
										</label>
									</div>
									<input type="text" id="dob" name="dob" class="form-control" placeholder="dd-mm-yyyy" autocomplete="off"
									value="<?= formatDate($user['cus_dob']); ?>">
									<script>
									/**
									*input: return input tag
									*inst: an object that including datepicker
									*dpDiv : a attribute of inst , it is datepicker
									*/
									
									$(document).ready(function() {
										$('#dob').datepicker({
											dateFormat: 'dd-mm-yy',
											yearRange: "-100:2100",
											changeMonth: true,
											changeYear: true,
											beforeShow: function (input, inst) {
												$(".ui-datepicker-month").insertAfter(".ui-datepicker-year");
												console.log(input, inst);
												setTimeout(function () {
													inst.dpDiv.css({
														top: 371,
														left: 410
													});
												},0);
											}
										});
										$('#dob').change(function() {
											console.log($(this).val());
										})
									});
									</script>
								</div>
								<div id="dobErr" class="alert-danger">
								</div>
							</div>

							<!-- gender -->
							<div class="form-group mb-3">
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="male" name="gender" class="custom-control-input" value="1"
										<?= $user['cus_gender'] == 1 ? "checked" : ""; ?>
									>
									<label for="male" class="custom-control-label">Nam</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="female" name="gender" class="custom-control-input" value="0"
										<?= $user['cus_gender'] == 0 ? "checked" : ""; ?>
									>
									<label for="female" class="custom-control-label">Nữ</label>
								</div>
								<div id="genderErr" class="alert-danger"></div>
							</div>

							<!-- email -->
							<div class="form-group mb-3">
								<div class="input-group">
									<div class="input-group-prepend">
										<label for="email" class="input-group-text">
											<i class="fas fa-envelope fa-lg"></i>
										</label>
									</div>
									<input type="text" id="email" name="email" class="form-control" placeholder="email" 
									value="<?= $user['cus_email']; ?>"
									>
								</div>
								<div id="emailErr" class="alert-danger">
								</div>
							</div>

							<!-- phone -->
							<div class="form-group mb-3">
								<div class="input-group">
									<div class="input-group-prepend">
										<label for="phone" class="input-group-text">
											<i class="fas fa-phone-alt fa-lg"></i>
										</label>
									</div>
									<input type="text" id="phone" name="phone" class="form-control" placeholder="phone"   
									value="<?= $user['cus_phone']; ?>"
									>
								</div>
								<div id="phoneErr" class="alert-danger">
								</div>
							</div>


							<?php 
								$address = $user['cus_address'];
								$address = explode('-', $address);

								$ten_tinh = $address[3];
								$id_tinh = s_cell("select matp from db_tinh where name=?", [$ten_tinh], "s");

								$ten_huyen = $address[2];
								$id_huyen = s_cell("select maqh from db_huyen where name=?", [$ten_huyen], "s");
								// echo $id_tinh. "-" . $id_huyen;

								$ten_xa = $address[1];

								$street = $address[0];
							 ?>
							<!-- địa chỉ -->
							<div class="form-group">
							<select name="tinh" id="tinh" class="custom-select" value="<?= $ten_tinh; ?>">
								<option value="" hidden>Tỉnh/Thành phố</option>
								<?php 
									$ds_tinh = db_fetch_table('db_tinh', 0);
									foreach ($ds_tinh as $key => $tinh) {
										if($tinh['name'] == $ten_tinh) {
											echo '
											<option value="'.$tinh['name'].'" data-id-tinh="'.$tinh['matp'].'" selected>'.$tinh['name'].'</option>
											';
										} else {
											echo '
											<option value="'.$tinh['name'].'" data-id-tinh="'.$tinh['matp'].'">'.$tinh['name'].'</option>
											';
										}
									}
								 ?>
							</select>
							<div id="tinhErr" class="alert-danger"></div>
						</div>

						<div class="form-group">
							<select name="huyen" id="huyen" class="custom-select" value="<?= $ten_huyen; ?>">
								<option value="" hidden>Quận/Huyện</option>
								<?php 
									$ds_huyen = db_get("select * from db_huyen where matp = ?", 0, [$id_tinh], "i"); 
									foreach ($ds_huyen as $key => $huyen) {
										if($huyen['name'] == $ten_huyen) {
											echo '  
											<option value="'.$huyen['name'].'" data-id-huyen="'.$huyen['maqh'].'" selected>'.$huyen['name'].'</option>
											';
										} else {
											echo '  
											<option value="'.$huyen['name'].'" data-id-huyen="'.$huyen['maqh'].'">'.$huyen['name'].'</option>
											';
										}
									}
								?>
							</select>
							<div id="huyenErr" class="alert-danger"></div>
						</div>

						<div class="form-group">
							<select name="xa" id="xa" class="custom-select" value="<?= $ten_xa; ?>">
								<option value="" hidden>Phường/Xã</option>
								<?php 
									$ds_xa = db_get("select * from db_xa where maqh = ?", 0, [$id_huyen], "i"); 
									foreach ($ds_xa as $key => $xa) {
										if($xa['name'] == $ten_xa) {
											echo '  
											<option value="'.$xa['name'].'" selected>'.$xa['name'].'</option>
											';
										} else {
											echo '  
											<option value="'.$xa['name'].'">'.$xa['name'].'</option>
											';
										}
									}
								?>
							</select>
							<div id="xaErr" class="alert-danger"></div>
						</div>

						<div class="form-group mb-3">
							<div class="input-group">
								<input type="text" name="street" id="street" class="form-control" placeholder="Thôn, xóm, đường..." value="<?= $street; ?>">
							</div>	
							<div id="streetErr" class="alert-danger"></div>
						</div>

						<!-- địa chỉ đầy đủ -->
						<input type="hidden" name="address" id="address">

							<!-- update button -->
							<button class="btn_user_update btn btn-primary btn-block mb-3">LƯU</button>
						</div>
						<div class="col-3">
							<div class="upload w-100 bg-faded mb-3 text-center">

								<!-- avatar edit -->
								<div id="user_avatar_edit" class="mb-2">
									<img src="../image/<?= $user['cus_avatar']; ?>" width="100%" class="img-thumbnail">
								</div>
								<h5>CHỌN ẢNH</h5>

								<!-- input file avatar -->
								<input type="file" id="avatar" name="avatar" class="form-control" style="overflow: hidden;">

								<input type="hidden" name="oldAvatar" value="<?= $user['cus_avatar']; ?>">
							</div>
							<script>
								$(function() {

									//hiển thị ảnh khi chọn
									$('#avatar').on('change', function() {
										showImg(this, "#user_avatar_edit", 0);
									});
								});
							</script>
							<div id="avatarErr" class="alert-danger"></div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<!-- /column -->
	</div>
	<!-- /row -->
</div>
</main>
<?php
require_once RF . '/user/include/footer.php';
?>

<script>
	$(function() {
		// lấy quận, huyện
		$(document).on('change', '#tinh', function() {
			let id_tinh = $(this).find("option:selected").data('id-tinh');
			$('#huyen').load("../fetch_unit.php", {id_tinh: id_tinh}, function() {
				$('#xa').html('<option value="">Phường/Xã</option>');
			});
		});

		// phường, xã
		$(document).on('change', '#huyen', function() {
			let id_huyen = $(this).find("option:selected").data('id-huyen');
			$('#xa').load("../fetch_unit.php", {id_huyen: id_huyen});
		});


		// submit
		$(document).on('submit', "#user_info_update_form", function(e) {
			e.preventDefault();
			joinAddress();
			console.log($(this).serializeArray());
			// validateUserUpdate();
			userInfoUpdate();
		});

		function joinAddress() {
			let tinh = $('#tinh').val();
			let huyen = $('#huyen').val();
			let xa = $('#xa').val();
			let street = $('#street').val().trim();
			let address = [street, xa, huyen, tinh];
			$('#address').val(address.join("-"));
		}
	});
</script>