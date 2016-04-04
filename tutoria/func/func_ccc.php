<?php
/*  ********************************************************************************************************
	getCCC --> Detall d'una CCC
************************************************************************************************************ */
function getCCC($idccc_taula_principal) {
	 $sql  = "SELECT ccc_tp.* ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 $sql .= "WHERE ccc_tp.idccc_taula_principal = ".$idccc_taula_principal;
	 $rec  = mysql_query($sql); 
	 	 
     return $rec;
  }
/* ********************************************************************************************************* */

/*  ******************************************************************************************************** */
/*   getTipusCCC --> Els tipus de CCC 
************************************************************************************************************ */
function getTipusCCC() {
    $sql = "SELECT * FROM ccc_tipus";
    $rec = mysql_query($sql);
    
    return $rec;
}
/* ********************************************************************************************************* */

/*  ******************************************************************************************************** */
/*   getLiteralTipusCCC --> Nom d'un tipus de CCC 
************************************************************************************************************ */
function getLiteralTipusCCC($idccc_tipus) {
    $sql = "SELECT * FROM ccc_tipus WHERE idccc_tipus=".$idccc_tipus;
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
/*   getMesuresCCC --> Les mesures de CCC 
************************************************************************************************************ */
function getMesuresCCC() {
    $sql = "SELECT * FROM ccc_tipus_mesura";
    $rec = mysql_query($sql);
    
    return $rec;
}
/* ********************************************************************************************************* */

/*  ******************************************************************************************************** */
/*   getLiteralMesuresCCC --> Nom d'una mesura de CCC 
************************************************************************************************************ */
function getLiteralMesuresCCC($idccc_tipus_mesura) {
    $sql = "SELECT * FROM ccc_tipus_mesura WHERE idccc_tipus_mesura=".$idccc_tipus_mesura;
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
/*   getMotiusCCC --> Els motius de CCC 
************************************************************************************************************ */
function getMotiusCCC() {
    $sql = "SELECT * FROM ccc_motius";
    $rec = mysql_query($sql);
    
    return $rec;
}
/* ********************************************************************************************************* */

/*  ******************************************************************************************************** */
/*   getLiteralMotiusCCC --> Nom d'un motiu de CCC 
************************************************************************************************************ */
function getLiteralMotiusCCC($idccc_motius) {
    $sql = "SELECT * FROM ccc_motius WHERE idccc_motius=".$idccc_motius;
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
	getCarrecsComunicacioTipusCCC --> Obtè els càrrecs als que s'els hi comunica un tipus de CCC
************************************************************************************************************ */
function getCarrecsComunicacioTipusCCC($id_tipus) {
	 if ($id_tipus == 'undefined') {
	   $id_tipus = 0;
	 }
	 $sql  = "SELECT * FROM ccc_tipus_comunicacio_carrec WHERE id_tipus=$id_tipus ";
	 $rec  = mysql_query($sql); 

	 return $rec;
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getCCCAlumne --> Total d'un alumne entre dates
	                      Per informes de faltes d'assistència 
************************************************************************************************************ */
function getCCCAlumne($idalumne,$data_inici,$data_fi) {
	 $sql  = "SELECT ccc_tp.* ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 $sql .= "WHERE ccc_tp.idalumne = ".$idalumne;
     $sql .= " AND ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."' ";
	 $sql .= "ORDER BY ccc_tp.data DESC ";
	 $rec  = mysql_query($sql); 
	 	 
     return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getCCCAlumneGrupMateria --> Total d'un alumne,grup i materia entre dates
************************************************************************************************************ */
function getCCCAlumneGrupMateria($idalumne,$idgrup,$idmateria,$data_inici,$data_fi) {
	 $sql  = "SELECT ccc_tp.* ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 $sql .= "WHERE ccc_tp.idalumne = ".$idalumne;
     $sql .= " AND ccc_tp.idgrup=".$idgrup." AND ccc_tp.idmateria=".$idmateria;
     $sql .= " AND ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."' ";
	 $sql .= "ORDER BY ccc_tp.data DESC ";
	 $rec  = mysql_query($sql); 
	 	 
     return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getCCCProfessor --> Total d'un professor entre dates
	                    Per informes de faltes d'assistència 
************************************************************************************************************ */
function getCCCProfessor($idprofessor,$data_inici,$data_fi) {
	 $sql  = "SELECT ccc_tp.* ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 $sql .= "WHERE ccc_tp.idprofessor = ".$idprofessor;
     $sql .= " AND ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."' ";
	 $sql .= "ORDER BY ccc_tp.data DESC ";
	 $rec  = mysql_query($sql); 
	 	 
     return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getCCCGrup --> Total d'un grup entre dates
	               Per informes de faltes d'assistència 
************************************************************************************************************ */
function getCCCGrup($idgrup,$data_inici,$data_fi) {
	 $sql  = "SELECT ccc_tp.* ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 $sql .= "WHERE ccc_tp.idgrup = ".$idgrup;
     $sql .= " AND ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."' ";
	 $sql .= "ORDER BY ccc_tp.data DESC ";
	 $rec  = mysql_query($sql); 
	 	 
     return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getCCCProfessorGrup --> Total d'un professor i grup entre dates
	                        Per informes de faltes d'assistència 
************************************************************************************************************ */
function getCCCProfessorGrup($idprofessor,$idgrup,$data_inici,$data_fi) {
	 $sql  = "SELECT ccc_tp.* ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 $sql .= "WHERE ccc_tp.idprofessor = ".$idprofessor." AND ccc_tp.idgrup = ".$idgrup;
     $sql .= " AND ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."' ";
	 $sql .= "ORDER BY ccc_tp.data DESC ";
	 $rec  = mysql_query($sql); 
	 	 
     return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getCCCProfessorGrupAlumne --> Total d'un professor, grup i alumne entre dates
	                              Per informes de faltes d'assistència 
************************************************************************************************************ */
function getCCCProfessorGrupAlumne($idprofessor,$idgrup,$idalumne,$data_inici,$data_fi) {
	 $sql  = "SELECT ccc_tp.* ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 $sql .= "WHERE ccc_tp.idprofessor = ".$idprofessor." AND ccc_tp.idgrup = ".$idgrup." AND ccc_tp.idalumne = ".$idalumne;
     $sql .= " AND ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."' ";
	 $sql .= "ORDER BY ccc_tp.data DESC ";
	 $rec  = mysql_query($sql); 
	 	 
     return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getInformeTotalCCC_Criterio --> Per informes de faltes d'assistència 
************************************************************************************************************ */
function getInformeTotalCCC_Criterio($data_inici,$data_fi,$criteri) {
	 $sql  = "SELECT ccc_tp.".$criteri.",COUNT(ccc_tp.id_falta) AS total ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 //$sql .= "WHERE ccc_tp.idgrup = ".$idgrup;
     $sql .= " WHERE ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 $sql .= " GROUP BY 1 ORDER BY 2 DESC "; 	 
	 $rec = mysql_query($sql);

	 return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getInformeTotalCCC_Criterio_Subcriterio --> Per informes de faltes d'assistència 
************************************************************************************************************ */
function getInformeTotalCCC_Criterio_Subcriterio($data_inici,$data_fi,$criteri,$valor_criteri,$subcriteri) {
	 $sql  = "SELECT ccc_tp.".$subcriteri.",COUNT(ccc_tp.id_falta) AS total ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 $sql .= "WHERE ccc_tp.".$criteri." = ".$valor_criteri;
     $sql .= " AND ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 $sql .= " GROUP BY 1 ORDER BY 2 DESC "; 	 
	 
	 $rec = mysql_query($sql);
     //echo $sql;

	 return $rec;
  }
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	getTotalCCCAlumne --> Total CCC d'un alumne entre dates
	                      Per informes de faltes d'assistència 
************************************************************************************************************ */
function getTotalCCCAlumne($idalumne,$data_inici,$data_fi) {
	 $sql  = "SELECT COUNT(ccc_tp.id_falta) AS total ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 $sql .= "WHERE ccc_tp.idalumne = ".$idalumne;
     $sql .= " AND ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 	 
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
	getTotalCCCAlumneGrupMateria --> Total CCC d'un alumne, grup i materia entre dates
************************************************************************************************************ */
function getTotalCCCAlumneGrupMateria($idalumne,$idgrup,$idmateria,$data_inici,$data_fi) {
	 $sql  = "SELECT COUNT(ccc_tp.id_falta) AS total ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 $sql .= "WHERE ccc_tp.idalumne = ".$idalumne;
     $sql .= " AND ccc_tp.idgrup=".$idgrup." AND ccc_tp.idmateria=".$idmateria;
	 $sql .= " AND ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 	 
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
	getTotalCCCProfessor --> Total CCC d'un professor entre dates
	                         Per informes de faltes d'assistència 
************************************************************************************************************ */
function getTotalCCCProfessor($idprofessor,$data_inici,$data_fi) {
	 $sql  = "SELECT COUNT(ccc_tp.id_falta) AS total ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 $sql .= "WHERE ccc_tp.idprofessor = ".$idprofessor;
     $sql .= " AND ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 	 
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
	getTotalCCCProfessorGrup --> Total CCC d'un professor i grup entre dates
	                             Per informes de faltes d'assistència 
************************************************************************************************************ */
function getTotalCCCProfessorGrup($idprofessor,$idgrup,$data_inici,$data_fi) {
	 $sql  = "SELECT COUNT(ccc_tp.id_falta) AS total ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 $sql .= "WHERE ccc_tp.idprofessor = ".$idprofessor. " AND ccc_tp.idgrup = ".$idgrup;
	 $sql .= " AND ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 	 
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
	getTotalCCCProfessorAlumne --> Total CCC d'un professor i alumne entre dates
	                               Per informes de faltes d'assistència 
************************************************************************************************************ */
function getTotalCCCProfessorAlumne($idprofessor,$idalumne,$data_inici,$data_fi) {
	 $sql  = "SELECT COUNT(ccc_tp.id_falta) AS total ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 $sql .= "WHERE ccc_tp.idprofessor = ".$idprofessor. " AND ccc_tp.idalumne = ".$idalumne;
	 $sql .= " AND ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 	 
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
	getTotalCCCProfessorAlumneGrup --> Total CCC d'un professor, alumne i grup entre dates
	                                   Per informes de faltes d'assistència 
************************************************************************************************************ */
function getTotalCCCProfessorAlumneGrup($idprofessor,$idalumne,$idgrup,$data_inici,$data_fi) {
	 $sql  = "SELECT COUNT(ccc_tp.id_falta) AS total ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 $sql .= "WHERE ccc_tp.idprofessor = ".$idprofessor. " AND ccc_tp.idalumne = ".$idalumne;
	 $sql .= " AND ccc_tp.idgrup =".$idgrup;
	 $sql .= " AND ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 	 
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
	getTotalCCCAlumneGrup --> Total CCC d'un alumne i grup entre dates
	                          Per informes de faltes d'assistència 
************************************************************************************************************ */
function getTotalCCCAlumneGrup($idalumne,$idgrup,$data_inici,$data_fi) {
	 $sql  = "SELECT COUNT(ccc_tp.id_falta) AS total ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 $sql .= "WHERE ccc_tp.idalumne = ".$idalumne. " AND ccc_tp.idgrup = ".$idgrup;
	 $sql .= " AND ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 	 
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
	getTotalCCCGrup --> Total CCC d'un grup entre dates
	                    Per informes de faltes d'assistència 
************************************************************************************************************ */
function getTotalCCCGrup($idgrup,$data_inici,$data_fi) {
	 $sql  = "SELECT COUNT(ccc_tp.id_falta) AS total ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 $sql .= "WHERE ccc_tp.idgrup = ".$idgrup;
     $sql .= " AND ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 	 
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
	getTotalCCCAlumnebyTipus --> Total CCC d'un alumne per tipus i entre dates
	                             Per informes de faltes d'assistència 
************************************************************************************************************ */
function getTotalCCCAlumnebyTipus($idalumne,$id_falta,$data_inici,$data_fi) {
	 $sql  = "SELECT ccc_tp.idalumne, ccc_tp.id_falta, COUNT(ccc_tp.id_falta) AS total ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 $sql .= "WHERE ccc_tp.idalumne = ".$idalumne." AND ccc_tp.id_falta=".$id_falta;
     $sql .= " AND ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 	 
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
	getTotalCCCProfessorbyTipus --> Total CCC d'un professor per tipus i entre dates
	                                Per informes de faltes d'assistència 
************************************************************************************************************ */
function getTotalCCCProfessorbyTipus($idprofessor,$id_falta,$data_inici,$data_fi) {
	 $sql  = "SELECT ccc_tp.idalumne, ccc_tp.id_falta, COUNT(ccc_tp.id_falta) AS total ";
	 $sql .= "FROM ccc_taula_principal ccc_tp ";
	 $sql .= "WHERE ccc_tp.idprofessor = ".$idprofessor." AND ccc_tp.id_falta=".$id_falta;
     $sql .= " AND ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
	 	 
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
?>