<?php
include_once('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$page = isset($_POST['page']) ? intval($_POST['page']) : 0;  
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;  
$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'Valor';  
$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc'; 
$cognoms = isset($_POST['cognoms']) ? mysql_real_escape_string($_POST['cognoms']) : '';
 
$offset = ($page-1)*$rows;  
  
$result = array(); 

$where = "cp.Valor like '%$cognoms%'";

$sql  = "SELECT count(*) FROM professor_carrec pc ";
$sql .= "INNER JOIN contacte_professor cp ON cp.id_professor=pc.idprofessors ";
$sql .= "WHERE $where AND cp.id_tipus_contacte=".TIPUS_nom_complet;
  
$rs = mysql_query($sql);  
$row = mysql_fetch_row($rs);  
$result["total"] = $row[0];  
  
$sql  = "SELECT cp.*,pc.*,c.nom_carrec,g.nom,pc.principal FROM professor_carrec pc ";
$sql .= "INNER JOIN contacte_professor cp ON cp.id_professor=pc.idprofessors ";
$sql .= "INNER JOIN carrecs c ON c.idcarrecs=pc.idcarrecs ";
$sql .= "LEFT JOIN grups g ON g.idgrups=pc.idgrups ";
$sql .= "WHERE $where AND cp.id_tipus_contacte=".TIPUS_nom_complet;

$sql .= " ORDER BY $sort $order limit $offset,$rows";

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
fclose($fp);*/

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