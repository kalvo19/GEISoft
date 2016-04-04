<?php
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
/*   validaFamilia --> Login usuari en la plataforma 
************************************************************************************************************ */
function validaFamilia($login,$contrasenya,$contacte_login,$contacte_contrasenya) {
    $sql  = "SELECT cf.id_families FROM contacte_families cf ";
	$sql .= "INNER JOIN families          f ON cf.id_families = f.idfamilies ";
	$sql .= "INNER JOIN alumnes_families af ON f.idfamilies   = af.idfamilies ";
	$sql .= "INNER JOIN alumnes           a ON af.idalumnes   = a.idalumnes ";
	$sql .= "WHERE a.acces_familia='S' AND f.activat='S' AND cf.id_tipus_contacte=$contacte_login AND cf.Valor='$login' ";
	$rec  = mysql_query($sql);
	$count = 0;
    $result = "";
	
    while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	}
	mysql_free_result($rec);
	if ($count == 0) {
		echo json_encode(array(
			'error' => true,
			'message' => 'usuari erroni!'
		));
		return 0;
	}
	else { 
	    $id_familia = $result->id_families;
	    $sql  = "SELECT cf.id_families FROM contacte_families cf ";
		$sql .= "INNER JOIN families f ON cf.id_families = f.idfamilies ";
		$sql .= "WHERE f.activat='S' AND cf.id_tipus_contacte=$contacte_contrasenya AND cf.id_families=$id_familia AND cf.Valor=MD5('$contrasenya')";
    	$rec = mysql_query($sql);
		$count = 0;
    	$result = ""; 
    	while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		}
	}
	
	mysql_free_result($rec);
	if ($count == 0) {
		echo json_encode(array(
			'error' => true,
			'message' => 'contrasenya err&ograve;nia!'
		));  
		return 0;
	}
	else { 
	    echo json_encode(array(
			'login' => $login,
			'passwd' => $contrasenya
		));
    	return $result->id_families;
	}
}

/* ********************************************************************************************************* */
/*  getFamilia --> Dades familia */
function getFamilia($idfamilies,$tipusContacte) {
	$sql  = "SELECT cf.Valor FROM contacte_families cf ";
	$sql .= "WHERE cf.id_tipus_contacte=$tipusContacte AND cf.id_families=$idfamilies ";
	
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
		return $result->Valor;
	}
}

/*  ********************************************************************************************************
	getValorTipusContacteFamilies --> Obtè el registre de la taula contacte_families amb un determinat valor de idtipus
************************************************************************************************************ */
function getValorTipusContacteFamilies($id_alumne,$idtipus_contacte) {
	 if ($id_alumne == 'undefined') {
	   $id_alumne = 0;
	 }
	 $sql  = "SELECT cf.* FROM contacte_families cf ";
	 $sql .= "INNER JOIN alumnes_families af ON cf.id_families = af.idfamilies ";
	 $sql .= "WHERE af.idalumnes=$id_alumne AND cf.id_tipus_contacte=$idtipus_contacte";
	 $rec  = mysql_query($sql); 
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
       return $result->Valor;
	 }
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	existValorTipusContacteFamilies --> Existeix el registre de la taula contacte_families amb un determinat valor de idtipus
************************************************************************************************************ */
function existValorTipusContacteFamilies($id_alumne,$idtipus_contacte) {
	 if ($id_alumne == 'undefined') {
	   $id_alumne = 0;
	 }
	 $sql  = "SELECT cf.* FROM contacte_families cf ";
	 $sql .= "INNER JOIN alumnes_families af ON cf.id_families = af.idfamilies ";
	 $sql .= "WHERE af.idalumnes=$id_alumne AND cf.id_tipus_contacte=$idtipus_contacte";

	 $rec  = mysql_query($sql); 
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
	getFamiliaAlumne --> Obtè el registre de la taula alumne_families amb un determinat valor de idalumnes
************************************************************************************************************ */
function getFamiliaAlumne($id_alumne) {
	 if ($id_alumne == 'undefined') {
	   $id_alumne = 0;
	 }
	 $sql  = "SELECT * FROM alumnes_families ";
	 $sql .= "WHERE idalumnes=$id_alumne";
	 $rec  = mysql_query($sql); 
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
            return $result->idfamilies;
	 }
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getAlumnesFamilia --> Obtè el registre de la taula alumne_families amb un determinat valor de idfamilies
************************************************************************************************************ */
function getAlumnesFamilia($idfamilies) {
	 if ($idfamilies == 'undefined') {
	   $idfamilies = 0;
	 }
	 $sql  = "SELECT * FROM alumnes_families ";
	 $sql .= "WHERE idfamilies=$idfamilies";
	 $rec  = mysql_query($sql); 
	 
	 return $rec;
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
/*   insertaLogFamilia --> Nova entrada al fitxer log de families 
************************************************************************************************************ */
function insertaLogFamilia($familia,$accio) {
	$data = date("Y/m/d");
	$hora = date("H:i:s");
    $sql  = "INSERT INTO log_families(data,hora,id_families,id_accio) ";
	$sql .= "VALUES ('$data','$hora',$familia,$accio) ";
	$rec  = mysql_query($sql);
	
	return 1;	
}

/* ********************************************************************************************************* */

?>