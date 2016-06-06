<?php

include('../bbdd/connect.php');
include_once('../func/generic.php');
include_once('../func/constants.php');
mysql_query("SET NAMES 'utf8'");

$idprofessors = $_POST['idprofessors'];

/**
 * Selecciona totes les programacions generals realitzades per al professor en actiu.
 */
$sql = "SELECT pg.idprogramacio_general, pg.idmoduls, pg.idcurs, pg.nom_document, m.nom_modul, c.nom_curs, pg.data_creacio, "
. "pe.Nom, pg.aprovat, pg.revisat FROM programacions_general pg, moduls m, periodes_escolars pe, curs c WHERE pg.idprofessors "
. "= $idprofessors AND pg.idcurs = c.idcurs AND pg.idmoduls = m.idmoduls AND pg.idperiodes_escolar = pe.idperiodes_escolars";

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
$i = 0;
while($row = mysql_fetch_assoc($rs)){  
    if ($row['revisat'] == "A") {
        if ($row['aprovat'] == "A") {
            $row['estat'] = "Aprovada";
        } else if ($row['aprovat'] == "E") {
            $row['estat'] = "Pendent d'aprovació";
        } else if ($row['aprovat'] == "D") {
            $row['estat'] = "Declinada";
        }
    } else {
        if ($row['revisat'] == "E") {
            $row['estat'] = "Pendent de revisió";
        } else if ($row['revisat'] == "G") {
            $row['estat'] = "No enviat";
        } else if ($row['revisat'] == "D") {
            $row['estat'] = "Declinada";
        }
    }
    $items[] = $row;
    
    $i++;
}   
    
echo json_encode($items);  

?>
