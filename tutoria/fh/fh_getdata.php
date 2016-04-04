<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$s_torn  = isset($_REQUEST['s_torn']) ? $_REQUEST['s_torn'] : '';

$page    = isset($_POST['page']) ? intval($_POST['page']) : 1;  
$rows    = isset($_POST['rows']) ? intval($_POST['rows']) : 20;  
$sort    = isset($_POST['sort']) ? strval($_POST['sort']) : '2,5';  
$order   = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

$offset = ($page-1)*$rows; 

$sql  = "select fh.*,t.nom_torn from franges_horaries fh ";
$sql .= "left join torn t on fh.idtorn=t.idtorn ";

if (isset($_REQUEST['s_torn'])) {
   $sql .= "WHERE fh.idtorn=".$s_torn;
}

$sql .= " order by $sort $order limit $offset,$rows ";

$rs = mysql_query($sql);

$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}

echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>
