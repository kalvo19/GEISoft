<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idgrups                = isset($_REQUEST['idgrups'])   ? $_REQUEST['idgrups']   : 0 ;
$idmateria              = isset($_REQUEST['idmateria']) ? $_REQUEST['idmateria'] : 0 ;
$grup_materia           = existGrupMateria($idgrups,$idmateria);

$data                   = date("Y-m-d");
$franja                 = isset($_REQUEST['idfranges_horaries']) ? $_REQUEST['idfranges_horaries'] : 0 ;
$dia                    = date("w");
$dia_franja             = existDiesFranges($dia,$franja);

$sql = "SELECT lectiva,seguiment FROM qp_seguiment WHERE id_grup_materia='$grup_materia' AND data='$data' AND id_dia_franja='$dia_franja'";

$rs = mysql_query($sql);

$items = array('lectiva' => '','seguiment' => '');
  
while($row = mysql_fetch_object($rs)){  
	$items = $row;
}  
$result["rows"] = $items;  
  
echo json_encode($items);

mysql_free_result($rs);
mysql_close();
?>