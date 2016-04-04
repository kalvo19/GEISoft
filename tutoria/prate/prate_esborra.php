<?php
include('../bbdd/connect.php');

$idprofatencio  = intval($_REQUEST['id']);

$sql = "DELETE FROM prof_atencions WHERE idprofatencio=$idprofatencio";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>$sql));
}

mysql_close();
?>