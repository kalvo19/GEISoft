<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idalumnes   = intval($_REQUEST['idalumnes']);
$id_families = getFamiliaAlumne($idalumnes);

// Treure l'accés per la familia per veure tot(e)s els/les german(e)s
$sql  = "SELECT a.idalumnes FROM alumnes a ";
$sql .= "INNER JOIN alumnes_families af ON af.idalumnes=a.idalumnes ";
$sql .= "WHERE af.idfamilies=".$id_families;
$rs = mysql_query($sql);
while ($row = mysql_fetch_assoc($rs)) {
	  $sql_al = "update alumnes set acces_familia='N' where idalumnes=".$row['idalumnes'];
      $result = @mysql_query($sql_al);
}	   
mysql_free_result($rs);
   
if ($result){
		echo json_encode(array('success'=>true));
	} else {
		echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
	}

mysql_close();
?>