<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$sql  = "SELECT * ";
$sql .= "FROM dades_centre ";
$sql .= "WHERE iddades_centre = 1";

$rs = mysql_query($sql);

$items = array('nom' => '','adreca' => '','cp' => '','poblacio' => '','tlf' => '','fax' => '','email' => '','prof_env_sms' => '');
  
while($row = mysql_fetch_object($rs)){  
	$items = $row;
}  
$result["rows"] = $items;  
  
echo json_encode($items);

mysql_free_result($rs);
mysql_close();
?>