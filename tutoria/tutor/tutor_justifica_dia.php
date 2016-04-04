<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idgrups   = $_REQUEST['grup'];
$idalumnes = $_REQUEST['idalumne'];
$data      = isset($_REQUEST['data_just']) ? substr($_REQUEST['data_just'],6,4)."-".substr($_REQUEST['data_just'],3,2)."-".substr($_REQUEST['data_just'],0,2) : '0000-00-00';
$curs      = $_SESSION['curs_escolar'];
$absencia  = isset($_REQUEST['absencia']) ? $_REQUEST['absencia'] : 0;
$retard    = isset($_REQUEST['retard'])   ? $_REQUEST['retard']   : 0;
$incident  = isset($_REQUEST['incident']) ? $_REQUEST['incident'] : 0;
$comentari = isset($_REQUEST['comentari']) ? str_replace("'","\'",$_REQUEST['comentari']) : '';

if (! exitsIncidenciaAlumnebyData($idalumnes,$data)) {
	// Justifiquem a futur, futures absències, retards ...
	$rsFranges    = getFrangesHoraries();				
    while ($row = mysql_fetch_assoc($rsFranges)) {
		$idfranges_horaries = $row['idfranges_horaries'];
		$rsMateriaAlumne    = getMateriesDiaHoraAlumne(date('w',strtotime($data)),$idfranges_horaries,$curs,$idalumnes);
		
		while ($row_mat = mysql_fetch_assoc($rsMateriaAlumne)) {
			
			$idmateria    = $row_mat['id_mat_uf_pla'];
			$idprofessors = getProfessorByGrupMateria($row_mat['idgrups_materies'])->idprofessors;
			
			$sql  = "INSERT INTO incidencia_alumne  (idalumnes,idgrups,id_mat_uf_pla,idprofessors,id_tipus_incidencia,data,idfranges_horaries,comentari) ";
			$sql .= "VALUES ($idalumnes,$idgrups,$idmateria,$idprofessors,".TIPUS_FALTA_ALUMNE_JUSTIFICADA.",'$data',$idfranges_horaries,'$comentari')";
			
			$result = @mysql_query($sql);
		}
	}
	
}

else {
	
	if ($idalumnes == 0) {
		
		if ($absencia) {
			$sql = "UPDATE incidencia_alumne SET comentari='$comentari',id_tipus_incidencia='".TIPUS_FALTA_ALUMNE_JUSTIFICADA."' WHERE idgrups='$idgrups' AND data='$data' AND id_tipus_incidencia=".TIPUS_FALTA_ALUMNE_ABSENCIA;
			$result = @mysql_query($sql);
		}
		
		if ($retard) {
			$sql = "UPDATE incidencia_alumne SET comentari='$comentari',id_tipus_incidencia='".TIPUS_FALTA_ALUMNE_JUSTIFICADA."' WHERE idgrups='$idgrups' AND data='$data' AND id_tipus_incidencia=".TIPUS_FALTA_ALUMNE_RETARD;
			$result = @mysql_query($sql);
		}
		
		if ($incident) {
			$sql = "UPDATE incidencia_alumne SET comentari='$comentari',id_tipus_incidencia='".TIPUS_FALTA_ALUMNE_JUSTIFICADA."' WHERE idgrups='$idgrups' AND data='$data' AND id_tipus_incidencia=".TIPUS_FALTA_ALUMNE_SEGUIMENT;
			$result = @mysql_query($sql);
		}
		
	}
	
	else {
	
		if ($absencia) {
			$sql = "UPDATE incidencia_alumne SET comentari='$comentari',id_tipus_incidencia='".TIPUS_FALTA_ALUMNE_JUSTIFICADA."' WHERE idgrups='$idgrups' AND idalumnes='$idalumnes' AND data='$data' AND id_tipus_incidencia=".TIPUS_FALTA_ALUMNE_ABSENCIA;
			$result = @mysql_query($sql);
		}
		
		if ($retard) {
			$sql = "UPDATE incidencia_alumne SET comentari='$comentari',id_tipus_incidencia='".TIPUS_FALTA_ALUMNE_JUSTIFICADA."' WHERE idgrups='$idgrups' AND idalumnes='$idalumnes' AND data='$data' AND id_tipus_incidencia=".TIPUS_FALTA_ALUMNE_RETARD;
			$result = @mysql_query($sql);
		}
		
		if ($incident) {
			$sql = "UPDATE incidencia_alumne SET comentari='$comentari',id_tipus_incidencia='".TIPUS_FALTA_ALUMNE_JUSTIFICADA."' WHERE idgrups='$idgrups' AND idalumnes='$idalumnes' AND data='$data' AND id_tipus_incidencia=".TIPUS_FALTA_ALUMNE_SEGUIMENT;
			$result = @mysql_query($sql);
		}
		
		//Emplenen les franges horàries que no tenen absències, retards, ...
		$rsFranges    = getFrangesHoraries();				
		while ($row = mysql_fetch_assoc($rsFranges)) {
			$idfranges_horaries = $row['idfranges_horaries'];
			if (! exitsIncidenciaAlumnebyDataFranja($idalumnes,$data,$idfranges_horaries)) {
				
				$rsMateriaAlumne = getMateriesDiaHoraAlumne(date('w',strtotime($data)),$idfranges_horaries,$curs,$idalumnes);
				while ($row_mat = mysql_fetch_assoc($rsMateriaAlumne)) {
					$idmateria    = $row_mat['id_mat_uf_pla'];
					$idprofessors = getProfessorByGrupMateria($row_mat['idgrups_materies'])->idprofessors;
					
					$sql  = "INSERT INTO incidencia_alumne  (idalumnes,idgrups,id_mat_uf_pla,idprofessors,id_tipus_incidencia,data,idfranges_horaries,comentari) ";
					$sql .= "VALUES ($idalumnes,$idgrups,$idmateria,$idprofessors,".TIPUS_FALTA_ALUMNE_JUSTIFICADA.",'$data',$idfranges_horaries,'$comentari')";
					
					$result = @mysql_query($sql);
				}
				
			}
			
		}
		
	
	}
	
}

echo json_encode(array('success'=>true));
if (isset($rsFranges)){
	mysql_free_result($rsFranges);
	mysql_free_result($rsMateriaAlumne);
}
mysql_close();
?>