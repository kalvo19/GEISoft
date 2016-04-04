<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$page    = isset($_POST['page']) ? intval($_POST['page']) : 1;  
$rows    = isset($_POST['rows']) ? intval($_POST['rows']) : 20;  
$sort    = isset($_POST['sort']) ? strval($_POST['sort']) : 'itemid';  
$order   = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
$cognoms = isset($_POST['cognoms']) ? mysql_real_escape_string($_POST['cognoms']) : '';


$offset = ($page-1)*$rows;  
  
$result = array();

$where = "cognoms like '%$cognoms%'";  
$rs = mysql_query("select count(*) from alumnes where " . $where);  
$row = mysql_fetch_row($rs);  
$result["total"] = $row[0];
  
$rs = mysql_query("select * from alumnes where " . $where . " order by $sort $order limit $offset,$rows");
  
$items = array();  
while($row = mysql_fetch_object($rs)){  
    array_push($items, $row); 
}  
$result["rows"] = $items;  
  
echo json_encode($result);

mysql_free_result($rs);
mysql_free_result($result);
mysql_close();
?>
