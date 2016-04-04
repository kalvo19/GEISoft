<?php
  session_start();
  include_once('../bbdd/connect.php');
  include_once('../func/constants.php');
  include_once('../func/generic.php');
  mysql_query("SET NAMES 'utf8'");
  
  
  // Ordre alfabètic d'alumnes
  
  $rsAlumnes = mysql_query("select * from alumnes");
  
  while($row = mysql_fetch_object($rsAlumnes)){
	$idalumne = $row->idalumnes;
	//echo $idalumne."<br>";
	
	$rsCognom1 = mysql_query("select * from contacte_alumne where id_tipus_contacte=4 and id_alumne=".$idalumne);
	while($row1 = mysql_fetch_object($rsCognom1)){
	    $cognom_1 = $row1->Valor;
	}
	
	$rsCognom2 = mysql_query("select * from contacte_alumne where id_tipus_contacte=5 and id_alumne=".$idalumne);
	while($row2 = mysql_fetch_object($rsCognom2)){
	    $cognom_2 = $row2->Valor;
	}
	
	$rsNom       = mysql_query("select * from contacte_alumne where id_tipus_contacte=6 and id_alumne=".$idalumne);
	while($row3 = mysql_fetch_object($rsNom)){
	    $nom = $row3->Valor;
	}				  
	
	$nom_complet = $cognom_1." ".$cognom_2.", ".$nom;
	$sql = "update contacte_alumne set Valor='$nom_complet' where id_alumne=$idalumne and id_tipus_contacte=1";
	$result = @mysql_query($sql);
	 
  }
?>

<script type="text/javascript">     
 $.messager.alert('Informaci&oacute;','Alumnes ordenats alfab&egrave;ticament.');
</script>

<?php
  // Reorganització taula incidencia_alumne. Ubicar cada incidencia en una franja horària el més aproximat
  // a la realitat posible.
  
  $dias = array(1,2,3,4,5,6,7);
  
  $rsIncidencies = mysql_query("select * from incidencia_alumne where idfranges_horaries=0 order by idincidencia_alumne desc");
  
  while($row = mysql_fetch_object($rsIncidencies)){
	$idincidencia_alumne = $row->idincidencia_alumne;
	
	$idalumne_agrupament = $row->idalumne_agrupament;
	$idgrups_materies    = getGrupMateriaAlumneAgrupament($idalumne_agrupament);
	
	//calcul del dia d'una data determinada
	$data		 = $row->data;
	$any         = substr($data,0,4);
	$mes         = substr($data,5,2);
	$dia         = substr($data,8,2);
	$iddies_setmana = diaSemana($any,$mes,$dia);
	
    // busquem a l'horari (unitats_clase) quin dia i grupmateria tè una determinada entrada
	$sql  = "SELECT fh.idfranges_horaries ";
	$sql .= "FROM unitats_classe uc ";
	$sql .= "INNER JOIN dies_franges df ON uc.id_dies_franges = df.id_dies_franges ";
	$sql .= "INNER JOIN franges_horaries fh ON df.idfranges_horaries = fh.idfranges_horaries ";
	$sql .= "WHERE uc.idgrups_materies=$idgrups_materies AND df.iddies_setmana=$iddies_setmana ";
	$sql .= "ORDER BY 1 DESC ";

    $rsFranja = mysql_query($sql);
    
	while($row_2 = mysql_fetch_object($rsFranja)){
		$sql = "update incidencia_alumne set idfranges_horaries=$row_2->idfranges_horaries where idincidencia_alumne=$idincidencia_alumne";
		$result = @mysql_query($sql);
	}
	
  }
?>

<script type="text/javascript">     
 $.messager.alert('Informaci&oacute;','Incid&egrave;ncies ordenades correctament.');
</script>

<?php
  // Reorganització taula incidencia_alumne. Desfer la granularitat del camp idalumne_agrupament
  // amb els camps idalumnes, idgrups i id_mat_uf_pla. Millora en la gestió dels històrics.
  
  $sql  = "select ia.* from incidencia_alumne ia ";
  $sql .= "inner join alumnes_grup_materia agm on ia.idalumne_agrupament=agm.idalumnes_grup_materia ";
  $sql .= "where ia.idalumnes=0 ";
  
  $rsIncidencies = mysql_query($sql);
  while($row = mysql_fetch_object($rsIncidencies)){
	$idincidencia_alumne = $row->idincidencia_alumne;
	
	$idalumne_agrupament = $row->idalumne_agrupament;
	$idalumnes           = getAlumneGrupMateria($idalumne_agrupament)->idalumnes;
	$idgrups_materies    = getAlumneGrupMateria($idalumne_agrupament)->idgrups_materies;
	$idgrups             = getGrupMateria($idgrups_materies)->id_grups;
	$idmateria           = getGrupMateria($idgrups_materies)->id_mat_uf_pla;
	
	//echo $idalumnes." ".$idgrups_materies." ".$idgrups." ".$idmateria."<br>";
	
	$sql    = "update incidencia_alumne set idalumnes=$idalumnes,idgrups=$idgrups,id_mat_uf_pla=$idmateria where idincidencia_alumne=$idincidencia_alumne";
	$result = @mysql_query($sql);
	
  }
  $sql    = "delete from incidencia_alumne where idalumnes=0";
  $result = @mysql_query($sql);
?>

<script type="text/javascript">     
 $.messager.alert('Informaci&oacute;','Incid&egrave;ncies organitzades correctament.');
</script>

<?php
  // Reorganització taula incidencia_alumne. Desfer la granularitat del camp idalumne_agrupament
  // amb els camps idalumnes, idgrups i id_mat_uf_pla. Millora en la gestió dels històrics.
  
  $sql  = "select ia.* from incidencia_alumne ia ";
  $sql .= "where ia.idprofessors=0 ";
  
  $rsIncidencies = mysql_query($sql);
  while($row = mysql_fetch_object($rsIncidencies)){
	$idincidencia_alumne = $row->idincidencia_alumne;
	$idgrups_materies	 = existGrupMateria($row->idgrups,$row->id_mat_uf_pla);
	$idprofessors        = 0;
	
	if ($idgrups_materies != 0) {
		if(is_object(getProfessorByGrupMateria($idgrups_materies))) {
			$idprofessors = getProfessorByGrupMateria($idgrups_materies)->idprofessors;
		}
	}
	
	$sql    = "update incidencia_alumne set idprofessors=$idprofessors where idincidencia_alumne=$idincidencia_alumne";
	$result = @mysql_query($sql);
	
  }
  $sql    = "delete from incidencia_alumne where idalumnes=0";
  $result = @mysql_query($sql);
?>

<script type="text/javascript">     
 $.messager.alert('Informaci&oacute;','Reassignaci&oacute; de professors feta correctament.');
</script>
  
<?php
mysql_free_result($rsAlumnes);
mysql_free_result($rsIncidencies);
//mysql_free_result($rsFranja);
mysql_close();
?>