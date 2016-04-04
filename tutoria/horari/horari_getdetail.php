<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");
 
$idalumnes   = $_REQUEST['idalumnes'];

$sql  = "SELECT agm.*,ma.nom_materia,CONCAT(ma.nom_materia,' - ',gr.nom) AS matgrup FROM alumnes_grup_materia agm ";
$sql .= "INNER JOIN grups_materies gm ON agm.idgrups_materies=gm.idgrups_materies ";
$sql .= "INNER JOIN materia ma ON gm.id_mat_uf_pla=ma.idmateria ";
$sql .= "INNER JOIN grups gr ON gm.id_grups=gr.idgrups ";
$sql .= "WHERE agm.idalumnes='".$idalumnes."'";

$rs = mysql_query($sql);

$items = array();  
while($row = mysql_fetch_object($rs)){  
    array_push($items, $row);  
}  
echo json_encode($items); 

mysql_free_result($rs);
mysql_close();
?>