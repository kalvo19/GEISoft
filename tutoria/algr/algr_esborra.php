<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');

$idalumnes    = isset($_REQUEST['idalumnes']) ? $_REQUEST['idalumnes'] : 0 ;
$idgrups      = isset($_REQUEST['idgrups'])   ? $_REQUEST['idgrups']   : 0 ;
$curs_escolar = $_SESSION['curs_escolar'];

$rsMateries = getMateriesGrup($curs_escolar,$idgrups);
while ($row = mysql_fetch_assoc($rsMateries)) {
    $idgrups_materies = $row['idgrups_materies'];
	$sql = "DELETE FROM alumnes_grup_materia WHERE idalumnes=$idalumnes AND idgrups_materies=$idgrups_materies";
	$result = @mysql_query($sql);
}

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_free_result($rsMateries);
mysql_close();
?>