<?php
include('../bbdd/connect.php');

$id  = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0 ;

$sql    = "delete from tipus_incidents where idtipus_incidents=$id";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>