<?php
session_start();
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id            = intval($_REQUEST['id']);
$id_mat_uf_pla = $_REQUEST['id_mat_uf_pla'];
$id_grups      = $_REQUEST['id_grups'];
$data_inici    = substr($_REQUEST['data_inici'],6,4)."-".substr($_REQUEST['data_inici'],3,2)."-".substr($_REQUEST['data_inici'],0,2);
$data_fi       = substr($_REQUEST['data_fi'],6,4)."-".substr($_REQUEST['data_fi'],3,2)."-".substr($_REQUEST['data_fi'],0,2);

$sql = "update grups_materies set id_mat_uf_pla='$id_mat_uf_pla',id_grups='$id_grups',data_inici='$data_inici',data_fi='$data_fi' where idgrups_materies=$id";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>