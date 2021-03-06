<?php
require_once '../../common.php';
require_once '../include/header.php';

if(!is_login() || !is_admin()) {
	redirect('admin/form_login.php');
} 

require_once '../include/sidebar.php';
require_once '../include/navbar.php';

?>
<!-- main content -row -->
<div class="main_content bg-white row m-0 pt-4">
	<div class="col-12">
		<div>
			<h5>KHÁCH HÀNG</h5>
			<p class="mb-4">Khách hàng là nơi bạn kiểm tra và chỉnh sửa thông tin khách hàng</p>
			<hr>
		</div>
		<div class="row m-0 mb-3">
			<div class="col-12 p-0 d-flex justify-content-end align-items-center">

				<!-- ===================== search  ====================== -->
				<div class="form-group m-0 p-0 d-flex align-items-center">
					<form action="" class="form-inline" id="search_box">
						<input 
							type        ="text" 
							name        ="q" 
							id          ="search" 
							class       ="form-control"
							placeholder ="Search..." 
							value       ="<?= $_GET['q'] ?? ""; ?>"
							>
						<button class="btn btn-outline-success">
							<i class="fas fa-search"></i>
						</button>
					</form>
				</div>

			</div>
		</div>
		<!-- lấy khách hàng -->
		<?php
			$q = data_input(input_get('q'));
			$key = "%" . $q . "%";

			if($q != "") {
				$searchSQL = "
				SELECT * FROM db_customer WHERE 
					cus_id LIKE(?) OR
					cus_name LIKE(?) OR 
					cus_address LIKE(?) OR
					cus_phone LIKE(?) OR
					cus_email LIKE(?) OR
					cus_dob LIKE(?) OR
					cus_phone LIKE(?)
				";

				$param = [$key, $key, $key, $key, $key, $key, $key];
				$listCustomer = db_get($searchSQL, 1, $param, "sssssss");
			} else {

				$listCustomer = getListUser(0);
			}
			

			// chia trang
			$totalCustomer = $listCustomer->num_rows;
			$customerPerPage = 5;
			$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
			$currentLink = create_link(base_url("admin/customer/index.php"), ["page"=>'{page}', 'q'=>$q]);
			$page = paginate($currentLink, $totalCustomer, $currentPage, $customerPerPage);

			// danh sách nhân viên sau khi chia trang
			if($q != "") {
				$searchResultSQL = $searchSQL . " LIMIT ? OFFSET ?";
				$param = [$key, $key, $key, $key, $key, $key, $key, $page['limit'], $page['offset']];

				// danh sách người dùng sau khi tìm kiếm và chia trang chia trang
				$listCustomerPaginate = db_get($searchResultSQL, 1, $param, "sssssssii");
			} else {

				$listCustomerPaginate = getListUser(0, $page['limit'], $page['offset']);
			}

			
			$totalCustomerPaginate = $listCustomerPaginate->num_rows;

			// số thứ tự
			$stt = 1 + (int)$page['offset'];

		?>
		<div class="content_table">
			<table class="table table-hover table-bordered" style="font-size: 13px;">
				<tr>
					<th>STT</th>
					<th>Mã</th>
					<th>Tên</th>
					<th>Ngày sinh</th>
					<th>Giới tính</th>
					<th>Email</th>
					<th>Điện thoại</th>
					<th>Ảnh</th>
					<th>Địa chỉ</th>
					<th>Trạng thái</th>
					<th>Sửa</th>
					<th>Xóa</th>
				</tr>
				<!-- in các đơn hàng -->
				<?php if ($totalCustomerPaginate > 0): ?>
				<?php foreach ($listCustomerPaginate as $key => $customer): ?>
				<tr>
					<td><?= $stt++; ?></td>
					<td><?= $customer['cus_id']; ?></td>
					<td><?= $customer['cus_name']; ?></td>
					<td><?= $customer['cus_dob']; ?></td>
					<td>
						<?= $customer['cus_gender'] ? "Nam" : "Nữ"; ?>
					</td>
					<td><?= $customer['cus_email']; ?></td>
					<td><?= $customer['cus_phone']; ?></td>
					<td>
						<img src="../../image/<?= $customer['cus_avatar']; ?>" width="30px" height="30px">
					</td>
					<td><?= $customer['cus_address']; ?></td>
					<td>
						<div class="custom-control custom-switch">
							<input 
								type="checkbox" 
								id="switch_active_<?= $customer['cus_id']; ?>" 
								data-customer-id="<?= $customer['cus_id']; ?>"
								class="btn_switch_active custom-control-input" 
								value="<?= $customer['cus_active']; ?>"
								<?= $customer['cus_active'] ? "checked" : ""; ?>
							>
							<label for="switch_active_<?= $customer['cus_id']; ?>" class="custom-control-label"></label>
						</div>
					</td>
					<td>
						<a
							href="
							<?= 
								create_link(base_url('admin/customer/update.php'), [
									"cusid"=>$customer['cus_id']
								]);
							?>
							"
							class="btn_edit_customer btn btn-success"
							data-customer-id="<?= $customer['cus_id']; ?>">
							<i class="fas fa-edit"></i>
						</a>
					</td>
					<td>
						<a 
							class="btn_remove_customer btn btn-danger"
							data-customer-id="<?= $customer['cus_id']; ?>">
							<i class="fas fa-trash-alt"></i>
						</a>
					</td>
				</tr>
				
				<?php endforeach ?>
				<?php endif ?>
			</table>
			<?php echo $page['html']; ?>
		</div>
	</div>
</div>
</div>
<!-- /right-col -->
</div>
<!-- /wrapper -row -->
</main>
</body>
</html>

<script>
	$(function() {

		// cập nhật nội dung thẻ search
		let q = "<?= $_GET['q'] ?? ""; ?>";
		$('#search').val(q);

		// Thay đổi trạng thái của khách hàng
		$(document).on('change', '.btn_switch_active', function() {

			// id khách hàng
			let customerID = $(this).data("customer-id");

			// trạng thái hiện tại
			let prevActive = $(this).val();
			console.log(prevActive);

			// trạng thái muốn thay đổi
			let newActive = $(this).prop('checked');
			newActive = newActive ? 1 : 0;
			console.log(newActive);

			// gửi yêu cầu thay đổi trạng thái
			let sendSwitchActive = sendAJax(
				"process_customer.php",
				"post",
				"json",
				{customerID: customerID, newActive: newActive, action: "switch_active"}
			)
			// alert(sendSwitchActive.status);

			// nếu không thành công khôi phục về trạng thái trước đó
			// if(sendSwitchActive.status == 1) {
			// 	alert("THIẾU DỮ LIỆU");
			// 	if(prevActive == 1) {
			// 		$("#switch_active_" + customerID).prop("checked", true);
			// 	} else {
			// 		$("#switch_active_" + customerID).prop("checked", false);
			// 	}
			// }

			// // nếu thành công thay đổi trang thái của nút trạng thái theo trạng thái được trả về
			// if(sendSwitchActive.status == 5) {

			// 	// mã khách hàng trả về
			// 	let customerID = sendSwitchActive.customerID;

			// 	// trạng thái trả về
			// 	let resActive = sendSwitchActive.active;
			// 	// alert(resActive);

			// 	// thay đổi trạng thái
			// 	if(resActive == 1) {
			// 		$("#switch_active_" + customerID).prop("checked", true);
			// 	} else {
			// 		$("#switch_active_" + customerID).prop("checked", false);
			// 	}
			// }
			

			// làm mới trang
			let q           = "<?= $_GET['q'] ?? ""; ?>";
			let prevPage    = "<?= getCurrentURL(); ?>";
			let currentPage = <?= $currentPage ?>;
			let fetchPage = sendAJax(
				"fetch_page.php",
				"post",
				"html",
				{action: "fetch", prevPage: prevPage, q: q, currentPage: currentPage }
			);

			$('.content_table').html(fetchPage);
		});


		// xóa người dùng
		$(document).on('click', '.btn_remove_customer', function() {

			let wantRemove = confirm("BẠN CÓ MUỐN XÓA NGƯỜI DÙNG NÀY? MỌI ĐƠN HÀNG LIÊN QUAN SẼ BỊ XÓA THEO");

			if(wantRemove) {
				// THỰC HIỆN HÀNH ĐỘNG
				let customerID = $(this).data('customer-id');
				let prevLink = "<?= getCurrentURL() ?>";
				
				let sendRemove = sendAJax(
					"process_customer.php",
					"post",
					"text",
					{customerID: customerID, action: "remove"}
				);

				// LÀM MỚI TRANG
				// trang trước(chuyển hướng đến sau khi cập nhật -dùng cho update)
				let prevPage    = "<?= getCurrentURL(); ?>";
				
				// trang hieenh tại(phân trang)
				let currentPage = <?= $currentPage ?>;
				
				let q           = "<?= $_GET['q'] ?? ""; ?>";

				// làm mới trang
				let fetchPage = sendAJax(
					"fetch_page.php",
					"post",
					"html",
					{action: "fetch", prevPage: prevPage, q: q, currentPage: currentPage }
				);

				$('.content_table').html(fetchPage);
			}

		});
	});
</script>	
