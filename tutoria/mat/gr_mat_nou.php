<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idgrups          = isset($_REQUEST['idgrup'])    ? $_REQUEST['idgrup'] : 0 ;
$idmateria        = isset($_REQUEST['idmateria']) ? $_REQUEST['idmateria'] : 0 ;

$sql              = "insert into grups_materies(id_grups,id_mat_uf_pla) values ('$idgrups','$idmateria')";
$result           = @mysql_query($sql);
$idgrups_materies = mysql_insert_id();

$rsAlumnes        = getAlumnesGrup($idgrups,TIPUS_nom_complet);
while ($row = mysql_fetch_assoc($rsAlumnes)) {
	$sql    = "INSERT INTO alumnes_grup_materia (idalumnes,idgrups_materies) VALUES ('".$row['idalumnes']."','$idgrups_materies')";
	$result = @mysql_query($sql);
}

echo json_encode(array('success'=>true));

mysql_free_result($rsAlumnes);
mysql_close();
?>