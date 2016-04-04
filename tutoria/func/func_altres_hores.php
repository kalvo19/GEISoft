<?php

/*  ********************************************************************************************************
	getDireccioDiaHoraProfessor --> Hores direcció un dia, una hora, curso escolar y por un determinado profesor
************************************************************************************************************ */
function getDireccioDiaHoraProfessor($dia,$franja,$curs,$professor) {
     $sql = "SELECT id_dies_franges FROM dies_franges WHERE iddies_setmana=$dia AND idfranges_horaries=$franja AND idperiode_escolar=$curs";
	 $rec = mysql_query($sql);
     $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 
	 if ($count==0) {
	 	$diafranja = 0;
	 }
	 else {
	 	$diafranja = $result->id_dies_franges;
	 }
	 
	 $sql  = "SELECT g.*,ec.descripcio AS espaicentre ";
	 $sql .= "FROM prof_direccio g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges    = df.id_dies_franges ";
	 $sql .= "INNER JOIN espais_centre      ec ON  g.idespais_centre    = ec.idespais_centre ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND g.idprofessors=$professor AND df.id_dies_franges=$diafranja";

	 $rec = mysql_query($sql);
	 return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getPrimeraDireccionDiaProfessor --> hora de la primera guardia d'un dia i un professor
************************************************************************************************************ */
function getPrimeraDireccionDiaProfessor($dia,$curs,$professor) {
	 $sql  = "SELECT fh.hora_inici AS hora ";
	 $sql .= "FROM prof_direccio g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges     = df.id_dies_franges ";
	 $sql .= "INNER JOIN franges_horaries   fh ON  fh.idfranges_horaries = df.idfranges_horaries ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND g.idprofessors=$professor AND df.iddies_setmana=$dia";
	 $sql .= " ORDER BY fh.hora_inici LIMIT 0,1";

	 $rec = mysql_query($sql);
     
	 $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count==0) {
	 	 return '00:00';
	 }
	 else {
		 return substr($result->hora,0,5);
	 }
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getDarreraDireccionDiaProfessor --> hora de la darrera guardia d'un dia i un professor
************************************************************************************************************ */
function getDarreraDireccionDiaProfessor($dia,$curs,$professor) {
	 $sql  = "SELECT fh.hora_fi AS hora ";
	 $sql .= "FROM prof_direccio g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges     = df.id_dies_franges ";
	 $sql .= "INNER JOIN franges_horaries   fh ON  fh.idfranges_horaries = df.idfranges_horaries ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND g.idprofessors=$professor AND df.iddies_setmana=$dia";
	 $sql .= " ORDER BY fh.hora_fi DESC LIMIT 0,1";

	 $rec = mysql_query($sql);
     
	 $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count==0) {
	 	 return '00:00';
	 }
	 else {
		 return substr($result->hora,0,5);
	 }
  }
/* ********************************************************************************************************* */



/*  ********************************************************************************************************
	getCoordinacioDiaHoraProfessor --> Hores coordinacio un dia, una hora, curso escolar y por un determinado profesor
************************************************************************************************************ */
function getCoordinacioDiaHoraProfessor($dia,$franja,$curs,$professor) {
     $sql = "SELECT id_dies_franges FROM dies_franges WHERE iddies_setmana=$dia AND idfranges_horaries=$franja AND idperiode_escolar=$curs";
	 $rec = mysql_query($sql);
     $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 
	 if ($count==0) {
	 	$diafranja = 0;
	 }
	 else {
	 	$diafranja = $result->id_dies_franges;
	 }
	 
	 $sql  = "SELECT g.*,ec.descripcio AS espaicentre ";
	 $sql .= "FROM prof_coordinacions g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges    = df.id_dies_franges ";
	 $sql .= "INNER JOIN espais_centre      ec ON  g.idespais_centre    = ec.idespais_centre ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND g.idprofessors=$professor AND df.id_dies_franges=$diafranja";

	 $rec = mysql_query($sql);
	 return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getPrimeraCoordinacioDiaProfessor --> hora de la primera guardia d'un dia i un professor
************************************************************************************************************ */
function getPrimeraCoordinacioDiaProfessor($dia,$curs,$professor) {
	 $sql  = "SELECT fh.hora_inici AS hora ";
	 $sql .= "FROM prof_coordinacions g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges     = df.id_dies_franges ";
	 $sql .= "INNER JOIN franges_horaries   fh ON  fh.idfranges_horaries = df.idfranges_horaries ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND g.idprofessors=$professor AND df.iddies_setmana=$dia";
	 $sql .= " ORDER BY fh.hora_inici LIMIT 0,1";

	 $rec = mysql_query($sql);
     
	 $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count==0) {
	 	 return '00:00';
	 }
	 else {
		 return substr($result->hora,0,5);
	 }
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getDarreraCoordinacioDiaProfessor --> hora de la darrera guardia d'un dia i un professor
************************************************************************************************************ */
function getDarreraCoordinacioDiaProfessor($dia,$curs,$professor) {
	 $sql  = "SELECT fh.hora_fi AS hora ";
	 $sql .= "FROM prof_coordinacions g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges     = df.id_dies_franges ";
	 $sql .= "INNER JOIN franges_horaries   fh ON  fh.idfranges_horaries = df.idfranges_horaries ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND g.idprofessors=$professor AND df.iddies_setmana=$dia";
	 $sql .= " ORDER BY fh.hora_fi DESC LIMIT 0,1";

	 $rec = mysql_query($sql);
     
	 $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count==0) {
	 	 return '00:00';
	 }
	 else {
		 return substr($result->hora,0,5);
	 }
  }
/* ********************************************************************************************************* */



/*  ********************************************************************************************************
	getAtencionsDiaHoraProfessor --> Hores atencio un dia, una hora, curso escolar y por un determinado profesor
************************************************************************************************************ */
function getAtencionsDiaHoraProfessor($dia,$franja,$curs,$professor) {
     $sql = "SELECT id_dies_franges FROM dies_franges WHERE iddies_setmana=$dia AND idfranges_horaries=$franja AND idperiode_escolar=$curs";
	 $rec = mysql_query($sql);
     $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 
	 if ($count==0) {
	 	$diafranja = 0;
	 }
	 else {
	 	$diafranja = $result->id_dies_franges;
	 }
	 
	 $sql  = "SELECT g.*,ec.descripcio AS espaicentre ";
	 $sql .= "FROM prof_atencions g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges    = df.id_dies_franges ";
	 $sql .= "INNER JOIN espais_centre      ec ON  g.idespais_centre    = ec.idespais_centre ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND g.idprofessors=$professor AND df.id_dies_franges=$diafranja";

	 $rec = mysql_query($sql);
	 return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getPrimeraAtencionsDiaProfessor --> hora de la primera guardia d'un dia i un professor
************************************************************************************************************ */
function getPrimeraAtencionsDiaProfessor($dia,$curs,$professor) {
	 $sql  = "SELECT fh.hora_inici AS hora ";
	 $sql .= "FROM prof_atencions g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges     = df.id_dies_franges ";
	 $sql .= "INNER JOIN franges_horaries   fh ON  fh.idfranges_horaries = df.idfranges_horaries ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND g.idprofessors=$professor AND df.iddies_setmana=$dia";
	 $sql .= " ORDER BY fh.hora_inici LIMIT 0,1";

	 $rec = mysql_query($sql);
     
	 $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count==0) {
	 	 return '00:00';
	 }
	 else {
		 return substr($result->hora,0,5);
	 }
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getDarreraAtencionsDiaProfessor --> hora de la darrera guardia d'un dia i un professor
************************************************************************************************************ */
function getDarreraAtencionsDiaProfessor($dia,$curs,$professor) {
	 $sql  = "SELECT fh.hora_fi AS hora ";
	 $sql .= "FROM prof_atencions g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges     = df.id_dies_franges ";
	 $sql .= "INNER JOIN franges_horaries   fh ON  fh.idfranges_horaries = df.idfranges_horaries ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND g.idprofessors=$professor AND df.iddies_setmana=$dia";
	 $sql .= " ORDER BY fh.hora_fi DESC LIMIT 0,1";

	 $rec = mysql_query($sql);
     
	 $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count==0) {
	 	 return '00:00';
	 }
	 else {
		 return substr($result->hora,0,5);
	 }
  }
/* ********************************************************************************************************* */



/*  ********************************************************************************************************
	getPermanenciesDiaHoraProfessor --> Hores permanencia un dia, una hora, curso escolar y por un determinado profesor
************************************************************************************************************ */
function getPermanenciesDiaHoraProfessor($dia,$franja,$curs,$professor) {
     $sql = "SELECT id_dies_franges FROM dies_franges WHERE iddies_setmana=$dia AND idfranges_horaries=$franja AND idperiode_escolar=$curs";
	 $rec = mysql_query($sql);
     $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 
	 if ($count==0) {
	 	$diafranja = 0;
	 }
	 else {
	 	$diafranja = $result->id_dies_franges;
	 }
	 
	 $sql  = "SELECT g.*,ec.descripcio AS espaicentre ";
	 $sql .= "FROM prof_permanencies g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges    = df.id_dies_franges ";
	 $sql .= "INNER JOIN espais_centre      ec ON  g.idespais_centre    = ec.idespais_centre ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND g.idprofessors=$professor AND df.id_dies_franges=$diafranja";

	 $rec = mysql_query($sql);
	 return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getPrimeraPermanenciesDiaProfessor --> hora de la primera guardia d'un dia i un professor
************************************************************************************************************ */
function getPrimeraPermanenciesDiaProfessor($dia,$curs,$professor) {
	 $sql  = "SELECT fh.hora_inici AS hora ";
	 $sql .= "FROM prof_permanencies g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges     = df.id_dies_franges ";
	 $sql .= "INNER JOIN franges_horaries   fh ON  fh.idfranges_horaries = df.idfranges_horaries ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND g.idprofessors=$professor AND df.iddies_setmana=$dia";
	 $sql .= " ORDER BY fh.hora_inici LIMIT 0,1";

	 $rec = mysql_query($sql);
     
	 $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count==0) {
	 	 return '00:00';
	 }
	 else {
		 return substr($result->hora,0,5);
	 }
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getDarreraPermanenciesDiaProfessor --> hora de la darrera guardia d'un dia i un professor
************************************************************************************************************ */
function getDarreraPermanenciesDiaProfessor($dia,$curs,$professor) {
	 $sql  = "SELECT fh.hora_fi AS hora ";
	 $sql .= "FROM prof_permanencies g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges     = df.id_dies_franges ";
	 $sql .= "INNER JOIN franges_horaries   fh ON  fh.idfranges_horaries = df.idfranges_horaries ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND g.idprofessors=$professor AND df.iddies_setmana=$dia";
	 $sql .= " ORDER BY fh.hora_fi DESC LIMIT 0,1";

	 $rec = mysql_query($sql);
     
	 $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count==0) {
	 	 return '00:00';
	 }
	 else {
		 return substr($result->hora,0,5);
	 }
  }
/* ********************************************************************************************************* */



/*  ********************************************************************************************************
	getReunionsDiaHoraProfessor --> Hores reunio un dia, una hora, curso escolar y por un determinado profesor
************************************************************************************************************ */
function getReunionsDiaHoraProfessor($dia,$franja,$curs,$professor) {
     $sql = "SELECT id_dies_franges FROM dies_franges WHERE iddies_setmana=$dia AND idfranges_horaries=$franja AND idperiode_escolar=$curs";
	 $rec = mysql_query($sql);
     $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 
	 if ($count==0) {
	 	$diafranja = 0;
	 }
	 else {
	 	$diafranja = $result->id_dies_franges;
	 }
	 
	 $sql  = "SELECT g.*,ec.descripcio AS espaicentre ";
	 $sql .= "FROM prof_reunions g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges    = df.id_dies_franges ";
	 $sql .= "INNER JOIN espais_centre      ec ON  g.idespais_centre    = ec.idespais_centre ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND g.idprofessors=$professor AND df.id_dies_franges=$diafranja";

	 $rec = mysql_query($sql);
	 return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getPrimeraReunionsDiaProfessor --> hora de la primera guardia d'un dia i un professor
************************************************************************************************************ */
function getPrimeraReunionsDiaProfessor($dia,$curs,$professor) {
	 $sql  = "SELECT fh.hora_inici AS hora ";
	 $sql .= "FROM prof_reunions g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges     = df.id_dies_franges ";
	 $sql .= "INNER JOIN franges_horaries   fh ON  fh.idfranges_horaries = df.idfranges_horaries ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND g.idprofessors=$professor AND df.iddies_setmana=$dia";
	 $sql .= " ORDER BY fh.hora_inici LIMIT 0,1";

	 $rec = mysql_query($sql);
     
	 $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count==0) {
	 	 return '00:00';
	 }
	 else {
		 return substr($result->hora,0,5);
	 }
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getDarreraReunionsDiaProfessor --> hora de la darrera guardia d'un dia i un professor
************************************************************************************************************ */
function getDarreraReunionsDiaProfessor($dia,$curs,$professor) {
	 $sql  = "SELECT fh.hora_fi AS hora ";
	 $sql .= "FROM prof_reunions g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges     = df.id_dies_franges ";
	 $sql .= "INNER JOIN franges_horaries   fh ON  fh.idfranges_horaries = df.idfranges_horaries ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND g.idprofessors=$professor AND df.iddies_setmana=$dia";
	 $sql .= " ORDER BY fh.hora_fi DESC LIMIT 0,1";

	 $rec = mysql_query($sql);
     
	 $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count==0) {
	 	 return '00:00';
	 }
	 else {
		 return substr($result->hora,0,5);
	 }
  }
/* ********************************************************************************************************* */



?>