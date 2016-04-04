<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

/*$page    = isset($_POST['page']) ? intval($_POST['page']) : 1;  
$rows    = isset($_POST['rows']) ? intval($_POST['rows']) : 20;  
$sort    = isset($_POST['sort']) ? strval($_POST['sort']) : 'data';  
$order   = isset($_POST['order']) ? strval($_POST['order']) : 'desc';*/

$idgrups_materies = isset($_REQUEST['idgrups_materies']) ? $_REQUEST['idgrups_materies'] : 0;
$idmateria        = getGrupMateria($idgrups_materies)->id_mat_uf_pla;
$escicleloe       = isMateria($idmateria) ? 0 : 1 ;
$curs_escolar     = getCursActual()->idperiodes_escolars;  

/*$fp = fopen("log.txt","a");
fwrite($fp, $idgrups_materies . PHP_EOL);
fclose($fp);*/

$data_inici = isset($_REQUEST['data_inici']) ? substr($_REQUEST['data_inici'],6,4)."-".substr($_REQUEST['data_inici'],3,2)."-".substr($_REQUEST['data_inici'],0,2) : getCursActual()->data_inici;
  if ($data_inici=='--') {
  	  $data_inici = getCursActual()->data_inici;
  }
  
$data_fi    = isset($_REQUEST['data_fi']) ? substr($_REQUEST['data_fi'],6,4)."-".substr($_REQUEST['data_fi'],3,2)."-".substr($_REQUEST['data_fi'],0,2) : date("Y-m-d");
  if ($data_fi=='--') {
  	  $data_fi = date("Y-m-d");
  }

if ($escicleloe) {
	$data_inici = getGrupMateria($idgrups_materies)->data_inici;
   	$data_fi    = getGrupMateria($idgrups_materies)->data_fi;	
}
//$dies = extreu_dies('2014-09-15',date("Y-m-d"),$curs_escolar);
//$idgrups_materies = 1505;

if ($idgrups_materies!=0){
	$dies = sessions_grup_materia($data_inici,$data_fi,$idgrups_materies,$curs_escolar);
}

echo json_encode($dies);

mysql_close();
?>