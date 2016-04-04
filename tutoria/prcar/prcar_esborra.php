<?php
include('../bbdd/connect.php');

$idprofessor_carrec  = intval($_REQUEST['id']);

$sql = "DELETE FROM professor_carrec WHERE idprofessor_carrec=$idprofessor_carrec";

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