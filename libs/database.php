<?php 
	//biến kết nối
	$connect = null;

	//hàm kết nối
	function db_connect() {
		global $connect;

		if(!$connect) {
			$connect = new mysqli("localhost", "root", "", "do_an_1");
			if($connect->connect_error) {
				die("CONNECTION FAILED " . $connect->connect_error);
			}
			$connect->set_charset("utf8");
		}

	}

	//hàm đóng kết nối
	function db_close() {
		global $connect;

		if($connect) {
			$connect->close();
		}
	}

	/**
	 * [fetch_tbl hàm lấy một bảng]
	 * @param  [type] $table [tên bảng cần lấy]
	 * @return [type]        [trả về mảng kết quả hoặc in ra thông báo lỗi]
	 */
	function fetch_tbl($table, $mode = 1) {
		db_connect();

		global $connect;
		$data   = [];
		$sql    = "SELECT * FROM {$table}";
		$result = $connect->query($sql);

		if(!$result) {
			die($connect->error);
		}

		switch ($mode) {
			case 0:
				$data = $result->num_rows;
				break;
			case 1:
				$data = $result->fetch_all(MYSQLI_ASSOC);
				break;
			case 2:
				$data = $result;
				break;
			default:
				$data = $result;
				break;
		}
		return $data;
	}

	/**
	 * [get_list lấy một danh sách bản ghi bằng câu sql]
	 * @param  [type] $sql [câu truy vấn]
	 * @return [type]      [trả về mảng kết quả hoặc in ra thông báo lỗi]
	 */
	function get_list($sql, $mode = 1) {
		db_connect();

		global $connect;
		$list   = [];
		$result = $connect->query($sql);

		if(!$result) {
			die($connect->error);
		}

		switch ($mode) {
			case 0:
				$list = $result->num_rows;
				break;
			case 1:
				$list = $result->fetch_all(MYSQLI_ASSOC);
				break;
			case 2:
				$list = $result;
				break;
			default:
				$list = $result;
				break;
		}
		return $list;
	}

	/**
	 * [fetch_list lấy một danh sách bản ghi với điều kiện và cột chỉ định]
	 * @param  [type] $table     [tên bảng cần lấy]
	 * @param  string $condition [điều kiện]
	 * @param  array  $column    [cột cần lấy]
	 * @return [type]            [mảng kết quả hoặc thông báo lỗi]
	 */
	function fetch_list($table, $condition = "1", $column = ["*"], $mode = 1) {
		db_connect();

		global $connect;
		$list   = [];
		$sql    = "SELECT " . implode(",", $column) . " FROM {$table} WHERE " . $condition;
		$result = $connect->query($sql);
		
		if(!$result) {
			die($connect->error);
		}

		switch ($mode) {
			case 0:
				$list = $result->num_rows;
				break;
			case 1:
				$list = $result->fetch_all(MYSQLI_ASSOC);
				break;
			case 2:
				$list = $result;
				break;
			default:
				$list = $result;
				break;
		}
		return $list;
	}

	/**
	 * [hàm lấy một hàng bằng câu sql]
	 * @param  [type] $sql [câu truy vấn]
	 * @return [type]      [mảng kết quả hoặc thông báo lỗi]
	 */
	function get_row($sql) {
		db_connect();

		global $connect;
		$row    = [];
		$result = $connect->query($sql);

		if(!$result) {
			die($connect->error);
		}

		$row = $result->fetch_assoc();
		return $row;
	}

	/**
	 * [fetch_list lấy một bản ghi với điều kiện và cột chỉ định]
	 * @param  [type] $table     [tên bảng cần lấy]
	 * @param  string $condition [điều kiện]
	 * @param  array  $column    [cột cần lấy]
	 * @return [type]            [mảng kết quả hoặc thông báo lỗi]
	 */
	function fetch_rows($table, $condition = "1", $column = ["*"]) {
		db_connect();

		global $connect;
		$row    = [];
		$sql    = "SELECT " . implode(",", $column) . " FROM {$table} WHERE " . $condition;
		$result = $connect->query($sql);

		if(!$result) {
			die($connect->error);
		}

		$row = $result->fetch_assoc();
		return $row;
	}

	/**
	 * [add hàm thêm một bản ghi mới]
	 * @param [type] $table [bảng cần thêm]
	 * @param [type] $data  [dữ liệu key=>value]
	 */
	function add($table, $data) {
		db_connect();
		
		global $connect;

		//loại bỏ ký tự đặc biệt
		foreach ($data as $key => $value) {
			$data[$key] = $connect->real_escape_string($value);
		}

		$column = array_keys($data);
		$value  = array_values($data);
		$sql    = "INSERT INTO {$table}(" . implode(",", $column) . ") VALUES('" . implode("','", $value) . "')";
		return $connect->query($sql);
	}

	/**
	 * [update hàm update]
	 * @param  [type] $table     [tên bảng]
	 * @param  [type] $data      [dữ liệu]
	 * @param  [type] $condition [điều kiện]
	 * @return [type]            [description]
	 */
	function update($table, $data, $condition) {
		db_connect();

		global $connect;

		foreach ($data as $key => $value) {
			$data[$key] = "$key = '" . $connect->real_escape_string($value) . "'";
		}

		$sql = "UPDATE {$table} SET " . implode(",", $data) . " WHERE " . $condition;
		return $connect->query($sql);
	}

	/**
	 * [del hàm xóa]
	 * @param  [type] $table     [tên bảng]
	 * @param  [type] $condition [điều kiện]
	 * @return [type]            [description]
	 */
	function del($table, $condition) {
		db_connect();

		global $connect;
		$sql = "DELETE FROM {$table} WHERE " . $condition;
		echo $sql;
		return $connect->query($sql);
	}

	//hàm chạy các câu sql không lấy về kết quả (insert, delete, update)
	// function db_run($sql) {
	// 	db_connect();

	// 	global $connect;
	// 	return $connect->query($sql);
	// }

	/**
	 * [safeQuery hàm chạy câu truy vấn lấy về kết quả]
	 * @param  [string] $sql    [câu sql với giá trị các trường được để là ?]
	 * @param  array  $param [] [mảng chứa giá trị các trường]
	 * @return [array / null]   [không lỗi trả về một mảng kết quả : null]
	 */
	function db_get($sql, $mode = 0, $param = [], $format = "") {
		db_connect();
		global $connect;
		$stmt = $connect->prepare($sql);
		$numField = count($param);

		//nếu các trường có giá trị truyền vào thì liên kết giá trị với các trường
		if($numField) {
			$format = ($format != "") ? $format : str_repeat("s", $numField);
			$stmt->bind_param($format, ...$param);
		}

		/**
		 * thực thi thành công câu SQL trả về kết quả theo chế độ lấy: trả về null
		 * +trả về null vì sử dụng toán tử ?? ở hàm s_row
		 */
		if($stmt->execute()) {
			switch ($mode) {
				//0: trả về kết quả đã được chuyển về mảng
				case 0:
					$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
					break;

				//1: trả về kết quả ở dạng đối tượng
				case 1:
					$result = $stmt->get_result();
					break;

				//trả về số bản ghi của kết quả
				case 2:
					$result = $stmt->get_result()->num_rows;
					break;
				
				//mặc định: $mode = 0
				default:
					$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
					break;
			}
			return $result;
		}
		return null;
	}

	//hàm chạy câu truy vấn không lấy về kết quả (insert, update, delete)
	function db_run($sql, $param = [], $format = "") {
		db_connect();
		global $connect;

		$stmt = $connect->prepare($sql);

		$numField = count($param);
		if($numField) {
			$format = ($format != "") ? $format : str_repeat("s", $numField);
			$stmt->bind_param($format, ...$param);
		} 

		return $stmt->execute();
	}

	//hàm lấy ra một hàng
	function s_row($sql, $param = [], $format = "") {
		return db_get($sql, 0, $param, $format)[0] ?? []; 
	}

	//hàm lấy ra một ô
	function s_cell($sql, $param = [], $format = "") {
		$result = s_row($sql, $param, $format);
		return array_shift($result);
	}


