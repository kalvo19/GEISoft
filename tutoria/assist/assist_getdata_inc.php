<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$id                     = intval($_REQUEST['id']);
$idgrups                = isset($_REQUEST['idgrups'])   ? $_REQUEST['idgrups']   : 0 ;
$idmateria              = isset($_REQUEST['idmateria']) ? $_REQUEST['idmateria'] : 0 ;
$data                   = date("Y-m-d");
$idfranges_horaries     = isset($_REQUEST['idfranges_horaries']) ? $_REQUEST['idfranges_horaries'] : 0 ;
$idalumnes_grup_materia = getAlumneMateriaGrup($idgrups,$idmateria,$id)->idalumnes_grup_materia;

$sql          = "SELECT id_tipus_incident,comentari FROM incidencia_alumne WHERE idalumnes='$id' AND data='$data' AND idfranges_horaries='$idfranges_horaries' AND id_tipus_incidencia='".TIPUS_FALTA_ALUMNE_SEGUIMENT."'";

$rs = mysql_query($sql);

/*$fp = fopen("log.txt","a");
fwrite($fp, $idgrups." ".$idmateria." ".$id . PHP_EOL);
fclose($fp);*/

$items = array('id_tipus_incident' => '','comentari' => '');
  
while($row = mysql_fetch_object($rs)){  
	$items = $row;
}  
$result["rows"] = $items;  
  
echo json_encode($items);

mysql_free_result($rs);
mysql_close();
?>