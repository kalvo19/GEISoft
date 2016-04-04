<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idprofessors = $_REQUEST['idprofessors'];
$idcarrecs    = $_REQUEST['idcarrecs'];
$idgrups      = $_REQUEST['idgrups'];
$principal    = $_REQUEST['principal'];

$sql = "INSERT INTO professor_carrec (idprofessors,idcarrecs,idgrups,principal) VALUES ('$idprofessors','$idcarrecs','$idgrups','$principal')";

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
