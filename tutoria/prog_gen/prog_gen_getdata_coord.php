<?php

include('../bbdd/connect.php');
include_once('../func/generic.php');
include_once('../func/constants.php');

mysql_query("SET NAMES 'utf8'");

$idprofessors = $_POST['idprofessors'];


$sql = "SELECT pg.idprogramacio_general, cp.Valor FROM programacions_general pg, moduls m, contacte_professor cp WHERE "
. "pg.idmoduls = m.idmoduls AND m.idplans_estudis IN (SELECT DISTINCT pe.idplans_estudis FROM professor_carrec pc, "
. "plans_estudis pe, grups_materies gp, moduls_materies_ufs mms WHERE pc.idprofessors = 3 AND pc.idcarrecs = 2 AND pc.idgrups "
. "= gp.id_grups AND gp.id_mat_uf_pla = mms.id_mat_uf_pla AND mms.idplans_estudis = pe.idplans_estudis) AND pg.idprofessors = "
. "cp.id_professor AND cp.id_tipus_contacte = 1 AND pg.revisat = 'E'";

if (is_numeric($_POST['idplans_estudis'])) {
    $idplans_estudis = $_POST['idplans_estudis'];
    
    $where = " AND m.idplans_estudis = $idplans_estudis";
    $sql .= $where;
}

/**
 * Afegeix un filtre de cerca sobre les programacions que es corresponguin amb el mòdul seleccionat.
 */
if (is_numeric($_POST['idmoduls'])) {
    $idmoduls = $_POST['idmoduls'];
    
    $where = " AND m.idmoduls = $idmoduls";
    $sql .= $where;
}

/**
 * Afegeix un filtre de cerca sobre les programacions que es corresponguin amb el curs seleccionat.
 */
if (is_numeric($_POST['idcurs'])) {
    $idcurs = $_POST['idcurs'];
    $where .= " AND c.idcurs = $idcurs";
    
    $sql .= $where;
}

$rs = mysql_query($sql);

$items = array();

while($row = mysql_fetch_assoc($rs)){  
    $items[] = $row;
}  

$sql = "SELECT cp.Valor FROM programacions_general pg, moduls m, contacte_professor cp WHERE "
. "pg.idmoduls = m.idmoduls AND m.idplans_estudis IN (SELECT DISTINCT pe.idplans_estudis FROM professor_carrec pc, "
. "plans_estudis pe, grups_materies gp, moduls_materies_ufs mms WHERE pc.idprofessors = 3 AND pc.idcarrecs = 2 AND pc.idgrups "
. "= gp.id_grups AND gp.id_mat_uf_pla = mms.id_mat_uf_pla AND mms.idplans_estudis = pe.idplans_estudis) AND pg.idprofessors = "
. "cp.id_professor AND cp.id_tipus_contacte = 4 AND pg.revisat = 'E'";

$rs = mysql_query($sql);

$i = 0;
while($row = mysql_fetch_assoc($rs)){  
    $items[$i]['Valor'] .= " " . $row['Valor'];
    $i++;
}  
    
echo json_encode($items);  

