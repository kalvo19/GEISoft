<?php 
# Cargamos la librería dompdf.
require_once('../dompdf/dompdf_config.inc.php');
 
# Contenido HTML del documento que queremos generar en PDF.

$mode_impresio      = 1;
$data_inici         = $_REQUEST['data_inici'];
$data_fi            = $_REQUEST['data_fi'];
$idprofessor        = $_REQUEST['idprofessor'];
$idgrup             = $_REQUEST['idgrup'];
$c_alumne           = $_REQUEST['c_alumne'];

$box_al             = isset($_REQUEST['box_al'])             ? $_REQUEST['box_al']             : '';
$box_faltes         = isset($_REQUEST['box_faltes'])         ? $_REQUEST['box_faltes']         : '';
$box_retards        = isset($_REQUEST['box_retards'])        ? $_REQUEST['box_retards']        : '';
$box_justificacions = isset($_REQUEST['box_justificacions']) ? $_REQUEST['box_justificacions'] : '';
$box_incidencies    = isset($_REQUEST['box_incidencies'])    ? $_REQUEST['box_incidencies']    : '';
$box_CCC            = isset($_REQUEST['box_CCC'])            ? $_REQUEST['box_CCC']            : '';

$fitxer_sortida  = "http://".$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,strlen($_SERVER['PHP_SELF'])-25)."inf_assist_prof_see.php?data_inici=";
$fitxer_sortida .= $data_inici."&data_fi=".$data_fi."&idprofessor=".$idprofessor."&idgrup=".$idgrup."&mode_impresio=".$mode_impresio;
$fitxer_sortida .= "&c_alumne=".$c_alumne."&box_al=".$box_al."&box_faltes=".$box_faltes."&box_retards=".$box_retards;
$fitxer_sortida .= "&box_justificacions=".$box_justificacions."&box_incidencies=".$box_incidencies."&box_CCC=".$box_CCC;

$html = file_get_contents($fitxer_sortida);

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
