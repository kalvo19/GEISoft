<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');

$id                     = intval($_REQUEST['id']);
$idgrups                = isset($_REQUEST['idgrups'])   ? $_REQUEST['idgrups']   : 0 ;
$idmateria              = isset($_REQUEST['idmateria']) ? $_REQUEST['idmateria'] : 0 ;
$idprofessors 			= isset($_SESSION['professor']) ? $_SESSION['professor'] : 0 ;
$data         			= date("Y-m-d");
$idfranges_horaries     = isset($_REQUEST['idfranges_horaries']) ? $_REQUEST['idfranges_horaries'] : 0 ;
$idalumnes_grup_materia = getAlumneMateriaGrup($idgrups,$idmateria,$id)->idalumnes_grup_materia;

if ( $idprofessors == 0 ) {
	$result = 0;
}
else {
	$sql    = "DELETE FROM incidencia_alumne WHERE idalumnes=$id AND data='$data' AND idfranges_horaries='$idfranges_horaries' AND id_tipus_incidencia=".TIPUS_FALTA_ALUMNE_ABSENCIA;
	$result = @mysql_query($sql);
	
	// No es permeten retards i absències a una mateixa sessió
	$sql    = "DELETE FROM incidencia_alumne WHERE idalumnes=$id AND data='$data' AND idfranges_horaries='$idfranges_horaries' AND id_tipus_incidencia=".TIPUS_FALTA_ALUMNE_RETARD;
	$result = @mysql_query($sql);
	
	$sql    = "INSERT INTO incidencia_alumne (idalumnes,idgrups,id_mat_uf_pla,idprofessors,id_tipus_incidencia,data,idfranges_horaries,comentari) ";
	$sql   .= "VALUES ('$id','$idgrups','$idmateria','$idprofessors','".TIPUS_FALTA_ALUMNE_ABSENCIA."','$data','$idfranges_horaries','')";
	$result = @mysql_query($sql);
}

/*$fp = fopen("log.txt","a");
fwrite($fp, $result . PHP_EOL);
fclose($fp);*/

if ($result != 0){
	echo json_encode(array('success'=>true,'multiple'=>exitsIncidenciaAlumnebyDataFranja($id,$data,$idfranges_horaries)));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>
