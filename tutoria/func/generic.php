<?php
/* ************************
    BIBLIOTECA DE FUNCIONS
	AUTOR: TONI LÓPEZ
	ANY: 2013
***************************  */

// Defincions tipus_contacte
	define("TOTAL_TIPUS_CONTACTE", countallTipusContacte());
	
	for ($i=1;$i<=TOTAL_TIPUS_CONTACTE;$i++) {
	   $nom_info  = getNomInfoTipusContacte($i);
	   define("TIPUS_".$nom_info, $i);
	}

// Includes
	include_once('func_alumnes.php');
	include_once('func_families.php');
	include_once('func_professors.php');
	include_once('func_materies.php');
	include_once('func_grups.php');
	include_once('func_espaiscentre.php');
	include_once('func_incidencies_alumne.php');
	include_once('func_incidencies_professor.php');
	include_once('func_guardies.php');
	include_once('func_ccc.php');
	include_once('func_dies.php');
	include_once('func_altres_hores.php');

/*  ********************************************************************************************************
/*   treureAccents --> Treure accents d'un string
************************************************************************************************************ */
function treureAccents ($cadena){
    $originales  = "ÀÁÂÄÅàáâäÒÓÔÖòóôöÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
    $modificadas = "AAAAAaaaaOOOOooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";

    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
    $cadena = strtolower($cadena);
    return utf8_encode($cadena);
}

/*  ********************************************************************************************************
/*   treureAccentsSms --> Treure accents d'un string
************************************************************************************************************ */

function treureAccentsSms ($cadena){
    $originales  = array("À","Á","à","á","Ò","Ó","ò","ó","È","É","è","é","Ç","ç","Ì","Í","ì","í","Ù","Ú","Ü","ù","ú","ü");
    $modificadas = array("A","A","à","a","O","O","ò","o","E","E","è","é","C","c","I","I","ì","i","U","U","U","ù","u","u");

    $cadena = str_replace($originales,$modificadas,$cadena);
    return $cadena;
}
	
/*  ********************************************************************************************************
/*   getDadesCentre --> Torna les dades del centre
************************************************************************************************************ */
function getDadesCentre() {
    $sql = "SELECT * FROM dades_centre WHERE iddades_centre = 1";
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
/*   getModulsActius --> Torna els moduls addcionals que el centre té actius
************************************************************************************************************ */
function getModulsActius() {
    $sql = "SELECT * FROM config";
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
    	return $result;
	}
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
/*   diaSemana --> Torna el dia de la setmana d'una determinada data 
************************************************************************************************************ */
function diaSemana($any,$mes,$dia) {
	// 0->diumenge	 | 6->dissabte
	$dia= date("w",mktime(0, 0, 0, $mes, $dia, $any));
	return $dia;
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
/*   getTipusContacte --> Torna el ID de la taula  
************************************************************************************************************ */
function getTipusContacte($strTipusContacte) {
    $sql  = "SELECT idtipus_contacte FROM tipus_contacte ";
	$sql .= "WHERE Nom_info_contacte = '$strTipusContacte'";
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
    	return $result->idtipus_contacte;
	}
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
/*   getNomInfoTipusContacte --> Torna el Nominfo de la taula tipus_contacte
************************************************************************************************************ */
function getNomInfoTipusContacte($idtipus_contacte) {
    $sql  = "SELECT Nom_info_contacte FROM tipus_contacte ";
	$sql .= "WHERE idtipus_contacte = $idtipus_contacte";
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
    	return $result->Nom_info_contacte;
	}
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getallPlaEstudis --> Obtè tots els registres de la taula plans_estudis
************************************************************************************************************ */
function getallPlaEstudis() {
	 $sql  = "SELECT * FROM plans_estudis";
	 $rec  = mysql_query($sql); 
	 
     return $rec;
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getallTipusContacte --> Obtè tots els registres de la taula tipus_contacte
************************************************************************************************************ */
function getallTipusContacte() {
	 $sql  = "SELECT * FROM tipus_contacte";
	 $rec  = mysql_query($sql); 
	 
     return $rec;
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	countallTipusContacte --> Conta tots els registres de la taula tipus_contacte
************************************************************************************************************ */
function countallTipusContacte() {
	 $sql  = "SELECT COUNT(*) AS total FROM tipus_contacte";
	 $rec  = mysql_query($sql); 
	 
     $count = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 return $result->total;
}
/* ********************************************************************************************************* */

/*   getDiaSetmana --> Dia setmana */
function getDiaSetmana($iddies_setmana) {
    $sql = "SELECT dies_setmana FROM dies_setmana WHERE iddies_setmana = $iddies_setmana";
    $rec = mysql_query($sql);
    $count = 0;
    $result = "";
    while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	}
	mysql_free_result($rec);
    return $result->dies_setmana;
}
/* ********************************************************************************************************* */

/*   getDiaSetmana --> Dia setmana */
function getMes($idmes) {
    $idmes       = intval($idmes);
	$array_mesos = array("GENER","FEBRER","MARÇ","ABRIL","MAIG","JUNY","JULIOL","AGOST","SETEMBRE","OCTUBRE","NOVEMBRE","DESEMBRE");

    return $array_mesos[$idmes-1];
}
/* ********************************************************************************************************* */

/*   getFrangesHoraries --> Totes les franges horàries */
function getFrangesHoraries() {
    $sql = "SELECT * FROM franges_horaries WHERE activada = 'S' AND esbarjo <> 'S' ORDER BY hora_inici";
    $rec = mysql_query($sql);
    
    return $rec;
}
/* ********************************************************************************************************* */

/*   getFranjaHoraria --> Dades d'una franja horària */
function getFranjaHoraria($idfranges_horaries) {
    $sql = "SELECT * FROM franges_horaries WHERE idfranges_horaries = $idfranges_horaries";
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

/*   getLiteralFranjaHoraria --> Franges horària */
function getLiteralFranjaHoraria($idfranges_horaries) {
    $sql = "SELECT CONCAT(LEFT(hora_inici,5),'-',LEFT(hora_fi,5)) AS hora  FROM franges_horaries WHERE idfranges_horaries = $idfranges_horaries";
    $rec = mysql_query($sql);
    
    $count = 0;
    $result = "";
    while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	}
	mysql_free_result($rec);
    if ($count == 0) {
		return "";
	}
	else {
		return $result->hora;
	}
}
/* ********************************************************************************************************* */

/*  getCursActual --> Curso actual */
function getCursActual() {
    $sql = "SELECT * FROM periodes_escolars WHERE actual = 'S'";
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

/*  getTorn --> Dades torn */
function getTorn($idtorn) {
    $sql = "SELECT * FROM torn WHERE idtorn = '$idtorn'";
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

/*  ******************************************************************************************************** */
/*   getLiteralCarrec --> Nom d'un carrec 
************************************************************************************************************ */
function getLiteralCarrec($idcarrecs) {
    $sql = "SELECT * FROM carrecs WHERE idcarrecs=".$idcarrecs;
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

/*  getIdTutor --> ID del càrrec Tutor a la taula mestra carrecs */
function getIdTutor() {
    $sql = "SELECT idcarrecs FROM carrecs WHERE nom_carrec = 'TUTOR'";
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

/*  comprovarHoraDia --> comprovar franja horaria */
function comprovarHoraDia($franja) {	
    $sql = "SELECT idfranges_horaries FROM franges_horaries WHERE hora_inici<='$franja' AND hora_fi>='$franja' LIMIT 0,1";
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
    	return $result->idfranges_horaries;
	}
}
/* ********************************************************************************************************* */

/*  comprovarHoraDiaTorn --> comprovar franja horaria diferents torns */
function comprovarHoraDiaTorn($franja) {	
    $sql = "SELECT idfranges_horaries FROM franges_horaries WHERE hora_inici<='$franja' AND hora_fi>='$franja'";
    $rec = mysql_query($sql);	
    $count = 0;
	$result = "";
		
    return $rec;
}
/* ********************************************************************************************************* */

/*  getSortida --> Dades sortida */
function getSortida($idsortides) {
    $sql = "SELECT * FROM sortides WHERE idsortides = '$idsortides'";
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

/*  getUnitatsClasse --> Dades horari */
function getUnitatsClasse($idunitats_classe) {
    $sql = "SELECT * FROM unitats_classe WHERE idunitats_classe = '$idunitats_classe'";
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

/*  getDiesFranges --> Dades dia-franja horària */
function getDiesFranges($id_dies_franges) {
    $sql = "SELECT * FROM dies_franges WHERE id_dies_franges = '$id_dies_franges'";
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

/*  existDiesFranges --> Existeix id per dia i franja */
function existDiesFranges($dia,$franja) {
    $sql = "SELECT id_dies_franges FROM dies_franges WHERE iddies_setmana=$dia AND idfranges_horaries=$franja";
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
		return $result->id_dies_franges;
	}
}
/* ********************************************************************************************************* */

?>