<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://fonts.googleapis.com/css?family=RobotoDraft" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="/iot/w3.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<title>Quản lý thiết bị</title>
	<style>
        .fixbody {width: 80%;}
		@media screen and (max-width: 1300px) {
			.fixsize {width: 100%;}
		}
        @media screen and (max-width: 720px) {
			.fixbody {width: 100%;}
		}
	</style>
<?php  
	require '../phpmongodb/vendor/autoload.php';
	$connection = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $connection->messages->ReceivedData;
	$cursor = $collection->find([],['sort' => ['time' => 1]]);
    $i=1;	
    $array_node = array();
	foreach ($cursor as $doc) {
        if($i==1) {
            $time=$doc['time']; 
            $i=0;
        }
  		$data_array =  json_decode($doc['data'],true);
  		if (count($array_node)==0 || !(in_array($doc['node_eui'], $array_node))) {
  			$array_node[$doc['node_eui']] = $data_array;
  		}
   		
	}

?>
<script>
var array_node = <?php echo json_encode($array_node); ?>;
var time=0;
// time hien tai cua data
time = <?php echo $time;?>;
var element;
console.log(time);
</script>
</head>
<body style="min-width:600px" onload="poll()">

	<div class="w3-container w3-white w3-card-4 w3-collapse w3-sidebar" style="z-index: 4;width: 250px;padding: 0;margin-top: 0;" id="mySidebar">
		<header class="w3-container w3-black w3-border-bottom" style="height: 64px; margin: 0;"><h1>Logo</h1></header>

		<!--Navigation-->
		<div class="w3-container w3-bar-block" style="padding: 0;">
			<h4 align="center">Menu</h4>
			<nav>
                <a href="javascript:void(0)" onclick="nav_close()" title="Close Sidemenu" class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>
				<a href="#" class="w3-bar-item w3-button w3-padding-16 w3-border-bottom" style="font-size: 18px"><span class="w3-xxlarge w3-margin-right"><i class="fa fa-home"></i></span>Home</a>
				<a href="#" class="w3-bar-item w3-button w3-padding-16 w3-border-bottom" style="font-size: 18px"><span class="w3-xxlarge w3-margin-right"><i class="fa fa-deviantart"></i></span>Device</b></a>
				<a href="#" class="w3-bar-item w3-button w3-padding-16 w3-border-bottom" style="font-size: 18px"><span class="w3-xxlarge w3-margin-right"><i class="fa fa-table"></i></span>Data</a>
			</nav>
		</div>
		<!--End Navigation-->
	</div>

	<div class="w3-main" style="margin-left: 250px;z-index: 3;position:-webkit-sticky;position: sticky;top: 0;width: 100%;">
		<div class="w3-container w3-card-4 w3-dark-gray" style="height: 64px;">
            <i class="fa fa-bars w3-button w3-white w3-hide-large w3-xlarge w3-margin-top" onclick="nav_open()"></i>
			Phan dung tieu de tung noi dung cua trang
		</div>
	</div>

	<div class="w3-main" style="margin-left: 250px">
		<div class="w3-container" style="height: 71px;"><h3>Phan cua Ten Navigation</h3></div>

		<!--Phan cua Noi Dung chu dao cua trang-->
		<div class="w3-container w3-margin-top">
			<div class="w3-row-padding w3-white fixbody" style="margin: 0 auto">

				<?php  

					foreach ($array_node as $node => $data) {
						echo '
							<div class="w3-third w3-panel fixsize" style="height: 204px;">
								<div class="w3-card-2 w3-round-xlarge" style="height: 100%;">
									<div class="w3-container" style="height: 100%">
										<div class="w3-display-container" style="height: 75%;">
											<img src="/iot/senc.svg" alt="iot" class="w3-display-topright" width="60px">
						';
						echo '<h4><b>'.$node.'</b></h4>';
						echo '<h4>Trang thai thiet bi</h4>';
						echo '<table align="center" width="100%" style="text-align: center" id="'.$node.'">';
                        echo '<tr>';
						foreach ($data as $k => $v) {
							echo '<th>'.$k.'</th>';
						}
						echo '</tr><tr>';
						foreach ($data as $k => $v) {
							echo '<td>'.$v.'</td>';
						}
						echo '</tr></table></div>';

						echo '
							<div class="w3-hover-none">
								<button class="w3-button w3-indigo w3-round-large w3-card w3-margin-bottom" onclick="on('.$node.')">Bật</button>
								<button class="w3-button w3-red w3-round-large w3-card w3-margin-bottom" onclick="off('.$node.')">Tắt</button>
							</div>
						</div>	
						</div>
						</div>
						';

						echo '
							<div class="w3-twothird w3-panel fixsize" style="height: 204px;">
								<div class="w3-card-2 w3-round" style="height:100% ;width: 100%">
									<div id="chart_'.$node.'" style="width: 100%"></div>
								</div>
							</div>
						';
					}	

				?>
				
			</div>
		</div>
		<!--Ket thuc phan chua noi dung cua trang-->
	</div>

<script>
/*Draw chart base on google API*/
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
var db = new Array();
for (element in array_node) {
    db[element] = new Array();
    db[element].push(['Time','TC']);
}
var i=0,tmp;
var options = {
        hAxis: {titleTextStyle: {color: '#333'}},
        vAxis: {minValue: 0},
        legend: { position: 'none' },
        pointSize: 5,
    };
function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Time', 'TC'],
        [0,0],
    ]);

    for (element in array_node) {
    	var chart = "chart_"+element;
    	array_node[element] = new google.visualization.LineChart(document.getElementById(chart));
    	array_node[element].draw(data,options);
    }
}

function updateChart(node, datab) {
    var chart = "chart_"+node;
    var data = google.visualization.arrayToDataTable(datab);
    chart = new google.visualization.LineChart(document.getElementById(chart));
    chart.draw(data,options);
}
/*End draw chart*/
/********************************************8*/
    function nav_open() {
        document.getElementById("mySidebar").style.display = "block";
    }

    function nav_close() {
    document.getElementById("mySidebar").style.display = "none";
    }    

	function on(number) {
		$.ajax({
                    url : "pocess.php",
                    type : "get",
                    dataType:"text",
                    data : {
                        status: "ON",
                        node : number
                    },
                    success : function (result){
                        alert(result);
                    }
                });
	}

    function off(number) {
		$.ajax({
                    url : "pocess.php",
                    type : "get",
                    dataType:"text",
                    data : {
                        status: "OFF",
                        node : number
                    },
                    success : function (result){
                        alert(result);
                    }
                });
	}
/*********************************************/
/*Update Data from MongoDB*/
function update(data) {
    var html = '';
    var node = '#';
    html += '<tr>';
    html += '<th>RSSI</th>';
    html += '<th>SNR</th>';
    html += '<th>TC</th>';
    html += '</tr>';
    html += '<tr>';
    html += '<td>';
    html += data['data']['RSSI'];
    html += '</td>';
    html += '<td>';
    html += data['data']['SNR'];
    html += '</td>';
    html += '<td>';
    html += data['data']['TC'];
    html += '</td>';
    html += '<tr>';
    node += data['node'];
    $(node).html(html);
}

function poll(){
    setTimeout(function(){
      $.ajax({ 
        url: "updateData.php", 
        type : "get",
        data: {
            time: time,
        },
        success: function(data){
            //Update your dashboard    
            console.log(data['data']);
            console.log(data['node']);
            time = data['time'];
            tmp = data['data']['TC'];
            if (db[data['node']].push([time,tmp]) == 50) {
                db[data['node']].splice(1,48);   
            }
            updateChart(data['node'],db[data['node']]);
                update(data);
            //Setup the next poll recursively
            poll();
        }, 
        dataType: "json"});
    }, 0);
};
/*End Update data*/
/******************************************/

</script>
</body>
</html>

