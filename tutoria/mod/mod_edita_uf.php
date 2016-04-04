<?php
session_start();
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id            = intval($_REQUEST['id']);
$nom_uf        = str_replace("'","\'",$_REQUEST['nom_uf']);
$hores         = $_REQUEST['hores'];
$data_inici    = substr($_REQUEST['data_inici'],6,4)."-".substr($_REQUEST['data_inici'],3,2)."-".substr($_REQUEST['data_inici'],0,2);
$data_fi       = substr($_REQUEST['data_fi'],6,4)."-".substr($_REQUEST['data_fi'],3,2)."-".substr($_REQUEST['data_fi'],0,2);
$automatricula = $_REQUEST['automatricula'];

$sql    = "update moduls_materies_ufs set hores_finals='$hores',automatricula='$automatricula' where id_mat_uf_pla=$id";
$result = @mysql_query($sql);

$sql    = "update unitats_formatives set nom_uf='$nom_uf',hores='$hores',data_inici='$data_inici',data_fi='$data_fi' where idunitats_formatives=$id";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>