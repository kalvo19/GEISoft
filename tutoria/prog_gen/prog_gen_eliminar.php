<?php

include('../bbdd/connect.php');
include_once('../func/generic.php');
include_once('../func/constants.php');
mysql_query("SET NAMES 'utf8'");

$idprogramacio_general = $_POST['idprogramacio_general'];

$sql = "DELETE FROM programacions_general WHERE idprogramacio_general = $idprogramacio_general";

$rs = mysql_query($sql);


