<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$page    = isset($_POST['page']) ? intval($_POST['page']) : 1;  
$rows    = isset($_POST['rows']) ? intval($_POST['rows']) : 20;  
$sort    = isset($_POST['sort']) ? strval($_POST['sort']) : 'codi_alumnes_saga';  
$order   = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
$cognoms = isset($_POST['cognoms']) ? mysql_real_escape_string($_POST['cognoms']) : '';

$offset = ($page-1)*$rows;
$result = array();

$where = "ca.Valor like '%$cognoms%'";

$sql  = "SELECT count(DISTINCT a.idalumnes) FROM alumnes a ";
$sql .= "INNER JOIN contacte_alumne ca ON ca.id_alumne=a.idalumnes ";
$sql .= "WHERE $where AND ca.id_tipus_contacte=".TIPUS_nom_complet;
  
$rs  = mysql_query($sql);  
$row = mysql_fetch_row($rs);  
$result["total"] = $row[0];  
  
$sql  = "SELECT DISTINCT a.codi_alumnes_saga,a.activat,a.acces_alumne,a.acces_familia,ca.* FROM alumnes a ";
$sql .= "INNER JOIN contacte_alumne ca       ON ca.id_alumne=a.idalumnes ";
$sql .= "WHERE $where AND ca.id_tipus_contacte=".TIPUS_nom_complet;
$sql .= " ORDER BY $sort $order limit $offset,$rows";

$rs = mysql_query($sql);  

$items = array();  
while($row = mysql_fetch_object($rs)){  
    array_push($items, $row);  
}  
$result["rows"] = $items;  
  
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>
