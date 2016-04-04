<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$curs_escolar  = getCursActual()->idperiodes_escolars; 
$idgrups       = isset($_REQUEST['idgrups']) ? $_REQUEST['idgrups'] : 0;

$sql  = "SELECT CONCAT('0') AS idmateria,CONCAT('  Totes les materies ...') AS materia UNION ";
$sql .= "SELECT m.idmateria AS idmateria, m.nom_materia AS materia ";
$sql .= "FROM unitats_classe uc ";
$sql .= "INNER JOIN dies_franges     df ON uc.id_dies_franges    = df.id_dies_franges ";
$sql .= "INNER JOIN grups_materies   gm ON uc.idgrups_materies   = gm.idgrups_materies ";
$sql .= "INNER JOIN materia           m ON gm.id_mat_uf_pla      = m.idmateria ";	 
$sql .= "WHERE df.idperiode_escolar=".$curs_escolar." AND gm.id_grups='".$idgrups."' ";	
$sql .= "GROUP BY uc.idgrups_materies";
	 
$sql .= " UNION ";
	 
$sql .= "SELECT uf.idunitats_formatives AS idmateria, CONCAT(m.nom_modul,'-',uf.nom_uf) AS materia ";
$sql .= "FROM unitats_classe uc ";
$sql .= "INNER JOIN dies_franges       df ON uc.id_dies_franges    = df.id_dies_franges ";
$sql .= "INNER JOIN grups_materies     gm ON uc.idgrups_materies   = gm.idgrups_materies ";
$sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla        = uf.idunitats_formatives ";
$sql .= "INNER JOIN moduls_ufs         mu ON uf.idunitats_formatives = mu.id_ufs ";
$sql .= "INNER JOIN moduls              m ON mu.id_moduls            = m.idmoduls "; 
$sql .= "WHERE df.idperiode_escolar=".$curs_escolar." AND gm.id_grups='".$idgrups."' ";	
$sql .= "GROUP BY uc.idgrups_materies";

$rs = mysql_query($sql);
  
$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>