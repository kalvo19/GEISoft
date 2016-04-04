<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idalumnes  = isset($_REQUEST['idalumnes']) ? $_REQUEST['idalumnes'] : 0 ;

$pos = 0;
foreach ($idalumnes as $id_al) {
    if ($pos == 0) {
		$idfamilies = getFamiliaAlumne($id_al);
	}
	
    $sql = "UPDATE alumnes_families SET idfamilies='".$idfamilies."' WHERE idalumnes=".$id_al;
	$result = @mysql_query($sql);
		
	$pos++;
}

echo json_encode(array('success'=>true));
mysql_close();
?>