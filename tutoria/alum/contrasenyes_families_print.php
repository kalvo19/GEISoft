<?php 
require_once('../dompdf/dompdf_config.inc.php');
 
# Contenido HTML del documento que queremos generar en PDF.
$fitxer_sortida  = "http://".$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,strlen($_SERVER['PHP_SELF'])-31);
$fitxer_sortida .= "contrasenyes_families_see.php";

$html = file_get_contents($fitxer_sortida);
 
# Instanciamos un objeto de la clase DOMPDF.
$mipdf = new DOMPDF();
 
# Definimos el tamaño y orientación del papel que queremos.
# O por defecto cogerá el que está en el fichero de configuración.
$mipdf ->set_paper("A3", "landscape");
 
# Cargamos el contenido HTML.
$mipdf ->load_html(utf8_decode($html));
 
# Renderizamos el documento PDF.
$mipdf ->render();
 
# Enviamos el fichero PDF al navegador.
$mipdf ->stream('Contrasenyes_Families.pdf');
?>