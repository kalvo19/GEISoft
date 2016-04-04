<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idtorn  = intval($_REQUEST['id']);

$sql = "DELETE FROM torn WHERE idtorn=$idtorn";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>