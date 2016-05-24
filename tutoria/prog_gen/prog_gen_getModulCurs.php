<?php

include('../bbdd/connect.php');
include_once('../func/generic.php');
include_once('../func/constants.php');
mysql_query("SET NAMES 'utf8'");

$idmodul = $_POST['modul'];
$idcurs = $_POST['curs'];

$sql = "SELECT m.nom_modul, c.nom_curs FROM curs c, moduls m WHERE m.idmoduls = $idmodul AND c.idcurs = $idcurs";

$rs = mysql_query($sql);
$items = array();

while ($row = mysql_fetch_assoc($rs)) {
    $items[] = $row;
}

echo json_encode($items);
