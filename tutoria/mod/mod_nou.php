<?php
session_start();
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idplans_estudis       = isset($_REQUEST['idplans_estudis']) ? intval($_REQUEST['idplans_estudis']) : 0;
$nom_modul             = str_replace("'","\'",$_REQUEST['nom_modul']);
$hores_finals          = isset($_REQUEST['hores_finals']) ? $_REQUEST['hores_finals'] : 0;
$horeslliuredisposicio = isset($_REQUEST['horeslliuredisposicio']) ? $_REQUEST['horeslliuredisposicio'] : 0;
$curs_escolar          = isset($_SESSION['curs_escolar']) ? intval($_SESSION['curs_escolar']) : 0;

// Insertem la moduls_materies_ufs
//$sql = "insert into moduls_materies_ufs (idplans_estudis,hores_finals,Curs) values ('$idplans_estudis','$hores_finals','$curs_escolar')";
//$result = @mysql_query($sql);

//$idmoduls = mysql_insert_id();
// Insertem a la taula de relació materia
$sql = "insert into moduls (idplans_estudis,nom_modul,hores_finals,horeslliuredisposicio) values ('$idplans_estudis','$nom_modul','$hores_finals','$horeslliuredisposicio')";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>
