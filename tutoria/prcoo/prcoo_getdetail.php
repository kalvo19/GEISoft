<?php
session_start();
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");
 
$idprofessors = isset($_REQUEST['idprofessors']) ? $_REQUEST['idprofessors'] : 0 ;
$g_pe         = isset($_REQUEST['g_pe']) ? $_REQUEST['g_pe'] : '';
$m_pe         = isset($_REQUEST['m_pe']) ? $_REQUEST['m_pe'] : '';
$curs_actual  = $_SESSION['curs_escolar']; 

$sql  = "SELECT g.*,CONCAT(t.nom_torn,'-',ds.dies_setmana,'(',LEFT(fh.hora_inici,5),'-',LEFT(fh.hora_fi,5),')') AS dia_hora,ec.descripcio ";
$sql .= "FROM prof_coordinacions g ";
$sql .= "INNER JOIN dies_franges     df ON    g.id_dies_franges  = df.id_dies_franges ";
$sql .= "INNER JOIN dies_setmana     ds ON df.iddies_setmana     = ds.iddies_setmana ";
$sql .= "INNER JOIN franges_horaries fh ON df.idfranges_horaries = fh.idfranges_horaries ";
$sql .= "INNER JOIN torn 			  t ON t.idtorn              = fh.idtorn ";
$sql .= "LEFT JOIN espais_centre    ec ON  g.idespais_centre    = ec.idespais_centre ";
$sql .= "WHERE df.idperiode_escolar=".$curs_actual." AND g.idprofessors='".$idprofessors."' ";
$sql .= "ORDER BY ds.iddies_setmana,fh.idfranges_horaries ";

$rs = mysql_query($sql);

$items = array();  
while($row = mysql_fetch_object($rs)){  
    array_push($items, $row);  
}  
echo json_encode($items); 

mysql_free_result($rs);
mysql_close();
?>