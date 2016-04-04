<?php
   session_start();
   include_once('../bbdd/connect.php');
   include_once('../func/generic.php');
   include_once('../func/constants.php');
   mysql_query("SET NAMES 'utf8'");
      
   $fechaSegundos = time();
   $strNoCache    = "?nocache=$fechaSegundos";
   $idalumne      = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0 ;
   $idgrup        = getGrupAlumne($idalumne)->idgrups;
   $idprofessor   = getCarrecPrincipalGrup(TIPUS_TUTOR,$idgrup);      
   $missatge      = isset($_REQUEST['missatge']) ? str_replace("'","\'",$_REQUEST['missatge']) : '';
   
   if ($idalumne != 0) {
   		$sql    = "INSERT INTO missatges_tutor (idprofessor,idalumne,idgrup,data,hora,missatge) ";
		$sql   .= "VALUES ('$idprofessor','$idalumne','$idgrup','".date("Y-m-d")."','".date("H:i")."','$missatge')";
		$result = @mysql_query($sql);
   }
   
   include('../families/families_missatge_tutor_send.php');

   echo json_encode(array('success'=>true));

   mysql_close();  
?>