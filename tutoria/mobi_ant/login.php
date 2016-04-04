<?php
	session_start();
	include_once('../bbdd/connect.php');
	include_once('../func/constants.php');
	include_once('../func/generic.php');
		
	if (isset($_POST['login'])) {
			   $result = validaProfessor($_POST['login'],$_POST['passwd'],TIPUS_login,TIPUS_contrasenya);
			   if ( $result != 0 ) {
				  $_SESSION['curs_escolar']         = getCursActual()->idperiodes_escolars;
				  $_SESSION['curs_escolar_literal'] = getCursActual()->Nom;
				  $_SESSION['professor']            = $result;
				  $_SESSION['usuari']               = $result;  
				  insertaLogProfessor($_SESSION['professor'],TIPUS_ACCIO_LOGIN);
			   }
			   /*else {
			      $result = validaAlumne($_POST['login'],$_POST['passwd'],TIPUS_login,TIPUS_contrasenya);				 
				  if ( $result != 0 ) {
					  $_SESSION['curs_escolar']         = getCursActual()->idperiodes_escolars;
					  $_SESSION['curs_escolar_literal'] = getCursActual()->Nom;
					  $_SESSION['alumne']               = $result;
					  $_SESSION['usuari']               = $result;  
				  }
			   }	*/
	}
?>
