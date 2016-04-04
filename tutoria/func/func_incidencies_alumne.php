<?php

/*  ******************************************************************************************************** */
/*   getLiteralTipusIncident--> Nom d'un tipus d'incident
************************************************************************************************************ */
function getLiteralTipusIncident($idtipus_incident) {
    $sql = "SELECT * FROM tipus_incidents WHERE idtipus_incident=".$idtipus_incident;
    $rec = mysql_query($sql);
    
    $count = 0;
    $result = "";
    while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	}
	mysql_free_result($rec);
    return $result;
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getIncidencia --> Incidencia d'un alumne per ID
************************************************************************************************************ */
function getIncidencia($idincidencia_alumne) {
	 $sql  = "SELECT * ";
	 $sql .= "FROM incidencia_alumne ";
	 $sql .= "WHERE idincidencia_alumne=".$idincidencia_alumne;	

	 $rec = mysql_query($sql);
     $count = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 return $result;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	exitsIncidenciaAlumne --> Existeix incidència?
************************************************************************************************************ */
function exitsIncidenciaAlumne($idalumnes,$data,$idfranges_horaries) {
	 $sql  = "SELECT * ";
	 $sql .= "FROM incidencia_alumne ";
	 //$sql .= "WHERE idalumne_agrupament=".$idalumne_agrupament." AND idprofessors=".$idprofessors." AND data='".$data."' ";	
	 $sql .= "WHERE idalumnes=".$idalumnes." AND data='".$data."' ";	
	 $sql .= "AND idfranges_horaries=".$idfranges_horaries." ";
	 
	 $rec = mysql_query($sql);
     $count = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count == 0) {
	   return 0;
	 }
	 else {
       return $result->id_tipus_incidencia;
	 }
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getIncidenciaAlumne --> Incidencia d'un alumne per grup, materia, profesor i data
	                        Per llistar les faltes d'assistència abans de la seva edició
************************************************************************************************************ */
function getIncidenciaAlumne($idalumnes,$data,$idfranges_horaries) {
	 $sql  = "SELECT * ";
	 $sql .= "FROM incidencia_alumne ";
	 //$sql .= "WHERE idalumne_agrupament=".$idalumne_agrupament." AND idprofessors=".$idprofessors." AND data='".$data."' ";	
	 $sql .= "WHERE idalumnes=".$idalumnes." AND data='".$data."' ";	
	 $sql .= "AND idfranges_horaries=".$idfranges_horaries." ";

	 $rec = mysql_query($sql);
     $count = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 return $result;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	existsIncidenciaAlumnebyTipus --> Incidencia d'un alumne per data, franja horària i tipus
************************************************************************************************************ */
function existsIncidenciaAlumnebyTipus($idalumnes,$data,$idfranges_horaries,$id_tipus_incidencia) {
	 $sql  = "SELECT * ";
	 $sql .= "FROM incidencia_alumne ";
	 //$sql .= "WHERE idalumne_agrupament=".$idalumne_agrupament." AND idprofessors=".$idprofessors." AND data='".$data."' ";	
	 $sql .= "WHERE idalumnes=".$idalumnes." AND data='".$data."' ";	
	 $sql .= "AND idfranges_horaries=".$idfranges_horaries." AND id_tipus_incidencia=".$id_tipus_incidencia;

	 $rec = mysql_query($sql);
     $count = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count == 0) {
	   return 0;
	 }
	 else {
       return 1;
	 }
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	exitsIncidenciaAlumnebyData --> Indica si existeix una incidència per un alumne i dia
	                                Per justificar faltes a futur.
************************************************************************************************************ */
function exitsIncidenciaAlumnebyData($idalumnes,$data) {
	 $sql  = "SELECT id_tipus_incidencia ";
	 $sql .= "FROM incidencia_alumne ";
	 $sql .= "WHERE idalumnes=".$idalumnes." AND data='".$data."' ";	

	 $rec = mysql_query($sql);
     $count = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 
	 if ($count == 0) {
	   return 0;
	 }
	 else {
       return 1;
	 }
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	exitsIncidenciaAlumnebyDataFranja --> Indica si existeix una incidència per un alumne, dia i hora
	                                Per justificar faltes a futur.
************************************************************************************************************ */
function exitsIncidenciaAlumnebyDataFranja($idalumnes,$data,$idfranges_horaries) {
	 $sql  = "SELECT COUNT(id_tipus_incidencia) AS total ";
	 $sql .= "FROM incidencia_alumne ";
	 $sql .= "WHERE idalumnes=".$idalumnes." AND data='".$data."' ";	
	 $sql .= "AND idfranges_horaries=".$idfranges_horaries." ";
	 
	 $rec = mysql_query($sql);
     $count = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 
	 if ($count == 0) {
	   return 0;
	 }
	 else {
       return $result->total;
	 }
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getTotalIncidenciasAlumne --> Total Incidencies d'un alumne per tipus i entre dates
	                              Per informes de faltes d'assistència 
************************************************************************************************************ */
function getTotalIncidenciasAlumne($idalumnes,$id_tipus_incidencia,$data_inici,$data_fi) {
	 $sql  = "SELECT ia.idalumnes, ia.id_tipus_incidencia, COUNT( ia.id_tipus_incidencia ) AS total ";
	 $sql .= "FROM incidencia_alumne ia ";
	 $sql .= "WHERE ia.idalumnes = ".$idalumnes." AND ia.id_tipus_incidencia=".$id_tipus_incidencia;
     $sql .= " AND ia.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 	 
	 $rec = mysql_query($sql);
     $count = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count == 0) {
	   return 0;
	 }
	 else {
       return $result->total;
	 }
	
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getTotalIncidenciasAlumneGrupMateria --> Total Incidencies d'un alumne, grup i materia per tipus i entre dates
************************************************************************************************************ */
function getTotalIncidenciasAlumneGrupMateria($idalumnes,$id_tipus_incidencia,$idgrups,$id_mat_uf_pla,$data_inici,$data_fi) {
	 $sql  = "SELECT ia.idalumnes, ia.id_tipus_incidencia, COUNT( ia.id_tipus_incidencia ) AS total ";
	 $sql .= "FROM incidencia_alumne ia ";
	 $sql .= "WHERE ia.idalumnes = ".$idalumnes." AND ia.id_tipus_incidencia=".$id_tipus_incidencia;
     $sql .= " AND ia.idgrups=".$idgrups." AND ia.id_mat_uf_pla=".$id_mat_uf_pla;
	 $sql .= " AND ia.data BETWEEN '".$data_inici."' AND '".$data_fi."'";

	 $rec = mysql_query($sql);
     $count = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count == 0) {
	   return 0;
	 }
	 else {
       return $result->total;
	 }
	
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getTotalIncidenciasProfessor --> Total Incidencies d'un professor  per tipus i entre dates
	                                 Per informes de faltes d'assistència 
************************************************************************************************************ */
function getTotalIncidenciasProfessor($idprofessors,$id_tipus_incidencia,$data_inici,$data_fi) {
	 $sql  = "SELECT ia.idalumnes, ia.id_tipus_incidencia, COUNT(ia.id_tipus_incidencia) AS total ";
	 $sql .= "FROM incidencia_alumne ia ";
	 $sql .= "WHERE ia.idprofessors = ".$idprofessors." AND ia.id_tipus_incidencia=".$id_tipus_incidencia;
     $sql .= " AND ia.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 	 
	 $rec = mysql_query($sql);
     $count = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count == 0) {
	   return 0;
	 }
	 else {
       return $result->total;
	 }
	
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getTotalIncidenciasProfessorGrupMateria --> Total Incidencies d'un professor, grupi materia per tipus i entre dates
	                                            Per informes de faltes d'assistència 
************************************************************************************************************ */
function getTotalIncidenciasProfessorGrupMateria($idprofessors,$idgrups,$id_mat_uf_pla,$id_tipus_incidencia,$data_inici,$data_fi) {
	 $sql  = "SELECT ia.idalumnes, ia.id_tipus_incidencia, COUNT(ia.id_tipus_incidencia) AS total ";
	 $sql .= "FROM incidencia_alumne ia ";
	 $sql .= "WHERE ia.idprofessors = ".$idprofessors." AND ia.idgrups=".$idgrups." AND ia.id_tipus_incidencia=".$id_tipus_incidencia;
     $sql .= " AND ia.id_mat_uf_pla=".$id_mat_uf_pla." AND ia.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 	 
	 $rec = mysql_query($sql);
     $count = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count == 0) {
	   return 0;
	 }
	 else {
       return $result->total;
	 }
	
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getTotalIncidenciasProfessorGrup --> Total Incidencies d'un professor i grup per tipus i entre dates
	                                     Per informes de faltes d'assistència 
************************************************************************************************************ */
function getTotalIncidenciasProfessorGrup($idprofessors,$idgrups,$id_tipus_incidencia,$data_inici,$data_fi) {
	 $sql  = "SELECT ia.idalumnes, ia.id_tipus_incidencia, COUNT(ia.id_tipus_incidencia) AS total ";
	 $sql .= "FROM incidencia_alumne ia ";
	 $sql .= "WHERE ia.idprofessors = ".$idprofessors." AND ia.idgrups=".$idgrups." AND ia.id_tipus_incidencia=".$id_tipus_incidencia;
     $sql .= " AND ia.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 	 
	 $rec = mysql_query($sql);
     $count = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count == 0) {
	   return 0;
	 }
	 else {
       return $result->total;
	 }
	
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getTotalIncidenciasProfessorAlumne --> Total Incidencies d'un professor i alumne per tipus i entre dates
	                                       Per informes de faltes d'assistència 
************************************************************************************************************ */
function getTotalIncidenciasProfessorAlumne($idprofessors,$idalumnes,$id_tipus_incidencia,$data_inici,$data_fi) {
	 $sql  = "SELECT ia.idalumnes, ia.id_tipus_incidencia, COUNT(ia.id_tipus_incidencia) AS total ";
	 $sql .= "FROM incidencia_alumne ia ";
	 $sql .= "WHERE ia.idprofessors = ".$idprofessors." AND ia.idalumnes=".$idalumnes." AND ia.id_tipus_incidencia=".$id_tipus_incidencia;
     $sql .= " AND ia.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 	 
	 $rec = mysql_query($sql);
     $count = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count == 0) {
	   return 0;
	 }
	 else {
       return $result->total;
	 }
	
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getTotalIncidenciasGrup --> Total Incidencies d'un grup per tipus i entre dates
	                            Per informes de faltes d'assistència 
************************************************************************************************************ */
function getTotalIncidenciasGrup($idgrups,$id_tipus_incidencia,$data_inici,$data_fi) {
	 $sql  = "SELECT ia.idalumnes, ia.id_tipus_incidencia, COUNT( ia.id_tipus_incidencia ) AS total ";
	 $sql .= "FROM incidencia_alumne ia ";
	 $sql .= "WHERE ia.idgrups = ".$idgrups." AND ia.id_tipus_incidencia=".$id_tipus_incidencia;
     $sql .= " AND ia.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 	 
	 $rec = mysql_query($sql);
     $count = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count == 0) {
	   return 0;
	 }
	 else {
       return $result->total;
	 }
	
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getIncidenciasAlumne --> Detall Incidencies d'un alumne per tipus i entre dates
	                         Per informes de faltes d'assistència 
************************************************************************************************************ */
function getIncidenciasAlumne($idalumnes,$id_tipus_incidencia,$data_inici,$data_fi) {
	 $sql  = "SELECT ia.* ";
	 $sql .= "FROM incidencia_alumne ia ";
	 $sql .= "INNER JOIN franges_horaries fh ON ia.idfranges_horaries = fh.idfranges_horaries ";
	 $sql .= "WHERE ia.idalumnes = ".$idalumnes." AND ia.id_tipus_incidencia=".$id_tipus_incidencia;
     $sql .= " AND ia.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 $sql .= " ORDER BY ia.data,fh.hora_inici,ia.id_tipus_incidencia,ia.id_tipus_incident ";

	 $rec = mysql_query($sql);
     return $rec;	
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getIncidenciasPlaEstudis --> Detall Incidencies d'un alumne per tipus i entre dates per pla d'estudis
	                         		   Per l'informe global
************************************************************************************************************ */
function getIncidenciasPlaEstudis($idplans_estudis,$id_tipus_incidencia,$data_inici,$data_fi) {
	 $sql  = "SELECT ia.idalumnes,COUNT(DISTINCT ia.data) AS total ";
	 $sql .= "FROM incidencia_alumne ia ";
	 $sql .= "INNER JOIN moduls_materies_ufs m on ia.id_mat_uf_pla=m.id_mat_uf_pla ";
	 $sql .= "WHERE ia.id_tipus_incidencia=".$id_tipus_incidencia." AND m.idplans_estudis=".$idplans_estudis;
	 $sql .= " AND ia.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 $sql .= "GROUP BY ia.idalumnes";

	 $rec = mysql_query($sql);
     return $rec;
	
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getIncidenciasAlumneGrupMateria --> Detall Incidencies d'un alumne, grup i materia per tipus i entre dates
************************************************************************************************************ */
function getIncidenciasAlumneGrupMateria($idalumnes,$id_tipus_incidencia,$idgrups,$id_mat_uf_pla,$data_inici,$data_fi) {
	 $sql  = "SELECT ia.* ";
	 $sql .= "FROM incidencia_alumne ia ";
	 $sql .= "INNER JOIN franges_horaries fh ON ia.idfranges_horaries = fh.idfranges_horaries ";
	 $sql .= "WHERE ia.idalumnes = ".$idalumnes." AND ia.id_tipus_incidencia=".$id_tipus_incidencia;
     $sql .= " AND ia.idgrups=".$idgrups." AND ia.id_mat_uf_pla=".$id_mat_uf_pla;
	 $sql .= " AND ia.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 $sql .= " ORDER BY ia.data,fh.hora_inici,ia.id_tipus_incidencia,ia.id_tipus_incident ";
	 $rec = mysql_query($sql);
	 return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getIncidenciasGrup --> Detall Incidencies d'un grup per tipus i entre dates
	                       Per informes de faltes d'assistència 
************************************************************************************************************ */
function getIncidenciasGrup($idgrups,$id_tipus_incidencia,$data_inici,$data_fi) {
	 $sql  = "SELECT ia.* ";
	 $sql .= "FROM incidencia_alumne ia ";
	 $sql .= "INNER JOIN franges_horaries fh ON ia.idfranges_horaries = fh.idfranges_horaries ";
	 $sql .= "WHERE ia.idgrups = ".$idgrups." AND ia.id_tipus_incidencia=".$id_tipus_incidencia;
     $sql .= " AND ia.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 $sql .= " ORDER BY ia.data,fh.hora_inici,ia.id_tipus_incidencia,ia.id_tipus_incident ";
	 $rec = mysql_query($sql);
     return $rec;
	
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getIncidenciasProfessor --> Detall Incidencies d'un professor per tipus i entre dates
	                            Per informes de faltes d'assistència 
************************************************************************************************************ */
function getIncidenciasProfessor($idprofessors,$id_tipus_incidencia,$data_inici,$data_fi) {
	 $sql  = "SELECT ia.* ";
	 $sql .= "FROM incidencia_alumne ia ";
	 $sql .= "INNER JOIN franges_horaries fh ON ia.idfranges_horaries = fh.idfranges_horaries ";
	 $sql .= "WHERE ia.idprofessors = ".$idprofessors." AND ia.id_tipus_incidencia=".$id_tipus_incidencia;
     $sql .= " AND ia.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 $sql .= " ORDER BY ia.data,fh.hora_inici,ia.id_tipus_incidencia,ia.id_tipus_incident ";
	 $rec = mysql_query($sql);
     return $rec;
	
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getIncidenciasProfessorGrup --> Detall Incidencies d'un professor i grup per tipus i entre dates
	                                Per informes de faltes d'assistència 
************************************************************************************************************ */
function getIncidenciasProfessorGrup($idprofessors,$idgrups,$id_tipus_incidencia,$data_inici,$data_fi) {
	 $sql  = "SELECT ia.* ";
	 $sql .= "FROM incidencia_alumne ia ";
	 $sql .= "INNER JOIN franges_horaries fh ON ia.idfranges_horaries = fh.idfranges_horaries ";
	 $sql .= "WHERE ia.idprofessors = ".$idprofessors." AND ia.id_tipus_incidencia=".$id_tipus_incidencia;
     $sql .= " AND ia.idgrups=".$idgrups." AND ia.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 $sql .= " ORDER BY ia.data,fh.hora_inici,ia.id_tipus_incidencia,ia.id_tipus_incident ";
	 $rec = mysql_query($sql);
	 
     return $rec;
	
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getIncidenciasProfessorGrupAlumne --> Detall Incidencies d'un professor, grup i alumne per tipus i entre dates
	                                      Per informes de faltes d'assistència 
************************************************************************************************************ */
function getIncidenciasProfessorGrupAlumne($idprofessors,$idgrups,$idalumnes,$id_tipus_incidencia,$data_inici,$data_fi) {
	 $sql  = "SELECT ia.* ";
	 $sql .= "FROM incidencia_alumne ia ";
	 $sql .= "INNER JOIN franges_horaries fh ON ia.idfranges_horaries = fh.idfranges_horaries ";
	 $sql .= "WHERE ia.idprofessors = ".$idprofessors." AND ia.id_tipus_incidencia=".$id_tipus_incidencia;
     $sql .= " AND ia.idgrups=".$idgrups." AND ia.idalumnes=".$idalumnes." AND ia.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 $sql .= " ORDER BY ia.data,fh.hora_inici,ia.id_tipus_incidencia,ia.id_tipus_incident ";
	 $rec = mysql_query($sql);
	 
     return $rec;
	
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getIncidenciaProfessor --> Incidencia d'un profesor 
************************************************************************************************************ */
function getIncidenciaProfessor($idprofessors,$data,$idfranges_horaries) {
	 $sql  = "SELECT id_tipus_incidencia ";
	 $sql .= "FROM incidencia_professor ";
	 $sql .= "WHERE idprofessors=".$idprofessors." AND data='".$data."' ";	
	 $sql .= "AND idfranges_horaries=".$idfranges_horaries." ";

	 $rec = mysql_query($sql);
     $count = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count == 0) {
	   return 0;
	 }
	 else {
       return $result->id_tipus_incidencia;
	 }
  }
/* ********************************************************************************************************* */

?>