<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idunitats_classe  = intval($_REQUEST['id']);
$id_dies_franges   = $_REQUEST['id_dies_franges'];
$idespais_centre   = $_REQUEST['idespais_centre'];
$idgrups_materies  = $_REQUEST['idgrups_materies'];

$sql = "UPDATE unitats_classe SET id_dies_franges='$id_dies_franges',idespais_centre='$idespais_centre',idgrups_materies='$idgrups_materies' WHERE idunitats_classe=$idunitats_classe";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>$sql));
}

mysql_close();
?>