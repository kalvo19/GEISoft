<?php
session_start();
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$curs               = $_SESSION['curs_escolar'];
$dia                = $_REQUEST['dia'];
$hora               = substr($_REQUEST['hora'],0,5);
$idfranges_horaries = comprovarHoraDia($hora);

$sql  = "SELECT uc.*,pa.idagrups_materies,g.idgrups, m.idmateria, m.nom_materia AS materia,ec.descripcio AS espaicentre,g.nom as grup, ";
$sql .= "CONCAT(LEFT(fh.hora_inici,5),'-',LEFT(fh.hora_fi,5)) AS hora,fh.idfranges_horaries ";
$sql .= "FROM prof_agrupament pa ";
$sql .= "INNER JOIN unitats_classe     uc ON pa.idagrups_materies  = uc.idgrups_materies ";
$sql .= "INNER JOIN dies_franges       df ON uc.id_dies_franges    = df.id_dies_franges ";
$sql .= "INNER JOIN franges_horaries   fh ON df.idfranges_horaries = fh.idfranges_horaries ";
$sql .= "INNER JOIN espais_centre      ec ON uc.idespais_centre    = ec.idespais_centre ";
$sql .= "INNER JOIN grups_materies     gm ON uc.idgrups_materies   = gm.idgrups_materies ";
$sql .= "INNER JOIN materia             m ON gm.id_mat_uf_pla      = m.idmateria ";
$sql .= "INNER JOIN grups               g ON gm.id_grups           = g.idgrups ";
$sql .= "WHERE df.iddies_setmana=$dia AND fh.esbarjo<>'S' AND df.idperiode_escolar=$curs AND fh.idfranges_horaries=$idfranges_horaries";

$sql .= " UNION ";

$sql .= "SELECT uc.*,pa.idagrups_materies,g.idgrups, uf.idunitats_formatives, CONCAT(m.nom_modul,'-',uf.nom_uf) AS materia, ";
$sql .= "ec.descripcio AS espaicentre,g.nom as grup,CONCAT(LEFT(fh.hora_inici,5),'-',LEFT(fh.hora_fi,5)) AS hora,fh.idfranges_horaries ";
$sql .= "FROM prof_agrupament pa ";
$sql .= "INNER JOIN unitats_classe     uc ON pa.idagrups_materies  = uc.idgrups_materies ";
$sql .= "INNER JOIN dies_franges       df ON uc.id_dies_franges    = df.id_dies_franges ";
$sql .= "INNER JOIN franges_horaries   fh ON df.idfranges_horaries = fh.idfranges_horaries ";
$sql .= "INNER JOIN espais_centre      ec ON uc.idespais_centre    = ec.idespais_centre ";
$sql .= "INNER JOIN grups_materies     gm ON uc.idgrups_materies   = gm.idgrups_materies ";
$sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
$sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
$sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
$sql .= "INNER JOIN grups               g ON gm.id_grups           = g.idgrups ";
$sql .= "WHERE df.iddies_setmana=$dia AND fh.esbarjo<>'S' AND df.idperiode_escolar=$curs AND fh.idfranges_horaries=$idfranges_horaries";
$sql .= " AND gm.data_inici<='".date("y-m-d")."' AND gm.data_fi>='".date("y-m-d")."' ";

$sql .= "ORDER BY 10";

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