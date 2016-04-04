<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idgrups         = $_REQUEST['grup'];
$idalumnes       = $_REQUEST['idalumne_abs'];
$grup_materia    = $_REQUEST['grup_materia'];

$data_abs_desde  = isset($_REQUEST['data_abs_desde']) ? substr($_REQUEST['data_abs_desde'],6,4)."-".substr($_REQUEST['data_abs_desde'],3,2)."-".substr($_REQUEST['data_abs_desde'],0,2) : '0000-00-00';
$data_abs_finsa  = isset($_REQUEST['data_abs_finsa']) ? substr($_REQUEST['data_abs_finsa'],6,4)."-".substr($_REQUEST['data_abs_finsa'],3,2)."-".substr($_REQUEST['data_abs_finsa'],0,2) : '0000-00-00';
$curs            = getCursActual()->idperiodes_escolars;

$comentari = isset($_REQUEST['comentari']) ? str_replace("'","\'",$_REQUEST['comentari']) : '';

$data_inici  = $data_abs_desde ;
$data_fi     = $data_abs_finsa ;
   
$startdate   = strtotime($data_inici);
$enddate     = strtotime($data_fi);
   
while($startdate < $enddate){
   	  if (!festiu($data_fi,$periode)) {
		$diasetmana         = date_format(date_create($data_fi), 'w');
		$dataactual         = date_format(date_create($data_fi), 'Y-m-d');		
		
		$sql="SELECT id_dies_franges,idfranges_horaries FROM dies_franges WHERE iddies_setmana=".$diasetmana." ORDER BY id_dies_franges DESC";
		$rec = mysql_query($sql);

		
		while($row = mysql_fetch_object($rec)) {
		   $result = $row;
		   $id_dies_franges    = $result->id_dies_franges;
		   $idfranges_horaries = $result->idfranges_horaries;
		   $idmateria          = getGrupMateria($grup_materia)->id_mat_uf_pla;
		   $idprofessors       = getProfessorByGrupMateria($grup_materia)->idprofessors;
		   
		   $sql_uc  = "SELECT * FROM unitats_classe WHERE idgrups_materies='".$grup_materia."' AND ";
           $sql_uc .= "id_dies_franges= ".$id_dies_franges ;
		   $rec_uc  = mysql_query($sql_uc);
		   
		   
		   while($row_uc = mysql_fetch_object($rec_uc)) {
			   $result_uc = $row_uc;
			   
			   $sql  = "DELETE FROM incidencia_alumne ";
			   $sql .= "WHERE idalumnes=".$idalumnes." AND idgrups=".$idgrups." AND idfranges_horaries=".$idfranges_horaries." AND data='".$dataactual."' AND id_tipus_incidencia=".TIPUS_FALTA_ALUMNE_ABSENCIA;
				
			   $result = @mysql_query($sql);
					
			   $sql  = "INSERT INTO incidencia_alumne  (idalumnes,idgrups,id_mat_uf_pla,idprofessors,id_tipus_incidencia,data,idfranges_horaries,comentari) ";
			   $sql .= "VALUES ($idalumnes,$idgrups,$idmateria,$idprofessors,".TIPUS_FALTA_ALUMNE_ABSENCIA.",'$dataactual',$idfranges_horaries,'$comentari')";
				
			   $result = @mysql_query($sql);					   
		   }
		   	   
		}	
	  }
	  $data_fi = date("Y-m-d", strtotime("$data_fi -1 day")); 
   	  $startdate += 86400;
}
   
if (isset($rec)) {
   	mysql_free_result($rec);
}
if (isset($rec_uc)) {
   	mysql_free_result($rec_uc);
}


echo json_encode(array('success'=>true));
mysql_close();
?>