<?php
	ini_set("session.cookie_lifetime","7200");
	ini_set("session.gc_maxlifetime","7200");
	session_start();
	include_once('./bbdd/connect.php');
	include_once('./func/constants.php');
	include_once('./func/generic.php');
	
	if($_POST['s3capcha'] == $_SESSION['s3capcha'] && $_POST['s3capcha'] != '') {
		//unset($_SESSION['s3capcha']);
		//session_unset();
		
		if (isset($_POST['login'])) {
			   $result = validaProfessor($_POST['login'],$_POST['passwd'],TIPUS_login,TIPUS_contrasenya);
			   if ( $result != 0 ) {
				  $_SESSION['curs_escolar']         = getCursActual()->idperiodes_escolars;
				  $_SESSION['curs_escolar_literal'] = getCursActual()->Nom;
				  $_SESSION['professor']            = $result;
				  $_SESSION['usuari']               = $result;  
				  insertaLogProfessor($_SESSION['professor'],TIPUS_ACCIO_LOGIN);
				  
				 /* if (! existLogProfessorData($_SESSION['professor'],TIPUS_ACCIO_ENTROALCENTRE,date("Y-m-d"))) {
					      insertaLogProfessor($_SESSION['professor'],TIPUS_ACCIO_ENTROALCENTRE);
				  } */
				  
			   }
			   else {
			      $result = validaAlumne($_POST['login'],$_POST['passwd'],TIPUS_login,TIPUS_contrasenya);				 
				  if ( $result != 0 ) {
					  $_SESSION['curs_escolar']         = getCursActual()->idperiodes_escolars;
					  $_SESSION['curs_escolar_literal'] = getCursActual()->Nom;
					  $_SESSION['alumne']               = $result;
					  $_SESSION['usuari']               = $result;  
					  insertaLogAlumne($_SESSION['alumne'],TIPUS_ACCIO_LOGIN);
				  }
				  else {
                      $result = validaFamilia($_POST['login'],$_POST['passwd'],TIPUS_login,TIPUS_contrasenya);				 
                      if ( $result != 0 ) {
                            $_SESSION['curs_escolar']         = getCursActual()->idperiodes_escolars;
                            $_SESSION['curs_escolar_literal'] = getCursActual()->Nom;
                            $_SESSION['familia']              = $result;
                            $_SESSION['usuari']               = $result;  
                            insertaLogFamilia($_SESSION['familia'],TIPUS_ACCIO_LOGIN);
                      }
					  else {
						  $_SESSION['errno'] = 1;
						  header('Location:index.php');
					  }
			     }	
		    } 	
		}
	}
?>
