<?php 
require_once('../dompdf/dompdf_config.inc.php');

$idgrups    = isset($_REQUEST['idgrups']) ? $_REQUEST['idgrups'] : 0 ;
 
# Contenido HTML del documento que queremos generar en PDF.
$fitxer_sortida  = "http://".$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,strlen($_SERVER['PHP_SELF'])-25);
$fitxer_sortida .= "cartes_families_see.php?idgrups=".$idgrups;

$html = file_get_contents($fitxer_sortida);
 
# Instanciamos un objeto de la clase DOMPDF.
$mipdf = new DOMPDF();
 
# Definimos el tamaño y orientación del papel que queremos.
# O por defecto cogerá el que está en el fichero de configuración.
$mipdf ->set_paper("A5", "landscape");
 
# Cargamos el contenido HTML.
$mipdf ->load_html($html);
 
# Renderizamos el documento PDF.
$mipdf ->render();
 
# Enviamos el fichero PDF al navegador.
$mipdf ->stream('Cartes_Contrasenyes_Families.pdf');
?>