<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$q    = isset($_POST['q']) ? strval($_POST['q']) : '';

$sql  = "SELECT * from grups WHERE idgrups<>0 ";
$sql .= "ORDER BY nom";

$rs = mysql_query($sql);

$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>