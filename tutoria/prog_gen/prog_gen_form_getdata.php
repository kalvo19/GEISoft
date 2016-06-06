<?php

include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

/*
 * Retorana el nom de les unitats formatives, les seves hores i les hores de lliure disposició
 * de les unitats formatives d'un mòdul concret en un curs determinat
 */
if (isset($_POST["peticio"])) {
    $peticio = json_decode($_POST["peticio"]);
    if (isset($peticio->unitats_formatives)) {
        $idmodul = $peticio->idmoduls;
        $idcurs = $peticio->idcurs;
        $sql = ("SELECT idunitats_formatives, nom_uf, hores, horeslliuredisposicio FROM unitats_formatives 
                WHERE idunitats_formatives IN(SELECT id_mat_uf_pla FROM grups_materies
                WHERE id_grups IN (SELECT idgrups FROM grups WHERE idcurs = $idcurs)) 
                AND idunitats_formatives IN(SELECT id_ufs FROM moduls_ufs WHERE id_moduls=$idmodul)");

        $rs = mysql_query($sql);
        $result = array();
        while($row = mysql_fetch_object($rs)){
            array_push($result, $row);
        } 
    }
    echo json_encode($result);
    mysql_free_result($rs);
    mysql_close(); 
}

/**
 * Comprova que la hora de lliure disposicio
 */
if (isset($_POST["gestorHores"])) {
    $gestorHores = json_decode($_POST['gestorHores']);
    $sql = "SELECT m.horeslliuredisposicio FROM moduls m WHERE m.idmoduls = $gestorHores->idmoduls";

    $rs = mysql_query($sql);
    $horesMax = "";
    while($row = mysql_fetch_object($rs)){
        $horesMax = $row->horeslliuredisposicio;
    } 
    
    if ($horesMax >= $gestorHores->totals) {
        for ($i = 0; $i < count($gestorHores->hores); $i++) {
            $sql = "UPDATE unitats_formatives SET horeslliuredisposicio = " . $gestorHores->hores[$i] . " WHERE idunitats_formatives "
            . "= " . $gestorHores->id[$i];
            
            mysql_query($sql);
        }
    }
}

/*
 * Retorna tots els camps d'una programacio pasada per post de la base de dades que utilitza el formulari
 */
if (isset($_POST["programacio"])) {
    $idprogramacio = json_decode($_POST["programacio"]);
    $sql = ("SELECT * FROM programacions_general"
            . " WHERE idprogramacio_general = $idprogramacio->idprogramacio_general");
    
    $rs = mysql_query($sql);
    $result = array();
    while($row = mysql_fetch_object($rs)){
        array_push($result, $row);
    } 
    echo json_encode($result);
    mysql_free_result($rs);
    mysql_close(); 
}

/*
 * Retorna el id del modul i del curs d'una programació determinada pasada per post.
 */
if (isset($_POST["cursmodul"])) {
    $idprogramacio = json_decode($_POST["cursmodul"]);
    $sql = ("SELECT idmoduls, idcurs FROM programacions_general"
            . " WHERE idprogramacio_general = $idprogramacio->idprogramacio_general");
    
    $rs = mysql_query($sql);
    $result = array();
    while($row = mysql_fetch_object($rs)){
        array_push($result, $row);
    } 
    
    echo json_encode($result);
    mysql_free_result($rs);
    mysql_close(); 
}

 

