<?php

include('../bbdd/connect.php');
include_once('../func/generic.php');
include_once('../func/constants.php');
mysql_query("SET NAMES 'utf8'");

$idprogramacio_general = $_POST['idprogramacio_general'];

$sql = "SELECT idprofessors FROM programacions_general WHERE idprogramacio_general = $idprogramacio_general";

$rs = mysql_query($sql);

while ($row = mysql_fetch_assoc($rs)) {
    $idprofessors = $row["idprofessors"];
}

$sql = "DELETE FROM programacions_general WHERE idprogramacio_general = $idprogramacio_general";

$rs = mysql_query($sql);

echo $idprofessors;

?>