<?php
include('../bbdd/connect.php');

$idprofpermanencia  = intval($_REQUEST['id']);

$sql = "DELETE FROM prof_permanencies WHERE idprofpermanencia=$idprofpermanencia";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>$sql));
}

mysql_close();
?>