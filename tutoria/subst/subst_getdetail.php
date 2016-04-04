<?php
$id = $_REQUEST['id'];  
  
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");
  
$rs = mysql_query("select * from alumnes where idalumnes='$id'");  
$items = array();  
while($row = mysql_fetch_object($rs)){  
    array_push($items, $row);  
}  
echo json_encode($items); 

mysql_free_result($rs);
mysql_close();
?>