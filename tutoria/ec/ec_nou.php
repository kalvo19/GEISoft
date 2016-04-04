<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$descripcio = $_REQUEST['descripcio'];
$activat    = $_REQUEST['activat'];

$sql = "insert into espais_centre(descripcio,activat) values ('$descripcio','$activat')";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>
