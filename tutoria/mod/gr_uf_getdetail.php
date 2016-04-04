<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");
 
$idunitats_formatives = isset($_REQUEST['idunitats_formatives']) ? $_REQUEST['idunitats_formatives'] : 0 ;

$sql  = "SELECT gm.id_grups,g.nom FROM grups_materies gm ";
$sql .= "INNER JOIN grups g ON gm.id_grups=g.idgrups ";
$sql .= "WHERE gm.id_mat_uf_pla=".$idunitats_formatives;
$sql .= " ORDER BY 2";

$rs = mysql_query($sql);

$items = array();  
while($row = mysql_fetch_object($rs)){  
    array_push($items, $row);  
}  
echo json_encode($items); 

mysql_free_result($rs);
mysql_close();
?>