<?php
include('../bbdd/connect.php');

$idprofdireccio  = intval($_REQUEST['id']);

$sql = "DELETE FROM prof_direccio WHERE idprofdireccio=$idprofdireccio";

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
fclose($fp);*/

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>$sql));
}

mysql_close();
?>