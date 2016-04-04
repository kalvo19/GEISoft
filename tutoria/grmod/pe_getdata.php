<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$sql  = "SELECT * FROM plans_estudis WHERE activat='S'";

$rs = mysql_query($sql);

$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>