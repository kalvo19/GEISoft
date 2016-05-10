<?php

include('../bbdd/connect.php');
include_once('../func/generic.php');
include_once('../func/constants.php');
mysql_query("SET NAMES 'utf8'");

$items = array();

if (isset($_POST["idpla_estudi"])) {
    $sql = "SELECT p.Nom_plan_estudis FROM plans_estudis p WHERE p.idplans_estudis = " . $_POST["idpla_estudi"];
    $rs = mysql_query($sql);

    while($row = mysql_fetch_assoc($rs)){  
        $items[] = $row;
    }   
}

if (isset($_POST["idmodul"])) {
    $sql = "SELECT m.nom_modul FROM moduls m WHERE m.idmoduls = " . $_POST["idmodul"];
    
    $rs = mysql_query($sql);

    while($row = mysql_fetch_assoc($rs)){  
        $items[] = $row;
    }   
}

if (isset($_POST["idcurs"])) {
    $sql = "SELECT c.nom_curs FROM curs c WHERE c.idcurs = " . $_POST["idcurs"];
    
    $rs = mysql_query($sql);

    while($row = mysql_fetch_assoc($rs)){  
        $items[] = $row;
    }   
}  
    
echo json_encode($items);  

?>

