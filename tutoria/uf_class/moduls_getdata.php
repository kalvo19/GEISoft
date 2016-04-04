<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idprofessors = isset($_REQUEST['idprofessors']) ? $_REQUEST['idprofessors'] : 0;

$sql .= "SELECT distinct(m.idmoduls),CONCAT(m.nom_modul) AS modul ";
$sql .= "FROM prof_agrupament pa  ";
$sql .= "INNER JOIN grups_materies  gm ON gm.idgrups_materies = pa.idagrups_materies ";
$sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla = uf.idunitats_formatives ";
$sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla = mu.id_ufs ";
$sql .= "INNER JOIN moduls              m ON mu.id_moduls     =  m.idmoduls ";
$sql .= "WHERE pa.idprofessors='".$idprofessors."' ";

$sql .= "ORDER BY 1,2 ";

$rs = mysql_query($sql);

$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>