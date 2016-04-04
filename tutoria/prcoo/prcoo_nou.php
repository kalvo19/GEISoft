<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idprofessors      = $_REQUEST['idprofessors'];
$id_dies_franges   = $_REQUEST['id_dies_franges'];
$idespais_centre   = $_REQUEST['idespais_centre'];

$sql = "INSERT INTO prof_coordinacions (idprofessors,id_dies_franges,idespais_centre) VALUES ('$idprofessors','$id_dies_franges','$idespais_centre')";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>$sql));
}

mysql_close();
?>
