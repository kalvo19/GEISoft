<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$idprofessors = isset($_REQUEST['idprofessors']) ? $_REQUEST['idprofessors'] : 0 ;
$data_inici   = isset($_REQUEST['data_inici']) ? substr($_REQUEST['data_inici'],6,4)."-".substr($_REQUEST['data_inici'],3,2)."-".substr($_REQUEST['data_inici'],0,2) : '1989-1-1';
$data_fi      = isset($_REQUEST['data_fi'])    ? substr($_REQUEST['data_fi'],6,4)."-".substr($_REQUEST['data_fi'],3,2)."-".substr($_REQUEST['data_fi'],0,2)          : '2189-1-1';

$sql  = "SELECT gs.data, g.nom AS grup, ma.nom_materia AS materia, CONCAT(LEFT(fh.hora_inici,5),'-',LEFT(fh.hora_fi,5)) AS hora, ";
$sql .= "CONCAT(SUBSTR(gs.data,9,2),'-',SUBSTR(gs.data,6,2),'-',SUBSTR(gs.data,1,4)) AS data_signat ";
$sql .= "FROM guardies_signades gs ";
$sql .= "INNER JOIN franges_horaries  fh ON gs.idfranges_horaries  = fh.idfranges_horaries ";
$sql .= "INNER JOIN grups              g ON gs.idgrups             = g.idgrups ";
$sql .= "INNER JOIN materia           ma ON gs.id_mat_uf_pla       = ma.idmateria ";
$sql .= "WHERE gs.idprofessors=".$idprofessors." AND gs.data BETWEEN '".$data_inici."' AND '".$data_fi."' ";

$sql .= " UNION ";

$sql .= "SELECT gs.data, g.nom AS grup, CONCAT(m.nom_modul,'-',uf.nom_uf) AS materia, CONCAT(LEFT(fh.hora_inici,5),'-',LEFT(fh.hora_fi,5)) AS hora, ";
$sql .= "CONCAT(SUBSTR(gs.data,9,2),'-',SUBSTR(gs.data,6,2),'-',SUBSTR(gs.data,1,4)) AS data_signat ";
$sql .= "FROM guardies_signades gs ";
$sql .= "INNER JOIN franges_horaries   fh ON gs.idfranges_horaries  = fh.idfranges_horaries ";
$sql .= "INNER JOIN grups               g ON gs.idgrups             = g.idgrups ";
$sql .= "INNER JOIN unitats_formatives uf ON gs.id_mat_uf_pla       = uf.idunitats_formatives ";
$sql .= "INNER JOIN moduls_ufs         mu ON gs.id_mat_uf_pla       = mu.id_ufs ";
$sql .= "INNER JOIN moduls              m ON mu.id_moduls           = m.idmoduls ";
$sql .= "WHERE gs.idprofessors=".$idprofessors." AND gs.data BETWEEN '".$data_inici."' AND '".$data_fi."' ";

$sql .= "ORDER BY 1 DESC";

$rs = mysql_query($sql);

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql . "\n\n".PHP_EOL);
fclose($fp);*/

$items = array();  
while($row = mysql_fetch_object($rs)){  
    array_push($items, $row);  
}  
$result["rows"] = $items; 

echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>