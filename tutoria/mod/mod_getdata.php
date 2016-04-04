<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$s_pe    = isset($_REQUEST['s_pe']) ? $_REQUEST['s_pe'] : '';

$page    = isset($_POST['page']) ? intval($_POST['page']) : 1;  
$rows    = isset($_POST['rows']) ? intval($_POST['rows']) : 20;  
$sort    = isset($_POST['sort']) ? strval($_POST['sort']) : 'idmoduls';  
$order   = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

$offset = ($page-1)*$rows;  
  
$result = array();

$sql  = "SELECT count(*) FROM moduls mo ";
if (isset($_REQUEST['s_pe'])) {
   $sql .= "WHERE mo.idplans_estudis=".$s_pe;
}
$rs  = mysql_query($sql);
$row = mysql_fetch_row($rs);  
$result["total"] = $row[0];


$sql  = "SELECT mo.*,pe.Acronim_pla_estudis FROM moduls mo ";
$sql .= "INNER JOIN plans_estudis pe ON mo.idplans_estudis=pe.idplans_estudis ";

if (isset($_REQUEST['s_pe'])) {
   $sql .= "WHERE mo.idplans_estudis=".$s_pe;
}
$sql .= " ORDER BY $sort $order LIMIT $offset,$rows";

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

