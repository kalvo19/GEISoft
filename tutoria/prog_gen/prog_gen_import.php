<?php

include_once('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
include_once('./class/Programacio_General.php');
mysql_query("SET NAMES 'utf8'");

$idprogramacio = $_POST['idprogramacio_general'];
$nom_document = $_POST['nom_document'];

$sql = "SELECT estrategies_metodologies, recursos, idprofessors, idmoduls, idcurs FROM programacions_general "
. "WHERE idprogramacio_general = $idprogramacio";
$rs = mysql_query($sql);
$items = Array();

while ($row = mysql_fetch_assoc($rs)) {
    $items[] = $row;
}

$novaProgramacioGeneral = new Programacio_General(null, null, null, $items[0]['estrategies_metodologies'], $items[0]['recursos'], 
 null, null, null, $items[0]['idprofessors'], $items[0]['idmoduls'], $items[0]['idcurs']);

$novaProgramacioGeneral->importarProgramacio($nom_document);
$novaProgramacioGeneral->guardarProgramacio();

echo $items[0]["idprofessors"];
    