<?php

include('../bbdd/connect.php');
include_once('../func/generic.php');
include_once('../func/constants.php');
mysql_query("SET NAMES 'utf8'");

$idprofessors = isset($_REQUEST['idprofessors']) ? strval($_REQUEST['idprofessors']) : '';
$idcarrecs = isset($_REQUEST['idcarrecs']) ? strval($_REQUEST['idcarrecs']) : '';

$sql = "SELECT DISTINCT pe.idplans_estudis, pe.Nom_plan_estudis FROM professor_carrec pc, plans_estudis pe, grups_materies gp, "
       . "moduls_materies_ufs mms WHERE pc.idprofessors = $idprofessors AND pc.idcarrecs = $idcarrecs AND pc.idgrups = gp.id_grups "
       . "AND gp.id_mat_uf_pla = mms.id_mat_uf_pla AND mms.idplans_estudis = pe.idplans_estudis";

$result = array();

$rs = mysql_query($sql);

$items = array();  
while($row = mysql_fetch_assoc($rs)){  
    $items[] = $row;
}  

echo json_encode($items);

?>



