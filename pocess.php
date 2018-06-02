<?php  

//echo $_GET['status'].' '.$_GET['node'];

$x=$_GET['status'];
$y=$_GET['node'];
//echo "Trang thai: $x $y";

echo shell_exec("python test.py");
?>
