<?php
	ini_set("session.cookie_lifetime","7200");
	ini_set("session.gc_maxlifetime","7200");
	session_start();
	include_once('./bbdd/connect.php');
	include_once('./func/constants.php');
	include_once('./func/generic.php');	
	
	if (isset($_SESSION['professor'])) {
		insertaLogProfessor($_SESSION['professor'],TIPUS_ACCIO_LOGOUT);
	}
	else if (isset($_SESSION['alumne'])){
		insertaLogAlumne($_SESSION['alumne'],TIPUS_ACCIO_LOGOUT);
	}
	else if (isset($_SESSION['familia'])){
		insertaLogAlumne($_SESSION['familia'],TIPUS_ACCIO_LOGOUT);
	}
	
	session_unset();
	header('Location: index.php');
?>
