<?php
include('../bbdd/connect.php');

$idprofcoordinacio  = intval($_REQUEST['id']);

$sql = "DELETE FROM prof_coordinacions WHERE idprofcoordinacio=$idprofcoordinacio";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>$sql));
}

mysql_close();
?>