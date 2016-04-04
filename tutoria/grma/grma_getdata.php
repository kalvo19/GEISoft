<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$page    = isset($_POST['page']) ? intval($_POST['page']) : 1;  
$rows    = isset($_POST['rows']) ? intval($_POST['rows']) : 20;  
$sort    = isset($_POST['sort']) ? strval($_POST['sort']) : 'idgrups';  
$order   = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

$g_pe    = isset($_REQUEST['g_pe']) ? $_REQUEST['g_pe'] : '';
$where = '';
if ($g_pe != '') {
   $where .= " AND idgrups=".$g_pe;
}

$offset = ($page-1)*$rows;  
  
$result = array();

$rs = mysql_query("SELECT count(*) FROM grups WHERE idgrups<>0 ".$where); 
$row = mysql_fetch_row($rs);  
$result["total"] = $row[0];  
  
$rs = mysql_query("SELECT * from grups WHERE idgrups<>0 ".$where." ORDER BY $sort $order LIMIT $offset,$rows");
  
$items = array();  
while($row = mysql_fetch_object($rs)){  
    array_push($items, $row);  
}  
$result["rows"] = $items;  
  
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>