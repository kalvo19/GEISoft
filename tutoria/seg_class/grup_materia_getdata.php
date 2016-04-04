<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idprofessors = isset($_REQUEST['idprofessors']) ? $_REQUEST['idprofessors'] : 0;

$sql  = "SELECT distinct(pa.idagrups_materies),gr.nom AS grup,m.nom_materia AS materia ";
$sql .= "FROM prof_agrupament pa  ";
$sql .= "INNER JOIN grups_materies  gm ON gm.idgrups_materies = pa.idagrups_materies ";
$sql .= "INNER JOIN grups           gr ON gr.idgrups          = gm.id_grups ";
$sql .= "INNER JOIN materia          m ON  m.idmateria        = gm.id_mat_uf_pla ";
$sql .= "WHERE pa.idprofessors='".$idprofessors."' ";
$sql .= "UNION ";
$sql .= "SELECT distinct(pa.idagrups_materies),gr.nom AS grup,CONCAT(LEFT(m.nom_modul,20),'-',uf.nom_uf) AS materia ";
$sql .= "FROM prof_agrupament pa  ";
$sql .= "INNER JOIN grups_materies  gm ON gm.idgrups_materies = pa.idagrups_materies ";
$sql .= "INNER JOIN grups           gr ON gr.idgrups          = gm.id_grups ";
$sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla = uf.idunitats_formatives ";
$sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla = mu.id_ufs ";
$sql .= "INNER JOIN moduls              m ON mu.id_moduls     =  m.idmoduls ";
$sql .= "WHERE pa.idprofessors='".$idprofessors."' ";

$sql .= "ORDER BY 2,3 ";

$rs = mysql_query($sql);

$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>