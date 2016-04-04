<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$sql  ="SELECT idmateria AS id, nom_materia AS nom FROM materia ";
$sql .="UNION ";
$sql .="SELECT uf.idunitats_formatives AS id, CONCAT(m.nom_modul,'-',uf.nom_uf) AS nom FROM moduls m ";
$sql .="INNER JOIN moduls_ufs mu         ON mu.id_moduls=m.idmoduls ";
$sql .="INNER JOIN unitats_formatives uf ON uf.idunitats_formatives=mu.id_ufs ";
$sql .=" ORDER BY 2";

$rs = mysql_query($sql);

$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>