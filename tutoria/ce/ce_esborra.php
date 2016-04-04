<?php
include('../bbdd/connect.php');

$id = intval($_REQUEST['idperiodes_escolars']);

$sql = "delete from periodes_escolars where idperiodes_escolars=$id";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>