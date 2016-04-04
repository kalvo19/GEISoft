<?php
session_start();
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id_grups   = $_SESSION['grup_edicio'];

$sql  = "SELECT gm.idgrups_materies, m.nom_materia AS nom_materia FROM materia m ";
$sql .= "INNER JOIN grups_materies gm ON m.idmateria=gm.id_mat_uf_pla ";
$sql .= "WHERE gm.id_grups=".$id_grups;
$sql .= " UNION ";
$sql .= "SELECT gm.idgrups_materies, CONCAT(m.nom_modul,'-',uf.nom_uf) AS nom_materia FROM moduls m ";
$sql .= "INNER JOIN moduls_ufs mu         ON mu.id_moduls=m.idmoduls ";
$sql .= "INNER JOIN unitats_formatives uf ON uf.idunitats_formatives=mu.id_ufs ";
$sql .= "INNER JOIN grups_materies gm     ON uf.idunitats_formatives=gm.id_mat_uf_pla ";
$sql .= "WHERE gm.id_grups=".$id_grups;

$rs = mysql_query($sql);
$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>
