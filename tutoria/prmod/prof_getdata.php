<?php
include('../bbdd/connect.php');
include('../func/constants.php');
include('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$sql  = "SELECT DISTINCT p.codi_professor,cp.* FROM professors p ";
$sql .= "INNER JOIN contacte_professor cp ON cp.id_professor=p.idprofessors ";
$sql .= "WHERE cp.id_tipus_contacte=".TIPUS_nom_complet;
$sql .= " ORDER BY cp.Valor";

$rs = mysql_query($sql);
$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>