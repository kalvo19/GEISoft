<?php


include('../bbdd/connect.php');
include_once('../func/generic.php');
include_once('../func/constants.php');
mysql_query("SET NAMES 'utf8'");

$q = isset($_REQUEST['idplan_estudi']) ? strval($_REQUEST['idplan_estudi']) : '';

$sql = "SELECT idmoduls, nom_modul FROM moduls WHERE idplans_estudis = $q";

$result = array();

$rs = mysql_query($sql);

$items = array();  
while($row = mysql_fetch_assoc($rs)){  
    $items[] = $row;
}  

echo json_encode($items);

?>
