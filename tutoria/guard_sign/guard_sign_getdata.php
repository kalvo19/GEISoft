<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$page       = isset($_POST['page']) ? intval($_POST['page']) : 1;  
$rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 20;  
$sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'data';  
$order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';

$data_inici = isset($_REQUEST['data_inici']) ? substr($_REQUEST['data_inici'],6,4)."-".substr($_REQUEST['data_inici'],3,2)."-".substr($_REQUEST['data_inici'],0,2) : '1989-1-1';
$data_fi    = isset($_REQUEST['data_fi'])    ? substr($_REQUEST['data_fi'],6,4)."-".substr($_REQUEST['data_fi'],3,2)."-".substr($_REQUEST['data_fi'],0,2)          : '2189-1-1';

$offset     = ($page-1)*$rows;
$result     = array();


$sql  = "SELECT cp.Valor,gs.idprofessors,COUNT(gs.idprofessors) AS Total ";
$sql .= "FROM guardies_signades gs ";
$sql .= "INNER JOIN contacte_professor cp ON gs.idprofessors = cp.id_professor ";
$sql .= "WHERE cp.id_tipus_contacte=".TIPUS_nom_complet." AND gs.data BETWEEN '".$data_inici."' AND '".$data_fi."' ";
$sql .= " GROUP BY gs.idprofessors ";
$sql .= " ORDER BY 3 DESC ";
  
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