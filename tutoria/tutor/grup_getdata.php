<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idgrups = $_REQUEST['idgrups'];
$rs = mysql_query('select * from grups where idgrups='.$idgrups);

$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>