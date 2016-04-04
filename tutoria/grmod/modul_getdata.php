<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idplans_estudis   = isset($_REQUEST['idplans_estudis']) ? $_REQUEST['idplans_estudis'] : 0;

$sql  = "SELECT m.idmoduls,m.nom_modul FROM moduls m ";
$sql .= "INNER JOIN plans_estudis pe ON m.idplans_estudis=pe.idplans_estudis ";
$sql .= "WHERE m.idplans_estudis=".$idplans_estudis;

$rs = mysql_query($sql);

$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>