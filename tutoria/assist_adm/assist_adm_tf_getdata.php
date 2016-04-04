<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
mysql_query("SET NAMES 'utf8'");
$rs = mysql_query('select * from tipus_falta_alumne');
$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>