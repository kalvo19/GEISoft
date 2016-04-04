<?php
	$idprofessors = $_REQUEST['id'];
	
	$sTempFileName = '../images/prof/' . $idprofessors . '.jpg';
	@unlink($sTempFileName);
	
	echo json_encode(array('success'=>true));
?>