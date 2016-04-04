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
$idespais_centre        = isset($_REQUEST['idespais_centre']) ? $_REQUEST['idespais_centre'] : 0 ;

$sql = "SELECT id_falta,expulsio,id_motius,descripcio_detallada FROM ccc_taula_principal WHERE idalumne='$id' AND data='$data' AND idfranges_horaries='$idfranges_horaries'";

$rs = mysql_query($sql);

$items = array('id_falta' => '','expulsio' => '','id_motius' => '','descripcio_detallada' => '');
  
while($row = mysql_fetch_object($rs)){  
	$items = $row;
}  
$result["rows"] = $items;  
  
echo json_encode($items);

mysql_free_result($rs);
mysql_close();
?>