<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id         = intval($_REQUEST['idfranges_horaries']);
$idtorn     = $_REQUEST['idtorn'];
$hora_inici = $_REQUEST['hora_inici'];
$hora_fi    = $_REQUEST['hora_fi'];
$activada   = $_REQUEST['activada'];
$esbarjo    = $_REQUEST['esbarjo'];

$sql = "update franges_horaries set idtorn='$idtorn',hora_inici='$hora_inici',hora_fi='$hora_fi',activada='$activada',esbarjo='$esbarjo' where idfranges_horaries=$id";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>