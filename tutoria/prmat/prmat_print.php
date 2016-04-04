<?php 
require_once('../dompdf/dompdf_config.inc.php');
 
# Contenido HTML del documento que queremos generar en PDF.

$mode_impresio    = 1;
$idprofessors     = intval($_REQUEST['idprofessors']);
$cursactual       = $_REQUEST['curs'];
$cursliteral      = $_REQUEST['cursliteral'];
  
$fitxer_sortida  = "http://".$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,strlen($_SERVER['PHP_SELF'])-15);
$fitxer_sortida .= "prmat_see.php?mode_impresio=".$mode_impresio."&idprofessors=".$idprofessors."&curs=".$cursactual."&cursliteral=".$cursliteral;

$html = file_get_contents($fitxer_sortida);
 
# Instanciamos un objeto de la clase DOMPDF.
$mipdf = new DOMPDF();
 
# Definimos el tamaño y orientación del papel que queremos.
# O por defecto cogerá el que está en el fichero de configuración.
$mipdf ->set_paper("A3", "landscape");
 
# Cargamos el contenido HTML.
$mipdf ->load_html($html);
 
# Renderizamos el documento PDF.
$mipdf ->render();
 
# Enviamos el fichero PDF al navegador.
$mipdf ->stream('HorariProfessor.pdf');
?>