<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$q    = isset($_POST['q']) ? strval($_POST['q']) : '';

$sql  = "SELECT gm.idgrups_materies,ma.nom_materia AS materia,gr.nom,CONCAT(ma.nom_materia,' - ',gr.nom) AS matgrup FROM grups_materies gm ";
$sql .= "INNER JOIN materia ma ON gm.id_mat_uf_pla=ma.idmateria ";
$sql .= "INNER JOIN grups gr ON gm.id_grups=gr.idgrups ";
$sql .= "WHERE ma.nom_materia like '%$q%' OR gr.nom like '%$q%' ";

$sql .= " UNION ";

$sql .= "SELECT gm.idgrups_materies,CONCAT(m.nom_modul,'-',uf.nom_uf) AS materia,gr.nom, ";
$sql .= "CONCAT(m.nom_modul,'::',uf.nom_uf,' - ',gr.nom) AS matgrup FROM grups_materies gm ";
$sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla        = uf.idunitats_formatives ";
$sql .= "INNER JOIN moduls_ufs         mu ON uf.idunitats_formatives = mu.id_ufs ";
$sql .= "INNER JOIN moduls              m ON mu.id_moduls            = m.idmoduls ";
$sql .= "INNER JOIN grups              gr ON gm.id_grups=gr.idgrups ";
$sql .= "WHERE uf.nom_uf like '%$q%' OR gr.nom like '%$q%' ";

$sql .= "ORDER BY 1,2";

$rs = mysql_query($sql);

$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>