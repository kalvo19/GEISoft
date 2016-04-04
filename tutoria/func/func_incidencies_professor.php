<?php

/*  ********************************************************************************************************
	getIncidenciapProfessorbyID --> Incidencia d'un professor per ID
************************************************************************************************************ */
function getIncidenciapProfessorbyID($idincidencia_professor) {
	 $sql  = "SELECT * ";
	 $sql .= "FROM incidencia_professor ";
	 $sql .= "WHERE idincidencia_professor=".$idincidencia_professor;	

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
	exitsIncidenciapProfessor --> Existeix incidència?
************************************************************************************************************ */
function exitsIncidenciapProfessor($idprofessors,$data,$idfranges_horaries) {
	 $sql  = "SELECT * ";
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

/*  ********************************************************************************************************
	getIncidenciapProfessor --> Incidencia d'un professor per grup, materia i data
************************************************************************************************************ */
function getIncidenciapProfessor($idprofessors,$data,$idfranges_horaries) {
	 $sql  = "SELECT * ";
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
	 return $result;
  }
/* ********************************************************************************************************* */

?>