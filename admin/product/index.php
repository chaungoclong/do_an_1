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
		<!-- tiêu đề -->
		<div class="d-flex justify-content-between align-items-center mb-2">
			<a class="" onclick="window.location='<?= base_url('admin/index.php'); ?>'" style="cursor: pointer;">
				<i class="fas fa-angle-left"></i> TRỞ LẠI
			</a>
		</div>

		<!-- nút thêm sản phẩm và thanh tìm kiếm -->
		<div class="row m-0 mb-3">
			<!-- nút thêm sản phẩm -->
			<div class="col-12 p-0 d-flex justify-content-between align-items-center">

				<!-- tìm kiếm, thêm -->
				<div class="filter d-flex pr-5">
					<!-- thêm -->
					<a href="
						<?= base_url('admin/product/add.php'); ?>
						"
						class="btn btn-success mr-2"
						data-toggle="tooltip"
						data-placement="top"
						title="Thêm sản phẩm mới"
						style="border-radius: 50%;"
						>
						<i class="fas fa-plus"></i>
					</a>

					<!-- sắp xếp -->
					<select id="sort" class="custom-select mr-1">
						<option value="1">Tên: A - Z</option>
						<option value="2">Tên: Z - A</option>
						<option value="3" selected>Ngày tạo: Mới nhất</option>
						<option value="4">Ngày tạo: Cũ nhất</option>
						<option value="5">Giá: Tăng dần</option>
						<option value="6">Giá: Giảm dần</option>
						<option value="7">Số lượng: Tăng dần</option>
						<option value="8">Số lượng: Giảm dần</option>
					</select>

					<!-- hãng -->
					<select id="brand_opt" class="custom-select mr-1">
						<?php $listBrand = db_fetch_table("db_brand", 0); ?>

						<option value="all">Hãng: Tất cả</option>
						<?php foreach ($listBrand as $key => $brand): ?>
							<option value="<?= $brand['bra_id'] ?>">Hãng: <?= $brand['bra_name']; ?> </option>
						<?php endforeach ?>
					</select>

					<!-- danh mục -->
					<select id="category_opt" class="custom-select mr-1">
						<?php $listCategory = db_fetch_table("db_category", 0); ?>

						<option value="all">Danh mục: Tất cả</option>
						<?php foreach ($listCategory as $key => $category): ?>
							<option value="<?= $category['cat_id'] ?>">Danh mục: <?= $category['cat_name']; ?> </option>
						<?php endforeach ?>
					</select>

					<!-- lọc trạng thái sản phẩm -->
					<select id="filter_status" class='custom-select mr-1'>
						<option value="all" selected>Trạng thái: Tất cả</option>
						<option value="on">Trạng thái: Bật</option>
						<option value="off">Trạng thái: Tắt</option>
					</select>

					<!-- tìm kiếm tên , id sản phẩm -->
					<input type="text" class="form-control" id="search" placeholder="Search...">
				</div>

				<!-- số hàng hiển thị -->
				<div class="d-flex justify-content-between align-items-center">
					<i class="far fa-file-excel fa-2x text-success mr-3" style="" onclick="window.location='export_file.php'"></i>

					<?php $option = [5, 10, 25, 50, 100]; ?>
					<select class="custom-select" id="number_of_rows">
						<?php foreach ($option as $key => $each): ?>
							<option value="<?= $each; ?>"> <?= $each; ?> </option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>

		<!-- lấy danh sách sản phẩm-->
		<div>
			<table class="table table-hover table-bordered" style="font-size: 15px;">
				<thead class="thead-light">
					<tr>
						<th class="align-middle">Mã</th>
						<th class="align-middle">Tên</th>
						<th class="align-middle">Ảnh</th>
						<th class="align-middle">Hãng</th>
						<th class="align-middle">Thể loại</th>
						<th class="align-middle">Giá</th>
						<th class="align-middle">Số lượng</th>
						<th class="align-middle">Trạng thái</th>
						<th class="align-middle" width="115px">Hành động</th>
					</tr>
				</thead>

				<tbody class="list_product">
				</tbody>
			
			</table>
			<div class="page"></div>
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
		// khôi phục trang trước(nếu quay lại từ trang update sau khi update)
		// hoặc làm mới trang(lấy danh sách sản phẩm tại trang đầu tiên vơi các tùy chọn tìm kiếm mặc định)
		fetchPageFirstTime();

		// lấy danh sách sản phẩm khi nhập tìm kiếm
		$(document).on('input', '#search', function() {
			fetchPage(1);
		});

		// lấy danh sách sản phẩm khi lọc
		$(document).on('change', '#filter_status, #sort, #brand_opt, #category_opt', function() {
			fetchPage(1);
		});

		// lấy danh sách sản phẩm khi thay đổi số hàng hiển thị
		$(document).on('change', '#number_of_rows', function() {
			fetchPage(1);
		});

		// lấy danh sách sản phẩm khi chuyển trang
		$(document).on('click', '.page-item', function() {
			let currentPage = parseInt($(this).data("page-number"));
			if(isNaN(currentPage)) {
				currentPage = 1;
			}
			fetchPage(currentPage);
		});

		// thay đổi trạng thái của 1 sản phẩm
		$(document).on('change', '.btn_switch_active', function() {
			changeStatus(this.id);
		});

		// xóa 1 sản phẩm
		$(document).on('click', '.btn_delete_pro', function() {
			if(confirm("BẠN CÓ MUỐN XÓA SẢN PHẨM NÀY?")) {
				deleteRow(this.id);
			}
		});

		// lưu dữ liệu của trang index trước khi chuyển sang trang update(để quay lại đúng trang sau khi update)
		$(document).on('click', '.btn_edit_pro', function() {
			setPrevPageData();
		});
	});

	// hàm lấy danh sách các mục
	function fetchPage(currentPage = 1) {
		let q = "%" + $('#search').val().trim() + "%";
		let sort = $('#sort').val();
		let status = $('#filter_status').val();
		let brand = $('#brand_opt').val();
		let category = $('#category_opt').val();
		let numRows = $('#number_of_rows').val();
		let action = "fetch";
		let data = {
			q : q, status: status, sort: sort, numRows: numRows, brand: brand, category: category, currentPage: currentPage, action: action
		};
		let result = sendAJax("fetch_page.php", "post", "json", data);
		$('.list_product').html(result.products);
		$('.page').html(result.pagination);
	}

	// hàm thay đổi trạng thái của sản phẩm
	function changeStatus(btnID) {
		let proID = $(`#${btnID}`).data('pro-id');
		let status = $(`#${btnID}`).prop('checked');
		let active = status ? 1 : 0;
		let action = "switch_active";
		let data = {proID: proID, active: active, action: action};
		let result = sendAJax("process_product.php", "post", "json", data);
		if(!result.ok) {
			alert("có lỗi khi thay đổi trạng thái");
		}
		// cập nhật lại sau khi thay đổi
		let currentPage = parseInt($('li.page-item.active').data('page-number'));
		if(isNaN(currentPage)) {
			currentPage = 1;
		};
		fetchPage(currentPage);
	}

	function deleteRow(btnID) {
		let proID = $(`#${btnID}`).data('pro-id'); 
		let action = "delete";
		let data = {proID : proID, action: action};
		let result = sendAJax("process_product.php", "post", "json", data);
		console.log(result);
		let status = result.status;
		switch (status) {
			case "success":
				alert("XÓA THÀNH CÔNG");
				break;
			case "has_order":
				alert("KHÔNG THỂ XÓA SẢN PHẨM ĐÃ CÓ ĐƠN HÀNG");
				break;
			case "error":
				alert("ĐÃ CÓ LỖI XẢY RA, VUI LÒNG THỬ LẠI");
				break;
			default:
				alert("ĐÃ CÓ LỖI XẢY RA, VUI LÒNG THỬ LẠI");
				break;
		}

		// cập nhật danh sách sau khi xóa
		let currentPage = parseInt($('li.page-item.active').data('page-number'));
		if(isNaN(currentPage)) {
			currentPage = 1;
		};
		fetchPage(currentPage);
	}

	/**
	 * hàm tạo dữ liệu của trang trước (để khi quay lại trang đó thì khôi phục lại)
	 */
	function setPrevPageData() {
		localStorage.setItem("search", $('#search').val());
		localStorage.setItem("sort", $('#sort').val());
		localStorage.setItem("numRows", $('#number_of_rows').val());
		localStorage.setItem("brand", $('#brand_opt').val());
		localStorage.setItem("category", $('#category_opt').val());
		localStorage.setItem("status", $('#filter_status').val());
		localStorage.setItem("oldPage", parseInt($('li.page-item.active').data('page-number')));
	}

	// hàm lấy trang lần đầu tiên (nếu quay về từ trang update thì khôi phục các thông tin về tùy chọn tìm kiếm, vị trí trang hiện tại)
	// nếu lần đầu vào trang hoặc quay về từ trang khác khác trang update thì làm mới trang(lấy dữ liệu trang đầu tiên, các tùy chọn tìm kiếm mặc định)
	function fetchPageFirstTime() {
		let search  = localStorage.getItem("search");
		if(search != null) {
			$('#search').val(search);
			localStorage.removeItem("search");
		}

		let sort    = localStorage.getItem("sort");
		if(sort != null) {
			$('#sort').val(sort);
			localStorage.removeItem("sort");
		}

		let numRows    = localStorage.getItem("numRows");
		if(numRows != null) {
			$('#number_of_rows').val(numRows);
			localStorage.removeItem("numRows");
		}

		let brand    = localStorage.getItem("brand");
		if(brand != null) {
			$('#brand_opt').val(brand);
			localStorage.removeItem("brand");
		}

		let category    = localStorage.getItem("category");
		if(category != null) {
			$('#category_opt').val(category);
			localStorage.removeItem("category");
		}

		let status  = localStorage.getItem("status");
		if(status != null) {
			$('#filter_status').val(status);
			localStorage.removeItem("status");
		}

		let oldPage = localStorage.getItem("oldPage");
		if(oldPage != null) {
			fetchPage(oldPage);
			localStorage.removeItem("oldPage");
		} else {
			fetchPage(1);
		}
	}
</script>	
