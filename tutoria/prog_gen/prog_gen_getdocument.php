<?php

include_once('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
include_once('./class/Programacio_General.php');
include_once('../prog_mod/class/Modificacio.php');
include_once ('../docsodt/docxpresso/CreateDocument.inc');
include_once('../docsodt/class/Document.php');
mysql_query("SET NAMES 'utf8'");

$idprogramacio = $_POST['idprogramacio_general'];
$format = $_POST['format'];

$sql = "SELECT * FROM programacions_general pg WHERE idprogramacio_general = $idprogramacio";
$rs = mysql_query($sql);
$items = Array();

while ($row = mysql_fetch_assoc($rs)) {
    $items[] = $row;
}

$novaProgramacioGeneral = new Programacio_General($items[0]["idprogramacio_general"],$items[0]["nom_document"], $items[0]["data_creacio"], $items[0]["estrategies_metodologies"], 
$items[0]["recursos"], $items[0]["revisat"], $items[0]["aprovat"], $items[0]["idperiodes_escolar"], $items[0]["idprofessors"], 
$items[0]["idmoduls"], $items[0]["idcurs"], $items[0]["professorRevisio"], $items[0]["professorAprovacio"]);

$nouDocument = new Document($novaProgramacioGeneral);

$fitxer = $novaProgramacioGeneral->getNom_document() . ".$format";
$nouDocument->generarProgramacioComuna("../docsodt/temp/programacions_comunes/" . $fitxer);

echo $fitxer;