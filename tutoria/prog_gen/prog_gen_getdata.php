<?php

include('../bbdd/connect.php');
include_once('../func/generic.php');
include_once('../func/constants.php');
mysql_query("SET NAMES 'utf8'");

$idprofessors = $_POST['idprofessors'];

/**
 * Selecciona totes les programacions generals realitzades per al professor en actiu.
 */
$sql = "SELECT pg.idprogramacio_general, pg.nom_document, c.nom_curs, pg.data_creacio, pe.Nom, pg.aprovat FROM programacions_general pg, moduls m, periodes_escolars pe, "
       . "curs c WHERE pg.idprofessors = $idprofessors AND pg.idcurs = c.idcurs AND pg.idmoduls = m.idmoduls AND "
       . "pg.idperiodes_escolar = pe.idperiodes_escolars";

/**
 * Afegeix un filtre de cerca sobre les programacions que es corresponguin amb el mòdul seleccionat.
 */
if (!empty($_POST['idmoduls'])) {
    $idplans_estudis = $_POST['idplans_estudis'];
    $idmoduls = $_POST['idmoduls'];
    
    $where = " AND m.idmoduls = $idmoduls AND m.idplans_estudis = $idplans_estudis";
    $sql .= $where;
}

/**
 * Afegeix un filtre de cerca sobre les programacions que es corresponguin amb el curs seleccionat.
 */
if (!empty($_REQUEST['idcurs'])) {
    $idcurs = $_REQUEST['idcurs'];
    $where .= " AND c.idcurs = $idcurs";
    
    $sql .= $where;
}

$result = array();

$rs = mysql_query($sql);

$items = array();
$i = 0;
while($row = mysql_fetch_assoc($rs)){  
    $items[] = $row;
    if ($row['aprovat'] == null) {
        $items[$i]['aprovat'] = 'Pendent';
    } else {
        if ($row['aprovat'] == 'S') {
            $items[$i]['aprovat'] = 'Aprovat';
        } 
        
        if ($row['aprovat'] == 'N') {
            $items[$i]['aprovat'] = 'Declinat';
        }
    }
    
    $i++;
}   
    
    echo json_encode($items);  



?>
