<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$tipus_incident = isset($_REQUEST['tipus_incident']) ? str_replace("'","\'",$_REQUEST['tipus_incident']) : 0 ;

$sql    = "insert into tipus_incidents (tipus_incident) values ('$tipus_incident')";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>
