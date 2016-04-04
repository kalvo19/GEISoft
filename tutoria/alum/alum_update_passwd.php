<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$id_alumne      = intval($_REQUEST['id']);
$id_families    = getFamiliaAlumne($id_alumne);

if ($id_families==0) {
	$sql         = "insert into families() values ()";
	$result      = @mysql_query($sql);
	$id_families = mysql_insert_id();
	
	$sql         = "insert into alumnes_families(idalumnes,idfamilies) values ('$id_alumne','$id_families')";
	$result      = @mysql_query($sql);
}

$contrasenya_1  = $_REQUEST['contrasenya_1'];
$contrasenya_2  = $_REQUEST['contrasenya_2'];

if ($contrasenya_1==$contrasenya_2) {
   $sql = "update alumnes set acces_alumne='S' where idalumnes=$id_alumne";
   $result = @mysql_query($sql);
   
   $sql = "delete from contacte_alumne where id_alumne=$id_alumne and id_tipus_contacte=".TIPUS_contrasenya;
   $result = @mysql_query($sql);
   
   $sql = "delete from contacte_alumne where id_alumne=$id_alumne and id_tipus_contacte=".TIPUS_contrasenya_notifica;
   $result = @mysql_query($sql);
   
   //$sql = "update contacte_alumne set Valor=MD5('$contrasenya_1') where id_alumne=$id_alumne and id_tipus_contacte=".TIPUS_contrasenya;
   $sql = "insert into contacte_alumne(id_alumne,id_tipus_contacte,Valor) values ($id_alumne,".TIPUS_contrasenya.",'".MD5($contrasenya_1)."')";
   $result = @mysql_query($sql);
   
   $sql = "insert into contacte_alumne(id_alumne,id_tipus_contacte,Valor) values ($id_alumne,".TIPUS_contrasenya_notifica.",'".$contrasenya_1."')";
   $result = @mysql_query($sql);
}

if ($result){
		echo json_encode(array('success'=>true));
	} else {
		echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
	}

mysql_close();
?>