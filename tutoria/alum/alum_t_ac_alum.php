<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idalumnes = intval($_REQUEST['idalumnes']);
$sql       = "update alumnes set acces_alumne='N' where idalumnes=$idalumnes";
$result    = @mysql_query($sql);

if ($result){
		echo json_encode(array('success'=>true));
	} else {
		echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
	}

mysql_close();
?>