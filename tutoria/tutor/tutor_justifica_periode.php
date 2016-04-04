<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idgrups         = $_REQUEST['grup_periode'];
$idalumnes       = $_REQUEST['idalumne_periode'];

$data_just_desde = isset($_REQUEST['data_just_desde']) ? substr($_REQUEST['data_just_desde'],6,4)."-".substr($_REQUEST['data_just_desde'],3,2)."-".substr($_REQUEST['data_just_desde'],0,2) : '0000-00-00';
$data_just_finsa = isset($_REQUEST['data_just_finsa']) ? substr($_REQUEST['data_just_finsa'],6,4)."-".substr($_REQUEST['data_just_finsa'],3,2)."-".substr($_REQUEST['data_just_finsa'],0,2) : '0000-00-00';

$curs      = getCursActual()->idperiodes_escolars;
$absencia  = isset($_REQUEST['absencia_periode']) ? $_REQUEST['absencia_periode'] : 0;
$retard    = isset($_REQUEST['retard_periode'])   ? $_REQUEST['retard_periode']   : 0;
$incident  = isset($_REQUEST['incident_periode']) ? $_REQUEST['incident_periode'] : 0;
$comentari = isset($_REQUEST['comentari']) ? str_replace("'","\'",$_REQUEST['comentari']) : '';


if (date("Y-m-d")<$data_just_desde) {

	$begin = new DateTime( $data_just_desde );
	$end   = new DateTime( $data_just_finsa );
	$end   = $end->modify( '+1 day' );
	
	$interval  = new DateInterval('P1D');
	$daterange = new DatePeriod($begin, $interval ,$end);
	
	foreach($daterange as $data){
		
		$rsFranges    = getFrangesHoraries();
		$data_actual  = $data->format("Y-m-d");			
		while ($row = mysql_fetch_assoc($rsFranges)) {
			$idfranges_horaries = $row['idfranges_horaries'];
			$rsMateriaAlumne    = getMateriesDiaHoraAlumne(date('w',strtotime($data_actual)),$idfranges_horaries,$curs,$idalumnes);
			
			while ($row_mat = mysql_fetch_assoc($rsMateriaAlumne)) {
				
				$idmateria    = $row_mat['id_mat_uf_pla'];
				$idprofessors = getProfessorByGrupMateria($row_mat['idgrups_materies'])->idprofessors;
				
				/*
				$sql  = "DELETE FROM incidencia_alumne ";
				$sql .= "WHERE idalumnes=".$idalumnes." AND idfranges_horaries=".$idfranges_horaries." AND data='".$data."' AND id_tipus_incidencia=".TIPUS_FALTA_ALUMNE_JUSTIFICADA;
				
				$result = @mysql_query($sql);*/
					
				$sql  = "INSERT INTO incidencia_alumne  (idalumnes,idgrups,id_mat_uf_pla,idprofessors,id_tipus_incidencia,data,idfranges_horaries,comentari) ";
				$sql .= "VALUES ($idalumnes,$idgrups,$idmateria,$idprofessors,".TIPUS_FALTA_ALUMNE_JUSTIFICADA.",'$data_actual',$idfranges_horaries,'$comentari')";
				
				$result = @mysql_query($sql);
				
			}
		}
	}
	
}

else {


	if ($idalumnes == 0) {
		
		if ($absencia) {
			$sql = "UPDATE incidencia_alumne SET comentari='$comentari',id_tipus_incidencia='".TIPUS_FALTA_ALUMNE_JUSTIFICADA."' WHERE idgrups='$idgrups' AND data BETWEEN '$data_just_desde' AND '$data_just_finsa' AND id_tipus_incidencia=".TIPUS_FALTA_ALUMNE_ABSENCIA;
			$result = @mysql_query($sql);
		}
		
		if ($retard) {
			$sql = "UPDATE incidencia_alumne SET comentari='$comentari',id_tipus_incidencia='".TIPUS_FALTA_ALUMNE_JUSTIFICADA."' WHERE idgrups='$idgrups' AND data BETWEEN '$data_just_desde' AND '$data_just_finsa' AND id_tipus_incidencia=".TIPUS_FALTA_ALUMNE_RETARD;
			$result = @mysql_query($sql);
		}
		
		if ($incident) {
			$sql = "UPDATE incidencia_alumne SET comentari='$comentari',id_tipus_incidencia='".TIPUS_FALTA_ALUMNE_JUSTIFICADA."' WHERE idgrups='$idgrups' AND data BETWEEN '$data_just_desde' AND '$data_just_finsa' AND id_tipus_incidencia=".TIPUS_FALTA_ALUMNE_SEGUIMENT;
			$result = @mysql_query($sql);
		}
		
	}
	
	else {
	
		if ($absencia) {
			$sql = "UPDATE incidencia_alumne SET comentari='$comentari',id_tipus_incidencia='".TIPUS_FALTA_ALUMNE_JUSTIFICADA."' WHERE idgrups='$idgrups' AND idalumnes='$idalumnes' AND data BETWEEN '$data_just_desde' AND '$data_just_finsa' AND id_tipus_incidencia=".TIPUS_FALTA_ALUMNE_ABSENCIA;
			$result = @mysql_query($sql);
		}
		
		if ($retard) {
			$sql = "UPDATE incidencia_alumne SET comentari='$comentari',id_tipus_incidencia='".TIPUS_FALTA_ALUMNE_JUSTIFICADA."' WHERE idgrups='$idgrups' AND idalumnes='$idalumnes' AND data BETWEEN '$data_just_desde' AND '$data_just_finsa' AND id_tipus_incidencia=".TIPUS_FALTA_ALUMNE_RETARD;
			$result = @mysql_query($sql);
		}
		
		if ($incident) {
			$sql = "UPDATE incidencia_alumne SET comentari='$comentari',id_tipus_incidencia='".TIPUS_FALTA_ALUMNE_JUSTIFICADA."' WHERE idgrups='$idgrups' AND idalumnes='$idalumnes' AND data BETWEEN '$data_just_desde' AND '$data_just_finsa' AND id_tipus_incidencia=".TIPUS_FALTA_ALUMNE_SEGUIMENT;
			$result = @mysql_query($sql);
		}
	
	}
	
}

echo json_encode(array('success'=>true));
mysql_close();
?>