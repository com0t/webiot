<?php 
session_start();

class Render {

	private $__title;
	private $__customer;

	function __construct($title,$customer) {
		$this->__title = $title;
		$this->__customer = $customer;
	}

	function __destruct() {

	}

	function header() {
		echo 
		'	
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="css/k3.css">
		<script src="js/jquery.min.js"></script>
		<script src="js/highcharts/highcharts.js"></script>
		<script src="js/highcharts/modules/series-label.js"></script>
		<script src="js/highcharts/modules/exporting.js"></script>
		<script src="js/highcharts/modules/export-data.js"></script>
		<script src="js/k3.js"></script>
		<title>'.$this->__title.'</title>
	</head>
	<body>
		<div id="disible" onclick="closeMenu()" class="disible hiden"></div>
		<div class="header">
			<div class="container">
				<button onclick="openMenu()">menu</button>
				<span style="margin-left:8px;">Tiêu đề trang</span>
				<div style="float:right">Hi : '.$this->__customer.'</div>
			</div>
		</div>

		<div id="sliebar" class="menu hiden">
			<ul>
			<li><a href="">Giám sát</a></li>
			<li><a href="">Điều khiển</a></li>
			<li><a href="">Bảng dữ liệu</a></li>
			<li><a href="">Logout</a></li>
			</ul>
		</div>

		<!--Phần nội dung trang web được viết trong đây-->
		';
	}

	function footer() {
		echo 
		'<!--Kết thúc nội dung trang web-->
	</body>
</html>
		';
	}

	function combo($id_view,$id_chart1,$id_chart2) {
		echo
		'
			<div class="flex-container">
				<div class="view">
					<img src="resource/senc.svg" width="50px">
					<h2>'.$id_view.'</h2>
					<p>Trạng thái</p>
					<table id="'.$id_view.'">
						<tr>
							<th>RSSI</th>
							<th>SNR</th>
							<th>TC</th>
							<th>HU</th>
						</tr>
						<tr>
							<td>35</td>
							<td>8</td>
							<td>24.79</td>
							<td>56.89</td>
						</tr>	
					</table>
				</div>
				<div class="charts" id="'.$id_chart1.'"></div>
				<div class="charts" id="'.$id_chart2.'"></div>
			</div>
		';
	}

}
?>