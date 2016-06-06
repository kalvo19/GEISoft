<?php

include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

if (isset($_POST['idmoduls'])) {
    $idmoduls = $_POST['idmoduls'];
    $sql = "SELECT m.horeslliuredisposicio AS horeslld_mod, SUM(uf.horeslliuredisposicio) AS horeslld_uf FROM moduls m, unitats_formatives uf, moduls_ufs "
    . "mu WHERE m.idmoduls = $idmoduls AND m.idmoduls = mu.id_moduls AND uf.idunitats_formatives = mu.id_ufs";
    
    $rs = mysql_query($sql);
    $result = array();
    
    while($row = mysql_fetch_object($rs)){
        array_push($result, $row);
    } 
    
    echo json_encode($result);
}