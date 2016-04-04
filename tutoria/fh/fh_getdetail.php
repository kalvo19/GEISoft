<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0 ;

$sql  = "SELECT df.*,ds.dies_setmana FROM dies_franges df ";
$sql .= "INNER JOIN dies_setmana ds ON df.iddies_setmana=ds.iddies_setmana ";
$sql .= "WHERE idfranges_horaries=".$id;

$rs = mysql_query($sql);

$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}

echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>
