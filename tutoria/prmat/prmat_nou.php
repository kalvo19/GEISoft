<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idprofessors       = $_REQUEST['idprofessors'];
$idagrups_materies  = $_REQUEST['idagrups_materies'];

$sql = "INSERT INTO prof_agrupament (idprofessors,idagrups_materies) VALUES ('$idprofessors','$idagrups_materies')";

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
