<?php 
	define('DBHOST','fdb20.awardspace.net');
	define('DBNAME','2795746_iot');
	define('DBUSERNAME','2795746_iot');
	define('DBPASSWORD','2795746_iot');

	$mysqli = new mysqli(DBHOST,DBUSERNAME,DBPASSWORD,DBNAME);
	if ($mysqli->connect_errno)
		die ('Failed to connect to MySQL: ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
?>