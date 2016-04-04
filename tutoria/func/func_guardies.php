<?php

/*  ********************************************************************************************************
	getGuardiaDiaProfessor --> Guardias un dia,curso escolar y por un determinado profesor
	                           Para consultar si el profesor titular ha dejado faena
************************************************************************************************************ */
function getGuardiaDiaProfessor($dia,$curs,$professor) {
	 $sql  = "SELECT g.*,ec.descripcio AS espaicentre,fh.hora_inici,fh.hora_fi ";
	 $sql .= "FROM guardies g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges     = df.id_dies_franges ";
	 $sql .= "INNER JOIN franges_horaries   fh ON  fh.idfranges_horaries = df.idfranges_horaries ";
	 $sql .= "INNER JOIN espais_centre      ec ON  g.idespais_centre     = ec.idespais_centre ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND g.idprofessors=$professor AND df.iddies_setmana=$dia";
	 $sql .= " ORDER BY fh.idfranges_horaries";

	 $rec = mysql_query($sql);
     
	 //echo $sql."<br><br>";
	 
 	 return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getPrimeraGuardiaDiaProfessor --> hora de la primera guardia d'un dia i un professor
************************************************************************************************************ */
function getPrimeraGuardiaDiaProfessor($dia,$curs,$professor) {
	 $sql  = "SELECT fh.hora_inici AS hora ";
	 $sql .= "FROM guardies g ";
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
	getDarreraGuardiaDiaProfessor --> hora de la darrera guardia d'un dia i un professor
************************************************************************************************************ */
function getDarreraGuardiaDiaProfessor($dia,$curs,$professor) {
	 $sql  = "SELECT fh.hora_fi AS hora ";
	 $sql .= "FROM guardies g ";
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
	existsGuardiaDiaHoraProfessor --> Existe guardias un dia, una hora, curso escolar y por un determinado profesor
	                               Para dibujar el horario de un profesor
************************************************************************************************************ */
function existsGuardiaDiaHoraProfessor($dia,$franja,$curs,$professor) {
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
	 $sql .= "FROM guardies g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges    = df.id_dies_franges ";
	 $sql .= "INNER JOIN espais_centre      ec ON  g.idespais_centre    = ec.idespais_centre ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND g.idprofessors=$professor AND df.id_dies_franges=$diafranja";

	 $rec = mysql_query($sql);
     
	 $count = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 
	 if ($count==0) {
	    return 0;
	 }
	 else {
	 	return 1;
	 }
		
	 //echo $sql."<br><br>";
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getGuardiaDiaHoraProfessor --> Guardias un dia, una hora, curso escolar y por un determinado profesor
	                               Para dibujar el horario de un profesor
************************************************************************************************************ */
function getGuardiaDiaHoraProfessor($dia,$franja,$curs,$professor) {
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
	 $sql .= "FROM guardies g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges    = df.id_dies_franges ";
	 $sql .= "INNER JOIN espais_centre      ec ON  g.idespais_centre    = ec.idespais_centre ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND g.idprofessors=$professor AND df.id_dies_franges=$diafranja";

	 $rec = mysql_query($sql);
	 return $rec;
	 //echo $sql."<br><br>";
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getGuardiaDiaHora --> Guardias un dia, una hora, curso escolar 
	                               Para dibujar el horario global de guardias
************************************************************************************************************ */
function getGuardiaDiaHora($dia,$franja,$curs) {
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
	 $sql .= "FROM guardies g ";
	 $sql .= "INNER JOIN dies_franges       df ON  g.id_dies_franges    = df.id_dies_franges ";
	 $sql .= "INNER JOIN espais_centre      ec ON  g.idespais_centre    = ec.idespais_centre ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND df.id_dies_franges=$diafranja ";
	 $sql .= "ORDER BY g.idespais_centre";

	 $rec = mysql_query($sql);
	 return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	existsGuardiaSignada --> True si un profesor ha firmado la guardia
************************************************************************************************************ */
function existsGuardiaSignada($idprofessors,$idfranges_horaries,$data,$id_mat_uf_pla,$idgrups) {
     $sql = "SELECT idguardia_signada FROM guardies_signades WHERE idprofessors=$idprofessors AND idfranges_horaries=$idfranges_horaries AND data='$data' AND id_mat_uf_pla=$id_mat_uf_pla AND idgrups=$idgrups";
	 $rec = mysql_query($sql);
     $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);

	 if ($count==0) {
	    return 0;
	 }
	 else {
	 	return 1;
	 }
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	existsProfeGuardiaSignada --> True si un profesor ha firmado la guardia
************************************************************************************************************ */
function existsProfeGuardiaSignada($idfranges_horaries,$data,$id_mat_uf_pla,$idgrups) {
     $sql = "SELECT idguardia_signada FROM guardies_signades WHERE idfranges_horaries=$idfranges_horaries AND data='$data' AND id_mat_uf_pla=$id_mat_uf_pla AND idgrups=$idgrups";
	 $rec = mysql_query($sql);
     $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);

$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
fclose($fp);

	 if ($count==0) {
	    return 0;
	 }
	 else {
	 	return 1;
	 }
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getProfeGuardiaSignada --> 
************************************************************************************************************ */
function getProfeGuardiaSignada($idfranges_horaries,$data,$id_mat_uf_pla,$idgrups) {
     $sql = "SELECT idprofessors FROM guardies_signades WHERE idfranges_horaries=$idfranges_horaries AND data='$data' AND id_mat_uf_pla=$id_mat_uf_pla AND idgrups=$idgrups";
	 $rec = mysql_query($sql);
     $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);

	 if ($count==0) {
	    return 0;
	 }
	 else {
	 	return $result->idprofessors;
	 }
  }
/* ********************************************************************************************************* */
?>