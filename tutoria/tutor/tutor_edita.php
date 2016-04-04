<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idincidencia_alumne  = intval($_REQUEST['id']);
$id_tipus_incidencia  = $_REQUEST['id_tipus_incidencia'];
$id_tipus_incident    = $_REQUEST['id_tipus_incident'];

$sql = "UPDATE incidencia_alumne SET id_tipus_incidencia='$id_tipus_incidencia',id_tipus_incident='$id_tipus_incident' WHERE idincidencia_alumne=$idincidencia_alumne";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>