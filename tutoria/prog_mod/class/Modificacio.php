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
class Modificacio {
    //put your code here
    
    private $idmodificacions;
    private $versio;
    private $data_modificacio;
    private $descripcio;
    private $idprogramacio_general;
    private $idprogramacio_aula;
    
    function __construct($data_modificacio, $descripcio, $idprogramacio_general = null, $idprogramacio_aula = null) {
        $this->idmodificacions = $this->assignarIdmodificacio();
        $this->versio = $this->assignarVersio();
        $this->data_modificacio = $data_modificacio;
        $this->descripcio = $descripcio;
        $this->idprogramacio_general = $idprogramacio_general;
        $this->idprogramacio_aula = $idprogramacio_aula;
    }
    
    /**
     * Insereix una modificació pertanyent a una programació comuna o una programació d'aula.
     */
    function inserirModificacio() {
        $sql = "";
        
        if ($this->idprogramacio_general == null) {
            $sql = "INSERT INTO modificacions VALUES($this->idmodificacions, $this->versio, '$this->data_modificacio', '$this->descripcio', null, $this->idprogramacio_aula)";
        } else if ($this->idprogramacio_aula == null) {
            $sql = "INSERT INTO modificacions VALUES($this->idmodificacions, $this->versio, '$this->data_modificacio', '$this->descripcio', $this->idprogramacio_general, null)";
        }      
       
        mysql_query($sql);
    }
    
    function assignarIdmodificacio() {
        $sql = "SELECT MAX(idmodificacions) as 'maxim' FROM modificacions";
        $rs = mysql_query($sql);
        
        $idmodificacions = "";
        
        if ($rs) {
            while ($row = mysql_fetch_assoc($rs)) {
                $idmodificacions = $row["maxim"];
            }
        } else {
            $idmodificacions = 0;
        }
        
        return ++$idmodificacions;

    }
    
    function assignarVersio() {
        $sql = "SELECT MAX(versio) as 'versio' FROM modificacions WHERE idprogramacio_general = $this->idprogramacio_general";
        $rs = mysql_query($sql);
        
        $versio = "";
        
        if ($rs) {
            while ($row = mysql_fetch_assoc($rs)) {
                $versio = $row["versio"];
            }
        } else {
            $versio = 0;
        }
        
        return ++$versio;
    }
    
    static function eliminarModificacionsProgramacio($tipusProgramacio, $idprogramacio) {
        if ($tipusProgramacio == 1) {
            $sql = "DELETE FROM modificacions WHERE idprogramacio_general = $idprogramacio";
        } else if ($tipusProgramacio == 2) {
            $sql = "DELETE FROM modificacions WHERE idprogramacio_aula = $idprogramacio";
        }
        
        mysql_query($sql);
    }
    
    static function getModificacions($tipusProgramacio, $idprogramacio) {
        if ($tipusProgramacio == 1) {
            $sql = "SELECT * FROM modificacions WHERE idprogramacio_general = $idprogramacio";
        } else if ($tipusProgramacio == 2) {
            $sql = "SELECT * FROM modificacions WHERE idprogramacio_aula = $idprogramacio";
        }
        
        $items = array();
        $rs = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($rs)) {
            $items[] = $row;
        }
        
        return $items;
    }
    
}
