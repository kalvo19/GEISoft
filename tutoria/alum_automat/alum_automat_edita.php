<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");
		
$idalumnes            = isset($_REQUEST['idalumnes'])        ? $_REQUEST['idalumnes']        : 0 ;
$idgrups_materies     = isset($_REQUEST['idgrups_materies']) ? $_REQUEST['idgrups_materies'] : 0 ;
$afegir               = isset($_REQUEST['afegir'])           ? $_REQUEST['afegir']           : 0 ;

foreach ($idgrups_materies as $id_gm) {
		//$idalumnes_grup_materia = getIDAlumneAgrupament($id_alumne,$id_grups_materies);
		
		if ($id_gm != 0){
			
			$sql = "DELETE FROM alumnes_grup_materia WHERE idalumnes=$idalumnes AND idgrups_materies=$id_gm";
			$result = @mysql_query($sql);
			
			if ($afegir == 1) {			
				$sql  = "INSERT INTO alumnes_grup_materia (idalumnes,idgrups_materies) ";
				$sql .= "VALUES ($idalumnes,$id_gm)";
				$result = @mysql_query($sql);			
			}		
		}	
}

echo json_encode(array('success'=>true));
mysql_close();
?>