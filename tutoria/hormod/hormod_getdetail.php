<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idgrups     = isset($_REQUEST['id_grups']) ? $_REQUEST['id_grups'] : 0;
$idtorn      = getGrup($idgrups)->idtorn;
$g_pe        = isset($_REQUEST['g_pe']) ? $_REQUEST['g_pe'] : '';
$m_pe        = isset($_REQUEST['m_pe']) ? $_REQUEST['m_pe'] : '';
$curs_actual = $_SESSION['curs_escolar']; 

$sql  = "SELECT df.id_dies_franges,CONCAT(t.nom_torn,'-',ds.dies_setmana,'(',LEFT(fh.hora_inici,5),'-',LEFT(fh.hora_fi,5),')') AS dia_hora,' ' AS idespais_centre ";
$sql .= "FROM dies_franges df ";
$sql .= "INNER JOIN dies_setmana     ds ON df.iddies_setmana     = ds.iddies_setmana ";
$sql .= "INNER JOIN franges_horaries fh ON df.idfranges_horaries = fh.idfranges_horaries ";
$sql .= "INNER JOIN torn 			  t ON t.idtorn              = fh.idtorn ";
$sql .= "WHERE fh.idtorn=".$idtorn." AND df.idperiode_escolar=".$curs_actual." AND fh.esbarjo<>'S'";
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