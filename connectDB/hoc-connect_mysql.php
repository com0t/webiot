<?php
	/*
	* Trong trang web freetuts.net có giới thiệu
	* 2 thư viện kết nối database: mysqli,PDO
	* trong mysqli thì có 2 cách đó là:
	* hướng đối tượng, hướng thủ tục
	* trong đây mình theo hướng hướng đối tượng
	*/

	// Tạo kết nối  
	$mysqli = new mysqli('
fdb20.awardspace.net','2795746_iot','2795746_iot');

	// Nếu kết nối thất bại
	if ($mysqli->connect_errno)
		echo ('Kết nối thất bại: '.$mysqli->connect_error);
	echo $mysqli->host_info;
	// Các phiên bản MySQL trước version 5.3 phải dùng đoạn code sau
	if (mysqli_connect_error())
		die ('Kết nối thất bại: '.mysqli_connect_error());
	die;
	// Tạo database
	$query="CREATE DATABASE `demo` DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci";
	// Tạo table
	$query="CREATE TABLE News (
		id int(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		title varchar(30) NOT NULL,
		content text,
		add_date timestamp
	)";
	// Thêm dữ liệu --> với dữ liệu tiếng viết bị lỗi
	$query="INSERT INTO news (title,content)
		VALUES ('Tiêu đề','Nội dung')";
	// Lấy id vừa insert bằng php sử dụng hàm LAST_INSERT_ID()

	//Bài7: insert nhiều record vào database
	$query="INSERT INTO news(title,content)
		VALUES ('tieu de 1','noi dung 1');";
	$query .= "INSERT INTO news(title,content)
		VALUES ('tieu de 2','noi dung 2')";
	//Thực hiện truy vấn
	// if ($mysqli->multi_query($query)===TRUE)
	// 	echo 'Thêm thành công';
	// else 
	// 	echo 'Lỗi: '.$query.'<br>'.$mysqli->error;

	//Bài8: Cơ chế prepared câu SQL trong php
	// khắc phục SQL injection
	/*
	* Chúng ta đưa vào một câu truy vấn với các tham số là một ẩn danh
	* Chúng ta truyền vào giá trị tương ứng cho các ẩn danh đó
	* PHP sẽ dựa vào thư tự các tham số ẩn danh và các giá trị để repared sao bảo mật nhất.
	* Cuối cùng sẽ thực thi câu truy vấn.
	* Khi bạn đã khai báo các tham số lần đầu rồi và sau đó muốn sử dụng tiếp thì không cần phải khai báo nữa. Đây cũng chính là lợi thế của cơ chế prepared trong PHP.
	*/
	// Cấu SQL
	$query="INSERT INTO news (title,content) VALUES (?,?)";
	// Tạo đối tượng repared
	$stmt=$mysqli->prepare($query);
	// Gán giá trị vào các tham số ẩn
	// Câu lệnh thực hiện bind data
	/*
	* các giá trị trong trường đầu của bind_param
	* + i:integer
	* + d:double
	* + s:string
	* + b:blob 
	*/
	$stmt->bind_param("ss",$title,$content);
	//Thực thi cấu truy vấn lần 1
	$title='Tiêu đề 1';
	$content='Nội dung 1';
	$stmt->execute();
	// Thực thi truy vấn lần 2
	$title='Tiêu đề 2';
	$content='Nội dung 2';
	$stmt->execute();
	echo 'Thành công';
	$stmt->close();

	//Bài 9: Select dữ liệu trong MySQL bằng PHP
	$query="SELECT id,title,content FROM news";
	//Thực thi câu và gán kết quả vào $result
	$result=$mysqli->query($query);
	if ($result->num_rows > 0) {
		// Sử dụng vòng lặp while để lặp kết quả
		while ($row=$result->fetch_assoc()) {
			echo 'Title: '.$row['title'].'- Content: '.$row['content'].'<br>';
		}
	} else {
		echo 'Không có record nào';
	}

	//Bài 10: Delete dữ liệu với MySQLi
	$query="DELETE FROM news WHERE id=1";
	//thực hiện truy vấn
	if ($mysqli->query($query)===TRUE) {
		echo 'Xoá thành công';
	} else {
		echo 'Xoá thất bại: '.$mysqli->error;
	}

	// Thực thi cấu lệnh
	// if ($mysqli->query($query)===TRUE){
	// 	$last_id = $mysqli->insert_id;
	// 	if (isset($last_id)) echo $last_id;
	// 	else echo 'Khong có gì';
	// } else 
	// 	echo 'Lỗi: '.$mysqli->error;

	mysqli_close($mysqli);


	// Phần kiến thức học trên php.net
	/* check connection*/
	if ($mysqli->connect_errno)
		die ('Failed to connect to MySQL: ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
	/* show information host*/
	echo $mysqli->host_info."<br>\n";

	/* Create table new */
	$query = "CREATE TABLE new (id int)";
	if ($mysqli->query($query)===TRUE) {
		echo 'Create table new Sucessfull!<br>';
	} else {
		echo 'Create table failly!<br>';
		echo 'Errno: '.$mysqli->errno.'<br>';
		echo 'Error: '.$mysqli->error.'<br>';
	}
	/* insert value into table new */
	$query = "INSERT INTO new VALUES (1)";
	if ($mysqli->query($query)===TRUE) {
		echo 'Insert value sucessfull<br>';
	} else {
		echo 'Insert value fail<br>';
	}

	$query = "SELECT * FROM new";
	if ($result = $mysqli->query($query)) {
		var_dump($result);echo '<br>';
		printf("Select returned %d rows.<br>",$result->num_rows);
		/* free result set*/
		$result->close();
	}

	/* Nếu số lượng dữ liệu lấy về lớn, sử dụng MYSQLI_USE_RESULT */
	if ($result = $mysqli->query("SELECT * FROM City", MYSQLI_USE_RESULT)) {
	/* Note, that we can't execute any functions which interact with the server until result set was closed. All calls will return an 'out of sync' error */

    /* Chú ý, không thể thực hiện thêm tương tác nào với máy chủ cho đến khi kết quả đã được đóng. Tất cả lời gọi sẽ trả về lỗi không đồng bộ */
       if (!$mysqli->query("SET @a:='this will not work'")) {
       	printf("Error: %s\n", $mysqli->error);
       }
       $result->close();
?>