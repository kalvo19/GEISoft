<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$id_professor    = intval($_REQUEST['id']);
$codi_professor  = $_REQUEST['codi_professor'];
$valor_mostrar   = "";

for ($tipus_contacte = 1; $tipus_contacte <= TOTAL_TIPUS_CONTACTE; $tipus_contacte++) {
	if ($tipus_contacte != TIPUS_contrasenya_notifica) {
		$valor  = str_replace("'","\'",$_REQUEST[$tipus_contacte]);
	}
	
	if ($tipus_contacte == TIPUS_contrasenya ) {
		$valor  = md5($valor);
	}
	else {
	
		if ($tipus_contacte == TIPUS_nom_complet ) {
			$valor_mostrar = $valor;
		}
		
		if ($tipus_contacte != TIPUS_contrasenya_notifica) {
			if ( existValorTipusContacteProfessor($id_professor,$tipus_contacte) ) {
				$sql = "update contacte_professor set Valor='$valor' where id_professor=$id_professor and id_tipus_contacte=$tipus_contacte";
			}
			else {
				$sql = "insert into contacte_professor(id_professor,id_tipus_contacte,Valor) values ($id_professor,$tipus_contacte,'$valor')";
			}
		}
		
		/*$fp = fopen("log.txt","a");
		fwrite($fp, $sql . PHP_EOL);
		fclose($fp);*/
		
		$result = @mysql_query($sql);

	}
}

if ($result){
		//echo json_encode(array('success'=>true));
		echo json_encode(array(
			'codi_professor' => $codi_professor,
			'Valor' => $valor_mostrar
		));
	} else {
		echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
	}

mysql_close();
?>