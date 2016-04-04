<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$curs      = $_REQUEST['curs'];
$actual    = $_REQUEST['actual'];

$sql = "insert into cursos_escolars (curs,actual) values ('$curs','$actual')";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>
