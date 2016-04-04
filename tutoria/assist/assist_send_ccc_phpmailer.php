<?php
/* ********************************************************* */
// Enviem els correus pertinents, segons la configuració 
/* ********************************************************* */
require("../phpmailer/PHPMailerAutoload.php");

$mail = new PHPMailer;
				
$mail->isSMTP();                             // Set mailer to use SMTP
$mail->SMTPAuth   = true;                    // enable SMTP authentication
$mail->SMTPSecure = "tls";                   // sets the prefix to the servier
$mail->Host       = "ssl0.ovh.net";   // sets the SMTP server
$mail->Port       = 465;                     // set the SMTP port
$mail->Username   = "info@insalmenar.cat";  
$mail->Password   = "Arreins@1234";           
$mail->isHTML(true);
$mail->WordWrap = 50;	

$mail->setFrom(getDadesCentre()->email, getDadesCentre()->nom);
$mail->Subject  = "[Geisoft] Nova CCC de tipus ".getLiteralTipusCCC($id_falta)->nom_falta." enregistrada";
		
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
						
$rsCarrecs = getCarrecsComunicacioTipusCCC($id_falta);
while ($row_c = mysql_fetch_assoc($rsCarrecs)) {
	$rsProfessorsCarrec = getProfessorsbyCargos($row_c['id_carrec']);
	while ($row_p = mysql_fetch_assoc($rsProfessorsCarrec)) {
						
		$rol = "<br><br><i> Missatge rebut com a </i>".getLiteralCarrec($row_c['id_carrec'])->nom_carrec;
		if ($row_c['id_carrec']==TIPUS_SUPERADMINISTRADOR || $row_c['id_carrec']==TIPUS_ADMINISTRADOR) {
			$mail->addAddress(getProfessor($row_p['idprofessors'],TIPUS_email),getProfessor($row_p['idprofessors'],TIPUS_nom_complet));
					
			$mail->Body     = $content.$rol;
			if(!$mail->Send()) {
					  	$fp = fopen("log.txt","a");
						fwrite($fp, $mail->ErrorInfo . PHP_EOL);
						fclose($fp);
			} else {
					  echo 'Message has been sent.';
			}

			}
				
		else if ($row_c['id_carrec']==TIPUS_COORDINADOR) {
			$coord_principal = getCarrecPrincipalGrup(TIPUS_COORDINADOR,$idgrups);
			if (($coord_principal != 0) && ($coord_principal == $row_p['idprofessors']) && ($idgrups == $row_p['idgrups']) ) {
				$mail->addAddress(getProfessor($coord_principal,TIPUS_email),getProfessor($coord_principal,TIPUS_nom_complet));
				$mail->Body     = $content.$rol;
				if(!$mail->Send()) {
							$fp = fopen("log.txt","a");
							fwrite($fp, $mail->ErrorInfo . PHP_EOL);
							fclose($fp);
				} else {
						  echo 'Message has been sent.';
				}
				
			}
		}
				
		else if (isCarrecInGrup($row_p['idprofessors'],$row_c['id_carrec'],$idgrups)) {
			if ($idgrups == $row_p['idgrups']) {
				$mail->addAddress(getProfessor($row_p['idprofessors'],TIPUS_email),getProfessor($row_p['idprofessors'],TIPUS_nom_complet));
				$mail->Body     = $content.$rol;
				if(!$mail->Send()) {
					  	$fp = fopen("log.txt","a");
						fwrite($fp, $mail->ErrorInfo . PHP_EOL);
						fclose($fp);
				} else {
					  echo 'Message has been sent.';
				}

			}	
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