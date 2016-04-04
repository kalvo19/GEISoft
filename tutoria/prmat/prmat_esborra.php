<?php
include('../bbdd/connect.php');

$idprof_grup_materia  = intval($_REQUEST['id']);

$sql = "DELETE FROM prof_agrupament WHERE idprof_grup_materia=$idprof_grup_materia";

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
fclose($fp);*/

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>