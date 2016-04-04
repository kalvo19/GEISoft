<?php
/*  ******************************************************************************************************** */
/*   getTipusFaltaAlumne --> Els tipus de faltes dels alumnes 
************************************************************************************************************ */
function getTipusFaltaAlumne() {
    $sql = "SELECT * FROM tipus_falta_alumne WHERE tipus_falta NOT LIKE '%justif%'";
    $rec = mysql_query($sql);
    
    return $rec;
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
/*   validaAlumne --> Login usuari en la plataforma 
************************************************************************************************************ */
function validaAlumne($login,$contrasenya,$contacte_login,$contacte_contrasenya) {
    $sql  = "SELECT ca.id_alumne FROM contacte_alumne ca ";
	//$sql .= "INNER JOIN alumnes_families af ON cf.id_families = af.idfamilies ";
	$sql .= "INNER JOIN alumnes a ON ca.id_alumne = a.idalumnes ";
	$sql .= "WHERE a.acces_alumne='S' AND a.activat='S' AND ca.id_tipus_contacte=$contacte_login AND ca.Valor='$login' ";
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
			'message' => 'usuari i/o contrasenya erroni!'
		));
		return 0;
	}
	else { 
	    $idalumnes = $result->id_alumne;
		$sql  = "SELECT ca.id_alumne FROM contacte_alumne ca ";
		//$sql .= "INNER JOIN alumnes_families af ON cf.id_families = af.idfamilies ";
		$sql .= "INNER JOIN alumnes a ON ca.id_alumne = a.idalumnes ";
		$sql .= "WHERE a.activat='S' AND ca.id_tipus_contacte=$contacte_contrasenya AND a.idalumnes=$idalumnes AND ca.Valor=MD5('$contrasenya') ";
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
			'message' => 'usuari i/o contrasenya erroni!'
		));
		return 0;
	}
	else { 
	     echo json_encode(array(
			'login' => $login,
			'passwd' => $contrasenya
		  ));
    	return $result->id_alumne;
	}
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getValorTipusContacteAlumne --> Obtè el registre de la taula contacte_alumne amb un determinat valor de idtipus
************************************************************************************************************ */
function getValorTipusContacteAlumne($id_alumne,$idtipus_contacte) {
	 if ($id_alumne == 'undefined') {
	   $id_alumne = 0;
	 }
	 $sql  = "SELECT * FROM contacte_alumne WHERE id_alumne=$id_alumne AND id_tipus_contacte=$idtipus_contacte";
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
	existValorTipusContacteAlumne --> Existeix el registre de la taula contacte_alumne amb un determinat valor de idtipus
************************************************************************************************************ */
function existValorTipusContacteAlumne($id_alumne,$idtipus_contacte) {
	 if ($id_alumne == 'undefined') {
	   $id_alumne = 0;
	 }
	 $sql  = "SELECT * FROM contacte_alumne WHERE id_alumne=$id_alumne AND id_tipus_contacte=$idtipus_contacte";
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

/*  getAlumne --> Dades alumne */
function getAlumne($idalumnes,$tipusContacte) {
	$sql  = "SELECT ca.Valor FROM contacte_alumne ca ";
	$sql .= "WHERE ca.id_tipus_contacte=$tipusContacte AND ca.id_alumne=$idalumnes ";
		
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

/*  getCodiSagaAlumne --> Codi Saga alumne */
function getCodiSagaAlumne($idalumnes) {
	if ($idalumnes == 'undefined') {
		$idalumnes = 0;
	}
	$sql  = "SELECT a.codi_alumnes_saga FROM alumnes a ";
	$sql .= "WHERE a.idalumnes=$idalumnes ";
	
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
		return $result->codi_alumnes_saga;
	}
}
/* ********************************************************************************************************* */

/*  getAlumneGrupMateria --> Dades alumne grup materia */
function getAlumneGrupMateria($idalumnes_grup_materia) {
    $sql = "SELECT * FROM alumnes_grup_materia WHERE idalumnes_grup_materia = '$idalumnes_grup_materia'";
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
	getMateriesDiaHoraAlumne --> Materias impartidas un dia, una hora, curso escolar y por un determinado alumno
	                                Para dibujar el horario de un alumno
************************************************************************************************************ */
function getMateriesDiaHoraAlumne($dia,$franja,$curs,$alumne) {
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
	 
	 $sql  = "SELECT agm.*,m.nom_materia AS materia,ec.descripcio AS espaicentre,g.nom as grup,gm.idgrups_materies,gm.id_mat_uf_pla ";
	 $sql .= "FROM alumnes_grup_materia agm ";
	 $sql .= "INNER JOIN grups_materies     gm ON agm.idgrups_materies = gm.idgrups_materies ";
	 //$sql .= "INNER JOIN prof_agrupament    pa ON agm.idgrups_materies = pa.idagrups_materies ";
	 $sql .= "INNER JOIN grups               g ON gm.id_grups          = g.idgrups ";
	 $sql .= "INNER JOIN materia             m ON  gm.id_mat_uf_pla    = m.idmateria ";
	 $sql .= "INNER JOIN unitats_classe     uc ON gm.idgrups_materies  = uc.idgrups_materies ";
	 $sql .= "INNER JOIN dies_franges       df ON uc.id_dies_franges   = df.id_dies_franges ";
	 $sql .= "INNER JOIN espais_centre      ec ON uc.idespais_centre   = ec.idespais_centre "; 
	 $sql .= "WHERE df.idperiode_escolar=$curs AND agm.idalumnes=$alumne AND uc.id_dies_franges=$diafranja";
     
	 $sql .= " UNION ";
	 
	 $sql .= "SELECT agm.*,CONCAT(m.nom_modul,'-',uf.nom_uf) AS materia,ec.descripcio AS espaicentre,g.nom as grup,gm.idgrups_materies,gm.id_mat_uf_pla ";
	 $sql .= "FROM alumnes_grup_materia agm ";
	 $sql .= "INNER JOIN grups_materies     gm ON agm.idgrups_materies = gm.idgrups_materies ";
	 //$sql .= "INNER JOIN prof_agrupament    pa ON agm.idgrups_materies = pa.idagrups_materies ";
	 $sql .= "INNER JOIN grups               g ON gm.id_grups          = g.idgrups ";
	 $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
	 $sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
	 $sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
	 $sql .= "INNER JOIN unitats_classe     uc ON gm.idgrups_materies  = uc.idgrups_materies ";
	 $sql .= "INNER JOIN dies_franges       df ON uc.id_dies_franges   = df.id_dies_franges ";
	 $sql .= "INNER JOIN espais_centre      ec ON uc.idespais_centre   = ec.idespais_centre "; 
	 $sql .= "WHERE df.idperiode_escolar=$curs AND agm.idalumnes=$alumne AND uc.id_dies_franges=$diafranja";
	 $sql .= " AND gm.data_inici<='".date("y-m-d")."' AND gm.data_fi>='".date("y-m-d")."'";
	 
	 $rec = mysql_query($sql);
     
	 //echo $sql."<br><br>";	 
 	 return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getMateriesAlumne --> Materias impartidas un curso escolar y por un determinado alumno
************************************************************************************************************ */
function getMateriesAlumne($curs,$idalumnes) {
	
	/* $sql  = "SELECT gm.id_mat_uf_pla,m.nom_materia AS materia,ec.descripcio AS espaicentre,g.idgrups,g.nom as grup ";
	 $sql .= "FROM alumnes_grup_materia agm ";
	 $sql .= "INNER JOIN grups_materies     gm ON agm.idgrups_materies = gm.idgrups_materies ";
	 $sql .= "INNER JOIN grups               g ON gm.id_grups          = g.idgrups ";
	 $sql .= "INNER JOIN materia             m ON  gm.id_mat_uf_pla    = m.idmateria ";
	 $sql .= "INNER JOIN unitats_classe     uc ON gm.idgrups_materies  = uc.idgrups_materies ";
	 $sql .= "INNER JOIN dies_franges       df ON uc.id_dies_franges   = df.id_dies_franges ";
	 $sql .= "INNER JOIN espais_centre      ec ON uc.idespais_centre   = ec.idespais_centre "; 
	 $sql .= "WHERE df.idperiode_escolar=$curs AND agm.idalumnes=$alumne ";
     $sql .= "GROUP BY 1 ";
	 
	 $sql .= " UNION ";
	 
	 $sql .= "SELECT gm.id_mat_uf_pla,CONCAT(LEFT(m.nom_modul,20),'-',uf.nom_uf) AS materia,ec.descripcio AS espaicentre,g.idgrups,g.nom as grup ";
	 $sql .= "FROM alumnes_grup_materia agm ";
	 $sql .= "INNER JOIN grups_materies     gm ON agm.idgrups_materies = gm.idgrups_materies ";
	 $sql .= "INNER JOIN grups               g ON gm.id_grups          = g.idgrups ";
	 $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
	 $sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
	 $sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
	 $sql .= "INNER JOIN unitats_classe     uc ON gm.idgrups_materies  = uc.idgrups_materies ";
	 $sql .= "INNER JOIN dies_franges       df ON uc.id_dies_franges   = df.id_dies_franges ";
	 $sql .= "INNER JOIN espais_centre      ec ON uc.idespais_centre   = ec.idespais_centre "; 
	 $sql .= "WHERE df.idperiode_escolar=$curs AND agm.idalumnes=$alumne ";
	 $sql .= "GROUP BY 1 ";
	 
	 $sql .= "ORDER BY 4,2 ";*/


	$sql  = "SELECT gm.id_mat_uf_pla,m.nom_materia AS materia,g.idgrups,g.nom as grup ";
	$sql .= "FROM alumnes_grup_materia agm ";
	$sql .= "INNER JOIN grups_materies     gm ON agm.idgrups_materies = gm.idgrups_materies ";
	$sql .= "INNER JOIN grups               g ON gm.id_grups          = g.idgrups ";
	$sql .= "INNER JOIN materia             m ON  gm.id_mat_uf_pla    = m.idmateria ";
	$sql .= "INNER JOIN unitats_classe     uc ON gm.idgrups_materies  = uc.idgrups_materies ";
	//$sql .= "INNER JOIN espais_centre      ec ON uc.idespais_centre   = ec.idespais_centre "; 
	$sql .= "INNER JOIN dies_franges       df ON uc.id_dies_franges   = df.id_dies_franges ";
	$sql .= "WHERE df.idperiode_escolar=$curs AND agm.idalumnes=$idalumnes ";
		 
	$sql .= " UNION ";
		 
	$sql .= "SELECT gm.id_mat_uf_pla,CONCAT(LEFT(m.nom_modul,15),'-',uf.nom_uf) AS materia,g.idgrups,g.nom as grup ";
	$sql .= "FROM alumnes_grup_materia agm ";
	$sql .= "INNER JOIN grups_materies     gm ON agm.idgrups_materies = gm.idgrups_materies ";
	$sql .= "INNER JOIN grups               g ON gm.id_grups          = g.idgrups ";
	$sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
	$sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
	$sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
	$sql .= "INNER JOIN unitats_classe     uc ON gm.idgrups_materies  = uc.idgrups_materies ";
	//$sql .= "INNER JOIN espais_centre      ec ON uc.idespais_centre   = ec.idespais_centre "; 
	$sql .= "INNER JOIN dies_franges       df ON uc.id_dies_franges   = df.id_dies_franges ";
	$sql .= "WHERE df.idperiode_escolar=$curs AND agm.idalumnes=$idalumnes ";
		 
	$sql .= " ORDER BY 3,2 ASC ";
	 
	$rec = mysql_query($sql);
     
	//echo $sql."<br><br>";
	 
 	return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getGrupAlumne --> Grupo de un determinado alumno. Cierto en caso de que pertenezca a un solo grupo
************************************************************************************************************ */
function getGrupAlumne($alumne) {
	 $count  = 0;
	 $result = "";
	 
	 $sql  = "SELECT DISTINCT(gr.idgrups), gr.nom, gr.Descripcio ";
	 $sql .= "FROM alumnes_grup_materia agm ";
	 $sql .= "INNER JOIN grups_materies gm ON agm.idgrups_materies = gm.idgrups_materies ";
	 $sql .= "INNER JOIN materia        ma ON gm.id_mat_uf_pla     = ma.idmateria ";
	 $sql .= "INNER JOIN grups          gr ON gm.id_grups          = gr.idgrups ";
 	 $sql .= "WHERE agm.idalumnes='".$alumne."' ";
  	 $sql .= "UNION ";
	 $sql .= "SELECT DISTINCT(gr.idgrups), gr.nom, gr.Descripcio ";
	 $sql .= "FROM alumnes_grup_materia agm ";
	 $sql .= "INNER JOIN grups_materies     gm ON agm.idgrups_materies = gm.idgrups_materies ";
	 $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
	 $sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
	 $sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
	 $sql .= "INNER JOIN grups              gr ON gm.id_grups          = gr.idgrups ";
	 $sql .= "WHERE agm.idalumnes='".$alumne."' ";
	
	 $sql .= "ORDER BY 1 LIMIT 1";

	 $rec = mysql_query($sql);
	 
	 while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
     
	 //echo $sql."<br><br>";
	 
 	 if ($count == 0) {
	   return 0;
	 }
	 else {
       return $result;
	 }
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getAlumnesMateriaGrup --> Alumnes que immarteixen una determinada materia en un determinat grup
	                          Per introduir les faltes d'assistència
************************************************************************************************************ */
function getAlumnesMateriaGrup($grup,$materia,$tipusContacte) {
	 $sql  = "SELECT agm.*,ca.Valor ";
	 $sql .= "FROM alumnes_grup_materia agm ";
	 $sql .= "INNER JOIN alumnes            a ON agm.idalumnes          = a.idalumnes ";
	 $sql .= "INNER JOIN contacte_alumne   ca ON agm.idalumnes          = ca.id_alumne ";
     $sql .= "INNER JOIN grups_materies    gm ON agm.idgrups_materies   = gm.idgrups_materies ";	 
     $sql .= "INNER JOIN grups              g ON gm.id_grups            = g.idgrups ";
     $sql .= "INNER JOIN materia            m ON gm.id_mat_uf_pla       = m.idmateria ";
	 $sql .= "WHERE a.activat='S' AND g.idgrups=".$grup." AND m.idmateria=".$materia." AND ca.id_tipus_contacte=".$tipusContacte;	
     
	 $sql .= " UNION ";
	 
	 $sql .= "SELECT agm.*,ca.Valor ";
	 $sql .= "FROM alumnes_grup_materia agm ";
 	 $sql .= "INNER JOIN alumnes             a ON agm.idalumnes         = a.idalumnes ";
	 $sql .= "INNER JOIN contacte_alumne    ca ON agm.idalumnes         = ca.id_alumne ";
     $sql .= "INNER JOIN grups_materies     gm ON agm.idgrups_materies  = gm.idgrups_materies ";	 
     $sql .= "INNER JOIN grups               g ON gm.id_grups           = g.idgrups ";
     $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla      = uf.idunitats_formatives ";
	 $sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla      = mu.id_ufs ";
	 $sql .= "INNER JOIN moduls              m ON mu.id_moduls          = m.idmoduls ";
	 $sql .= "WHERE a.activat='S' AND g.idgrups=".$grup." AND uf.idunitats_formatives=".$materia." AND ca.id_tipus_contacte=".$tipusContacte;
	 
	 $sql .= " ORDER BY 4 ";

	 $rec = mysql_query($sql);
	 
	 //echo $sql;
	 
 	 return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getAlumneMateriaGrup --> Alumne que immarteixen una determinada materia en un determinat grup
	                         Per introduir les faltes d'assistència
************************************************************************************************************ */
function getAlumneMateriaGrup($grup,$materia,$idalumnes) {
	 $sql  = "SELECT agm.* ";
	 $sql .= "FROM alumnes_grup_materia agm ";
     $sql .= "INNER JOIN grups_materies    gm ON agm.idgrups_materies  = gm.idgrups_materies ";	 
     $sql .= "INNER JOIN grups             g ON gm.id_grups            = g.idgrups ";
     $sql .= "INNER JOIN materia           m ON gm.id_mat_uf_pla       = m.idmateria ";
	 $sql .= "WHERE g.idgrups=".$grup." AND m.idmateria=".$materia." AND agm.idalumnes=".$idalumnes;	
	 
	 $sql .= " UNION ";
	 
	 $sql .= "SELECT agm.* ";
	 $sql .= "FROM alumnes_grup_materia agm ";
     $sql .= "INNER JOIN grups_materies     gm ON agm.idgrups_materies  = gm.idgrups_materies ";	 
     $sql .= "INNER JOIN grups               g ON gm.id_grups            = g.idgrups ";
     $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
	 $sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
	 $sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
	 $sql .= "WHERE g.idgrups=".$grup." AND uf.idunitats_formatives=".$materia." AND agm.idalumnes=".$idalumnes;
	 
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
	getIDAlumneAgrupament --> ID alumne_agrupament
************************************************************************************************************ */
function getIDAlumneAgrupament($alumne,$grupmateria) {
	 $sql  = "SELECT agm.idalumnes_grup_materia ";
	 $sql .= "FROM alumnes_grup_materia agm ";
	 $sql .= "WHERE agm.idgrups_materies=".$grupmateria." AND agm.idalumnes=".$alumne;	

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
       return $result->idalumnes_grup_materia;
	 }
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getAlumnesGrup --> Alumnes d'un determinat grup
	                   Per informes d'assistèncis
************************************************************************************************************ */
function getAlumnesGrup($grup,$tipusContacte) {
	 $sql  = "SELECT DISTINCT(agm.idalumnes),ca.Valor ";
	 $sql .= "FROM alumnes_grup_materia agm ";
	 $sql .= "INNER JOIN contacte_alumne ca ON agm.idalumnes=ca.id_alumne ";
     $sql .= "INNER JOIN grups_materies    gm ON agm.idgrups_materies  = gm.idgrups_materies ";	 
     $sql .= "INNER JOIN grups             g ON gm.id_grups            = g.idgrups ";
     $sql .= "INNER JOIN materia           m ON gm.id_mat_uf_pla       = m.idmateria ";
	 $sql .= "WHERE g.idgrups=".$grup." AND ca.id_tipus_contacte=".$tipusContacte;	

     $sql .= " UNION ";
	 
	 $sql .= "SELECT DISTINCT(agm.idalumnes),ca.Valor ";
	 $sql .= "FROM alumnes_grup_materia agm ";
	 $sql .= "INNER JOIN contacte_alumne ca ON agm.idalumnes=ca.id_alumne ";
     $sql .= "INNER JOIN grups_materies    gm ON agm.idgrups_materies  = gm.idgrups_materies ";	 
     $sql .= "INNER JOIN grups             g ON gm.id_grups            = g.idgrups ";
     $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
	 $sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
	 $sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
	 $sql .= "WHERE g.idgrups=".$grup." AND ca.id_tipus_contacte=".$tipusContacte;

     $sql .= " ORDER BY 2 ";

	 $rec = mysql_query($sql);
	 
 	 return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getTotalAlumnesPlaEstudis --> Total Alumnes d'un determinat pla d'estudis
************************************************************************************************************ */
function getTotalAlumnesPlaEstudis($idplans_estudis) {
	 $sql  = "SELECT COUNT(DISTINCT(agm.idalumnes)) AS total ";
	 $sql .= "FROM alumnes_grup_materia agm ";
     $sql .= "INNER JOIN grups_materies      gm ON agm.idgrups_materies  = gm.idgrups_materies ";	 
     $sql .= "INNER JOIN moduls_materies_ufs  m ON gm.id_mat_uf_pla      = m.id_mat_uf_pla ";
	 $sql .= "WHERE m.idplans_estudis=".$idplans_estudis;

	 $rec = mysql_query($sql);
     $count = 0;
	 $total = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$result = $row;
			$count++;
			$total = $total + $result->total;
	 }
	 mysql_free_result($rec);
	 if ($count == 0) {
	   return 0;
	 }
	 else {
       return $total;
	 }
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getTotalAlumnesGrup --> Total Alumnes d'un determinat grup
	                        Per informes d'assistèncis
************************************************************************************************************ */
function getTotalAlumnesGrup($grup) {
	 $sql  = "SELECT COUNT(DISTINCT(agm.idalumnes)) AS total ";
	 $sql .= "FROM alumnes_grup_materia agm ";
     $sql .= "INNER JOIN grups_materies    gm ON agm.idgrups_materies  = gm.idgrups_materies ";	 
     $sql .= "INNER JOIN grups             g ON gm.id_grups            = g.idgrups ";
     $sql .= "INNER JOIN materia           m ON gm.id_mat_uf_pla       = m.idmateria ";
	 $sql .= "WHERE g.idgrups=".$grup;	
     $sql .= " UNION ";	 
	 $sql .= "SELECT COUNT(DISTINCT(agm.idalumnes)) AS total ";
	 $sql .= "FROM alumnes_grup_materia agm ";
     $sql .= "INNER JOIN grups_materies    gm ON agm.idgrups_materies  = gm.idgrups_materies ";	 
     $sql .= "INNER JOIN grups             g ON gm.id_grups            = g.idgrups ";
     $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
	 $sql .= "WHERE g.idgrups=".$grup;

	 $rec = mysql_query($sql);
     $count = 0;
	 $total = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$result = $row;
			$count++;
			$total = $total + $result->total;
	 }
	 mysql_free_result($rec);
	 if ($count == 0) {
	   return 0;
	 }
	 else {
       return $total;
	 }
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getTotalAlumnesGrupMateria --> Total Alumnes d'un determinat grup materia
************************************************************************************************************ */
function getTotalAlumnesGrupMateria($grup_materia) {
	 $sql  = "SELECT COUNT(DISTINCT(idalumnes)) AS total ";
	 $sql .= "FROM alumnes_grup_materia ";
	 $sql .= "WHERE idgrups_materies=".$grup_materia;	

	 $rec = mysql_query($sql);
	 $total = 0;
     $result = "";
     while($row = mysql_fetch_object($rec)) {
			$result = $row;
			$total = $total + $result->total;
	 }
	 mysql_free_result($rec);
       return $total;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
/*   insertaLogAlumne --> Nova entrada al fitxer log de alumnes 
************************************************************************************************************ */
function insertaLogAlumne($alumne,$accio) {
	$data = date("Y/m/d");
	$hora = date("H:i:s");
    $sql  = "INSERT INTO log_alumnes(data,hora,id_alumne,id_accio) ";
	$sql .= "VALUES ('$data','$hora',$alumne,$accio) ";
	$rec  = mysql_query($sql);
	
	return 1;	
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	existAlumneSortidaData --> En tal data hi ha tal alumne de sortida?
************************************************************************************************************ */
function existAlumneSortidaData($id_alumne,$data,$hora) {
	 $count  = 0;
	 $result = "";
	 
	 $sql  = "SELECT s.idsortides ";
	 $sql .= "FROM sortides s ";
	 $sql .= "INNER JOIN sortides_alumne sa ON sa.id_sortida = s.idsortides ";
 	 $sql .= "WHERE sa.id_alumne=".$id_alumne;
	 $sql .= " AND s.data_inici<='".$data."' AND s.data_fi>='".$data."'";
	 $sql .= " AND s.hora_inici<='".$hora."' AND s.hora_fi>='".$hora."'";
	 
	 $rec = mysql_query($sql);

     while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 
	 //echo $sql."<br><br>";
     if ($count==0) {
	 	$id_sortida = 0;
	 }
	 else {
	 	$id_sortida = $result->idsortides;
	 }

     return $id_sortida;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	existAlumneCCCData --> En tal data hi ha tal alumne amb CCC?
************************************************************************************************************ */
function existAlumneCCCData($idalumne,$data,$idfranges_horaries) {
	 $count  = 0;
	 $result = "";
	 
	 $sql  = "SELECT idccc_taula_principal ";
	 $sql .= "FROM ccc_taula_principal ";
 	 $sql .= "WHERE idalumne=".$idalumne;
	 $sql .= " AND data='".$data."' ";
	 $sql .= " AND idfranges_horaries='".$idfranges_horaries."' ";
	 
	 $rec = mysql_query($sql);

     while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	 }
	 mysql_free_result($rec);
	 
	 //echo $sql."<br><br>";
     if ($count==0) {
	 	$idccc_taula_principal = 0;
	 }
	 else {
	 	$idccc_taula_principal = $result->idccc_taula_principal;
	 }

     return $idccc_taula_principal;
  }
/* ********************************************************************************************************* */

?>