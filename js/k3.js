/*
	Khai báo biến toàn cục cho chương trình
*/
/*
	data_node: Cần sử dụng php để tạo dữ liệu trước
*/
var condition = 0; // Sử dụng để lấy dữ liệu mới nhất
var chart = []; // mảng các biếu đồ

function openMenu() {
	var show = document.getElementById("sliebar").style;
	if (show.display=='') {
           show.display='block';
           document.getElementById("disible").style.display = "block";
        }
	else if (show.display=='block') {
           show.display='none';
           document.getElementById("disible").style.display = "none";  
        }
	else {
           show.display='block';
	   document.getElementById("disible").style.display = "block";
        }
}

function closeMenu() {
	var show = document.getElementById("sliebar").style;
	show.display='none';
	document.getElementById("disible").style.display = "none";
}

/* Thiết lập toà cục cho chart không sử dụng time UTC */
Highcharts.setOptions({
	global: {
		useUTC: false
	},
});
function chartjs(node, datanode, namec) {
	var myname = "chart" + namec + node;
	chart[namec + node] = Highcharts.chart(myname, {
		chart: {
			animation: false,
		},
		title: {
			text: false
		},
		xAxis: {
			type: 'datetime'
		},
		yAxis: {
			title: {
				text: false
			}
		},
		legend: {
			enabled: false
		},
		lang: {
			noData: "Khong co du lieu"
		},
		exporting: {
			enabled: false
		},
		credits: {
			enabled: false
		},
		tooltip: {
			formatter: function() {
				return '<b>' + this.series.name + '</b><br/>' +
				Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
				Highcharts.numberFormat(this.y, 2);
			}
		},
		series: [{
			name: namec,
			data: datanode
		}]
	});
}
// Lấy giá trị mảng dữ liệu và tạo mảng biểu đồ
function InitChart(array) {
	for (node in array) {
		chartjs(array[node],data_tc[array[node]],'TC');
		chartjs(array[node],data_hu[array[node]],'HU');
	}
}

/*
	tham số data là một Object: 
	{
		condition: int,
		data: Object:{RSSI: int,SNR: int,TC: int,...},
		node: int,
		time: milliseconds
	}
*/
function updateView(data) {
	var view = document.getElementById(data["node"]);
	var field = new Array();
	var value = new Array();
	var i=0;
	var txt='';
	for (var f in data["data"]) {
		field[i]=f;
		value[i++]=data["data"][f];
	}
	txt="<tr>";
	for (i=0;i<field.length;i++) {
		txt += "<th>"+field[i]+"</th>";
	}
	txt+="</tr>";
	txt+="<tr style=\"color:red;\">";
	for (i=0;i<field.length;i++) {
		txt += "<td>"+value[i]+"</td>";
	}
	txt+="</tr>";
	view.innerHTML = txt;
}

/* 
	AJAX Polling request data to update_ui_mysql.php
	data is Object Json:
	{
		condition: int,
		data: Object:{RSSI: int,SNR: int,TC: int,...},
		node: int,
		time: milliseconds
	}
*/
function poll() {
	setTimeout(function() {
		$.ajax({
			url: '../../../update_ui_mysql.php',
			type: "get",
			data: {
				condition: condition,
			},
			success: function(data) {
                //Update your dashboard
                console.log(data);
                time = data['time'];
                condition = data['condition'];
                updateView(data);
                time = time * 1000;
                var x = (new Date(time)).getTime(),y = data['data']['TC'];
                var y1 = data['data']['HU'];
                chart['TC' + data['node']].series[0].addPoint([x, y]);
                chart['HU' + data['node']].series[0].addPoint([x, y1]);
                poll();
            },
            dataType: "json"
        });
	}, 0);
}

/* Hàm scale sử dụng để click phóng to chart */
/*
var check=0;
function scale(e){
	var x=e.id;
	var a=document.getElementById(x).style;
	if(check==0){
		a.width="80%";
		check=1;
		}
	else{
		a.width="37.5%";
		
		
		check=0;
		console.log(check);
	};
}
*/