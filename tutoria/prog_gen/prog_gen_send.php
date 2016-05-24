<?php

include('../bbdd/connect.php');
include_once('../func/generic.php');
include_once('../func/constants.php');
include_once('./class/Programacio_General.php');
mysql_query("SET NAMES 'utf8'");

$idprogramacio_general = $_POST['idprogramacio'];
$aprovat = $_POST['aprovat'];

$enviat = Programacio_General::enviarProgramacio($idprogramacio_general);

echo $enviat;
