<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$nom_torn = isset($_REQUEST['nom_torn']) ? $_REQUEST['nom_torn'] : '';

$sql = "INSERT INTO torn (nom_torn) VALUES('$nom_torn')";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>