<?php
session_start();
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idplans_estudis       = $_REQUEST['idplans_estudis'];
$nom_uf                = str_replace("'","\'",$_REQUEST['nom_uf']);
$hores_finals          = $_REQUEST['hores_finals'];
$hores                 = $_REQUEST['hores'];
$curs_escolar          = $_SESSION['curs_escolar'];

// Insertem la moduls_materies_ufs
$sql = "insert into moduls_materies_ufs (idplans_estudis,hores_finals,Curs) values ('$idplans_estudis','$hores_finals','$curs_escolar')";
$result = @mysql_query($sql);

$idmoduls = mysql_insert_id();
// Insertem a la taula de relació materia
$sql = "insert into unitats_formatives (idunitats_formatives,nom_uf,hores) values ('$idmoduls','$nom_uf','$hores')";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>
