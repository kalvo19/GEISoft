<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idalumnes    = isset($_REQUEST['idalumnes']) ? $_REQUEST['idalumnes'] : 0 ;
$idgrups      = isset($_REQUEST['idgrups'])   ? $_REQUEST['idgrups']   : 0 ;
$curs_escolar = $_SESSION['curs_escolar'];

$result = 0;
$rsMateries = getMateriesGrup($curs_escolar,$idgrups);
while ($row = mysql_fetch_assoc($rsMateries)) {
    $idgrups_materies = $row['idgrups_materies'];
	$sql = "INSERT INTO alumnes_grup_materia (idalumnes,idgrups_materies) VALUES ('$idalumnes','$idgrups_materies')";
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
