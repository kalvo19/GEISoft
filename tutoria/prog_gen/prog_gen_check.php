<?php

include_once('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
include_once('./class/Programacio_General.php');
mysql_query("SET NAMES 'utf8'");

$idmodul = $_POST['modul'];
$idcurs = $_POST['curs'];

echo Programacio_General::existeixProgramacio($idmodul, $idcurs);
