<?php 
# Cargamos la librería dompdf.
require_once('../dompdf/dompdf_config.inc.php');
 
# Contenido HTML del documento que queremos generar en PDF.

$mode_impresio      = 1;
$data_inici         = $_REQUEST['data_inici'];
$data_fi            = $_REQUEST['data_fi'];
$percentatge        = $_REQUEST['percentatge'];

$fitxer_sortida  = "http://".$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,strlen($_SERVER['PHP_SELF'])-20)."inf_global_see.php?data_inici=";
$fitxer_sortida .= $data_inici."&data_fi=".$data_fi."&percentatge=".$percentatge."&mode_impresio=".$mode_impresio;

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
$mipdf ->stream('DadesGlobals.pdf');
?>
