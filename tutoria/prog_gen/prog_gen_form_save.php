<?php
include_once('../prog_gen/class/Programacio_General.php');
include_once('../prog_mod/class/Modificacio.php');
include_once('../bbdd/connect.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

/*
 * Crea un objecte de la classe 'Programacio' amb els atributs passats com a paràmetre a través
 * del objecte 'programacio' en el post. Si l'objecte conté una id realitza una modificació a la
 * base de dades per canviar els valors ja existents amb la funció 'modificarProgramacio', en cas
 * contrari realitza els inserts pertinents amb la funció 'guardarProgramacio'. Retorna l'objecte
 * en format json.
 */
if (isset($_POST["programacio"])){
    $programacioForm = json_decode($_POST["programacio"]);
    $programacio = new Programacio_General($programacioForm->idprogramacio_general,$programacioForm->nomDocument,date("Y-m-d"),
    $programacioForm->estrategies,$programacioForm->recursos,$programacioForm->revisat,null,null,
    $programacioForm->idprofessors,$programacioForm->idmoduls,$programacioForm->idcurs,null,null); 
    
    if ($programacioForm->idprogramacio_general != null){
        
        $programacio->modificarProgramacio(); 
        
        if ($programacioForm->enviar == true) {
            if(property_exists($programacioForm, "modificacions")) {
              $novaModificacio = new Modificacio(date("Y-m-d"), $programacioForm->modificacions, $programacioForm->idprogramacio_general, null);
              $novaModificacio->inserirModificacio($programacioForm->modificacions);
            }
        } 
            
    } else{
        $programacio->guardarProgramacio();
        
        $novaModificacio = new Modificacio(date("Y-m-d"), "Creació del document.", $programacio->getIdprogramacio_general(), null);
        $novaModificacio->inserirModificacio();
    }
    
    echo $programacio->to_json();
   
}

