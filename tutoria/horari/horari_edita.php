<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idalumnes_grup_materia = intval($_REQUEST['id']);
$idalumnes         = $_REQUEST['idalumnes'];
$idgrups_materies  = $_REQUEST['idgrups_materies'];

$sql = "UPDATE alumnes_grup_materia SET idalumnes='$idalumnes',idgrups_materies='$idgrups_materies' WHERE idalumnes_grup_materia=$idalumnes_grup_materia";

$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
fclose($fp);

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>