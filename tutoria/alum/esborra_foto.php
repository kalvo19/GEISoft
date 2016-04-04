<?php
	$idalumnes = $_REQUEST['id'];
	
	$sTempFileName = '../images/alumnes/' . $idalumnes . '.jpg';
	@unlink($sTempFileName);
	
	echo json_encode(array('success'=>true));
?>