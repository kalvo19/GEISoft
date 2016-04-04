<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id         = intval($_REQUEST['idespais_centre']);
$descripcio = $_REQUEST['descripcio'];
$activat    = $_REQUEST['activat'];

$sql = "update espais_centre set descripcio='$descripcio',activat='$activat' where idespais_centre=$id";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>