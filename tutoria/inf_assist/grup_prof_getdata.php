<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idprofessors = isset($_REQUEST['idprofessors']) ? $_REQUEST['idprofessors'] : 0;

$sql  = "SELECT distinct(gr.idgrups),gr.nom FROM grups gr ";
$sql .= "INNER JOIN grups_materies  gm ON gm.id_grups=gr.idgrups ";
$sql .= "INNER JOIN prof_agrupament pa ON pa.idagrups_materies=gm.idgrups_materies ";
$sql .= "WHERE pa.idprofessors='".$idprofessors."' ";
$sql .= "ORDER BY 2 ";

$rs = mysql_query($sql);
$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>