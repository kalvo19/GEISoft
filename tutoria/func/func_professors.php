<?php

/*  ******************************************************************************************************** */
/*   isActivat --> Determina si un professor està o no actiu
************************************************************************************************************ */
function isActivat($professor) {
    $sql = "SELECT activat FROM professors WHERE idprofessors=$professor";
    $rec = mysql_query($sql);
    while($row = mysql_fetch_object($rec)) {
		$isActivat = $row->activat;
	}
    mysql_free_result($rec);
	
    return $isActivat;
}
/* ********************************************************************************************************* */

/*  ******************************************************************************************************** */
/*   getTipusFaltaProfessor --> Els tipus de faltes dels professors 
************************************************************************************************************ */
function getTipusFaltaProfessor() {
    $sql = "SELECT * FROM tipus_falta_professor WHERE tipus_falta NOT LIKE '%justif%'";
    $rec = mysql_query($sql);
    
    return $rec;
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
/*   validaProfessor --> Login usuari en la plataforma 
************************************************************************************************************ */
function validaProfessor($login,$contrasenya,$contacte_login,$contacte_contrasenya) {
    $sql  = "SELECT cp.id_professor FROM contacte_professor cp ";
	$sql .= "INNER JOIN professors p ON cp.id_professor = p.idprofessors ";
	$sql .= "WHERE p.activat='S' AND cp.id_tipus_contacte=$contacte_login AND cp.Valor='$login' ";
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
	    $id_professor = $result->id_professor;
	    $sql  = "SELECT cp.id_professor FROM contacte_professor cp ";
		$sql .= "INNER JOIN professors p ON cp.id_professor = p.idprofessors ";
		$sql .= "WHERE p.activat='S' AND cp.id_tipus_contacte=$contacte_contrasenya AND cp.id_professor=$id_professor AND cp.Valor=MD5('$contrasenya')";
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
    	return $result->id_professor;
	}
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
/*   validaRegistreProfessor --> Validar professor pel registre de entrada/sortida 
************************************************************************************************************ */
function validaRegistreProfessor($login,$contrasenya,$contacte_login,$contacte_contrasenya) {
    $sql  = "SELECT cp.id_professor FROM contacte_professor cp ";
	$sql .= "INNER JOIN professors p ON cp.id_professor = p.idprofessors ";
	$sql .= "WHERE p.activat='S' AND cp.id_tipus_contacte=$contacte_login AND cp.Valor='$login' ";
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
	    $id_professor = $result->id_professor;
	    $sql  = "SELECT cp.id_professor FROM contacte_professor cp ";
		$sql .= "INNER JOIN professors p ON cp.id_professor = p.idprofessors ";
		$sql .= "WHERE p.activat='S' AND cp.id_tipus_contacte=$contacte_contrasenya AND cp.id_professor=$id_professor AND cp.Valor=MD5('$contrasenya')";
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
		return 0;
	}
	else { 
    	return $result->id_professor;
	}
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
/*   isCarrec --> Determina si l'usuari que conecta te cert càrrec o no
************************************************************************************************************ */
function isCarrec($idprofessors,$idcarrecs) {
    $sql  = "SELECT idprofessor_carrec FROM professor_carrec ";
	$sql .= "WHERE idprofessors = $idprofessors AND idcarrecs=$idcarrecs";
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
/*   isCarrecInGrup --> Determina si l'usuari que conecta te cert càrrec o no en determinat grup
************************************************************************************************************ */
function isCarrecInGrup($idprofessors,$idcarrecs,$idgrups) {
    $sql  = "SELECT idprofessor_carrec FROM professor_carrec ";
	$sql .= "WHERE idprofessors = $idprofessors AND idcarrecs=$idcarrecs AND idgrups=$idgrups";
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
	getValorTipusContacteProfessor --> Obtè el registre de la taula contacte_professor amb un determinat valor de idtipus
************************************************************************************************************ */
function getValorTipusContacteProfessor($id_professor,$idtipus_contacte) {
	 if ($id_professor == 'undefined') {
	   $id_professor = 0;
	 }
	 $sql  = "SELECT * FROM contacte_professor WHERE id_professor=$id_professor AND id_tipus_contacte=$idtipus_contacte";
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
	existValorTipusContacteProfessor --> Existeix el registre de la taula contacte_professor amb un determinat valor de idtipus
************************************************************************************************************ */
function existValorTipusContacteProfessor($id_professor,$idtipus_contacte) {
	 if ($id_professor == 'undefined') {
	   $id_professor = 0;
	 }
	 $sql  = "SELECT * FROM contacte_professor WHERE id_professor=$id_professor AND id_tipus_contacte=$idtipus_contacte";
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

/* ********************************************************************************************************* */
/*   getCargosProfessor --> Obtiene los cargos asignados a un profesor                                       */
/* ********************************************************************************************************* */
function getCargosProfessor($idprofessors) {
	$sql  = "SELECT pc.*,g.idgrups,c.nom_carrec,g.nom ";
	$sql .= "FROM professor_carrec pc ";
	$sql .= "LEFT JOIN carrecs    c ON pc.idcarrecs    = c.idcarrecs ";
	$sql .= "LEFT JOIN grups      g ON pc.idgrups      = g.idgrups ";
	$sql .= "WHERE pc.idprofessors=$idprofessors ";
	$sql .= "ORDER BY c.nom_carrec,g.nom ";	
	
    $rec = mysql_query($sql);

    return $rec;
}
/* ********************************************************************************************************* */

/* ********************************************************************************************************* */
/*   getProfessorsbyCargos --> Obtiene los profesores con un determinado cargo                                    */
/* ********************************************************************************************************* */
function getProfessorsbyCargos($idcarrecs) {
	if ($idcarrecs == 'undefined') {
	   $idcarrecs = 0;
	}
	 
	$sql  = "SELECT pc.* ";
	$sql .= "FROM professor_carrec pc ";
	$sql .= "INNER JOIN professors p ON pc.idprofessors = p.idprofessors ";
	$sql .= "LEFT JOIN carrecs     c ON pc.idcarrecs    = c.idcarrecs ";
	$sql .= "LEFT JOIN grups       g ON pc.idgrups      = g.idgrups ";
	$sql .= "WHERE pc.idcarrecs=$idcarrecs AND p.activat='S' ";
	$sql .= "ORDER BY c.nom_carrec,g.nom ";	

    $rec = mysql_query($sql);

    return $rec;
}
/* ********************************************************************************************************* */

/* ********************************************************************************************************* */
/*  getProfessorsActius --> Professors en actiu                                                              */
/* ********************************************************************************************************* */
function getProfessorsActius($tipusContacte) {
	$sql  = "SELECT cp.Valor,p.idprofessors FROM contacte_professor cp ";
	$sql .= "INNER JOIN professors p ON p.idprofessors=cp.id_professor ";
	$sql .= "WHERE cp.id_tipus_contacte=$tipusContacte AND p.activat='S' ";
	$sql .= "ORDER BY 1 ";
	
    $rec = mysql_query($sql);
	return $rec;
}
/* ********************************************************************************************************* */

/* ********************************************************************************************************* */
/*  getProfessor --> Dades professor                                                                         */
/* ********************************************************************************************************* */
function getProfessor($idprofessors,$tipusContacte) {
	$sql  = "SELECT cp.Valor FROM contacte_professor cp ";
	$sql .= "WHERE cp.id_tipus_contacte=$tipusContacte AND cp.id_professor=$idprofessors ";
	
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
/* ********************************************************************************************************* */

/* ********************************************************************************************************* */
/*  getCodiProfessor --> Codi professor                                                                      */
/* ********************************************************************************************************* */
function getCodiProfessor($idprofessors) {
	if ($idprofessors == 'undefined') {
		$idprofessors = 0;
	}
	$sql  = "SELECT p.codi_professor FROM professors p ";
	$sql .= "WHERE p.idprofessors=$idprofessors ";
	
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
		return $result->codi_professor;
	}
}
/* ********************************************************************************************************* */

/* ********************************************************************************************************* */
/*  getIdProfessorByCarrecGrup --> ID professor a partir del seu càrrec i grup                                */
/* ********************************************************************************************************* */
function getIdProfessorByCarrecGrup($idcarrecs,$idgrups) {
    $sql = "SELECT idprofessors FROM professor_carrec WHERE idcarrecs = '$idcarrecs' AND idgrups = '$idgrups'";
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

/* ********************************************************************************************************* */
/*  getProfessorByGrupMateria --> Dades professor per grup y materia										 */
/* ********************************************************************************************************* */
function getProfessorByGrupMateria($idgrups_materies) {
	$sql  = "SELECT * FROM prof_agrupament pa ";
	$sql .= "INNER JOIN professors p ON pa.idprofessors = p.idprofessors ";
	$sql .= "WHERE pa.idagrups_materies = '$idgrups_materies' LIMIT 0,1 ";
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
	getMateriesDiaHoraProfessor --> Materias impartidas un dia, una hora, curso escolar y por un determinado profesor
	                                Para dibujar el horario de un profesor
************************************************************************************************************ */
function getMateriesDiaHoraProfessor($dia,$franja,$curs,$professor) {
     $sql = "SELECT id_dies_franges FROM dies_franges WHERE iddies_setmana=$dia AND idfranges_horaries=$franja AND idperiode_escolar=$curs";
	 $rec = mysql_query($sql);
     $count = 0;
	 $result = "";
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 if ($count==0) {
	 	$diafranja = 0;
	 }
	 else {
		 $diafranja = $result->id_dies_franges;
	 }
	 
	 $sql  = "SELECT uc.*,g.idgrups,m.idmateria,m.nom_materia AS materia,ec.descripcio AS espaicentre,g.nom as grup ";
	 $sql .= "FROM prof_agrupament pa ";
	 $sql .= "INNER JOIN unitats_classe     uc ON pa.idagrups_materies  = uc.idgrups_materies ";
	 $sql .= "INNER JOIN dies_franges       df ON uc.id_dies_franges    = df.id_dies_franges ";
	 $sql .= "INNER JOIN espais_centre      ec ON uc.idespais_centre    = ec.idespais_centre ";
	 $sql .= "INNER JOIN grups_materies     gm ON uc.idgrups_materies   = gm.idgrups_materies ";
	 $sql .= "INNER JOIN materia             m ON gm.id_mat_uf_pla      = m.idmateria ";
	 $sql .= "INNER JOIN grups               g ON gm.id_grups           = g.idgrups ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND pa.idprofessors=$professor AND uc.id_dies_franges=$diafranja";

	 $sql .= " UNION ";
	
	 $sql .= "SELECT uc.*,gm.id_grups AS idgrups,uf.idunitats_formatives AS idmateria,CONCAT(m.nom_modul,'-',uf.nom_uf) AS materia, ";
	 $sql .= "ec.descripcio AS espaicentre,g.nom as grup ";
	 $sql .= "FROM prof_agrupament pa ";
	 $sql .= "INNER JOIN unitats_classe     uc ON pa.idagrups_materies    = uc.idgrups_materies ";
	 $sql .= "INNER JOIN dies_franges       df ON uc.id_dies_franges      = df.id_dies_franges ";
	 $sql .= "INNER JOIN espais_centre      ec ON uc.idespais_centre      = ec.idespais_centre ";
	 $sql .= "INNER JOIN grups_materies     gm ON uc.idgrups_materies     = gm.idgrups_materies ";
	 $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla        = uf.idunitats_formatives ";
	 $sql .= "INNER JOIN moduls_ufs         mu ON uf.idunitats_formatives = mu.id_ufs ";
	 $sql .= "INNER JOIN moduls              m ON mu.id_moduls            = m.idmoduls ";
	 $sql .= "INNER JOIN grups               g ON gm.id_grups             = g.idgrups ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND pa.idprofessors=$professor AND uc.id_dies_franges=$diafranja ";
     $sql .= "AND gm.data_inici<='".date("y-m-d")."' AND gm.data_fi>='".date("y-m-d")."'";
	 
	 $rec = mysql_query($sql);
     
	 //echo $sql."<br><br>";
	 
 	 return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	existMateriaDiaHoraProfessor --> Existe Materia impartida un dia, una hora, curso escolar y por un determinado profesor
************************************************************************************************************ */
function existMateriaDiaHoraProfessor($dia,$franja,$curs,$professor) {
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
	 mysql_free_result($rec);
	 
	 $sql  = "SELECT uc.idgrups_materies ";
	 $sql .= "FROM prof_agrupament pa ";
	 $sql .= "INNER JOIN unitats_classe     uc ON pa.idagrups_materies  = uc.idgrups_materies ";
	 $sql .= "INNER JOIN dies_franges       df ON uc.id_dies_franges    = df.id_dies_franges ";
	 $sql .= "INNER JOIN grups_materies     gm ON uc.idgrups_materies   = gm.idgrups_materies ";
	 $sql .= "INNER JOIN materia             m ON gm.id_mat_uf_pla      = m.idmateria ";
	 $sql .= "INNER JOIN grups               g ON gm.id_grups           = g.idgrups ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND pa.idprofessors=$professor AND uc.id_dies_franges=$diafranja";

	 $sql .= " UNION ";
	
	 $sql .= "SELECT uc.idgrups_materies ";
	 $sql .= "FROM prof_agrupament pa ";
	 $sql .= "INNER JOIN unitats_classe     uc ON pa.idagrups_materies    = uc.idgrups_materies ";
	 $sql .= "INNER JOIN dies_franges       df ON uc.id_dies_franges      = df.id_dies_franges ";
	 $sql .= "INNER JOIN espais_centre      ec ON uc.idespais_centre      = ec.idespais_centre ";
	 $sql .= "INNER JOIN grups_materies     gm ON uc.idgrups_materies     = gm.idgrups_materies ";
	 $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla        = uf.idunitats_formatives ";
	 $sql .= "INNER JOIN moduls_ufs         mu ON uf.idunitats_formatives = mu.id_ufs ";
	 $sql .= "INNER JOIN moduls              m ON mu.id_moduls            = m.idmoduls ";
	 $sql .= "INNER JOIN grups               g ON gm.id_grups             = g.idgrups ";
	 $sql .= "WHERE df.idperiode_escolar=$curs AND pa.idprofessors=$professor AND uc.id_dies_franges=$diafranja ";
     $sql .= "AND gm.data_inici<='".date("y-m-d")."' AND gm.data_fi>='".date("y-m-d")."'";
	 
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
		 return $result->idgrups_materies;
	 }	 
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getGrupsProfessor --> Grupos de un determinado professor. Para observar si su horario pertence a distintos turnos
************************************************************************************************************ */
function getGrupsProfessor($professor) {
	 $count  = 0;
	 $result = "";
	 
	 $sql  = "SELECT DISTINCT(gr.idgrups), gr.nom, gr.Descripcio,gr.idtorn ";
	 $sql .= "FROM prof_agrupament pa ";
	 $sql .= "INNER JOIN grups_materies gm ON pa.idagrups_materies = gm.idgrups_materies ";
	 $sql .= "INNER JOIN materia        ma ON gm.id_mat_uf_pla     = ma.idmateria ";
	 $sql .= "INNER JOIN grups          gr ON gm.id_grups          = gr.idgrups ";
 	 $sql .= "WHERE pa.idprofessors='".$professor."' ";
  	 $sql .= "UNION ";
	 $sql .= "SELECT DISTINCT(gr.idgrups), gr.nom, gr.Descripcio,gr.idtorn ";
	 $sql .= "FROM prof_agrupament pa ";
	 $sql .= "INNER JOIN grups_materies     gm ON pa.idagrups_materies = gm.idgrups_materies ";
	 $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
	 $sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
	 $sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
	 $sql .= "INNER JOIN grups              gr ON gm.id_grups          = gr.idgrups ";
	 $sql .= "WHERE pa.idprofessors='".$professor."' ";
	
	 $sql .= "ORDER BY 1";
	 
	 $rec = mysql_query($sql);
	 
	 //echo $sql."<br><br>";
     return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getProfessorsGrup --> Professors,equip docent de un determinado grup. 
************************************************************************************************************ */
function getProfessorsGrup($idgrups) {
	 $count  = 0;
	 $result = "";
	 
	 $sql  = "SELECT DISTINCT(pa.idprofessors),cp.Valor AS nom,ma.nom_materia AS materia,ma.idmateria AS codi_materia ";
	 $sql .= "FROM prof_agrupament pa ";
	 $sql .= "INNER JOIN professors          p ON pa.idprofessors      = p.idprofessors ";
	 $sql .= "INNER JOIN contacte_professor cp ON cp.id_professor      = p.idprofessors ";
	 $sql .= "INNER JOIN grups_materies     gm ON pa.idagrups_materies = gm.idgrups_materies ";
	 $sql .= "INNER JOIN materia            ma ON gm.id_mat_uf_pla     = ma.idmateria ";
	 $sql .= "INNER JOIN grups              gr ON gm.id_grups          = gr.idgrups ";
 	 $sql .= "WHERE gm.id_grups='".$idgrups."' AND cp.id_tipus_contacte=".TIPUS_nom_complet;
  	 $sql .= " UNION ";
	 $sql .= "SELECT DISTINCT(pa.idprofessors),cp.Valor AS nom,CONCAT(m.nom_modul,'-',uf.nom_uf) AS materia,mu.id_ufs AS codi_materia ";
	 $sql .= "FROM prof_agrupament pa ";
	 $sql .= "INNER JOIN professors          p ON pa.idprofessors      = p.idprofessors ";
	 $sql .= "INNER JOIN contacte_professor cp ON cp.id_professor      = p.idprofessors ";
	 $sql .= "INNER JOIN grups_materies     gm ON pa.idagrups_materies = gm.idgrups_materies ";
	 $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
	 $sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
	 $sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
	 $sql .= "INNER JOIN grups              gr ON gm.id_grups          = gr.idgrups ";
	 $sql .= "WHERE gm.id_grups='".$idgrups."' AND cp.id_tipus_contacte=".TIPUS_nom_complet;
	
	 $sql .= " ORDER BY 2,3";
	 
	 $rec = mysql_query($sql);
	 
	 //echo $sql."<br><br>";
     return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
/*   insertaLogProfessor --> Nova entrada al fitxer log de professors 
************************************************************************************************************ */
function insertaLogProfessor($professor,$accio) {
	$data = date("Y/m/d");
	$hora = date("H:i:s");
	
    $sql  = "INSERT INTO log_professors(data,hora,id_professor,id_accio) ";
	$sql .= "VALUES ('$data','$hora',$professor,$accio) ";
	$rec  = mysql_query($sql);
    
	return 1;	
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
/*   getFirstLogProfessor --> Hora de la darrer log d'un professor i una determinada accio
************************************************************************************************************ */
function getFirstLogProfessor($professor,$data,$accio) {
	//$data = date("Y/m/d");
	
    $sql  = "SELECT hora FROM log_professors ";
	$sql .= "WHERE data='$data' AND id_professor=$professor AND id_accio=$accio ";
	$sql .= "ORDER BY hora ASC LIMIT 0,1 ";
	$rec  = mysql_query($sql);
	$count = 0;

	while($row = mysql_fetch_object($rec)) {
		$count++;
		$result = $row;
	}
	 
	 if ($count==0) {
	 	$hora_log = '00:00';
	 }
	 else {
	 	$hora_log = $result->hora;
	 }

	return $hora_log;	
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
/*   getLastLogProfessor --> Hora de la darrer log d'un professor i una determinada accio
************************************************************************************************************ */
function getLastLogProfessor($professor,$data,$accio) {
	//$data = date("Y/m/d");
	
    $sql  = "SELECT hora FROM log_professors ";
	$sql .= "WHERE data='$data' AND id_professor=$professor AND id_accio=$accio ";
	$sql .= "ORDER BY hora DESC LIMIT 0,1 ";
	$rec  = mysql_query($sql);
	$count = 0;

	while($row = mysql_fetch_object($rec)) {
		$count++;
		$result = $row;
	}
	 
	 if ($count==0) {
	 	$hora_log = '00:00';
	 }
	 else {
	 	$hora_log = $result->hora;
	 }

	return $hora_log;	
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
/*   validEntryLogProfessor --> Coincideixen franjes horaries actual i la darrera entrada?
************************************************************************************************************ */
function validEntryLogProfessor($professor,$accio) {
	$data   = date("Y/m/d");
	$hora   = date("H:i:s");
	$valida = 1;
	
	$rsFrangesActuals        = comprovarHoraDiaTorn($hora);
	$hora_darrera_entrada    = getLastLogProfessor($professor,$data,$accio);
	$rsFrangesDarreraEntrada = comprovarHoraDiaTorn($hora_darrera_entrada);
 	
	while ($row_fa = mysql_fetch_assoc($rsFrangesActuals)) {
		while ($row_de = mysql_fetch_assoc($rsFrangesDarreraEntrada)) {	
			if ($row_fa['idfranges_horaries'] == $row_de['idfranges_horaries']) {
				$valida = 0;
			}
		}
	}
	
	if (isset($rsFrangesActuals)) {
		mysql_free_result($rsFrangesActuals);
	}
	
	if (isset($rsFrangesDarreraEntrada)) {
		mysql_free_result($rsFrangesDarreraEntrada);
	}
						  
	return $valida;	
}
/* ********************************************************************************************************* */
/*  ********************************************************************************************************
/*   existLogProfessorData --> existeix una entrada del tipos $accio al log de professors
************************************************************************************************************ */
function existLogProfessorData($professor,$accio,$data) {
	$sql  = "SELECT hora FROM log_professors ";
	$sql .= "WHERE data='$data' AND id_professor=$professor AND id_accio=$accio ";
	$sql .= "ORDER BY hora DESC LIMIT 0,1 ";
	$rec  = mysql_query($sql);
	$count = 0;

	while($row = mysql_fetch_object($rec)) {
		$count++;
		$result = $row;
	}
	 
	return $count;
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getProfessorsLogAccioData --> Professors que han fet una eterminada acció en una determinada data. 
************************************************************************************************************ */
function getProfessorsLogAccioData($accio,$data) {
	$sql  = "SELECT lp.*,cp.Valor FROM log_professors lp ";
	$sql .= "INNER JOIN contacte_professor cp ON cp.id_professor = lp.id_professor ";
	$sql .= "WHERE lp.data='$data' AND lp.id_accio=$accio AND cp.id_tipus_contacte=".TIPUS_nom_complet;
	$sql .= " GROUP BY 4 ORDER BY 5";
	$rec  = mysql_query($sql);
    
	return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getHoraEntradaProfessorDia --> Hora d'entrada d'un professor al centre una data determinada 
************************************************************************************************************ */
function getHoraEntradaProfessorDia($professor,$data,$curs_escolar) {
	$dia_setmana = date('w', strtotime($data));
	
	$sql   = "SELECT LEFT(fh.hora_inici,5) AS hora ";
	$sql  .= "FROM prof_agrupament pa INNER JOIN unitats_classe     uc ON pa.idagrups_materies  = uc.idgrups_materies "; 
	$sql  .= "INNER JOIN dies_franges       df ON uc.id_dies_franges    = df.id_dies_franges "; 
	$sql  .= "INNER JOIN franges_horaries   fh ON df.idfranges_horaries = fh.idfranges_horaries "; 
	$sql  .= "INNER JOIN grups_materies     gm ON uc.idgrups_materies   = gm.idgrups_materies "; 
	$sql  .= "WHERE df.iddies_setmana=$dia_setmana AND fh.esbarjo<>'S' AND df.idperiode_escolar=$curs_escolar ";
	$sql  .= "AND pa.idprofessors=$professor "; 
	$sql  .= "UNION "; 
	$sql  .= "SELECT LEFT(fh.hora_inici,5) AS hora ";
	$sql  .= "FROM prof_agrupament pa "; 
	$sql  .= "INNER JOIN unitats_classe     uc ON pa.idagrups_materies  = uc.idgrups_materies "; 
	$sql  .= "INNER JOIN dies_franges       df ON uc.id_dies_franges    = df.id_dies_franges "; 
	$sql  .= "INNER JOIN franges_horaries   fh ON df.idfranges_horaries = fh.idfranges_horaries "; 
	$sql  .= "INNER JOIN grups_materies     gm ON uc.idgrups_materies   = gm.idgrups_materies "; 
	$sql  .= "WHERE df.iddies_setmana=$dia_setmana AND fh.esbarjo<>'S' AND df.idperiode_escolar=$curs_escolar ";
	$sql  .= "AND pa.idprofessors=$professor AND gm.data_inici<=$data AND gm.data_fi>=$data ";
	$sql  .= "ORDER BY 1 LIMIT 0,1 ";
	
	$rec  = mysql_query($sql);
    
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
		 return $result->hora;
	}	 
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getHoraSortidadaProfessorDia --> Hora de sortida d'un professor al centre una data determinada 
************************************************************************************************************ */
function getHoraSortidaProfessorDia($professor,$data,$curs_escolar) {
	$dia_setmana = date('w', strtotime($data));
	
	$sql   = "SELECT LEFT(fh.hora_fi,5) AS hora ";
	$sql  .= "FROM prof_agrupament pa INNER JOIN unitats_classe     uc ON pa.idagrups_materies  = uc.idgrups_materies "; 
	$sql  .= "INNER JOIN dies_franges       df ON uc.id_dies_franges    = df.id_dies_franges "; 
	$sql  .= "INNER JOIN franges_horaries   fh ON df.idfranges_horaries = fh.idfranges_horaries "; 
	$sql  .= "INNER JOIN grups_materies     gm ON uc.idgrups_materies   = gm.idgrups_materies "; 
	$sql  .= "WHERE df.iddies_setmana=$dia_setmana AND fh.esbarjo<>'S' AND df.idperiode_escolar=$curs_escolar ";
	$sql  .= "AND pa.idprofessors=$professor "; 
	$sql  .= "UNION "; 
	$sql  .= "SELECT LEFT(fh.hora_fi,5) AS hora ";
	$sql  .= "FROM prof_agrupament pa "; 
	$sql  .= "INNER JOIN unitats_classe     uc ON pa.idagrups_materies  = uc.idgrups_materies "; 
	$sql  .= "INNER JOIN dies_franges       df ON uc.id_dies_franges    = df.id_dies_franges "; 
	$sql  .= "INNER JOIN franges_horaries   fh ON df.idfranges_horaries = fh.idfranges_horaries "; 
	$sql  .= "INNER JOIN grups_materies     gm ON uc.idgrups_materies   = gm.idgrups_materies "; 
	$sql  .= "WHERE df.iddies_setmana=$dia_setmana AND fh.esbarjo<>'S' AND df.idperiode_escolar=$curs_escolar ";
	$sql  .= "AND pa.idprofessors=$professor AND gm.data_inici<=$data AND gm.data_fi>=$data ";
	$sql  .= "ORDER BY 1 DESC LIMIT 0,1 ";
	
	$rec  = mysql_query($sql);
    
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
		 return $result->hora;
	}	 
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	existProfessorSortidaData --> En tal data hi ha tal professor de sortida?
************************************************************************************************************ */
function existProfessorSortidaData($id_professor,$data,$hora) {
	 $count  = 0;
	 $result = "";
	 
	 $sql  = "SELECT s.idsortides ";
	 $sql .= "FROM sortides s ";
	 $sql .= "INNER JOIN sortides_professor sp ON sp.id_sortida = s.idsortides ";
 	 $sql .= "WHERE sp.id_professorat=".$id_professor;
	 $sql .= " AND s.data_inici<='".$data."' AND s.data_fi>='".$data."'";
	 $sql .= " AND s.hora_inici<='".$hora."' AND s.hora_fi>='".$hora."'";
	 
	 $rec = mysql_query($sql);

	 //echo $sql."<br><br>";
	 
	 while($row = mysql_fetch_object($rec)) {
		$count++;
		$result = $row;
	}
	 
	 if ($count==0) {
	 	$id_sortida = 0;
	 }
	 else {
	 	$id_sortida = $result->idsortides;
	 }
	 
     return $id_sortida;
  }
/* ********************************************************************************************************* */

?>