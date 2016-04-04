<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idincidencia_alumne = isset($_REQUEST['idincidencia_alumne']) ? $_REQUEST['idincidencia_alumne'] : 0 ;

foreach ($idincidencia_alumne as $id_in) {
    $sql = "DELETE FROM incidencia_alumne WHERE idincidencia_alumne='$id_in'";
	$result = @mysql_query($sql);	
}

echo json_encode(array('success'=>true));
mysql_close();
?>