<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$nom         = str_replace("'","\'",$_REQUEST['nom']);
$descripcio  = str_replace("'","\'",$_REQUEST['descripcio']);

$sql         = "insert into textos_sms(nom,descripcio) values ('$nom','$descripcio')";
$result      = @mysql_query($sql);
	
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>
