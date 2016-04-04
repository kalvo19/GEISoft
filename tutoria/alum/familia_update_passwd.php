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

$login_familia  = $_REQUEST['login_familia'];
$contrasenya_1  = $_REQUEST['contrasenya_1_familia'];
$contrasenya_2  = $_REQUEST['contrasenya_2_familia'];

if ($contrasenya_1==$contrasenya_2) {
   // Accés per la familia per veure tot(e)s els/les german(e)s
   $sql  = "SELECT a.idalumnes FROM alumnes a ";
   $sql .= "INNER JOIN alumnes_families af ON af.idalumnes=a.idalumnes ";
   $sql .= "WHERE af.idfamilies=".$id_families;
   $rs = mysql_query($sql);
   while ($row = mysql_fetch_assoc($rs)) {
	  $sql_al = "update alumnes set acces_familia='S' where idalumnes=".$row['idalumnes'];
      $result = @mysql_query($sql_al);
   }	   
   mysql_free_result($rs);
   
      
   $sql = "delete from contacte_families where id_families=$id_families and id_tipus_contacte=".TIPUS_login;
   $result = @mysql_query($sql);
   
   $sql = "delete from contacte_families where id_families=$id_families and id_tipus_contacte=".TIPUS_contrasenya;
   $result = @mysql_query($sql);
   
   $sql = "delete from contacte_families where id_families=$id_families and id_tipus_contacte=".TIPUS_contrasenya_notifica;
   $result = @mysql_query($sql);
   
   $sql = "insert into contacte_families(id_families,id_tipus_contacte,Valor) values ($id_families,".TIPUS_login.",'".$login_familia."')";
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