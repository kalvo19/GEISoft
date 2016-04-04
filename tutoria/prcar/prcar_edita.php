<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idprofessor_carrec = intval($_REQUEST['id']);
$idprofessors       = isset($_REQUEST['idprofessors']) ? $_REQUEST['idprofessors'] : 0 ;
$idcarrecs          = $_REQUEST['idcarrecs'];
$idgrups            = $_REQUEST['idgrups'];
$principal          = $_REQUEST['principal'];

$sql = "UPDATE professor_carrec SET idprofessors='$idprofessors',idcarrecs='$idcarrecs',idgrups='$idgrups',principal='$principal' WHERE idprofessor_carrec=$idprofessor_carrec";

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
fclose($fp);*/

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>$sql));
}

mysql_close();
?>