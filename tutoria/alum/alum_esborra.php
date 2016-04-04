<?php
include('../bbdd/connect.php');

$id = intval($_REQUEST['id']);

$sql    = "delete from alumnes_grup_materia where idalumnes=$id";
$result = @mysql_query($sql);

$fp = fopen("log.txt","a");
fwrite($fp, $result . PHP_EOL);
fclose($fp);

$sql    = "delete from incidencia_alumne where idalumnes=$id ";
$result = @mysql_query($sql);

$fp = fopen("log.txt","a");
fwrite($fp, $result . PHP_EOL);
fclose($fp);

$sql    = "delete from alumnes_families where idalumnes=$id";
$result = @mysql_query($sql);

$fp = fopen("log.txt","a");
fwrite($fp, $result . PHP_EOL);
fclose($fp);

$sql    = "delete from contacte_alumne where id_alumne=$id";
$result = @mysql_query($sql);

$fp = fopen("log.txt","a");
fwrite($fp, $result . PHP_EOL);
fclose($fp);

$sql    = "delete from contacte_families where id_families = (select idfamilies from alumnes_families where idalumnes=$id");
$result = @mysql_query($sql);

$fp = fopen("log.txt","a");
fwrite($fp, $result . PHP_EOL);
fclose($fp);

$sql    = "delete from alumnes where idalumnes=$id";
$result = @mysql_query($sql);

$fp = fopen("log.txt","a");
fwrite($fp, $result . PHP_EOL);
fclose($fp);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>