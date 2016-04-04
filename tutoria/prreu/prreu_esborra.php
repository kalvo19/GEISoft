<?php
include('../bbdd/connect.php');

$idprofreunio  = intval($_REQUEST['id']);

$sql = "DELETE FROM prof_reunions WHERE idprofreunio=$idprofreunio";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>$sql));
}

mysql_close();
?>