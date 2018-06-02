<?php  
    require '../phpmongodb/vendor/autoload.php';

	$connection = new MongoDB\Client();
	$collection = $connection->messages->ReceivedData;
    $cursor = $collection->find([],['sort' => ['time' => 1]]);
    $result = array();
    $data   = array();
    //echo $_GET['time'];
    //die();
    //sleep(1);
    foreach ($cursor as $doc){
        if($_GET['time'] < $doc['time']) {
            //die($doc['time']);
            if (count($result)==0) {
                $data['node'] = json_decode($doc['node_eui']);
                $data['time'] = json_decode($doc['time'],true);
                $data['data'] = json_decode($doc['data'],true);
                //$result[] = $data;
                die(json_encode($data));
            }
        }
    }
    var_dump(json_encode($result));
    foreach ($result as $key => $val) {
        print_r($key); echo ' => '; print_r($val);

    }
?>
