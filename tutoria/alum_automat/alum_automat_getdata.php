<?php
session_start();
include_once('../bbdd/connect.php');
include_once('../func/generic.php');
include_once('../func/constants.php');
mysql_query("SET NAMES 'utf8'");

$sort    = isset($_POST['sort'])  ? strval($_POST['sort'])  : '4,3';  
$order   = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

$idplans_estudis = isset($_REQUEST['idplans_estudis'])                       ? $_REQUEST['idplans_estudis']             : 0;
$idmoduls        = isset($_REQUEST['idmoduls']) && $_REQUEST['idmoduls']!='' ? ' m.idmoduls='.$_REQUEST['idmoduls'].' ' : 1;
$curs            = getCursActual()->idperiodes_escolars;
$idalumnes       = $_SESSION['alumne'];

// Obtenim totes les matèries que pertanyen a un determinat plà d'estudis

$sql  = "SELECT gm.idgrups_materies,gm.id_mat_uf_pla,m.nom_materia AS materia,g.nom as grup,CONCAT('1') AS matriculado,mmu.contrasenya ";
$sql .= "FROM grups_materies gm ";
$sql .= "INNER JOIN grups                 g ON gm.id_grups          = g.idgrups ";
$sql .= "INNER JOIN moduls_materies_ufs mmu ON  gm.id_mat_uf_pla    = mmu.id_mat_uf_pla ";
$sql .= "INNER JOIN materia               m ON  gm.id_mat_uf_pla    = m.idmateria ";
$sql .= "WHERE mmu.automatricula='S' AND mmu.activat='S' AND mmu.idplans_estudis=$idplans_estudis AND EXISTS ";
$sql .= " (select * from alumnes_grup_materia where idalumnes=$idalumnes and idgrups_materies=gm.idgrups_materies) ";
$sql .= " GROUP BY 1 "; 

$sql .= " UNION ";

$sql .= "SELECT gm.idgrups_materies,gm.id_mat_uf_pla,m.nom_materia AS materia,g.nom as grup,CONCAT('0') AS matriculado,mmu.contrasenya ";
$sql .= "FROM grups_materies gm ";
$sql .= "INNER JOIN grups                 g ON gm.id_grups          = g.idgrups ";
$sql .= "INNER JOIN moduls_materies_ufs mmu ON  gm.id_mat_uf_pla    = mmu.id_mat_uf_pla ";
$sql .= "INNER JOIN materia               m ON  gm.id_mat_uf_pla    = m.idmateria ";
$sql .= "WHERE mmu.automatricula='S' AND mmu.activat='S' AND mmu.idplans_estudis=$idplans_estudis AND NOT EXISTS ";
$sql .= " (select * from alumnes_grup_materia where idalumnes=$idalumnes and idgrups_materies=gm.idgrups_materies) ";
$sql .= " GROUP BY 1 ";

$sql .= " UNION ";
	 
$sql .= "SELECT gm.idgrups_materies,gm.id_mat_uf_pla,CONCAT(LEFT(m.nom_modul,20),'-',uf.nom_uf) AS materia,g.nom as grup,CONCAT('1') AS matriculado,mmu.contrasenya ";
$sql .= "FROM grups_materies gm ";
$sql .= "INNER JOIN grups                 g ON gm.id_grups      = g.idgrups ";
$sql .= "INNER JOIN moduls_materies_ufs mmu ON gm.id_mat_uf_pla = mmu.id_mat_uf_pla ";
$sql .= "INNER JOIN unitats_formatives   uf ON gm.id_mat_uf_pla = uf.idunitats_formatives ";
$sql .= "INNER JOIN moduls_ufs           mu ON gm.id_mat_uf_pla = mu.id_ufs ";
$sql .= "INNER JOIN moduls                m ON mu.id_moduls     = m.idmoduls ";
$sql .= "WHERE ".$idmoduls." AND mmu.automatricula='S' AND mmu.activat='S' AND mmu.idplans_estudis=$idplans_estudis AND EXISTS ";
$sql .= " (select * from alumnes_grup_materia where idalumnes=$idalumnes and idgrups_materies=gm.idgrups_materies) ";
$sql .= " GROUP BY 1 ";

$sql .= " UNION ";

$sql .= "SELECT gm.idgrups_materies,gm.id_mat_uf_pla,CONCAT(LEFT(m.nom_modul,20),'-',uf.nom_uf) AS materia,g.nom as grup,CONCAT('0') AS matriculado,mmu.contrasenya ";
$sql .= "FROM grups_materies gm ";
$sql .= "INNER JOIN grups                 g ON gm.id_grups          = g.idgrups ";
$sql .= "INNER JOIN moduls_materies_ufs mmu ON  gm.id_mat_uf_pla    = mmu.id_mat_uf_pla ";
$sql .= "INNER JOIN unitats_formatives   uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
$sql .= "INNER JOIN moduls_ufs           mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
$sql .= "INNER JOIN moduls                m ON mu.id_moduls         = m.idmoduls ";
$sql .= "WHERE ".$idmoduls." AND mmu.automatricula='S' AND mmu.activat='S' AND mmu.idplans_estudis=$idplans_estudis AND NOT EXISTS ";
$sql .= " (select * from alumnes_grup_materia where idalumnes=$idalumnes and idgrups_materies=gm.idgrups_materies) ";
$sql .= " GROUP BY 1 ";
	 
$sql .= " ORDER BY $sort $order ";

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