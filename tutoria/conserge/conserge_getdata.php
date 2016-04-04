<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$page    = isset($_POST['page']) ? intval($_POST['page']) : 1;  
$rows    = isset($_POST['rows']) ? intval($_POST['rows']) : 20;  
$sort    = isset($_POST['sort']) ? strval($_POST['sort']) : '1';  
$order   = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
$data   = isset($_REQUEST['data']) ? substr($_REQUEST['data'],6,4)."-".substr($_REQUEST['data'],3,2)."-".substr($_REQUEST['data'],0,2) : date("Y-m-d");

$sort   = isset($_POST['sort']) ? strval($_POST['sort']) : '2';  

$offset = ($page-1)*$rows;

$any         = substr($data,0,4);
$mes         = substr($data,5,2);
$dia         = substr($data,8,2);
$dia_setmana = diaSemana($any,$mes,$dia);

$result      = array();

$sql  = "SELECT ia.idincidencia_alumne,CONCAT(LEFT(fh.hora_inici,5),'-',LEFT(fh.hora_fi,5)) AS dia_hora, ";
$sql .= "ca.Valor AS alumne,cp.Valor AS professor,gr.nom AS grup,m.nom_materia,tf.tipus_falta,ia.comentari, ";
$sql .= "gr.idgrups,ia.idalumnes,ia.id_tipus_incidencia,cp.id_professor,ti.tipus_incident ";
$sql .= "FROM incidencia_alumne ia ";
$sql .= "INNER JOIN tipus_incidents        ti ON ia.id_tipus_incident    = ti.idtipus_incident "; 
$sql .= "INNER JOIN materia                 m ON ia.id_mat_uf_pla        = m.idmateria ";
$sql .= "INNER JOIN franges_horaries       fh ON ia.idfranges_horaries   = fh.idfranges_horaries ";
$sql .= "INNER JOIN contacte_alumne        ca ON ia.idalumnes            = ca.id_alumne ";
$sql .= "INNER JOIN contacte_professor     cp ON ia.idprofessors         = cp.id_professor ";
$sql .= "INNER JOIN tipus_falta_alumne     tf ON ia.id_tipus_incidencia  = tf.idtipus_falta_alumne ";
$sql .= "INNER JOIN grups                  gr ON ia.idgrups              = gr.idgrups ";
$sql .= "WHERE ia.data='".$data."' AND ca.id_tipus_contacte=".TIPUS_nom_complet." AND cp.id_tipus_contacte=".TIPUS_nom_complet." ";

$sql .= " UNION ";

$sql .= "SELECT ia.idincidencia_alumne,CONCAT(LEFT(fh.hora_inici,5),'-',LEFT(fh.hora_fi,5)) AS dia_hora, ";
$sql .= "ca.Valor AS alumne,cp.Valor AS professor,gr.nom AS grup,CONCAT(m.nom_modul,'-',uf.nom_uf) AS nom_materia,tf.tipus_falta,ia.comentari, ";
$sql .= "gr.idgrups,ia.idalumnes,ia.id_tipus_incidencia,cp.id_professor,ti.tipus_incident ";
$sql .= "FROM incidencia_alumne ia ";
$sql .= "INNER JOIN tipus_incidents        ti ON ia.id_tipus_incident    = ti.idtipus_incident "; 
$sql .= "INNER JOIN unitats_formatives     uf ON ia.id_mat_uf_pla        = uf.idunitats_formatives ";
$sql .= "INNER JOIN moduls_ufs             mu ON ia.id_mat_uf_pla        = mu.id_ufs ";
$sql .= "INNER JOIN moduls                  m ON mu.id_moduls            = m.idmoduls ";
$sql .= "INNER JOIN franges_horaries       fh ON ia.idfranges_horaries   = fh.idfranges_horaries ";
$sql .= "INNER JOIN contacte_alumne        ca ON ia.idalumnes            = ca.id_alumne ";
$sql .= "INNER JOIN contacte_professor     cp ON ia.idprofessors         = cp.id_professor ";
$sql .= "INNER JOIN tipus_falta_alumne     tf ON ia.id_tipus_incidencia  = tf.idtipus_falta_alumne ";
$sql .= "INNER JOIN grups                  gr ON ia.idgrups              = gr.idgrups ";
$sql .= "WHERE ia.data='".$data."' AND ca.id_tipus_contacte=".TIPUS_nom_complet." AND cp.id_tipus_contacte=".TIPUS_nom_complet." ";

$sql .= " ORDER BY $sort $order ";

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
