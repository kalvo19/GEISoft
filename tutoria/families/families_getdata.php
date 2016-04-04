<?php
session_start();
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$sort    = isset($_POST['sort']) ? strval($_POST['sort']) : '3,4';  
$order   = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

$idalumnes       = $_SESSION['alumne'];


$sql  = " ";

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql ."\n\n". PHP_EOL);
fclose($fp);*/
	 
$rs = mysql_query($sql);
 
$items = array();  
while($row = mysql_fetch_object($rs)){  
    array_push($items, $row);  
}  
$result["rows"] = $items;  
  
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>