<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="w3.css">
    <link rel="stylesheet" href="part1.css">
    <link rel="stylesheet" href="mycss.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/control.js"></script>
    <script src="js/highcharts/highcharts.js"></script>
    <script src="js/highcharts/modules/series-label.js"></script>
    <script src="js/highcharts/modules/exporting.js"></script>
    <script src="js/highcharts/modules/export-data.js"></script>

    <title>Quản lý thiết bị</title>
    <script type="text/javascript">
        // noi khai bao bien toan cuc
        var data_node = new Array(); // data ban dau cho tung node
        var condition = 0;
        var time = 0;
        var chart = [];

        function nav_open() {
            document.getElementById("mySidebar").style.display = "block";
        }

        function nav_close() {
            document.getElementById("mySidebar").style.display = "none";
        }

        // cap nhat du lieu cho bang hien thi
        function update(data) {
            var html = '';
            var node = '#';
            html += '<tr>';
            html += '<th>RSSI</th>';
            html += '<th>SNR</th>';
            html += '<th>TC</th>';
            html += '<th>HU</th>';
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
            html += '<td>';
            html += data['data']['HU'];
            html += '</td>';
            html += '<tr>';
            node += data['node'];
            $(node).html(html);
        }

        function readNode() {
            var data = new Array();
            data = [];

            for (node in data) {
                chartjs(data[node], data_node[data[node]], 'TC');
                chartjs(data[node], data_hu[data[node]], 'HU');
                //console.log(data_node[data[node]]);
            }
            //console.log(chart);

        }

        Highcharts.setOptions({
            global: {
                useUTC: false
            },

        });

        function chartjs(node, datanode, namec) {
            chart[namec + node] = Highcharts.chart("chart_" + namec + node, {
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

        function poll() {
            setTimeout(function() {
                $.ajax({
                    url: "update_ui_mysql.php",
                    type: "get",
                    data: {
                        condition: condition,
                    },
                    success: function(data) {
                        //Update your dashboard
                        console.log(data);
                        time = data['time'];
                        condition = data['condition'];
                        update(data);
                        time = time * 1000;
                        var x = (new Date(time)).getTime(),
                            y = data['data']['TC'];
                        var y1 = data['data']['HU'];
                        chart['TC' + data['node']].series[0].addPoint([x, y]);
                        chart['HU' + data['node']].series[0].addPoint([x, y1]);
                        poll();
                    },
                    dataType: "json"
                });
            }, 0);
        }

        $(document).ready(function() {
            readNode();
            poll();
        });
    </script>
</head>

<body style="min-width:600px;background-color: lightgray">
    <div class="w3-container w3-card-4 w3-sidebar nav" style="z-index: 4;width: 250px;padding: 0;margin-top: 0;display:none" id="mySidebar">
        <details open>
            <summary title="iot">iot</summary>
            <nav>
                <div class="w3-large pa" onclick="nav_close()"><a href="javascript:void(0)" title="Close Sidemenu">Close</a></div>
                <div class="item"><a href="">Giám Sát</a><br></div>
                <div class="item"><a href="control.php">Điều Khiển</a><br></div>
                <div class="item"><a href="table-data-sql.php">Bảng Dữ Liệu</a></div>
                <div class="pp">
                    <p class="item"></p>
                </div>
            </nav>
        </details>
    </div>
    <!--Bat dau phan chua noi dung cua trang-->
    <div class="w3-main nav" style="z-index: 3;position:-webkit-sticky;position: sticky;top: 0;width: 100%;">
        <div class="w3-container w3-card-4 nav" style="height: 94px;">
            <button onclick="nav_open()" id="button">Menu</button>
            <h3 style="display: inline-block; margin-left: 16px;line-height:70px">Giám Sát thiết bị</h3>
        </div>
    </div>

    <div class="w3-main" style="margin:24px 0 24px 0px">
        <div class="w3-container w3-margin-top">
            <div class="w3-row-padding fixbody" style="margin: 0 auto;overflow:hidden">
            </div>
        </div>
    </div>
    <!--Ket thuc phan chua noi dung cua trang-->
</body>

</html>