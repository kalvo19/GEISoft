<?php
	$sTempFileName = '../images/logo.jpg';
	@unlink($sTempFileName);
	
	echo json_encode(array('success'=>true));
?>