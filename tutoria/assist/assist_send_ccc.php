<?php
/* ********************************************************* */
		// Enviem els correus pertinents, segons la configuració 
		/* ********************************************************* */
		$header  = 'MIME-Version: 1.0' . "\r\n";
		$header .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$header .= 'From: '.getDadesCentre()->nom."<".getDadesCentre()->email.">".'' . "\r\n";
		
		$subject  =	"[Geisoft] Nova CCC de tipus ".getLiteralTipusCCC($id_falta)->nom_falta." enregistrada";
		
		$content  = "Data incid&egrave;ncia: ".date("d-m-Y")."<br>";
		$content .= "Franja hor&agrave;ria: ".getLiteralFranjaHoraria($idfranges_horaries)."<br><hr>";	
		$content .= "Alumne:          ".getAlumne($id,TIPUS_nom_complet)."<br>";
		$content .= "Professor:       ".getProfessor($idprofessors,TIPUS_nom_complet)."<br>";
		$content .= "Grup:            ".getGrup($idgrups)->nom."<br>";
		$content .= "Materia:         ".getMateria($idmateria)->nom_materia."<br>";
		$content .= "Espai:           ".getEspaiCentre($idespais_centre)->descripcio."<br><hr>";
		$content .= "Tipus CCC:       ".getLiteralTipusCCC($id_falta)->nom_falta."<br>";
		$content .= "Expulsi&oacute;: ".$expulsio."<br><hr>";
		$content .= "<b><u>Descripci&oacute; breu</u></b><br>";
		$content .= getLiteralMotiusCCC($id_motius)->nom_motiu."<br><br>";
		$content .= "<b><u>Descripci&oacute; detallada</u></b><br>";
		$content .= $_REQUEST['descripcio_detallada']."<br><hr>";
		//$content .= nl2br($descripcio_detallada)."<br><hr>";
		//$content .= "Sanci&oacute;:    ".getLiteralMesuresCCC($id_tipus_sancio)->ccc_nom."<br><hr>";
				
		$rsCarrecs = getCarrecsComunicacioTipusCCC($id_falta);
		while ($row_c = mysql_fetch_assoc($rsCarrecs)) {
			$rsProfessorsCarrec = getProfessorsbyCargos($row_c['id_carrec']);
			while ($row_p = mysql_fetch_assoc($rsProfessorsCarrec)) {
				$rol = "<br><br><i> Missatge rebut com a </i>".getLiteralCarrec($row_c['id_carrec'])->nom_carrec;
				if ($row_c['id_carrec']==TIPUS_SUPERADMINISTRADOR || $row_c['id_carrec']==TIPUS_ADMINISTRADOR) {
					$to = getProfessor($row_p['idprofessors'],TIPUS_email);
					mail($to,$subject,$content.$rol,$header);
				}
				else if (isCarrecInGrup($row_p['idprofessors'],$row_c['id_carrec'],$idgrups)) {
					$to = getProfessor($row_p['idprofessors'],TIPUS_email);			
					mail($to,$subject,$content.$rol,$header);
				}
			}
		}
		
				
/* ********************************************************* */
/* ********************************************************* */
if (isset($rsCarrecs)) {
	mysql_free_result($rsCarrecs);
}
if (isset($rsProfessorsCarrec)) {
	mysql_free_result($rsProfessorsCarrec);
}
       
?>       