<?php
include('../bbdd/connect.php');

$id = intval($_REQUEST['id']);

$sql = "delete from espais_centre where idespais_centre=$id";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>