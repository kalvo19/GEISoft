<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$page    = isset($_POST['page']) ? intval($_POST['page']) : 1;  
$rows    = isset($_POST['rows']) ? intval($_POST['rows']) : 20;  
$sort    = isset($_POST['sort']) ? strval($_POST['sort']) : '10';  
$order   = isset($_POST['order']) ? strval($_POST['order']) : 'desc';

$idgrups    = isset($_REQUEST['idgrups']) ? $_REQUEST['idgrups'] : 0 ;
$data_inici = isset($_REQUEST['data_inici']) ? substr($_REQUEST['data_inici'],6,4)."-".substr($_REQUEST['data_inici'],3,2)."-".substr($_REQUEST['data_inici'],0,2) : getCursActual()->data_inici;
$data_fi    = isset($_REQUEST['data_fi'])    ? substr($_REQUEST['data_fi'],6,4)."-".substr($_REQUEST['data_fi'],3,2)."-".substr($_REQUEST['data_fi'],0,2)          : getCursActual()->data_fi;

$offset = ($page-1)*$rows;
$result  = array();

$sql  = "SELECT COUNT(tp.idccc_taula_principal) ";
$sql .= "FROM ccc_taula_principal tp ";
$sql .= "WHERE tp.data BETWEEN '".$data_inici."' AND '".$data_fi."' AND tp.idalumne IN ";
$sql .= "(SELECT DISTINCT(agm.idalumnes) ";
$sql .= "FROM alumnes_grup_materia agm ";
$sql .= "INNER JOIN grups_materies    gm ON agm.idgrups_materies  = gm.idgrups_materies ";	 
$sql .= "WHERE gm.id_grups=".$idgrups.") ";
  
$rs = mysql_query($sql);  
$row = mysql_fetch_row($rs);  
$result["total"] = $row[0]; 

$sql  = "SELECT tp.*,t.nom_falta,tm.ccc_nom,ca.Valor AS alumne,cp.Valor AS professor, ";
$sql .= "CONCAT(SUBSTR(tp.data,9,2),'-',SUBSTR(tp.data,6,2),'-',SUBSTR(tp.data,1,4)) AS data_ccc, ";
$sql .= "CONCAT(SUBSTR(tp.data_inici_sancio,9,2),'-',SUBSTR(tp.data_inici_sancio,6,2),'-',SUBSTR(tp.data_inici_sancio,1,4)) AS data_inici_sancio_ccc, ";
$sql .= "CONCAT(SUBSTR(tp.data_fi_sancio,9,2),'-',SUBSTR(tp.data_fi_sancio,6,2),'-',SUBSTR(tp.data_fi_sancio,1,4)) AS data_fi_sancio_ccc, ";
$sql .= "CONCAT(LEFT(fh.hora_inici,5),'-',LEFT(fh.hora_fi,5)) AS hora,tm.ccc_nom AS mesura,tp.data_inici_sancio,tp.data_fi_sancio ";
$sql .= "FROM ccc_taula_principal tp ";
$sql .= "INNER JOIN ccc_tipus           t ON t.idccc_tipus          = tp.id_falta ";
$sql .= "LEFT JOIN ccc_tipus_mesura    tm ON tm.idccc_tipus_mesura  = tp.id_tipus_sancio ";
$sql .= "LEFT JOIN franges_horaries    fh ON fh.idfranges_horaries  = tp.idfranges_horaries ";
$sql .= "INNER JOIN contacte_alumne    ca ON ca.id_alumne           = tp.idalumne ";
$sql .= "INNER JOIN contacte_professor cp ON cp.id_professor        = tp.idprofessor ";
$sql .= "WHERE ca.id_tipus_contacte=".TIPUS_nom_complet." AND cp.id_tipus_contacte=".TIPUS_nom_complet;
$sql .= " AND tp.data BETWEEN '".$data_inici."' AND '".$data_fi."' AND tp.idalumne IN ";
$sql .= "(SELECT DISTINCT(agm.idalumnes) ";
$sql .= "FROM alumnes_grup_materia agm ";
$sql .= "INNER JOIN grups_materies    gm ON agm.idgrups_materies  = gm.idgrups_materies ";	 
$sql .= "WHERE gm.id_grups=".$idgrups.") ";	
$sql .= " ORDER BY $sort $order LIMIT $offset,$rows";

$rs = mysql_query($sql);

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
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
