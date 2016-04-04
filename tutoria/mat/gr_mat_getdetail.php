<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");
 
$idmateria = isset($_REQUEST['idmateria']) ? $_REQUEST['idmateria'] : 0 ;

$sql  = "SELECT gm.id_grups,g.nom FROM grups_materies gm ";
$sql .= "INNER JOIN grups g ON gm.id_grups=g.idgrups ";
$sql .= "WHERE gm.id_mat_uf_pla=".$idmateria;
$sql .= " ORDER BY 2";

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql ."\n\n". PHP_EOL);
fclose($fp);*/

$rs = mysql_query($sql);

$items = array();  
while($row = mysql_fetch_object($rs)){  
    array_push($items, $row);  
}  
echo json_encode($items); 

mysql_free_result($rs);
mysql_close();
?>