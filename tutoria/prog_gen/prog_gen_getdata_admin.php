<?php

include('../bbdd/connect.php');
include_once('../func/generic.php');
include_once('../func/constants.php');

mysql_query("SET NAMES 'utf8'");

$idprofessors = $_POST['idprofessors'];


$sqlNom = "SELECT pg.idprogramacio_general, pg.nom_document, cp.Valor, pg.data_creacio, m.nom_modul, c.nom_curs FROM programacions_general pg, moduls m, contacte_professor cp, curs c WHERE "
. "pg.idmoduls = m.idmoduls AND m.idplans_estudis IN (SELECT DISTINCT pe.idplans_estudis FROM professor_carrec pc, "
. "plans_estudis pe, grups_materies gp, moduls_materies_ufs mms WHERE pc.idprofessors = $idprofessors AND pc.idcarrecs = 3 AND pc.idgrups "
. "= gp.id_grups AND gp.id_mat_uf_pla = mms.id_mat_uf_pla AND mms.idplans_estudis = pe.idplans_estudis) AND pg.idprofessors = "
. "cp.id_professor AND cp.id_tipus_contacte = 1 AND pg.aprovat = 'E' AND pg.idcurs = c.idcurs";

$sqlCognom = "SELECT pg.idprogramacio_general, pg.nom_document, cp.Valor, pg.data_creacio, m.nom_modul, c.nom_curs FROM programacions_general pg, moduls m, contacte_professor cp, curs c WHERE "
. "pg.idmoduls = m.idmoduls AND m.idplans_estudis IN (SELECT DISTINCT pe.idplans_estudis FROM professor_carrec pc, "
. "plans_estudis pe, grups_materies gp, moduls_materies_ufs mms WHERE pc.idprofessors = $idprofessors AND pc.idcarrecs = 3 AND pc.idgrups "
. "= gp.id_grups AND gp.id_mat_uf_pla = mms.id_mat_uf_pla AND mms.idplans_estudis = pe.idplans_estudis) AND pg.idprofessors = "
. "cp.id_professor AND cp.id_tipus_contacte = 4 AND pg.aprovat = 'E' AND pg.idcurs = c.idcurs";

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

/*
 * Recull el nom del autor de la programació comuna.
 */

$rs = mysql_query($sqlNom);

$items = array();

while($row = mysql_fetch_assoc($rs)){  
    $items[] = $row;
}  

/**
 * Recull el nom del professor que ha realizat la revisió de la programació comuna.
 */
$sqlNomRevisio = "SELECT cp.Valor AS 'revisat' FROM programacions_general pg, moduls m, contacte_professor cp, curs c WHERE "
. "pg.idmoduls = m.idmoduls AND m.idplans_estudis IN (SELECT DISTINCT pe.idplans_estudis FROM professor_carrec pc, "
. "plans_estudis pe, grups_materies gp, moduls_materies_ufs mms WHERE pc.idprofessors = 4 AND pc.idcarrecs = 3 AND pc.idgrups "
. "= gp.id_grups AND gp.id_mat_uf_pla = mms.id_mat_uf_pla AND mms.idplans_estudis = pe.idplans_estudis) AND pg.professorRevisio "
. "= cp.id_professor AND cp.id_tipus_contacte = 1 AND pg.aprovat = 'E' AND pg.idcurs = c.idcurs";

$rs = mysql_query($sqlNomRevisio);

$i = 0;
while($row = mysql_fetch_assoc($rs)){  
    $items[$i]['revisio'] = $row['revisat'];
    $i++;
}  

/**
 * Recull el cognom del professor que ha realizat la revisió de la programació comuna.
 */
$rs = mysql_query($sqlCognom);

$i = 0;
while($row = mysql_fetch_assoc($rs)){  
    $items[$i]['Valor'] .= " " . $row['Valor'];
    $i++;
} 

/**
 * Recull el nom del professor que ha realizat la revisió de la programació comuna.
 */
$sqlCognomRevisio = "SELECT cp.Valor AS 'revisat' FROM programacions_general pg, moduls m, contacte_professor cp, curs c WHERE "
. "pg.idmoduls = m.idmoduls AND m.idplans_estudis IN (SELECT DISTINCT pe.idplans_estudis FROM professor_carrec pc, "
. "plans_estudis pe, grups_materies gp, moduls_materies_ufs mms WHERE pc.idprofessors = 4 AND pc.idcarrecs = 3 AND pc.idgrups "
. "= gp.id_grups AND gp.id_mat_uf_pla = mms.id_mat_uf_pla AND mms.idplans_estudis = pe.idplans_estudis) AND pg.professorRevisio "
. "= cp.id_professor AND cp.id_tipus_contacte = 4 AND pg.aprovat = 'E' AND pg.idcurs = c.idcurs";

$rs = mysql_query($sqlCognomRevisio);

$i = 0;
while($row = mysql_fetch_assoc($rs)){  
    $items[$i]['revisio'] .= " " . $row['revisat'];
    $i++;
}  
    
echo json_encode($items);  

