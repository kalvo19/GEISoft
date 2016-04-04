<?php
session_start();
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id                    = intval($_REQUEST['id']);
$idplans_estudis       = $_REQUEST['idplans_estudis'];
$nom_materia           = str_replace("'","\'",$_REQUEST['nom_materia']);
$hores_finals          = $_REQUEST['hores_finals'];
$curs_escolar          = $_SESSION['curs_escolar'];
$automatricula         = $_REQUEST['automatricula'];

$sql = "update materia set nom_materia='$nom_materia' where idmateria=$id";
$result = @mysql_query($sql);

$sql = "update moduls_materies_ufs set idplans_estudis='$idplans_estudis',hores_finals='$hores_finals',Curs='$curs_escolar',automatricula='$automatricula' where id_mat_uf_pla=$id";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>