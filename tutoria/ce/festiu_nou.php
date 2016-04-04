<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id_periode = $_REQUEST['id_periode'];
$festiu     = substr($_REQUEST['festiu'],6,4)."-".substr($_REQUEST['festiu'],3,2)."-".substr($_REQUEST['festiu'],0,2);

$sql = "insert into periodes_escolars_festius (id_periode,festiu) values ($id_periode,'$festiu')";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>