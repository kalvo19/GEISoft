<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$id           		    = intval($_REQUEST['id']);
$comentari    		    = isset($_REQUEST['comentari']) ? str_replace("'","\'",$_REQUEST['comentari']) : '';
$id_tipus_incident      = isset($_REQUEST['id_tipus_incident'])   ? $_REQUEST['id_tipus_incident']     : 0 ;
$idprofessors 			= isset($_SESSION['professor']) ? $_SESSION['professor']                       : 0 ;
$idgrups     			= isset($_REQUEST['idgrups'])   ? $_REQUEST['idgrups']                         : 0 ;
$idmateria    			= isset($_REQUEST['idmateria']) ? $_REQUEST['idmateria']                       : 0 ;
$data         			= date("Y-m-d");
$idfranges_horaries     = isset($_REQUEST['idfranges_horaries']) ? $_REQUEST['idfranges_horaries'] : 0 ;
$idalumnes_grup_materia = getAlumneMateriaGrup($idgrups,$idmateria,$id)->idalumnes_grup_materia;

$sql  = "SELECT id_tipus_incident,comentari FROM incidencia_alumne WHERE idalumnes='$id' AND data='$data' ";
$sql .= "AND idfranges_horaries='$idfranges_horaries' AND id_tipus_incidencia='".TIPUS_FALTA_ALUMNE_SEGUIMENT."'";

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
fclose($fp);*/

$rec          = mysql_query($sql);
$count        = 0;
while($row = mysql_fetch_object($rec)) {
	$count++;
}

if ( $idprofessors == 0 ) {
	$result = 0;
}
else {
	
	if ($count == 0) {
		/*$sql    = "DELETE FROM incidencia_alumne WHERE idalumnes=$id AND data='$data' AND idfranges_horaries='$idfranges_horaries'";
		$result = @mysql_query($sql);*/
		
		$sql    = "INSERT INTO incidencia_alumne (idalumnes,idgrups,id_mat_uf_pla,idprofessors,id_tipus_incidencia,id_tipus_incident,data,idfranges_horaries,comentari) ";
		$sql   .= "VALUES ('$id','$idgrups','$idmateria','$idprofessors','".TIPUS_FALTA_ALUMNE_SEGUIMENT."','$id_tipus_incident','$data','$idfranges_horaries','$comentari')";
	}
	else {  
		$sql    = "UPDATE incidencia_alumne SET data='$data',id_tipus_incident='$id_tipus_incident',comentari='$comentari' WHERE idalumnes='$id' AND data='$data' AND idfranges_horaries='$idfranges_horaries' AND id_tipus_incidencia='".TIPUS_FALTA_ALUMNE_SEGUIMENT."'";
	}
}

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
fclose($fp);*/

$result = @mysql_query($sql);
echo json_encode(array('success'=>true,'id'=>$id,'multiple'=>exitsIncidenciaAlumnebyDataFranja($id,$data,$idfranges_horaries)));

/*if ($result != 0){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}*/

mysql_free_result($rec);
mysql_close();
?>
