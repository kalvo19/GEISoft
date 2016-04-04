<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idprofessors = isset($_SESSION['professor']) ? $_SESSION['professor'] : 0 ;

if (validEntryLogProfessor($idprofessors,TIPUS_ACCIO_NOENTRACLASSE)) {
	  insertaLogProfessor($idprofessors,TIPUS_ACCIO_NOENTRACLASSE);
}

echo json_encode(array('success'=>true));
?>