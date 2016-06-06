<?php

include_once('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
include_once('./class/Programacio_General.php');
include_once('../prog_mod/class/Modificacio.php');
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

$novaProgramacioGeneral = new Programacio_General(null, $nom_document, date("Y-m-d"), $items[0]['estrategies_metodologies'], $items[0]['recursos'], 
 'G', null, null, $items[0]['idprofessors'], $items[0]['idmoduls'], $items[0]['idcurs'], null, null);

$novaProgramacioGeneral->guardarProgramacio();
$novaModificacio = new Modificacio(date("Y-m-d"), "Creació del document.", $novaProgramacioGeneral->getIdprogramacio_general(), null);
$novaModificacio->inserirModificacio();

echo json_encode($items);
    