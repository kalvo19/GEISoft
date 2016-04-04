<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0 ;

$sql  = "SELECT pe.idperiodes_escolars,pef.id_festiu, ";
$sql .= "CONCAT(SUBSTR(pef.festiu,9,2),'-',SUBSTR(pef.festiu,6,2),'-',SUBSTR(pef.festiu,1,4)) AS festiu ";
$sql .= "FROM periodes_escolars pe ";
$sql .= "INNER JOIN periodes_escolars_festius pef ON pe.idperiodes_escolars=pef.id_periode ";
$sql .= "WHERE pef.id_periode=".$id;

$rs = mysql_query($sql);

$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}

echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>
