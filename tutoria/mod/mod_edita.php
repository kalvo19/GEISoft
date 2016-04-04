<?php
session_start();
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id                    = intval($_REQUEST['id']);
$idplans_estudis       = isset($_REQUEST['idplans_estudis']) ? intval($_REQUEST['idplans_estudis']) : 0;
$nom_modul             = str_replace("'","\'",$_REQUEST['nom_modul']);
$hores_finals          = isset($_REQUEST['hores_finals']) ? $_REQUEST['hores_finals'] : 0;
$horeslliuredisposicio = isset($_REQUEST['horeslliuredisposicio']) ? $_REQUEST['horeslliuredisposicio'] : 0;
$curs_escolar          = isset($_SESSION['curs_escolar']) ? intval($_SESSION['curs_escolar']) : 0;

$sql = "update moduls set idplans_estudis='$idplans_estudis',nom_modul='$nom_modul',hores_finals='$hores_finals',horeslliuredisposicio='$horeslliuredisposicio' where idmoduls=$id";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>