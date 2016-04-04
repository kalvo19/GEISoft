<?php
session_start();
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id                 = intval($_REQUEST['id']);
$idfranges_horaries = $_REQUEST['idfranges_horaries'];
$iddies_setmana     = $_REQUEST['iddies_setmana'];
$curs_escolar       = $_SESSION['curs_escolar'];

$sql = "update dies_franges set iddies_setmana='$iddies_setmana',idfranges_horaries='$idfranges_horaries',idperiode_escolar='$curs_escolar' where id_dies_franges=$id";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>