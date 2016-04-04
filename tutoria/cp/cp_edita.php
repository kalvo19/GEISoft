<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id       = intval($_REQUEST['idcursos_escolars']);
$curs     = $_REQUEST['curs'];
$actual   = $_REQUEST['actual'];

$sql = "update cursos_escolars set curs='$curs',actual='$actual' where idcursos_escolars=$id";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>