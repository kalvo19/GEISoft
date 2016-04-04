<?php
session_start();
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idgrups_materies = isset($_REQUEST['idgrups_materies']) ? $_REQUEST['idgrups_materies'] : 0 ;
$idalumnes        = isset($_REQUEST['idalumnes']) ? $_REQUEST['idalumnes'] : 0 ;

$sql = "INSERT INTO alumnes_grup_materia (idalumnes,idgrups_materies) VALUES ('$idalumnes','$idgrups_materies')";
$result = @mysql_query($sql);

echo json_encode(array('success'=>true));
mysql_close();
?>