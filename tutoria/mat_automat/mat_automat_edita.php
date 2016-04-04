<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id_mat_uf_pla = intval($_REQUEST['id']);
$activat       = $_REQUEST['activat'];
$contrasenya   = $_REQUEST['contrasenya'];

$sql = "UPDATE moduls_materies_ufs SET activat='$activat',contrasenya='$contrasenya' WHERE id_mat_uf_pla=$id_mat_uf_pla";

/*
$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
fclose($fp);
*/

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>