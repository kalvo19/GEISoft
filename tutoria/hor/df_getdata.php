<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$sql  = "SELECT df.*,ds.dies_setmana,CONCAT(t.nom_torn,'-',ds.dies_setmana,'(',LEFT(fh.hora_inici,5),'-',LEFT(fh.hora_fi,5),')') AS dia_hora,CONCAT(LEFT(fh.hora_inici,5),'-',LEFT(fh.hora_fi,5)) AS hora from dies_franges df ";
$sql .= "INNER JOIN dies_setmana     ds ON df.iddies_setmana     = ds.iddies_setmana ";
$sql .= "INNER JOIN franges_horaries fh ON df.idfranges_horaries = fh.idfranges_horaries ";
$sql .= "INNER JOIN torn 			  t ON t.idtorn              = fh.idtorn ";
$sql .= "WHERE fh.esbarjo<>'S' ORDER BY 2,6";

$rs = mysql_query($sql);
$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>