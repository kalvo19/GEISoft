<?php

include('../bbdd/connect.php');
include_once('../func/generic.php');
include_once('../func/constants.php');
include_once('./class/Programacio_General.php');
mysql_query("SET NAMES 'utf8'");

$idprogramacio_general = $_POST['idprogramacio'];

if (isset($_POST['idprofessor'])) {
    $idprofessor = $_POST['idprofessor'];
    if (isset($_POST['revisat'])) {
        $revisat = $_POST['revisat'];
        $enviat = Programacio_General::enviarRevisio($idprogramacio_general, $revisat, $idprofessor);
    } else if (isset($_POST['aprovat'])) {
        $aprovat = $_POST['aprovat'];
        $enviat = Programacio_General::enviarAprovacio($idprogramacio_general, $aprovat, $idprofessor);
    } 
} else {
    $enviat = Programacio_General::enviarProgramacio($idprogramacio_general);
}

echo $enviat;
