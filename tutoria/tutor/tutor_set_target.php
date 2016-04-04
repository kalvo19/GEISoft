<?php
  session_start();
  include_once('../bbdd/connect.php');
  include_once('../func/constants.php');
  include_once('../func/generic.php');
  mysql_query("SET NAMES 'utf8'");
  
  $_SESSION['target_sms'] = array();
  $idalumnes = $_REQUEST['idalumnes'];
  
  $alumnes_array     = explode(",", $idalumnes);
  $sms_array         = array();
  $nom_alumnes_array = array();
  
  foreach ($alumnes_array as $id_alumne) {
    $mobil_sms = "+34.".getValorTipusContacteFamilies($id_alumne,TIPUS_mobil_sms);
	$nom_sms   = str_replace(",","",getValorTipusContacteAlumne($id_alumne,TIPUS_nom_complet));
	array_push($sms_array,$mobil_sms);
	array_push($nom_alumnes_array,$nom_sms);
  }
  $_SESSION['target_sms']         = $sms_array;
  $_SESSION['target_nom_alumnes'] = $nom_alumnes_array;
  
  mysql_close();
?>
