<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$nom        = $_REQUEST['Nom'];
$descripcio = $_REQUEST['Descripcio'];
$data_inici = substr($_REQUEST['data_inici'],6,4)."-".substr($_REQUEST['data_inici'],3,2)."-".substr($_REQUEST['data_inici'],0,2);
$data_fi    = substr($_REQUEST['data_fi'],6,4)."-".substr($_REQUEST['data_fi'],3,2)."-".substr($_REQUEST['data_fi'],0,2);
$actual     = $_REQUEST['actual'];

$sql = "insert into periodes_escolars (nom,descripcio,data_inici,data_fi,actual) values ('$nom','$descripcio','$data_inici','$data_fi','$actual')";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>
