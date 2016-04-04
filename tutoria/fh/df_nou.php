<?php
session_start();
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idfranges_horaries = $_REQUEST['idfranges_horaries'];
$iddies_setmana     = $_REQUEST['iddies_setmana'];
$curs_escolar       = $_SESSION['curs_escolar'];

$sql = "insert into dies_franges (idfranges_horaries,iddies_setmana,idperiode_escolar) values ($idfranges_horaries,$iddies_setmana,$curs_escolar)";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>