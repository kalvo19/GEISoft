<?php

/*  ********************************************************************************************************
	getGrups --> Todos los grupos
************************************************************************************************************ */
function getGrups($order) {
	 $count  = 0;
	 $result = "";
	 
	 $sql  = "SELECT DISTINCT(gr.idgrups), gr.nom, gr.Descripcio,gr.idtorn ";
	 $sql .= "FROM prof_agrupament pa ";
	 $sql .= "INNER JOIN grups_materies gm ON pa.idagrups_materies = gm.idgrups_materies ";
	 $sql .= "INNER JOIN materia        ma ON gm.id_mat_uf_pla     = ma.idmateria ";
	 $sql .= "INNER JOIN grups          gr ON gm.id_grups          = gr.idgrups ";
  	 $sql .= "UNION ";
	 $sql .= "SELECT DISTINCT(gr.idgrups), gr.nom, gr.Descripcio,gr.idtorn ";
	 $sql .= "FROM prof_agrupament pa ";
	 $sql .= "INNER JOIN grups_materies     gm ON pa.idagrups_materies = gm.idgrups_materies ";
	 $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
	 $sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
	 $sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
	 $sql .= "INNER JOIN grups              gr ON gm.id_grups          = gr.idgrups ";
	
	 $sql .= "ORDER BY $order";
	 
	 $rec = mysql_query($sql);
	 
	 //echo $sql."<br><br>";
     return $rec;
  }
/* ********************************************************************************************************* */

/*  getGrup --> Dades grup */
function getGrup($idgrups) {
    $sql = "SELECT * FROM grups WHERE idgrups = '$idgrups'";
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
/*  getCarrecPrincipalGrup --> Carrec principal d'un grup                                                      */
/* ********************************************************************************************************* */
function getCarrecPrincipalGrup($idcarrecs,$idgrups) {
    $sql  = "SELECT idprofessors FROM professor_carrec ";
    $sql .= "WHERE idcarrecs = '$idcarrecs' AND idgrups = '$idgrups' AND principal=1 ";
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

/* ********************************************************************************************************* */
/*  getCarrecsGrup --> Carrec o carrecs d'un grup . EX: tutors d'un grup                                     */
/* ********************************************************************************************************* */
function getCarrecsGrup($idcarrecs,$idgrups) {
    $sql  = "SELECT idprofessors FROM professor_carrec ";
    $sql .= "WHERE idcarrecs = '$idcarrecs' AND idgrups = '$idgrups' ";
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


/*  getGrupMateria --> Dades grup materia */
function getGrupMateria($idgrups_materies) {
    $sql = "SELECT * FROM grups_materies WHERE idgrups_materies = '$idgrups_materies'";
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

/*  existGrupMateria --> Existeix grup materia */
function existGrupMateria($idgrups,$id_mat_uf_pla) {
    $sql = "SELECT * FROM grups_materies WHERE id_grups = $idgrups AND id_mat_uf_pla = $id_mat_uf_pla";
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
	getGrupMateriaAlumneAgrupament --> GrupMateria correspondiente a un id de alumno y agrupamiento
************************************************************************************************************ */
function getGrupMateriaAlumneAgrupament($idalumne_agrupament) {
     $sql  = "SELECT gm.idgrups_materies FROM alumnes_grup_materia agm ";
	 $sql .= "INNER JOIN grups_materies gm ON agm.idgrups_materies=gm.idgrups_materies ";
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
       return $result->idgrups_materies;
	 }
	 
  }
/* ********************************************************************************************************* */

?>