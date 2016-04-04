<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id = intval($_REQUEST['id']);

$sql = "delete from periodes_escolars_festius where id_festiu=$id";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>
