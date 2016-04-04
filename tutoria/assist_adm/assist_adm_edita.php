<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");
		
$idalumnes            = isset($_REQUEST['idalumnes'])           ? $_REQUEST['idalumnes']           : 0 ;
$idgrups_materies     = isset($_REQUEST['idgrups_materies'])    ? $_REQUEST['idgrups_materies']    : 0 ;
$id_tipus_incidencia  = isset($_REQUEST['id_tipus_incidencia']) ? $_REQUEST['id_tipus_incidencia'] : 0 ;
$idprofessors 		  = $_SESSION['professor'];
$data         		  = isset($_REQUEST['data'])            ? substr($_REQUEST['data'],6,4)."-".substr($_REQUEST['data'],3,2)."-".substr($_REQUEST['data'],0,2) : date("Y-m-d");
$idfranges_horaries   = isset($_REQUEST['idfranges_horaries']) ? $_REQUEST['idfranges_horaries']           : 0 ;
$comentari            = isset($_REQUEST['comentari'])       ? str_replace("'","\'",$_REQUEST['comentari']) : '' ;
$afegir               = isset($_REQUEST['afegir'])          ? $_REQUEST['afegir']                          : 0 ;

foreach ($idalumnes as $id_alumne) {
	$pos = 0;
	foreach ($idgrups_materies as $id_grups_materies) {
		$idgrups   = getGrupMateria($id_grups_materies)->id_grups;
		$idmateria = getGrupMateria($id_grups_materies)->id_mat_uf_pla;
		$id_fh     = $idfranges_horaries[$pos];
		//$idalumnes_grup_materia = getIDAlumneAgrupament($id_alumne,$id_grups_materies);
		
		
		if ($id_grups_materies != 0){
			//esborrar incidencia per aquell alumne y dia
			$sql = "DELETE FROM incidencia_alumne WHERE idalumnes='$id_alumne' AND data='$data' AND idfranges_horaries='$id_fh'";
			$result = @mysql_query($sql);
			
			if ($afegir == 1) {
				//insertar falta asistencia per aquell alumne i dia
				$sql  = "INSERT INTO incidencia_alumne (idalumnes,idgrups,id_mat_uf_pla,idprofessors,id_tipus_incidencia,data,idfranges_horaries,comentari) ";
				$sql .= "VALUES ('$id_alumne','$idgrups','$idmateria','$idprofessors','$id_tipus_incidencia','$data','$id_fh','$comentari')";
				$result = @mysql_query($sql);
				
			}
			
		}
		$pos++;	
	}
}

echo json_encode(array('success'=>true));

mysql_close();
?>