<?php
include('../bbdd/connect.php');

$id = intval($_REQUEST['id']);

$sql = "delete from materia where idmateria=$id";
$result = @mysql_query($sql);

$sql = "delete from moduls_materies_ufs where id_mat_uf_pla=$id";
$result = @mysql_query($sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'No es posible esborrar aquesta mat&egrave;ria.'));
}

mysql_close();
?>