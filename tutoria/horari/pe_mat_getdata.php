<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$sql  = "SELECT gm.*,ma.nom_materia,gr.nom,CONCAT(ma.nom_materia,' - ',gr.nom) AS matgrup FROM grups_materies gm ";
$sql .= "INNER JOIN materia ma ON gm.id_mat_uf_pla=ma.idmateria ";
$sql .= "INNER JOIN grups gr ON gm.id_grups=gr.idgrups ";
$sql .= "ORDER BY ma.nom_materia,gr.nom";

$rs = mysql_query($sql);

$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>