<?php

include('../bbdd/connect.php');
include_once('../func/generic.php');
include_once('../func/constants.php');

mysql_query("SET NAMES 'utf8'");

$idprofessors = $_POST['idprofessors'];


$sqlNom = "SELECT pg.idprogramacio_general, pg.nom_document, cp.Valor, pg.data_creacio, m.nom_modul, c.nom_curs, pe.Nom FROM programacions_general pg, moduls m, contacte_professor cp, curs c, periodes_escolars pe WHERE "
. "pg.idmoduls = m.idmoduls AND m.idplans_estudis IN (SELECT DISTINCT pe.idplans_estudis FROM professor_carrec pc, "
. "plans_estudis pe, grups_materies gp, moduls_materies_ufs mms WHERE pc.idprofessors = $idprofessors AND pc.idcarrecs = 2 AND pc.idgrups "
. "= gp.id_grups AND gp.id_mat_uf_pla = mms.id_mat_uf_pla AND mms.idplans_estudis = pe.idplans_estudis) AND pg.idprofessors = "
. "cp.id_professor AND cp.id_tipus_contacte = 1 AND pg.revisat = 'E' AND pg.idcurs = c.idcurs AND pg.idperiodes_escolar = pe.idperiodes_escolars";

$sqlCognom = "SELECT cp.Valor FROM programacions_general pg, moduls m, contacte_professor cp, curs c WHERE "
. "pg.idmoduls = m.idmoduls AND m.idplans_estudis IN (SELECT DISTINCT pe.idplans_estudis FROM professor_carrec pc, "
. "plans_estudis pe, grups_materies gp, moduls_materies_ufs mms WHERE pc.idprofessors = 3 AND pc.idcarrecs = 2 AND pc.idgrups "
. "= gp.id_grups AND gp.id_mat_uf_pla = mms.id_mat_uf_pla AND mms.idplans_estudis = pe.idplans_estudis) AND pg.idprofessors = "
. "cp.id_professor AND cp.id_tipus_contacte = 4 AND pg.revisat = 'E' AND pg.idcurs = c.idcurs";

if (is_numeric($_POST['idplans_estudis'])) {
    $idplans_estudis = $_POST['idplans_estudis'];
    
    $where = " AND m.idplans_estudis = $idplans_estudis";
    $sqlNom .= $where;
    $sqlCognom .= $where;
    
}

/**
 * Afegeix un filtre de cerca sobre les programacions que es corresponguin amb el mòdul seleccionat.
 */
if (is_numeric($_POST['idmoduls'])) {
    $idmoduls = $_POST['idmoduls'];
    
    $where = " AND m.idmoduls = $idmoduls";
    $sqlNom .= $where;
    $sqlCognom .= $where;
}

/**
 * Afegeix un filtre de cerca sobre les programacions que es corresponguin amb el curs seleccionat.
 */
if (is_numeric($_POST['idcurs'])) {
    $idcurs = $_POST['idcurs'];
    
    $where .= " AND c.idcurs = $idcurs";
    $sqlNom .= $where;
    $sqlCognom .= $where;
}

$rs = mysql_query($sqlNom);

$items = array();

while($row = mysql_fetch_assoc($rs)){  
    $items[] = $row;
}  

$rs = mysql_query($sqlCognom);

$i = 0;
while($row = mysql_fetch_assoc($rs)){  
    $items[$i]['Valor'] .= " " . $row['Valor'];
    $i++;
} 
    
echo json_encode($items);  

