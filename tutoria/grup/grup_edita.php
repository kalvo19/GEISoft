<?php
session_start();
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id         = intval($_REQUEST['id']);
$idtorn     = $_REQUEST['idtorn'];
$nom        = str_replace("'","\'",$_REQUEST['nom']);
$Descripcio = str_replace("'","\'",$_REQUEST['Descripcio']);

$sql = "update grups set idtorn='$idtorn',nom='$nom',Descripcio='$Descripcio' where idgrups=$id";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>