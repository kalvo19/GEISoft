<?php
include('../bbdd/connect.php');

$id = intval($_REQUEST['id']);
$op = $_REQUEST['op'];

$sql = "update alumnes set activat='$op' where idalumnes=$id";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>