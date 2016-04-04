<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$id   =  intval($_REQUEST['id']);

$sql  = "SELECT cf.Valor as login_familia,CONCAT('') as contrasenya_1_familia,CONCAT('') as contrasenya_2_familia ";
$sql .= "FROM contacte_families cf ";
$sql .= "INNER JOIN alumnes_families af ON cf.id_families = af.idfamilies ";
$sql .= "WHERE af.idalumnes=".$id." AND cf.id_tipus_contacte=".TIPUS_login;

$rs = mysql_query($sql);

$items = array('login_familia' => '','contrasenya_1_familia' => '','contrasenya_2_familia' => '');
  
while($row = mysql_fetch_object($rs)){  
	$items = $row;
}  
$result["rows"] = $items;  
  
echo json_encode($items);

mysql_free_result($rs);
mysql_close();
?>