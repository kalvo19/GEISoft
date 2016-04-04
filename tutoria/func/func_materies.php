<?php
/*  ******************************************************************************************************** */
/*  getMateria --> Dades materia */
/************************************************************************************************************ */
function getMateria($idmateria) {
    $sql = "SELECT * FROM materia WHERE idmateria = '$idmateria'";
    $rec = mysql_query($sql);
    $count = 0;
    $result = "";
    while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
	}
	
	if ($count == 0) {
		$sql  = "SELECT uf.idunitats_formatives,CONCAT(m.nom_modul,'-',uf.nom_uf) AS nom_materia,uf.hores FROM unitats_formatives uf ";
		$sql .= "INNER JOIN moduls_ufs         mu ON uf.idunitats_formatives = mu.id_ufs ";
		$sql .= "INNER JOIN moduls              m ON mu.id_moduls            = m.idmoduls ";
		$sql .= "WHERE uf.idunitats_formatives = '$idmateria' ";
		
		$rec = mysql_query($sql);
		$count = 0;
		$result = "";
		while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		}	
	}
	
	mysql_free_result($rec);
    return $result;
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
/*  isMateria --> Es materia o UF */
/************************************************************************************************************ */
function isMateria($idmateria) {
    $sql = "SELECT * FROM materia WHERE idmateria = '$idmateria'";
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
	getMateriesDiaHoraGrup --> Materias impartidas un dia, una hora, curso escolar y en un determinado grupo
	                           Para dibujar el horario de un grupo
************************************************************************************************************ */
function getMateriesDiaHoraGrup($dia,$franja,$curs,$grup) {
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
	 
	 $sql  = "SELECT uc.*,m.nom_materia AS materia,ec.descripcio AS espaicentre ";
	 $sql .= "FROM unitats_classe uc ";
     $sql .= "INNER JOIN dies_franges     df ON uc.id_dies_franges    = df.id_dies_franges ";
     $sql .= "INNER JOIN espais_centre    ec ON uc.idespais_centre    = ec.idespais_centre ";
     $sql .= "INNER JOIN grups_materies   gm ON uc.idgrups_materies   = gm.idgrups_materies ";
     $sql .= "INNER JOIN materia           m ON gm.id_mat_uf_pla      = m.idmateria ";	 
     $sql .= "WHERE df.idperiode_escolar=".$curs." AND gm.id_grups='".$grup."' AND uc.id_dies_franges='".$diafranja."'";	

	 $sql .= " UNION ";
	
	 $sql .= "SELECT uc.*,CONCAT(m.nom_modul,'-',uf.nom_uf) AS materia, ";
	 $sql .= "ec.descripcio AS espaicentre ";
	 $sql .= "FROM unitats_classe uc ";
	 $sql .= "INNER JOIN dies_franges       df ON uc.id_dies_franges      = df.id_dies_franges ";
	 $sql .= "INNER JOIN espais_centre      ec ON uc.idespais_centre      = ec.idespais_centre ";
	 $sql .= "INNER JOIN grups_materies     gm ON uc.idgrups_materies     = gm.idgrups_materies ";
	 $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla        = uf.idunitats_formatives ";
	 $sql .= "INNER JOIN moduls_ufs         mu ON uf.idunitats_formatives = mu.id_ufs ";
	 $sql .= "INNER JOIN moduls              m ON mu.id_moduls            = m.idmoduls ";
	 $sql .= "INNER JOIN grups               g ON gm.id_grups             = g.idgrups ";
	 $sql .= "WHERE df.idperiode_escolar=".$curs." AND gm.id_grups='".$grup."' AND uc.id_dies_franges='".$diafranja."' ";
	 $sql .= "AND gm.data_inici<='".date("y-m-d")."' AND gm.data_fi>='".date("y-m-d")."'";
	 	 
	 $rec = mysql_query($sql);
	 
	 //echo $sql."<br><br>";
	 
 	 return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getMateriesGrup --> Materias impartidas un curso escolar y en un determinado grupo
************************************************************************************************************ */
function getMateriesGrup($curs,$grup) {	 
	 $sql  = "SELECT uc.*,m.idmateria AS idmateria, m.nom_materia AS materia ";
	 $sql .= "FROM unitats_classe uc ";
	 $sql .= "INNER JOIN dies_franges     df ON uc.id_dies_franges    = df.id_dies_franges ";
     $sql .= "INNER JOIN grups_materies   gm ON uc.idgrups_materies   = gm.idgrups_materies ";
     $sql .= "INNER JOIN materia           m ON gm.id_mat_uf_pla      = m.idmateria ";	 
     $sql .= "WHERE df.idperiode_escolar=".$curs." AND gm.id_grups='".$grup."' ";	
     $sql .= "GROUP BY uc.idgrups_materies";
	 
	 $sql .= " UNION ";
	 
	 $sql .= "SELECT uc.*,uf.idunitats_formatives AS idmateria, CONCAT(m.nom_modul,'-',uf.nom_uf) AS materia ";
	 $sql .= "FROM unitats_classe uc ";
	 $sql .= "INNER JOIN dies_franges       df ON uc.id_dies_franges    = df.id_dies_franges ";
     $sql .= "INNER JOIN grups_materies     gm ON uc.idgrups_materies   = gm.idgrups_materies ";
     $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla        = uf.idunitats_formatives ";
	 $sql .= "INNER JOIN moduls_ufs         mu ON uf.idunitats_formatives = mu.id_ufs ";
	 $sql .= "INNER JOIN moduls              m ON mu.id_moduls            = m.idmoduls "; 
     $sql .= "WHERE df.idperiode_escolar=".$curs." AND gm.id_grups='".$grup."' ";	
     $sql .= "GROUP BY uc.idgrups_materies";
	 
	 $rec = mysql_query($sql);
	 //echo $sql."<br><br>";
	 
 	 return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getMateriesDiaHoraEspaiCentre --> Materias impartidas un dia, una hora, curso escolar y en un determinado
	                                  espacio del centro. Para dibujar el horario de un espacio concreto
************************************************************************************************************ */
function getMateriesDiaHoraEspaiCentre($dia,$franja,$curs,$espaicentre) {
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
	 
	 $sql  = "SELECT uc.*,m.nom_materia AS materia,g.nom AS grup ";
	 $sql .= "FROM unitats_classe uc ";
     $sql .= "INNER JOIN dies_franges     df ON uc.id_dies_franges  = df.id_dies_franges ";
     $sql .= "INNER JOIN grups_materies   gm ON uc.idgrups_materies = gm.idgrups_materies ";
	 $sql .= "INNER JOIN grups             g ON gm.id_grups         = g.idgrups ";
     $sql .= "INNER JOIN materia           m ON gm.id_mat_uf_pla    = m.idmateria ";	 
     $sql .= "WHERE df.idperiode_escolar=".$curs." AND uc.idespais_centre='".$espaicentre."' AND uc.id_dies_franges='".$diafranja."'";	
	 
	 $sql .= " UNION ";
	 
	 $sql .= "SELECT uc.*,CONCAT(m.nom_modul,'-',uf.nom_uf) AS materia,g.nom AS grup ";
	 $sql .= "FROM unitats_classe uc ";
     $sql .= "INNER JOIN dies_franges     df ON uc.id_dies_franges  = df.id_dies_franges ";
     $sql .= "INNER JOIN grups_materies   gm ON uc.idgrups_materies = gm.idgrups_materies ";
	 $sql .= "INNER JOIN grups             g ON gm.id_grups         = g.idgrups ";
     $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
	 $sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
	 $sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";	 
     $sql .= "WHERE df.idperiode_escolar=".$curs." AND uc.idespais_centre='".$espaicentre."' AND uc.id_dies_franges='".$diafranja."' ";
	 $sql .= "AND gm.data_inici<='".date("y-m-d")."' AND gm.data_fi>='".date("y-m-d")."'";
	 
	 $rec = mysql_query($sql);

 	 return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getMateriaAlumneAgrupament --> Materia correspondiente a un id de alumno y agrupamiento
************************************************************************************************************ */
function getMateriaAlumneAgrupament($idalumne_agrupament) {
     $sql  = "SELECT m.nom_materia FROM alumnes_grup_materia agm ";
	 $sql .= "INNER JOIN grups_materies gm ON agm.idgrups_materies=gm.idgrups_materies ";
	 $sql .= "INNER JOIN materia         m ON gm.id_mat_uf_pla=m.idmateria ";
	 $sql .=" WHERE agm.idalumnes_grup_materia=$idalumne_agrupament";
	 
	 $sql .= " UNION ";
	 
	 $sql .= "SELECT CONCAT(m.nom_modul,'-',uf.nom_uf) AS nom_materia FROM alumnes_grup_materia agm ";
	 $sql .= "INNER JOIN grups_materies gm ON agm.idgrups_materies=gm.idgrups_materies ";
	 $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
	 $sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
	 $sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
	 $sql .=" WHERE agm.idalumnes_grup_materia=$idalumne_agrupament";
	 
	 
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
       return $result->nom_materia;
	 }
	 
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getModul --> Modul  
************************************************************************************************************ */
function getModul($idmoduls) {
	 $sql .= "SELECT m.idmoduls,m.nom_modul FROM moduls m ";
	 $sql .=" WHERE m.idmoduls=$idmoduls";	 
	 
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
	getModulbyUF --> Modul corresponent a una UF
************************************************************************************************************ */
function getModulbyUF($idunitats_formatives) {
	 $sql .= "SELECT m.idmoduls,m.nom_modul FROM unitats_formatives uf ";
	 $sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
	 $sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
	 $sql .=" WHERE uf.idunitats_formatives=$idunitats_formatives";	 
	 
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


/*  ******************************************************************************************************** */
/*   HoresSetmanals --> Determina les hores de docència d'una matèria en un grup
************************************************************************************************************ */
function HoresSetmanals($idgrup,$idmateria) {
    $sql  = "SELECT COUNT(uc.idunitats_classe) AS total ";
	$sql .= "FROM unitats_classe uc  ";
	$sql .= "INNER JOIN grups_materies gm ON uc.idgrups_materies=gm.idgrups_materies ";
	$sql .= "WHERE gm.id_grups=$idgrup AND gm.id_mat_uf_pla=$idmateria ";
	
    $rec = mysql_query($sql);
    while($row = mysql_fetch_object($rec)) {
		$total = $row->total;
	}
    mysql_free_result($rec);
	
    return $total;
}
/* ********************************************************************************************************* */

?>