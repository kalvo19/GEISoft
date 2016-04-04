<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idgrups    = isset($_REQUEST['idgrups']) ? $_REQUEST['idgrups'] : 0 ;
$cognoms = isset($_POST['cognoms']) ? mysql_real_escape_string($_POST['cognoms']) : '';

$sort   = isset($_POST['sort']) ? strval($_POST['sort']) : '2';  
$order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

$result = array();

$where = "AND ca.Valor like '%$cognoms%'";

$sql  = "SELECT DISTINCT(agm.idalumnes),ca.Valor,a.acces_alumne,a.acces_familia,a.activat,a.codi_alumnes_saga ";
$sql .= "FROM alumnes_grup_materia agm ";
$sql .= "INNER JOIN alumnes            a ON agm.idalumnes         = a.idalumnes ";
$sql .= "INNER JOIN contacte_alumne   ca ON agm.idalumnes         = ca.id_alumne ";
$sql .= "INNER JOIN grups_materies    gm ON agm.idgrups_materies  = gm.idgrups_materies ";	 
$sql .= "INNER JOIN grups              g ON gm.id_grups           = g.idgrups ";
$sql .= "INNER JOIN materia            m ON gm.id_mat_uf_pla      = m.idmateria ";
$sql .= "WHERE a.activat='S' AND g.idgrups=".$idgrups." AND ca.id_tipus_contacte=".TIPUS_nom_complet." ".$where;	
//$sql .= " ORDER BY $sort $order ";

$sql .= " UNION ";

$sql .= "SELECT DISTINCT(agm.idalumnes),ca.Valor,a.acces_alumne,a.acces_familia,a.activat,a.codi_alumnes_saga ";
$sql .= "FROM alumnes_grup_materia agm ";
$sql .= "INNER JOIN alumnes             a ON agm.idalumnes        = a.idalumnes ";
$sql .= "INNER JOIN contacte_alumne    ca ON agm.idalumnes        = ca.id_alumne ";
$sql .= "INNER JOIN grups_materies     gm ON agm.idgrups_materies = gm.idgrups_materies ";	 
$sql .= "INNER JOIN grups               g ON gm.id_grups          = g.idgrups ";
$sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
$sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
$sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
$sql .= "WHERE a.activat='S' AND g.idgrups=".$idgrups." AND ca.id_tipus_contacte=".TIPUS_nom_complet." ".$where;	

$sql .= " ORDER BY 2 $order ";

$rs = mysql_query($sql);

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
fclose($fp);*/

$items = array();  
while($row = mysql_fetch_object($rs)){  
    array_push($items, $row);  
}  
$result["rows"] = $items;  
  
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>

