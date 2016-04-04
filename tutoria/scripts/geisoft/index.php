<?php
	require_once("config/db.php");
	$sql  = "SELECT * FROM vista_app";
	$rec  = mysql_query($sql); 
	
	$count = 0;
	$result = array();
	
	while($row = mysql_fetch_object($rec)) {
		$retorno = array();
		$retorno[nom] = utf8_encode($row->nom_centre);
		$retorno[activat] = $row->activat;
		$retorno[url] = utf8_encode($row->url_logo);
		
		array_push($result,$retorno);
	}
	$salida = json_encode($result,true);
	echo $salida;
	mysql_free_result($rec);
	
	
?>