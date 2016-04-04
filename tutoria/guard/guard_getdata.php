<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$grup_materia        = isset($_REQUEST['grup_materia']) ? $_REQUEST['grup_materia'] : 0 ;
$data =  date("Y-m-d");
$idfranges_horaries  = isset($_REQUEST['idfranges_horaries']) ? $_REQUEST['idfranges_horaries']     : 0 ;

$sort   = isset($_POST['sort']) ? strval($_POST['sort']) : '2';  
$order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

$result = array();

$sql  = "(SELECT DISTINCT(agm.idalumnes),ca.Valor AS alumne,ia.id_tipus_incidencia,tf.tipus_falta,ia.data,ia.comentari,agm.idalumnes_grup_materia,CONCAT('".$data."') AS data,ia.idfranges_horaries ";
$sql .= "FROM alumnes_grup_materia agm ";
$sql .= "INNER JOIN alumnes            a ON agm.idalumnes = a.idalumnes ";
$sql .= "INNER JOIN incidencia_alumne  ia ON agm.idalumnes = ia.idalumnes "; 
$sql .= "INNER JOIN tipus_falta_alumne tf ON ia.id_tipus_incidencia = tf.idtipus_falta_alumne ";
$sql .= "INNER JOIN contacte_alumne   ca ON agm.idalumnes = ca.id_alumne ";
$sql .= "INNER JOIN grups_materies    gm ON agm.idgrups_materies = gm.idgrups_materies ";
$sql .= "WHERE a.activat='S' AND agm.idgrups_materies=".$grup_materia." AND ca.id_tipus_contacte=".TIPUS_nom_complet." AND ia.data='".$data."' AND ia.idfranges_horaries='".$idfranges_horaries."' GROUP BY 1) ";
$sql .= "UNION (SELECT DISTINCT(agm.idalumnes),ca.Valor AS alumne,";
$sql .= "CONCAT(' ') AS id_tipus_incidencia,CONCAT(' ') AS tipus_falta,CONCAT(' ') AS data,CONCAT(' ') AS comentari, "; 
$sql .= "agm.idalumnes_grup_materia,CONCAT('".$data."') AS data,".$idfranges_horaries." AS idfranges_horaries ";
$sql .= "FROM alumnes_grup_materia agm ";
$sql .= "INNER JOIN alumnes            a ON agm.idalumnes = a.idalumnes ";
$sql .= "INNER JOIN contacte_alumne   ca ON agm.idalumnes = ca.id_alumne ";
$sql .= "INNER JOIN grups_materies    gm ON agm.idgrups_materies = gm.idgrups_materies ";
$sql .= "WHERE a.activat='S' AND agm.idgrups_materies=".$grup_materia." AND ca.id_tipus_contacte=".TIPUS_nom_complet." AND agm.idalumnes NOT IN ";
$sql .= "(SELECT DISTINCT(agm.idalumnes) ";
$sql .= "FROM alumnes_grup_materia agm ";
$sql .= "INNER JOIN incidencia_alumne  ia ON agm.idalumnes=ia.idalumnes "; 
$sql .= "INNER JOIN contacte_alumne   ca ON agm.idalumnes=ca.id_alumne ";
$sql .= "INNER JOIN grups_materies    gm ON agm.idgrups_materies  = gm.idgrups_materies ";
$sql .= "INNER JOIN grups              g ON gm.id_grups           = g.idgrups "; 
$sql .= "WHERE agm.idgrups_materies=".$grup_materia." AND ca.id_tipus_contacte=".TIPUS_nom_complet." AND ia.data='".$data."' AND ia.idfranges_horaries='".$idfranges_horaries."')) ";
$sql .= "ORDER BY $sort $order ";

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
