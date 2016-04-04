<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id         = intval($_REQUEST['id']);
$id_periode = $_REQUEST['id_periode'];
$festiu     = substr($_REQUEST['festiu'],6,4)."-".substr($_REQUEST['festiu'],3,2)."-".substr($_REQUEST['festiu'],0,2);

$sql = "update periodes_escolars_festius set id_periode='$id_periode',festiu='$festiu' where id_festiu=$id";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>