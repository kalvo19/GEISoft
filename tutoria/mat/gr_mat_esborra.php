<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idmateria = isset($_REQUEST['idmateria']) ? $_REQUEST['idmateria'] : 0 ;
$idgrups   = isset($_REQUEST['idgrups']) ? $_REQUEST['idgrups'] : 0 ;

foreach ($idgrups as $id_grup) {
	$idgrups_materies = existGrupMateria($id_grup,$idmateria);
	
	$rsAlumnes        = getAlumnesGrup($id_grup,TIPUS_nom_complet);
	while ($row = mysql_fetch_assoc($rsAlumnes)) {
		$sql    = "DELETE FROM alumnes_grup_materia WHERE idgrups_materies='$idgrups_materies' AND idalumnes='".$row['idalumnes']."'";
		$result = @mysql_query($sql);		
	}
	
	$sql    = "DELETE FROM grups_materies WHERE idgrups_materies='$idgrups_materies'";
	$result = @mysql_query($sql);

}

echo json_encode(array('success'=>true));

mysql_free_result($rsAlumnes);
mysql_close();
?>