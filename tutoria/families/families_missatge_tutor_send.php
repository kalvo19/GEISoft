<?php
   /* ********************************************************* */
   // Enviem els correus pertinents, segons la configuració 
   /* ********************************************************* */
		$header  = 'MIME-Version: 1.0' . "\r\n";
		$header .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$header .= 'From: '.getDadesCentre()->nom."<".getDadesCentre()->email.">".'' . "\r\n";
		
		$subject  =	"[Geisoft] Nou missatge al tutor/a de ".getGrup($idgrup)->nom." ";
			
		$content  = "Alumne:          ".getAlumne($idalumne,TIPUS_nom_complet)."<br>";
		$content .= "Professor:       ".getProfessor($idprofessor,TIPUS_nom_complet)."<br><hr>";
		//$content .= "Grup:            ".getGrup($idgrup)->nom."<br>";
		$content .= "Data:   ".date("d-m-Y")."<br>";
		$content .= "Hora:   ".date("H:i")."<br><hr>";
		$content .= "<b><u>Missatge</u></b><br>";
		$content .= $_REQUEST['missatge']."<br><hr>";
		
		// Enviem missatge al tutor principal si aquest existeix
		if ($idprofessor != 0) {
			$rol = "<br><br><i> Missatge rebut com a </i>".getLiteralCarrec(TIPUS_TUTOR)->nom_carrec;
			$to = getProfessor($idprofessor,TIPUS_email);	
			mail($to,$subject,$content.$rol,$header);
		}
		
		/*$rsProfessorsCarrec = getProfessorsbyCargos(TIPUS_TUTOR);
		while ($row_p = mysql_fetch_assoc($rsProfessorsCarrec)) {
				$rol = "<br><br><i> Missatge rebut com a </i>".getLiteralCarrec(TIPUS_TUTOR)->nom_carrec;
				if (isCarrecInGrup($row_p['idprofessors'],TIPUS_TUTOR,$idgrup)) {
					$to = getProfessor($row_p['idprofessors'],TIPUS_nom_complet)."<".getProfessor($row_p['idprofessors'],TIPUS_email).">";			
					mail($to,$subject,$content.$rol,$header);
				}
		}*/
		
		// Enviem missatge als administradors
		$rsProfessorsCarrec = getProfessorsbyCargos(TIPUS_ADMINISTRADOR);
		while ($row_p = mysql_fetch_assoc($rsProfessorsCarrec)) {
				$rol = "<br><br><i> Missatge rebut com a </i>".getLiteralCarrec(TIPUS_ADMINISTRADOR)->nom_carrec;
				$to = getProfessor($row_p['idprofessors'],TIPUS_email);
				mail($to,$subject,$content.$rol,$header);
		}
   /* ********************************************************* */
   /* ********************************************************* */
   if (isset($rsProfessorsCarrec)) {
   		mysql_free_result($rsProfessorsCarrec);
   }
?>