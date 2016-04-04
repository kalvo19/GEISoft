<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id_dia_franja   = $_REQUEST['id_dia_franja'];
$id_grup_materia = $_REQUEST['id_grup_materia'];
$lectiva         = $_REQUEST['lectiva'];
$seguiment       = str_replace("'","\'",$_REQUEST['seguiment']);
$data            = substr($_REQUEST['data'],6,4)."-".substr($_REQUEST['data'],3,2)."-".substr($_REQUEST['data'],0,2);

$sql    = "delete from qp_seguiment WHERE id_dia_franja=$id_dia_franja AND id_grup_materia=$id_grup_materia AND data='$data'";
$result = @mysql_query($sql);

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
fclose($fp);*/

$sql  = "insert into qp_seguiment (id_dia_franja,id_grup_materia,lectiva,seguiment,data) ";
$sql .= "values ($id_dia_franja,$id_grup_materia,$lectiva,'$seguiment','$data')";
$result = @mysql_query($sql);

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
fclose($fp);*/

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>