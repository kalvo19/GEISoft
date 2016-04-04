<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$id           		    = intval($_REQUEST['id']);
$comentari    		    = isset($_REQUEST['comentari']) ? str_replace("'","\'",$_REQUEST['comentari']) : '';
$idprofessors 			= isset($_SESSION['professor']) ? $_SESSION['professor']                       : 0 ;
$idgrups     			= isset($_REQUEST['idgrups'])   ? $_REQUEST['idgrups']                         : 0 ;
$idmateria    			= isset($_REQUEST['idmateria']) ? $_REQUEST['idmateria']                       : 0 ;
$data         			= date("Y-m-d");
$hora         			= date("H:i");
$idfranges_horaries     = isset($_REQUEST['idfranges_horaries']) ? $_REQUEST['idfranges_horaries'] : 0 ;
$idespais_centre        = isset($_REQUEST['idespais_centre']) ? $_REQUEST['idespais_centre'] : 0 ;

$id_falta    		    = isset($_REQUEST['id_falta'])  ? $_REQUEST['id_falta'] : 0;
$id_motius    		    = isset($_REQUEST['id_motius']) ? $_REQUEST['id_motius'] : 0;
//$descripcio_breu    	= isset($_REQUEST['descripcio_breu']) ? str_replace("'","\'",$_REQUEST['descripcio_breu']) : '';
$descripcio_detallada   = isset($_REQUEST['descripcio_detallada']) ? str_replace("'","\'",$_REQUEST['descripcio_detallada']) : '';
$expulsio    		    = isset($_REQUEST['expulsio']) ? $_REQUEST['expulsio'] : 'N';

$id_tipus_sancio       = isset($_REQUEST['id_tipus_sancio'])      ? $_REQUEST['id_tipus_sancio'] : 0 ;
$data_inici_sancio     = isset($_REQUEST['data_inici_sancio'])    ? $_REQUEST['data_inici_sancio'] : 0 ;
$data_fi_sancio        = isset($_REQUEST['data_fi_sancio'])       ? $_REQUEST['data_fi_sancio'] : 0 ;

$sql = "SELECT id_falta,expulsio,id_motius,descripcio_detallada FROM ccc_taula_principal WHERE idalumne='$id' AND data='$data' AND idfranges_horaries='$idfranges_horaries'";

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
		$sql    = "DELETE FROM ccc_taula_principal WHERE idalumnes=$id AND data='$data' AND idfranges_horaries='$idfranges_horaries'";
		$result = @mysql_query($sql);

		$sql    = "INSERT INTO ccc_taula_principal (idalumne,idgrup,idprofessor,idmateria,idfranges_horaries,idespais,id_falta,data,hora,id_motius,descripcio_detallada,expulsio,id_tipus_sancio,data_inici_sancio,data_fi_sancio) ";
		$sql   .= "VALUES ('$id','$idgrups','$idprofessors','$idmateria','$idfranges_horaries','$idespais_centre','$id_falta','$data','$hora','$id_motius','$descripcio_detallada','$expulsio','$id_tipus_sancio','$data_inici_sancio','$data_fi_sancio')";
		$result = @mysql_query($sql);
		
		include('../assist/assist_send_ccc.php');		

	}
	else {  
		$sql    = "UPDATE ccc_taula_principal SET hora='$hora',id_falta='$id_falta',expulsio='$expulsio',id_motius='$id_motius',descripcio_detallada='$descripcio_detallada' WHERE idalumne='$id' AND data='$data' AND idfranges_horaries='$idfranges_horaries' ";
		$result = @mysql_query($sql);
	}
}

if ($result != 0){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_free_result($rec);
mysql_close();
?>