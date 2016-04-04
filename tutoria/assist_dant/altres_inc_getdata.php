<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idalumnes          = isset($_REQUEST['idalumnes']) ? $_REQUEST['idalumnes'] : 0 ;
$data               = isset($_REQUEST['data']) ? $_REQUEST['data'] : '1989-1-1';
$idfranges_horaries = isset($_REQUEST['idfranges_horaries'])     ? $_REQUEST['idfranges_horaries']     : 0 ;

$result = array();

$sql  = "SELECT a.idalumnes,ia.id_tipus_incidencia,ia.id_tipus_incident,tf.tipus_falta,ia.comentari,ti.tipus_incident,ia.idincidencia_alumne ";
$sql .= "FROM alumnes a ";
$sql .= "INNER JOIN incidencia_alumne  ia ON a.idalumnes=ia.idalumnes "; 
$sql .= "INNER JOIN tipus_incidents  ti ON ia.id_tipus_incident=ti.idtipus_incident "; 
$sql .= "INNER JOIN tipus_falta_alumne tf ON ia.id_tipus_incidencia=tf.idtipus_falta_alumne ";
$sql .= "WHERE a.activat='S' AND ia.data='".$data."' AND ia.idfranges_horaries='".$idfranges_horaries."' AND ia.idalumnes=".$idalumnes;

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