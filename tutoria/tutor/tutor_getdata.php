<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idgrups    = isset($_REQUEST['idgrups']) ? $_REQUEST['idgrups'] : 0 ;
$page       = isset($_POST['page']) ? intval($_POST['page']) : 1;  
$rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 20;  
$sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'data';  
$order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';

$data_inici = isset($_REQUEST['data_inici_tutor']) ? substr($_REQUEST['data_inici_tutor'],6,4)."-".substr($_REQUEST['data_inici_tutor'],3,2)."-".substr($_REQUEST['data_inici_tutor'],0,2) : '1989-1-1';
$data_fi    = isset($_REQUEST['data_fi_tutor'])    ? substr($_REQUEST['data_fi_tutor'],6,4)."-".substr($_REQUEST['data_fi_tutor'],3,2)."-".substr($_REQUEST['data_fi_tutor'],0,2)          : '2189-1-1';

$offset     = ($page-1)*$rows;
$result     = array();

$sql  = "SELECT count(*)";
$sql .= "FROM incidencia_alumne ia ";
$sql .= "WHERE ia.idgrups=".$idgrups." AND ia.data BETWEEN '".$data_inici."' AND '".$data_fi."'";

$rs = mysql_query($sql);  

$row = mysql_fetch_row($rs);  
$result["total"] = $row[0];

$sql  = "SELECT distinct(ia.idincidencia_alumne),ia.data,ca.Valor AS alumne,ia.comentari,";
$sql .= "CONCAT(SUBSTR(ia.data,9,2),'-',SUBSTR(ia.data,6,2),'-',SUBSTR(ia.data,1,4)) AS data_incidencia, ";
$sql .= "ia.id_tipus_incidencia,cp.Valor AS professor,tf.tipus_falta,m.nom_materia,ca.id_alumne,cp.id_professor, ";
$sql .= "CONCAT(ELT(WEEKDAY(ia.data) + 1, 'DILLUNS', 'DIMARTS', 'DIMECRES', 'DIJOUS', 'DIVENDRES', 'DISSABTE', 'DIUMENGE')) AS dia, ";
$sql .= "CONCAT(LEFT(fh.hora_inici,5),'-',LEFT(fh.hora_fi,5)) AS hora,ia.id_tipus_incident,ti.tipus_incident ";
$sql .= "FROM incidencia_alumne ia ";
$sql .= "INNER JOIN tipus_incidents        ti ON ia.id_tipus_incident    = ti.idtipus_incident "; 
$sql .= "INNER JOIN contacte_professor     cp ON ia.idprofessors         = cp.id_professor ";
$sql .= "INNER JOIN franges_horaries       fh ON ia.idfranges_horaries   = fh.idfranges_horaries ";
$sql .= "INNER JOIN tipus_falta_alumne     tf ON ia.id_tipus_incidencia  = tf.idtipus_falta_alumne ";	 
$sql .= "INNER JOIN alumnes_grup_materia  agm ON ia.idalumnes  			 = agm.idalumnes ";
$sql .= "INNER JOIN contacte_alumne        ca ON agm.idalumnes           = ca.id_alumne ";
$sql .= "INNER JOIN grups_materies         gm ON agm.idgrups_materies    = gm.idgrups_materies ";
$sql .= "INNER JOIN materia                 m ON  ia.id_mat_uf_pla       = m.idmateria ";
$sql .= "WHERE ca.id_tipus_contacte=".TIPUS_nom_complet." AND cp.id_tipus_contacte=".TIPUS_nom_complet." AND gm.id_grups=".$idgrups." AND ia.data BETWEEN '".$data_inici."' AND '".$data_fi."' ";

$sql .= " UNION ";

$sql .= "SELECT distinct(ia.idincidencia_alumne),ia.data,ca.Valor AS alumne,ia.comentari,";
$sql .= "CONCAT(SUBSTR(ia.data,9,2),'-',SUBSTR(ia.data,6,2),'-',SUBSTR(ia.data,1,4)) AS data_incidencia, ";
$sql .= "ia.id_tipus_incidencia,cp.Valor AS professor,tf.tipus_falta,CONCAT(m.nom_modul,'-',uf.nom_uf) AS nom_materia,ca.id_alumne,cp.id_professor, ";
$sql .= "CONCAT(ELT(WEEKDAY(ia.data) + 1, 'DILLUNS', 'DIMARTS', 'DIMECRES', 'DIJOUS', 'DIVENDRES', 'DISSABTE', 'DIUMENGE')) AS dia, ";
$sql .= "CONCAT(LEFT(fh.hora_inici,5),'-',LEFT(fh.hora_fi,5)) AS hora,ia.id_tipus_incident,ti.tipus_incident ";
$sql .= "FROM incidencia_alumne ia ";
$sql .= "INNER JOIN tipus_incidents        ti ON ia.id_tipus_incident    = ti.idtipus_incident "; 
$sql .= "INNER JOIN contacte_professor     cp ON ia.idprofessors         = cp.id_professor ";
$sql .= "INNER JOIN franges_horaries       fh ON ia.idfranges_horaries   = fh.idfranges_horaries ";
$sql .= "INNER JOIN tipus_falta_alumne     tf ON ia.id_tipus_incidencia  = tf.idtipus_falta_alumne ";	 
$sql .= "INNER JOIN alumnes_grup_materia  agm ON ia.idalumnes            = agm.idalumnes ";
$sql .= "INNER JOIN contacte_alumne        ca ON agm.idalumnes           = ca.id_alumne ";
$sql .= "INNER JOIN grups_materies         gm ON agm.idgrups_materies    = gm.idgrups_materies ";
$sql .= "INNER JOIN unitats_formatives uf ON ia.id_mat_uf_pla     = uf.idunitats_formatives ";
$sql .= "INNER JOIN moduls_ufs         mu ON ia.id_mat_uf_pla     = mu.id_ufs ";
$sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
$sql .= "WHERE ca.id_tipus_contacte=".TIPUS_nom_complet." AND cp.id_tipus_contacte=".TIPUS_nom_complet." AND gm.id_grups=".$idgrups." AND ia.data BETWEEN '".$data_inici."' AND '".$data_fi."' ";

$sql .= "ORDER BY $sort $order LIMIT $offset,$rows";
  
/*$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
fclose($fp);*/

$rs = mysql_query($sql);
  
$items = array();  
while($row = mysql_fetch_object($rs)){  
    array_push($items, $row);  
}  
$result["rows"] = $items;  
  
echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>