<?php

include('../bbdd/connect.php');
include_once('../func/generic.php');
include_once('../func/constants.php');
include_once('./class/Programacio_General.php');
include_once('../prog_mod/class/Modificacio.php');
mysql_query("SET NAMES 'utf8'");

$idprogramacio_general = $_POST['idprogramacio_general'];

$sql = "SELECT idprofessors FROM programacions_general WHERE idprogramacio_general = $idprogramacio_general";

$rs = mysql_query($sql);

while ($row = mysql_fetch_assoc($rs)) {
    $idprofessors = $row;
}

Modificacio::eliminarModificacionsProgramacio(1, $idprogramacio_general);

Programacio_General::eliminarProgramacio($idprogramacio_general);

$items = array();

$items[] = $idprofessors;

echo json_encode($items);

?>