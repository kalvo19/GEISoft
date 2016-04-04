<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
//mysql_query("SET NAMES 'utf8'");
ini_set('memory_limit', '512M' ); // Aumento de memoria
ini_set('max_execution_time', 15400 ); // minutos
ini_set('soap.wsdl_cache_enabled', 1 ); // original 0
ini_set('soap.wsdl_cache_ttl', '86400' ); // original 0, suggest 3600
ini_set('output_buffering', 0);

$primer_cognom = '';
$segon_cognom  = '';
$linia         = 1;

$rsAlumnes     = mysql_query("select * from alumnes where activat='S'");

// Esborrem les dades anteriors de conexió de les families
/*$sql = "delete from contacte_families where id_tipus_contacte=".TIPUS_login;
$result = @mysql_query($sql);
   
$sql = "delete from contacte_families where id_tipus_contacte=".TIPUS_contrasenya;
$result = @mysql_query($sql);
   
$sql = "delete from contacte_families where id_tipus_contacte=".TIPUS_contrasenya_notifica;
$result = @mysql_query($sql);*/

// Creem les noves dades de conexió per cada familia   
while($row = mysql_fetch_object($rsAlumnes)){
	$idalumnes           = $row->idalumnes;
	$idfamilies          = getFamiliaAlumne($idalumnes);
	
	if ( (!existValorTipusContacteFamilies($idalumnes,TIPUS_contrasenya))){
	
		$primer_cognom       = preg_replace('[\s+]',"",treureAccents(strtolower(getValorTipusContacteAlumne($idalumnes,TIPUS_cognom1_alumne))));
		$segon_cognom        = preg_replace('[\s+]',"",treureAccents(strtolower(getValorTipusContacteAlumne($idalumnes,TIPUS_cognom2_alumne))));		
		$login_familia       = $primer_cognom.$segon_cognom;
		
		$ini_primer_cognom   = substr($primer_cognom,0,1);
		$ini_segon_cognom    = substr($segon_cognom,0,1);
		$random_number       = rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		$contrasenya_familia = $ini_primer_cognom.$ini_segon_cognom.$random_number;
	
		// Esborrem dades previes en el cas d'haver algun altre fill
		$sql = "delete from contacte_families where id_families=$idfamilies and id_tipus_contacte=".TIPUS_login;
		$result = @mysql_query($sql);
	   
		$sql = "delete from contacte_families where id_families=$idfamilies and id_tipus_contacte=".TIPUS_contrasenya;
		$result = @mysql_query($sql);
	   
		$sql = "delete from contacte_families where id_families=$idfamilies and id_tipus_contacte=".TIPUS_contrasenya_notifica;
		$result = @mysql_query($sql);
		
		// Inserim dades de connexió per la familia
		$sql = "insert into contacte_families(id_families,id_tipus_contacte,Valor) values ($idfamilies,".TIPUS_login.",'".$login_familia."')";
		$result = @mysql_query($sql);
	   
		$sql = "insert into contacte_families(id_families,id_tipus_contacte,Valor) values ($idfamilies,".TIPUS_contrasenya.",'".MD5($contrasenya_familia)."')";
		$result = @mysql_query($sql);
	   
		$sql = "insert into contacte_families(id_families,id_tipus_contacte,Valor) values ($idfamilies,".TIPUS_contrasenya_notifica.",'".$contrasenya_familia."')";
		$result = @mysql_query($sql);
		
		$sql_al = "update alumnes set acces_familia='S' where idalumnes=".$idalumnes;
		$result = @mysql_query($sql_al);
		
		echo "<b>$linia.</b> Processades dades de la familia <font color=blue><u>".utf8_encode(getValorTipusContacteAlumne($idalumnes,TIPUS_cognom1_alumne))." ".utf8_encode(getValorTipusContacteAlumne($idalumnes,TIPUS_cognom2_alumne))."</u></font><br>";
		$linia++;

		/*flush();
        sleep(1);*/
	}
	
}

//echo json_encode(array('success'=>true));
?>

<script type="text/javascript">
$.messager.alert('Informaci&oacute;','Acc&eacute;s establert correctament!','info');
$('#dg').datagrid('reload');
</script>

<?php
ob_end_flush();					
mysql_free_result($rsAlumnes);
mysql_close();
?>