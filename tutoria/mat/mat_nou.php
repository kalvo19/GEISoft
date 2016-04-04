<?php
session_start();
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idplans_estudis = $_REQUEST['idplans_estudis'];
$nom_materia     = str_replace("'","\'",$_REQUEST['nom_materia']);
$hores_finals    = $_REQUEST['hores_finals'];
$curs_escolar    = $_SESSION['curs_escolar'];
$automatricula   = $_REQUEST['automatricula'];

// Insertem la moduls_materies_ufs
$sql = "insert into moduls_materies_ufs (idplans_estudis,hores_finals,Curs,automatricula) values ('$idplans_estudis','$hores_finals','$curs_escolar','$automatricula')";
$result = @mysql_query($sql);

$idmateria = mysql_insert_id();
// Insertem a la taula de relació materia
$sql = "insert into materia (idmateria,nom_materia) values ('$idmateria','$nom_materia')";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>
