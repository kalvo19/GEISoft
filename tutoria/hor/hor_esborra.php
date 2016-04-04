<?php
include('../bbdd/connect.php');

$idunitats_classe  = intval($_REQUEST['id']);

$sql = "DELETE FROM unitats_classe WHERE idunitats_classe=$idunitats_classe";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>$sql));
}

mysql_close();
?>