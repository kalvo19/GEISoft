<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Modificacions
 *
 * @author kalvo19
 */
class Modificacions {
    //put your code here
    
    private $idmodificacions;
    private $versio;
    private $data_modificacio;
    private $descripcio;
    private $idprogramacio_general;
    private $idprogramacio_aula;
    
    function __construct($idmodificacions, $versio, $data_modificacio, $descripcio, $idprogramacio_general = null, $idprogramacio_aula = null) {
        $this->idmodificacions = $idmodificacions;
        $this->versio = $versio;
        $this->data_modificacio = $data_modificacio;
        $this->descripcio = $descripcio;
        $this->idprogramacio_general = $idprogramacio_general;
        $this->idprogramacio_aula = $idprogramacio_aula;
    }
    
    /**
     * Insereix una modificació pertanyent a una programació comuna o una programació d'aula.
     */
    function inserirModificacio() {
        $sql = "INSERT INTO modificacions VALUES($this->idmodificacions, $this->versio, $this->data_modificacio, '$this->descripcio', $this->idprogramacio_general, $this->idprogramacio_aula)";
        mysql_query($sql);
    }
    
    /**
     * 
     * @param intetger $tipusProgramacio
     * @param integer $idprogramacio
     * 
     * Elimina totes les modificacions de la programació passada com a paràmetre.
     */
    static function eliminarModificacionsProgramacio($tipusProgramacio, $idprogramacio) {
        if ($tipusProgramacio == 1) {
            $sql = "DELETE FROM modificacions WHERE idprogramacio_general = $idprogramacio";
        } else if ($tipusProgramacio == 2) {
            $sql = "DELETE FROM modificacions WHERE idprogramacio_aula = $idprogramacio";
        }
        
        mysql_query($sql);
    }
    
}
