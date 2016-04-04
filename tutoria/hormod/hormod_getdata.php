<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$sort    = isset($_POST['sort']) ? strval($_POST['sort']) : 'nom';  
$order   = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

$id_grups   = isset($_REQUEST['id_grups']) ? $_REQUEST['id_grups'] : ' ';
 
$sql  = "SELECT gm.idgrups_materies,gm.id_mat_uf_pla as id_mat_uf_pla,ma.nom_materia AS nom, ";
$sql .= "CONCAT(SUBSTR(gm.data_inici,9,2),'-',SUBSTR(gm.data_inici,6,2),'-',SUBSTR(gm.data_inici,1,4)) AS data_inici, ";
$sql .= "CONCAT(SUBSTR(gm.data_fi,9,2),'-',SUBSTR(gm.data_fi,6,2),'-',SUBSTR(gm.data_fi,1,4)) AS data_fi ";
$sql .= "FROM grups_materies gm ";
$sql .= "INNER JOIN materia ma ON gm.id_mat_uf_pla=ma.idmateria ";
$sql .= "INNER JOIN grups gr ON gm.id_grups=gr.idgrups ";
$sql .= "WHERE gm.id_grups='".$id_grups."' ";
$sql .= "UNION ";
$sql .= "SELECT gm.idgrups_materies,gm.id_mat_uf_pla as id_mat_uf_pla,CONCAT(m.nom_modul,'-',uf.nom_uf) AS nom, ";
$sql .= "CONCAT(SUBSTR(gm.data_inici,9,2),'-',SUBSTR(gm.data_inici,6,2),'-',SUBSTR(gm.data_inici,1,4)) AS data_inici, ";
$sql .= "CONCAT(SUBSTR(gm.data_fi,9,2),'-',SUBSTR(gm.data_fi,6,2),'-',SUBSTR(gm.data_fi,1,4)) AS data_fi ";
$sql .= "FROM grups_materies gm ";
$sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla=uf.idunitats_formatives ";
$sql .= "INNER JOIN moduls_ufs mu         ON gm.id_mat_uf_pla=mu.id_ufs ";
$sql .= "INNER JOIN moduls m              ON mu.id_moduls=m.idmoduls ";
$sql .= "INNER JOIN grups gr ON gm.id_grups=gr.idgrups ";
$sql .= "WHERE gm.id_grups='".$id_grups."' ";
$sql .= "ORDER BY $sort $order";

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