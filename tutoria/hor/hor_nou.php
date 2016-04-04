<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id_dies_franges   = $_REQUEST['id_dies_franges'];
$idespais_centre   = $_REQUEST['idespais_centre'];
$idgrups_materies  = $_REQUEST['idgrups_materies'];

$sql = "INSERT INTO unitats_classe (id_dies_franges,idespais_centre,idgrups_materies) VALUES ('$id_dies_franges','$idespais_centre','$idgrups_materies')";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>$sql));
}

mysql_close();
?>
