<?php 
	include_once 'common.php';
	$cat = "2";
	$brand = "1";
	// db_connect();
	// $kq = get_list("select * from db_category");
	// var_dump($kq);
	// // update("db_category", ["cat_name" => "ĐIỆN THOẠI"], "cat_id = 1");
	// $id = 1;
	// $username = "long";
	// $role = 3;
	// set_login($id, $username, $role);
	// var_dump(get_session("user_token"));
	// getUserById($id);
	//set_logout();
	// $id = 1;
	// $num = fetch_tbl('db_customer', 2);
	// var_dump($num);
	// echo $num->num_rows;
	// 
	// $time = time();
	// echo date("Y", $time);
	// echo "<br>";
	// echo date("m", $time);
	// echo "<br>";
	// echo date("d", $time);
	// echo "<br>";
	// echo date("H", $time);
	// echo "<br>";
	// echo date("i", $time);
	// echo "<br>";
	// echo date("s", $time);
	// echo "leap year";
	// echo leap_Year(2016);
	// echo "<br>";
	// $year = date("Y");
	// echo $year;
	// echo "<br>";
	// echo (new DateTime)->format("Y");
	// echo "<br>";
	// echo leap_Year(date("Y"));
	// echo "<br>";
	// $time = strtotime("3 October 2005");
	// echo $time;
	// echo "<br>";
	// echo read_date($time);

	// $result = fetch_tbl("db_product");
	// $row = get_display([1, 2, 3], 4);
	// var_dump($row);
	// echo gettype($result);
	// echo fetch_tbl("db_product", 0);
	// $link = "http://localhost/do_an_1/product.php";
	// $currentLink = create_link(base_url("product.php"), ["page"=>"{page}"]);
	// //paginate($currentLink, 20, 1, 4);
	// //echo str_replace("page", "123", $currentLink);
	// echo "hello";
	// 
	// $test = db_get('select pro_id from db_product where cat_id = ? and bra_id = 3', [1], 2);
	// var_dump($test);
	// echo "<br><br><br>";

	// $test = s_row('select * from db_product where cat_id = ? and bra_id = 3', [1]);
	// var_dump($test);
	// echo "<br><br><br>";

	// $test = s_cell('select pro_name from db_product where cat_id = ? and bra_id = 3', [1]);
	// var_dump($test);

	// $test = db_run("update db_product set pro_price = ? where pro_id = ?", 123, 5);
	// echo $test;
	$test = get_session("cart");
	vd($test);


