<?php
session_start();
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id_moduls       = $_REQUEST['id_moduls'];
$idplans_estudis = $_REQUEST['idplans_estudis'];
$nom_uf          = str_replace("'","\'",$_REQUEST['nom_uf']);
$hores           = $_REQUEST['hores'];
$data_inici      = substr($_REQUEST['data_inici'],6,4)."-".substr($_REQUEST['data_inici'],3,2)."-".substr($_REQUEST['data_inici'],0,2);
$data_fi         = substr($_REQUEST['data_fi'],6,4)."-".substr($_REQUEST['data_fi'],3,2)."-".substr($_REQUEST['data_fi'],0,2);
$curs_escolar    = $_SESSION['curs_escolar'];
$automatricula   = $_REQUEST['automatricula'];

// Insertem a moduls_materies_ufs
$sql = "insert into moduls_materies_ufs (idplans_estudis,hores_finals,Curs,automatricula) values ('$idplans_estudis','$hores','$curs_escolar','$automatricula')";
$result = @mysql_query($sql);

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
fclose($fp);*/
	
$idunitats_formatives = mysql_insert_id();
// Insertem a unitats_formatives
$sql = "insert into unitats_formatives (idunitats_formatives,nom_uf,hores,data_inici,data_fi) values ('$idunitats_formatives','$nom_uf','$hores','$data_inici','$data_fi')";
$result = @mysql_query($sql);

// Insertem a moduls_ufs
$sql = "insert into moduls_ufs (id_moduls,id_ufs) values ('$id_moduls','$idunitats_formatives')";
$result = @mysql_query($sql);

$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
fclose($fp);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>