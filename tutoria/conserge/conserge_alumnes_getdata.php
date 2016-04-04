<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$data   = isset($_REQUEST['data']) ? $_REQUEST['data'] : date("Y-m-d");
if ($data == '--') {
	$data = date("Y-m-d");
}

$any         = substr($data,0,4);
$mes         = substr($data,5,2);
$dia         = substr($data,8,2);
$dia_setmana = diaSemana($any,$mes,$dia);

$sort   = isset($_POST['sort']) ? strval($_POST['sort']) : '2';  
$order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

$result      = array();

$sql  = "SELECT agm.idalumnes, ca.Valor AS alumne,ia.id_tipus_incidencia ";
$sql .= "FROM incidencia_alumne ia ";
$sql .= "INNER JOIN alumnes_grup_materia  agm ON ia.idalumnes            = agm.idalumnes "; 
$sql .= "INNER JOIN grups_materies         gm ON agm.idgrups_materies    = gm.idgrups_materies "; 
$sql .= "INNER JOIN unitats_classe         uc ON agm.idgrups_materies    = uc.idgrups_materies ";
$sql .= "INNER JOIN materia                 m ON gm.id_mat_uf_pla        = m.idmateria ";
$sql .= "INNER JOIN dies_franges           df ON uc.id_dies_franges      = df.id_dies_franges ";
$sql .= "INNER JOIN franges_horaries       fh ON df.idfranges_horaries   = fh.idfranges_horaries ";
$sql .= "INNER JOIN contacte_alumne        ca ON agm.idalumnes           = ca.id_alumne ";
$sql .= "INNER JOIN tipus_falta_alumne     tf ON ia.id_tipus_incidencia  = tf.idtipus_falta_alumne ";
$sql .= "INNER JOIN grups                  gr ON gm.id_grups             = gr.idgrups ";
$sql .= "WHERE ia.data='".$data."' AND ca.id_tipus_contacte=".TIPUS_nom_complet." ";
$sql .= "AND df.iddies_setmana=".$dia_setmana." GROUP BY 1 ";

$sql .= "UNION ";

$sql .= "SELECT agm.idalumnes, ca.Valor AS alumne,ia.id_tipus_incidencia ";
$sql .= "FROM incidencia_alumne ia ";
$sql .= "INNER JOIN alumnes_grup_materia  agm ON ia.idalumnes            = agm.idalumnes "; 
$sql .= "INNER JOIN grups_materies         gm ON agm.idgrups_materies    = gm.idgrups_materies "; 
$sql .= "INNER JOIN unitats_classe         uc ON agm.idgrups_materies    = uc.idgrups_materies ";
$sql .= "INNER JOIN unitats_formatives     uf ON gm.id_mat_uf_pla        = uf.idunitats_formatives ";
$sql .= "INNER JOIN moduls_ufs             mu ON gm.id_mat_uf_pla        = mu.id_ufs ";
$sql .= "INNER JOIN moduls                  m ON mu.id_moduls            = m.idmoduls ";
$sql .= "INNER JOIN dies_franges           df ON uc.id_dies_franges      = df.id_dies_franges ";
$sql .= "INNER JOIN franges_horaries       fh ON df.idfranges_horaries   = fh.idfranges_horaries ";
$sql .= "INNER JOIN contacte_alumne        ca ON agm.idalumnes           = ca.id_alumne ";
$sql .= "INNER JOIN tipus_falta_alumne     tf ON ia.id_tipus_incidencia  = tf.idtipus_falta_alumne ";
$sql .= "INNER JOIN grups                  gr ON gm.id_grups             = gr.idgrups ";
$sql .= "WHERE ia.data='".$data."' AND ca.id_tipus_contacte=".TIPUS_nom_complet." ";
$sql .= "AND df.iddies_setmana=".$dia_setmana." GROUP BY 1 ";

$sql .= " ORDER BY $sort $order ";

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
