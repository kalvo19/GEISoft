<?php 
# Cargamos la librería dompdf.
require_once('../dompdf/dompdf_config.inc.php');
 
# Contenido HTML del documento que queremos generar en PDF.
$mode_impresio = 1;
$data_inici    = $_REQUEST['data_inici'];
$data_fi       = $_REQUEST['data_fi'];
$c_alumne      = $_REQUEST['c_alumne'];
$percent       = $_REQUEST['percent'];

$fitxer_sortida  = "http://".$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,strlen($_SERVER['PHP_SELF'])-16)."assist_see.php?data_inici=".$data_inici."&mode_impresio=".$mode_impresio;
$fitxer_sortida .= "&data_fi=".$data_fi."&c_alumne=".$c_alumne."&percent=".$percent;

if ( isset($_REQUEST['grup_materia'])) {
	$grup_materia    = $_REQUEST['grup_materia'];
	$fitxer_sortida .= "&grup_materia=".$grup_materia;
}
else {
	$idgrups         = $_REQUEST['idgrups'];
	$idmateria       = $_REQUEST['idmateria'];
	$fitxer_sortida .= "&idgrups=".$idgrups."&idmateria=".$idmateria;
}

$html = file_get_contents($fitxer_sortida);

//.file_get_contents($fitxer_sortida);

# Instanciamos un objeto de la clase DOMPDF.
$mipdf = new DOMPDF();
		  
# Definimos el tamaño y orientación del papel que queremos.
# O por defecto cogerá el que está en el fichero de configuración.
$mipdf ->set_paper("A3", "portrait");
 
# Cargamos el contenido HTML.
$mipdf ->load_html($html);
 
# Renderizamos el documento PDF.
$mipdf ->render();
 
# Enviamos el fichero PDF al navegador.
$mipdf ->stream('Informe.pdf');
?>