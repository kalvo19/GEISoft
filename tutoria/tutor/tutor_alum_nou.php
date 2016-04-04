<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idalumnes         = $_REQUEST['idalumnes'];
$idgrups_materies  = $_REQUEST['idgrups_materies'];

$sql = "INSERT INTO alumnes_grup_materia (idalumnes,idgrups_materies) VALUES ('$idalumnes','$idgrups_materies')";

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
