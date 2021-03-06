<?php
require_once 'common.php';
	//lấy từ khóa tìm kiếm
$keyWord = "%" . input_get("q") . "%";
	//lấy kết quả
$getResultSQL = "SELECT * FROM db_product WHERE pro_active = 1 AND pro_name LIKE(?)";
$listResult = db_get($getResultSQL, 0, [$keyWord], "s");
	//in kết quả
foreach ($listResult as $key => $result): ?>
	<li class="ajax_search_item list-group-item p-0">
		<a href='<?= create_link(base_url("product_detail.php"), ["proid"=>$result["pro_id"]]); ?>' class="d-flex align-items-center">
			<span class="item_result_img">
				<img src="<?= base_url('image/') . $result['pro_img']; ?>" alt="" height="60px  " class="float-left mr-3">
			</span>
			<span class="item_result_text">
				<h5 class="text-uppercase"><?= $result['pro_name'] ?></h5>
				<span><?= number_format($result['pro_price'], 0, ",", ".") ?> &#8363;</span>
			</span>
		</a>
	</li>
<?php endforeach ?>