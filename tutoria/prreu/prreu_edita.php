<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idprofreunio      = intval($_REQUEST['id']);
$id_dies_franges   = $_REQUEST['id_dies_franges'];
$idespais_centre   = $_REQUEST['idespais_centre'];

$sql = "UPDATE prof_reunions SET id_dies_franges='$id_dies_franges',idespais_centre='$idespais_centre' WHERE idprofreunio=$idprofreunio";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>$sql));
}

mysql_close();
?>