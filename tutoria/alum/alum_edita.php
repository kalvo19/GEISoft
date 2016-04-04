<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$id_alumne         = intval($_REQUEST['id']);
$id_families       = getFamiliaAlumne($id_alumne);

if ($id_families==0) {
	$sql         = "insert into families() values ()";
	$result      = @mysql_query($sql);
	$id_families = mysql_insert_id();
	
$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
fclose($fp);
	
	$sql         = "insert into alumnes_families(idalumnes,idfamilies) values ('$id_alumne','$id_families')";
	$result      = @mysql_query($sql);
}
//$codi_alumnes_saga = getCodiSagaAlumne($id_alumne);
$codi_alumnes_saga = $_REQUEST['codi_alumnes_saga'];
$valor_mostrar     = "";
$dadesalumneArray  = array(TIPUS_nom_complet,TIPUS_iden_ref,TIPUS_cognom1_alumne,
  						   TIPUS_cognom2_alumne,TIPUS_nom_alumne,TIPUS_genere,
						   TIPUS_a_determinar,TIPUS_nom_grup,TIPUS_login,TIPUS_contrasenya);

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
		
		if (in_array($tipus_contacte, $dadesalumneArray)) {
		
			if ( existValorTipusContacteAlumne($id_alumne,$tipus_contacte) ) {   
				$sql = "update contacte_alumne set Valor='$valor' where id_alumne=$id_alumne and id_tipus_contacte=$tipus_contacte";
			}
			else {
				$sql = "insert into contacte_alumne(id_alumne,id_tipus_contacte,Valor) values ($id_alumne,$tipus_contacte,'$valor')";
			}
			
		}
		else {
			if ($tipus_contacte != TIPUS_contrasenya_notifica) {
				if ( existValorTipusContacteFamilies($id_alumne,$tipus_contacte) ) {   
					$sql = "update contacte_families set Valor='$valor' where id_families=$id_families and id_tipus_contacte=$tipus_contacte";
				}
				else {
					$sql = "insert into contacte_families(id_families,id_tipus_contacte,Valor) values ($id_families,$tipus_contacte,'$valor')";
				}
			}
		}
	
	}
	
	/*$fp = fopen("log.txt","a");
	fwrite($fp, $sql . PHP_EOL);
	fclose($fp);*/
	
	$result = @mysql_query($sql);
}

echo json_encode(array(
			'codi_alumnes_saga' => $codi_alumnes_saga,
			'Valor' => $valor_mostrar
		));
		
/*if ($result){
		echo json_encode(array(
			'codi_alumnes_saga' => $codi_alumnes_saga,
			'Valor' => $valor_mostrar
		));
	} else {
		echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
	}*/

mysql_close();
?>