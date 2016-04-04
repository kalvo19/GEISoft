<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idincidencia_alumne = isset($_REQUEST['idincidencia_alumne']) ? $_REQUEST['idincidencia_alumne'] : 0 ;
$comentari           = isset($_REQUEST['comentari']) ? str_replace("'","\'",$_REQUEST['comentari']) : '';

foreach ($idincidencia_alumne as $id_in) {
    $sql = "UPDATE incidencia_alumne SET comentari='$comentari',id_tipus_incidencia='".TIPUS_FALTA_ALUMNE_JUSTIFICADA."' WHERE idincidencia_alumne='$id_in'";
	$result = @mysql_query($sql);	
}

echo json_encode(array('success'=>true));
mysql_close();
?>