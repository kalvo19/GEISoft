<?php
session_start();

$idprofessors = isset($_SESSION['professor']) ? $_SESSION['professor'] : 0 ;

if ( $idprofessors == 0 ) {
	$result = 0;
}
else {
	$result = 1;
}

/*$fp = fopen("log.txt","a");
fwrite($fp, $result . PHP_EOL);
fclose($fp);*/

if ($result != 0){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}
?>
