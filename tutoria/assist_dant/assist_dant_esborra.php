<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idalumnes_grup_materia = isset($_REQUEST['idalumnes_grup_materia']) ? $_REQUEST['idalumnes_grup_materia'] : 0 ;
$idalumnes              = getAlumneGrupMateria($idalumnes_grup_materia)->idalumnes;
$idgrups_materies       = getAlumneGrupMateria($idalumnes_grup_materia)->idgrups_materies;
$idgrups                = getGrupMateria($idgrups_materies)->id_grups;
$idmateria              = getGrupMateria($idgrups_materies)->id_mat_uf_pla;
$id_tipus_incidencia    = isset($_REQUEST['id_tipus_incidencia'])    ? $_REQUEST['id_tipus_incidencia']    : 0 ;
$idprofessors 			= isset($_SESSION['professor'])              ? $_SESSION['professor']              : 0 ;
$data         			= isset($_REQUEST['data'])                   ? $_REQUEST['data']                   : 0 ;
$idfranges_horaries     = isset($_REQUEST['idfranges_horaries'])     ? $_REQUEST['idfranges_horaries']     : 0 ;
$comentari    			= isset($_REQUEST['comentari'])              ? $_REQUEST['comentari']              : '' ;

$sql    = "DELETE FROM incidencia_alumne WHERE idalumnes=$idalumnes AND data='$data' AND idfranges_horaries='$idfranges_horaries'";
$result = @mysql_query($sql);

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql ."\n\n". PHP_EOL);
fclose($fp);*/

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>