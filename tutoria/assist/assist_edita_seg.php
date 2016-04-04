<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$lectiva 		   = isset($_REQUEST['lectiva'])   ? $_REQUEST['lectiva'] : 0 ;
$seguiment    	   = isset($_REQUEST['seguiment']) ? str_replace("'","\'",$_REQUEST['seguiment']) : '';

$idgrups     	   = isset($_REQUEST['idgrups'])   ? $_REQUEST['idgrups']    : 0 ;
$idmateria         = isset($_REQUEST['idmateria']) ? $_REQUEST['idmateria']  : 0 ;
$grup_materia      = existGrupMateria($idgrups,$idmateria);

$data         	   = date("Y-m-d");
$dia         	   = date("w");
$franja            = isset($_REQUEST['idfranges_horaries']) ? $_REQUEST['idfranges_horaries'] : 0 ;
$dia_franja        = existDiesFranges($dia,$franja);

$sql = "SELECT lectiva,seguiment FROM qp_seguiment WHERE id_grup_materia='$grup_materia' AND data='$data' AND id_dia_franja='$dia_franja'";

$rec          = mysql_query($sql);
$count        = 0;
while($row = mysql_fetch_object($rec)) {
	$count++;
}

	
if ($count == 0) {
		$sql    = "DELETE FROM qp_seguiment WHERE id_grup_materia='$grup_materia' AND data='$data' AND id_dia_franja='$dia_franja'";
		$result = @mysql_query($sql);

		$sql    = "INSERT INTO qp_seguiment (id_dia_franja,id_grup_materia,lectiva,seguiment,data) ";
		$sql   .= "VALUES ('$dia_franja','$grup_materia','$lectiva','$seguiment','$data')";
		$result = @mysql_query($sql);
}
else {  
		$sql    = "UPDATE qp_seguiment SET lectiva=$lectiva,seguiment='$seguiment' WHERE id_grup_materia='$grup_materia' AND data='$data' AND id_dia_franja='$dia_franja'";
		$result = @mysql_query($sql);
}


if ($result != 0){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_free_result($rec);
mysql_close();
?>