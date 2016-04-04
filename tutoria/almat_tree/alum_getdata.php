<?php
include('../bbdd/connect.php');
include('../func/constants.php');
include('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$sql  = "SELECT DISTINCT ca.id_alumne,ca.Valor as alumne FROM alumnes a ";
$sql .= "INNER JOIN contacte_alumne ca ON ca.id_alumne        =  a.idalumnes ";
$sql .= "INNER JOIN alumnes_grup_materia agm ON agm.idalumnes = ca.id_alumne ";
$sql .= "WHERE ca.id_tipus_contacte=".TIPUS_nom_complet;
$sql .= " ORDER BY ca.Valor";

$rs = mysql_query($sql);
$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>