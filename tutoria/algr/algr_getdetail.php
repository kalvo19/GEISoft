<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");
 
$idalumnes   = isset($_REQUEST['idalumnes']) ? $_REQUEST['idalumnes'] : 0 ;

$sql  = "SELECT DISTINCT(gr.idgrups), gr.nom, gr.Descripcio ";
$sql .= "FROM alumnes_grup_materia agm ";
$sql .= "INNER JOIN grups_materies gm ON agm.idgrups_materies = gm.idgrups_materies ";
$sql .= "INNER JOIN materia        ma ON gm.id_mat_uf_pla     = ma.idmateria ";
$sql .= "INNER JOIN grups          gr ON gm.id_grups          = gr.idgrups ";
$sql .= "WHERE agm.idalumnes='".$idalumnes."' ";
$sql .= "UNION ";
$sql .= "SELECT DISTINCT(gr.idgrups), gr.nom, gr.Descripcio ";
$sql .= "FROM alumnes_grup_materia agm ";
$sql .= "INNER JOIN grups_materies     gm ON agm.idgrups_materies = gm.idgrups_materies ";
$sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
$sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
$sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
$sql .= "INNER JOIN grups              gr ON gm.id_grups          = gr.idgrups ";
$sql .= "WHERE agm.idalumnes='".$idalumnes."' ";

$sql .= "ORDER BY 1";

$rs = mysql_query($sql);

$items = array();  
while($row = mysql_fetch_object($rs)){  
    array_push($items, $row);  
}  
echo json_encode($items); 

mysql_free_result($rs);
mysql_close();
?>