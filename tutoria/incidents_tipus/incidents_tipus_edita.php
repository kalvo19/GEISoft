<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id      		= isset($_REQUEST['idtipus_incident']) ? intval($_REQUEST['idtipus_incident']) : 0 ;
$tipus_incident = isset($_REQUEST['tipus_incident'])   ? str_replace("'","\'",$_REQUEST['tipus_incident']) : '' ;

$sql    = "update tipus_incidents set tipus_incident='$tipus_incident' where idtipus_incident=$id";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>