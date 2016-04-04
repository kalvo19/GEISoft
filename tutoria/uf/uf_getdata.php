<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");
$rs = mysql_query('select * from unitats_formatives');
$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}

echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>