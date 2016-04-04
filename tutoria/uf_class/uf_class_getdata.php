<?php
include('../bbdd/connect.php');
include_once('../func/generic.php');
include_once('../func/constants.php');
mysql_query("SET NAMES 'utf8'");

$sort     = isset($_POST['sort'])  ? strval($_POST['sort'])  : '1';  
$order    = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

$idmoduls = isset($_REQUEST['idmoduls']) ? $_REQUEST['idmoduls'] : 0;
$idgrups  = isset($_REQUEST['idgrups'])  ? $_REQUEST['idgrups']  : 0;

$where = '';
if (isset($idmoduls)) {
   $where .= " WHERE mu.id_moduls=".$idmoduls;
}

$result = array();

if ($idgrups!=0) {
	
	$sql  = "SELECT uf.idunitats_formatives,uf.nom_uf,uf.hores, ";
	$sql .= "CONCAT(SUBSTR(uf.data_inici,9,2),'-',SUBSTR(uf.data_inici,6,2),'-',SUBSTR(uf.data_inici,1,4)) AS uf_data_inici, ";
	$sql .= "CONCAT(SUBSTR(uf.data_fi,9,2),'-',SUBSTR(uf.data_fi,6,2),'-',SUBSTR(uf.data_fi,1,4)) AS uf_data_fi, ";
	$sql .= "CONCAT(SUBSTR(gm.data_inici,9,2),'-',SUBSTR(gm.data_inici,6,2),'-',SUBSTR(gm.data_inici,1,4)) AS data_inici, ";
	$sql .= "CONCAT(SUBSTR(gm.data_fi,9,2),'-',SUBSTR(gm.data_fi,6,2),'-',SUBSTR(gm.data_fi,1,4)) AS data_fi ";
	$sql .= "FROM unitats_formatives uf ";
	$sql .= "INNER JOIN moduls_ufs            mu ON mu.id_ufs=uf.idunitats_formatives ";
	$sql .= "LEFT JOIN grups_materies         gm ON mu.id_ufs=gm.id_mat_uf_pla ";
	$sql .= $where." AND gm.id_grups=$idgrups ";
	
	$sql .= " UNION ";
	
	$sql .= "SELECT uf.idunitats_formatives,uf.nom_uf,uf.hores, ";
    $sql .= "CONCAT(SUBSTR(uf.data_inici,9,2),'-',SUBSTR(uf.data_inici,6,2),'-',SUBSTR(uf.data_inici,1,4)) AS uf_data_inici, ";
    $sql .= "CONCAT(SUBSTR(uf.data_fi,9,2),'-',SUBSTR(uf.data_fi,6,2),'-',SUBSTR(uf.data_fi,1,4)) AS uf_data_fi, ";
    $sql .= "CONCAT(SUBSTR(uf.data_inici,9,2),'-',SUBSTR(uf.data_inici,6,2),'-',SUBSTR(uf.data_inici,1,4)) AS data_inici, ";
	$sql .= "CONCAT(SUBSTR(uf.data_fi,9,2),'-',SUBSTR(uf.data_fi,6,2),'-',SUBSTR(uf.data_fi,1,4)) AS data_fi ";
	$sql .= "FROM unitats_formatives uf ";
	$sql .= "INNER JOIN moduls_ufs             mu ON mu.id_ufs=uf.idunitats_formatives ";
	$sql .= $where." AND uf.idunitats_formatives NOT IN ";
	$sql .= "(SELECT uf.idunitats_formatives 
			  FROM unitats_formatives uf 
			  INNER JOIN moduls_ufs            mu ON mu.id_ufs=uf.idunitats_formatives 
			  LEFT JOIN grups_materies         gm ON mu.id_ufs=gm.id_mat_uf_pla  
			  WHERE mu.id_moduls=$idmoduls AND gm.id_grups=$idgrups) ";
	
	$sql .= "ORDER BY $sort $order";
	
	$rs   = mysql_query($sql);
}

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql ."\n\n". PHP_EOL);
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