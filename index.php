<?php  
include_once ('include/Render.php');
include_once ('connectDB/connect_mysql.php');

$render = new Render('index','Admin');
$render->header();
?>
<script type="text/javascript">
	<?php
	$query="SELECT * FROM node";
	$node = array();
	if (!$result=$mysqli->query($query)) die("Lỗi không truy vấn được");
	if ($result->num_rows>0) {
		while ($row = $result->fetch_assoc()) {
			$node[]=(int)$row["node"];
		}
		$result->close();
	}
	sort($node);
	$txt = "data=[";
	for ($i=0;$i<count($node);$i++) {
		if ($i!=(count($node)-1)) {
			$txt .= $node[$i].',';
		}
		else $txt .= $node[$i]."];\n";
	}
	echo $txt;
	/* Lấy dữ liệu để vẽ */
	$data_tc = array();
	$data_hu = array();
	for ($i=0;$i<count($node);$i++) {
		$data_tc[$node[$i]] = array();
		$data_hu[$node[$i]] = array();
		$query = "SELECT `time`,`tc`,`hu` FROM `$node[$i]` ORDER BY `stt` DESC limit 10";
		$result=$mysqli->query($query);
		if ($result->num_rows > 0) {
			while ($row=$result->fetch_assoc()) {
				$time = (double)$row['time'];
				if ((int)($time/1e+12) > 0) {
					$data_tc[$node[$i]][]= array($time,(float)$row['tc']);
					$data_hu[$node[$i]][]= array($time,(float)$row['hu']);
				} else {
					$time *= 1000;
					$data_tc[$node[$i]][]= array($time,(float)$row['tc']);
					$data_hu[$node[$i]][]= array($time,(float)$row['hu']);
				}
			}
			$result->close();
		}
	}
	echo 'var data_tc='.json_encode($data_tc,true).";\n";
	echo 'var data_hu='.json_encode($data_hu,true).";\n";
	?>
	$(document).ready(function() {
		InitChart(data);
        poll();
    });
</script>
<div class="body">
	<?php 
	for ($i=0;$i<count($node);$i++){
		$render->combo($node[$i],'chartTC'.$node[$i],'chartHU'.$node[$i]);
	}
	?>
</div>
<?php
$render->footer();
?>