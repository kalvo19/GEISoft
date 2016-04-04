<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$codi_professor = $_REQUEST['codi_professor'];
$sql            = "insert into professors(codi_professor,activat) values ('$codi_professor','S')";
$result         = @mysql_query($sql);
$id_professor   = mysql_insert_id();
	
for ($tipus_contacte = 1; $tipus_contacte <= TOTAL_TIPUS_CONTACTE; $tipus_contacte++) {
	if ($tipus_contacte != TIPUS_contrasenya_notifica) {
		$valor  = str_replace("'","\'",$_REQUEST[$tipus_contacte]);
	}
	
	if ($tipus_contacte == TIPUS_contrasenya ) {
		$valor  = md5($valor);
	}
	
	if ($tipus_contacte == TIPUS_nom_complet ) {
		$valor_mostrar = $valor;
	}
	
	if ($tipus_contacte != TIPUS_contrasenya_notifica) {
		$sql    = "insert into contacte_professor(id_professor,id_tipus_contacte,Valor) values ($id_professor,$tipus_contacte,'$valor')";
		$result = @mysql_query($sql);
	}
}

if ($result){
	echo json_encode(array(
			'codi_professor' => $codi_professor,
			'Valor' => $valor_mostrar
		 ));
	} else {
		echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
	}
	
mysql_close();
?>
