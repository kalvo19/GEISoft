<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$nom_uf = str_replace("'","\'",$_REQUEST['nom_uf']);
$hores  = $_REQUEST['hores'];

$sql = "insert into unitats_formatives (nom_uf,hores) values ('$nom_uf','$hores')";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>
