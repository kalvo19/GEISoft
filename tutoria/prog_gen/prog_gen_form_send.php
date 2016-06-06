<?php
include('../prog_gen/class/Programacio_General.php');
include('../bbdd/connect.php');
include('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

/*
 * Recull l'id d'una programació enviat per post i posteriorment utilitza la classe
 * 'Programacio_general' per canviar el seu atribut revisat. Si l'operació es realitza
 * correctament retorna 'true'
 */
if (isset($_POST["programacio"])){
    $programacioForm = json_decode($_POST["programacio"]);
    $correcte = Programacio_general::enviarProgramacio($programacioForm->idprogramacio_general);
    Programacio_General::inserirModificacio($programacioForm->modificacions);
    echo $correcte;       
}
