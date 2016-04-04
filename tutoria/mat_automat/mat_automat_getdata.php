<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

//$idgrups  = isset($_REQUEST['idgrups']) ? $_REQUEST['idgrups'] : 0 ;
$idprofessors  = isset($_SESSION['professor']) ? $_SESSION['professor'] : 0;
$curs     = $_SESSION['curs_escolar'];

$sort   = isset($_POST['sort']) ? strval($_POST['sort']) : '5,6';  
$order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

$result = array();

$sql  = "SELECT uc.*,m.nom_materia AS materia,g.idgrups,g.nom AS grup,mmuf.* ";
$sql .= "FROM unitats_classe uc ";
$sql .= "INNER JOIN dies_franges          df ON uc.id_dies_franges    = df.id_dies_franges ";
$sql .= "INNER JOIN prof_agrupament       pa ON uc.idgrups_materies   = pa.idagrups_materies ";
$sql .= "INNER JOIN grups_materies        gm ON uc.idgrups_materies   = gm.idgrups_materies ";
$sql .= "INNER JOIN grups                  g ON gm.id_grups           = g.idgrups ";
$sql .= "INNER JOIN materia                m ON gm.id_mat_uf_pla      = m.idmateria ";
$sql .= "INNER JOIN moduls_materies_ufs mmuf ON m.idmateria           = mmuf.id_mat_uf_pla ";
$sql .= "WHERE df.idperiode_escolar=".$curs." AND pa.idprofessors='".$idprofessors."' AND mmuf.automatricula='S' ";	
$sql .= "GROUP BY uc.idgrups_materies";
	 
$sql .= " UNION ";
	 
$sql .= "SELECT uc.*,CONCAT(LEFT(m.nom_modul,20),'-',uf.nom_uf) AS materia,g.idgrups,g.nom AS grup,mmuf.* ";
$sql .= "FROM unitats_classe uc ";
$sql .= "INNER JOIN dies_franges          df ON uc.id_dies_franges      = df.id_dies_franges ";
$sql .= "INNER JOIN prof_agrupament       pa ON uc.idgrups_materies     = pa.idagrups_materies ";
$sql .= "INNER JOIN grups_materies        gm ON uc.idgrups_materies     = gm.idgrups_materies ";
$sql .= "INNER JOIN grups                  g ON gm.id_grups             = g.idgrups ";
$sql .= "INNER JOIN unitats_formatives    uf ON gm.id_mat_uf_pla        = uf.idunitats_formatives ";
$sql .= "INNER JOIN moduls_materies_ufs mmuf ON uf.idunitats_formatives = mmuf.id_mat_uf_pla ";
$sql .= "INNER JOIN moduls_ufs            mu ON uf.idunitats_formatives = mu.id_ufs ";
$sql .= "INNER JOIN moduls                 m ON mu.id_moduls            = m.idmoduls "; 
$sql .= "WHERE df.idperiode_escolar=".$curs." AND pa.idprofessors='".$idprofessors."' AND mmuf.automatricula='S' ";
$sql .= "GROUP BY uc.idgrups_materies";

$sql .= " ORDER BY $sort $order ";
 
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

