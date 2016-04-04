<?php
session_start();
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");
 
$idprofessors = isset($_REQUEST['idprofessors']) ? $_REQUEST['idprofessors'] : 0 ;
$g_pe         = isset($_REQUEST['g_pe']) ? $_REQUEST['g_pe'] : '';
$m_pe         = isset($_REQUEST['m_pe']) ? $_REQUEST['m_pe'] : '';
$curs_actual  = $_SESSION['curs_escolar']; 

$sql  = "SELECT uc.*,CONCAT(t.nom_torn,'-',ds.dies_setmana,'(',LEFT(fh.hora_inici,5),'-',LEFT(fh.hora_fi,5),')') AS dia_hora,m.nom_materia,ec.descripcio,ds.iddies_setmana,fh.hora_inici,CONCAT(m.nom_materia,' - ',gr.nom) AS matgrup ";
$sql .= "FROM unitats_classe uc ";
$sql .= "INNER JOIN prof_agrupament  pa ON uc.idgrups_materies   = pa.idagrups_materies ";
$sql .= "INNER JOIN dies_franges     df ON uc.id_dies_franges    = df.id_dies_franges ";
$sql .= "INNER JOIN dies_setmana     ds ON df.iddies_setmana     = ds.iddies_setmana ";
$sql .= "INNER JOIN franges_horaries fh ON df.idfranges_horaries = fh.idfranges_horaries ";
$sql .= "INNER JOIN torn 			  t ON t.idtorn              = fh.idtorn ";
$sql .= "INNER JOIN espais_centre    ec ON uc.idespais_centre    = ec.idespais_centre ";
$sql .= "INNER JOIN grups_materies   gm ON uc.idgrups_materies   = gm.idgrups_materies ";
$sql .= "INNER JOIN materia           m ON gm.id_mat_uf_pla      = m.idmateria ";
$sql .= "INNER JOIN grups            gr ON gm.id_grups           = gr.idgrups ";
$sql .= "WHERE df.idperiode_escolar=".$curs_actual." AND pa.idprofessors='".$idprofessors."' ";

$sql .= " UNION ";

$sql .= "SELECT uc.*,CONCAT(t.nom_torn,'-',ds.dies_setmana,'(',LEFT(fh.hora_inici,5),'-',LEFT(fh.hora_fi,5),')') AS dia_hora,CONCAT(m.nom_modul,'-',uf.nom_uf) AS nom_materia,ec.descripcio,ds.iddies_setmana,fh.hora_inici,CONCAT(m.nom_modul,'::',uf.nom_uf,' - ',gr.nom) AS matgrup ";
$sql .= "FROM unitats_classe uc ";
$sql .= "INNER JOIN prof_agrupament    pa ON uc.idgrups_materies   = pa.idagrups_materies ";
$sql .= "INNER JOIN dies_franges       df ON uc.id_dies_franges    = df.id_dies_franges ";
$sql .= "INNER JOIN dies_setmana       ds ON df.iddies_setmana     = ds.iddies_setmana ";
$sql .= "INNER JOIN franges_horaries   fh ON df.idfranges_horaries = fh.idfranges_horaries ";
$sql .= "INNER JOIN torn 			    t ON t.idtorn              = fh.idtorn ";
$sql .= "INNER JOIN espais_centre      ec ON uc.idespais_centre    = ec.idespais_centre ";
$sql .= "INNER JOIN grups_materies     gm ON uc.idgrups_materies   = gm.idgrups_materies ";
$sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla      = uf.idunitats_formatives ";
$sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla      = mu.id_ufs ";
$sql .= "INNER JOIN moduls              m ON mu.id_moduls          =  m.idmoduls ";
$sql .= "INNER JOIN grups              gr ON gm.id_grups           = gr.idgrups ";
$sql .= "WHERE df.idperiode_escolar=".$curs_actual." AND pa.idprofessors='".$idprofessors."' ";

$sql .= "ORDER BY 8,9 ";

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
fclose($fp);*/

$rs = mysql_query($sql);

$items = array();  
while($row = mysql_fetch_object($rs)){  
    array_push($items, $row);  
}  
echo json_encode($items); 

mysql_free_result($rs);
mysql_close();
?>