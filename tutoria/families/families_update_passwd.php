<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$id_families    = intval($_REQUEST['id']);

$contrasenya_1  = $_REQUEST['contrasenya_1'];
$contrasenya_2  = $_REQUEST['contrasenya_2'];

if ($contrasenya_1==$contrasenya_2) {
   $sql = "delete from contacte_families where id_families=$id_families and id_tipus_contacte=".TIPUS_contrasenya;
   $result = @mysql_query($sql);
   $sql = "delete from contacte_families where id_families=$id_families and id_tipus_contacte=".TIPUS_contrasenya_notifica;
   $result = @mysql_query($sql);
   $sql = "insert into contacte_families(id_families,id_tipus_contacte,Valor) values ($id_families,".TIPUS_contrasenya.",'".MD5($contrasenya_1)."')";
   $result = @mysql_query($sql);
   $sql = "insert into contacte_families(id_families,id_tipus_contacte,Valor) values ($id_families,".TIPUS_contrasenya_notifica.",'".$contrasenya_1."')";
   $result = @mysql_query($sql);
}

if ($result){
		echo json_encode(array('success'=>true));
	} else {
		echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
	}

mysql_close();
?>