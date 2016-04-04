<?php
session_start();
//include('../bbdd/connect.php');
include('../bbdd/connect_sms.php');
include_once('../func/constants.php');
//include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

$page    = isset($_POST['page']) ? intval($_POST['page']) : 1;  
$rows    = isset($_POST['rows']) ? intval($_POST['rows']) : 20;  
$sort    = isset($_POST['sort']) ? strval($_POST['sort']) : '4';  
$order   = isset($_POST['order']) ? strval($_POST['order']) : 'desc';

$data_inici = isset($_REQUEST['data_inici']) ? substr($_REQUEST['data_inici'],6,4)."-".substr($_REQUEST['data_inici'],3,2)."-".substr($_REQUEST['data_inici'],0,2) : '1989-01-01';
 
$data_fi    = isset($_REQUEST['data_fi'])    ? substr($_REQUEST['data_fi'],6,4)."-".substr($_REQUEST['data_fi'],3,2)."-".substr($_REQUEST['data_fi'],0,2)          : '2189-01-01';

$idalumne   = isset($_REQUEST['idalumne']) ? intval($_REQUEST['idalumne']) : 0;


/*if ( isset($_REQUEST['idalumne']) && ($_REQUEST['idalumne']==0) ) {
  	$idalumne = 0;
}
else if ( isset($_REQUEST['idalumne']) ) {
    $idalumne = $_REQUEST['idalumne'];
}
if (! isset($idalumne)) {
    $idalumne = 0;
}*/

if ($idalumne != 0) {
	$tlf_alumne = getAlumne($idalumne,TIPUS_mobil_sms);
}

/*$fp = fopen("log.txt","a");
fwrite($fp, $actual_link . PHP_EOL);
fclose($fp);*/

$offset = ($page-1)*$rows;
$result  = array();

$sql  = "SELECT COUNT(*) ";
$sql .= "FROM vista_log_sms vls ";
$sql .= "WHERE centre='".HEADER_SMS."' AND DATE(vls.data_hora) BETWEEN '".$data_inici."' AND '".$data_fi."' ";
/*if ($idalumne != 0) {
	$sql .= " AND SUBSTR(vls.data_hora,3,9)='".$tlf_alumne."' ";
}*/

$rs = mysql_query($sql);
$row = mysql_fetch_row($rs);  
$result["total"] = $row[0]; 

$sql  = "SELECT vls.*, ";
$sql .= "CONCAT(SUBSTR(vls.data_hora,9,2),'-',SUBSTR(vls.data_hora,6,2),'-',SUBSTR(vls.data_hora,1,4)) AS data, ";
$sql .= "SUBSTR(vls.data_hora,11,9) AS hora ";
$sql .= "FROM vista_log_sms vls ";
$sql .= "WHERE centre='".HEADER_SMS."' AND DATE(vls.data_hora) BETWEEN '".$data_inici."' AND '".$data_fi."' ";
/*if ($idalumne != 0) {
	$sql .= " AND SUBSTR(vls.data_hora,3,9)='".$tlf_alumne."' ";
}*/
$sql .= "ORDER BY $sort $order LIMIT $offset,$rows";

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
