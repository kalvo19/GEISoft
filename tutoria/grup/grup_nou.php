<?php
session_start();
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idtorn     = $_REQUEST['idtorn'];
$nom        = str_replace("'","\'",$_REQUEST['nom']);
$Descripcio = str_replace("'","\'",$_REQUEST['Descripcio']);

// Insertem la moduls_materies_ufs
$sql = "insert into grups (idtorn,nom,Descripcio) values ('$idtorn','$nom','$Descripcio')";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>
