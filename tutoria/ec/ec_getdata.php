<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$page       = isset($_POST['page']) ? intval($_POST['page']) : 1;  
$rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 20;  
$sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'descripcio';  
$order      = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
$descripcio = isset($_POST['descripcio']) ? mysql_real_escape_string($_POST['descripcio']) : '';

$offset = ($page-1)*$rows; 

$result = array();

$where = "descripcio like '%$descripcio%'";

$sql = "select count(*) from espais_centre where " . $where;
$rs = mysql_query($sql);  
$row = mysql_fetch_row($rs);  
$result["total"] = $row[0];
  
$sql = "select * from espais_centre where " . $where . " order by $sort $order limit $offset,$rows";
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
