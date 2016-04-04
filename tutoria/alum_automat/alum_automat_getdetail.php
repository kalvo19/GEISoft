<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idgrups     = isset($_REQUEST['idgrups']) ? $_REQUEST['idgrups'] : 0 ;
$data        = isset($_REQUEST['data']) ? substr($_REQUEST['data'],6,4)."-".substr($_REQUEST['data'],3,2)."-".substr($_REQUEST['data'],0,2) : date("Y-m-d");
$curs_actual = $_SESSION['curs_escolar']; 
$result      = array();

$any         = substr($data,0,4);
$mes         = substr($data,5,2);
$dia         = substr($data,8,2);
$dia_setmana = diaSemana($any,$mes,$dia);

$sql  = "SELECT uc.*,CONCAT(LEFT(fh.hora_inici,5),'-',LEFT(fh.hora_fi,5)) AS dia_hora,m.nom_materia,ec.descripcio ";
$sql .= "FROM unitats_classe uc ";
$sql .= "INNER JOIN dies_franges     df ON uc.id_dies_franges    = df.id_dies_franges ";
$sql .= "INNER JOIN dies_setmana     ds ON df.iddies_setmana     = ds.iddies_setmana ";
$sql .= "INNER JOIN franges_horaries fh ON df.idfranges_horaries = fh.idfranges_horaries ";
$sql .= "INNER JOIN espais_centre    ec ON uc.idespais_centre    = ec.idespais_centre ";
$sql .= "INNER JOIN grups_materies   gm ON uc.idgrups_materies   = gm.idgrups_materies ";
$sql .= "INNER JOIN materia           m ON gm.id_mat_uf_pla      = m.idmateria ";
$sql .= "WHERE fh.esbarjo<>'S' AND ds.iddies_setmana=".$dia_setmana." AND df.idperiode_escolar=".$curs_actual." AND gm.id_grups='".$idgrups."' ";

$sql .= "UNION ";

$sql .= "SELECT uc.*,CONCAT(LEFT(fh.hora_inici,5),'-',LEFT(fh.hora_fi,5)) AS dia_hora,CONCAT(m.nom_modul,'-',uf.nom_uf) AS nom_materia,ec.descripcio ";
$sql .= "FROM unitats_classe uc ";
$sql .= "INNER JOIN dies_franges     df ON uc.id_dies_franges    = df.id_dies_franges ";
$sql .= "INNER JOIN dies_setmana     ds ON df.iddies_setmana     = ds.iddies_setmana ";
$sql .= "INNER JOIN franges_horaries fh ON df.idfranges_horaries = fh.idfranges_horaries ";
$sql .= "INNER JOIN espais_centre    ec ON uc.idespais_centre    = ec.idespais_centre ";
$sql .= "INNER JOIN grups_materies   gm ON uc.idgrups_materies   = gm.idgrups_materies ";
$sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
$sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
$sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
$sql .= "WHERE fh.esbarjo<>'S' AND ds.iddies_setmana=".$dia_setmana." AND df.idperiode_escolar=".$curs_actual." AND gm.id_grups='".$idgrups."' ";
$sql .= "AND gm.data_inici<='".date("y-m-d")."' AND gm.data_fi>='".date("y-m-d")."'";

$sql .= "ORDER BY 5 ";

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