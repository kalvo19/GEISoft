<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idtorn  = intval($_REQUEST['id']);
$nom_torn = isset($_REQUEST['nom_torn']) ? $_REQUEST['nom_torn'] : '';

$sql = "UPDATE torn SET nom_torn='$nom_torn' WHERE idtorn=$idtorn";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>