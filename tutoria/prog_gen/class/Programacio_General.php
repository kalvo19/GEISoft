<?php

/**
 * Descripció de Programacio_General
 * 
 * Classe que conté els atributs i mètodes necessaris per a la gestió de les programacions comunes
 * dels diferents mòduls que hi han registrats. 
 *
 * @author kalvo19
 */
class Programacio_General {
    
    private $idprogramacio_general;
    private $nom_document;
    private $data_creacio;
    private $estrategies_metodologies;
    private $recursos;
    private $revisat;
    private $aprovat;
    private $idperiodes_escolars;
    private $idprofessors;
    private $idmoduls;
    private $idcurs;
    
    function __construct($idprogramacio_general = null, $nom_document = null, $data_creacio = null, $estrategies_metodologies = null, $recursos = null, $revisat = null, $aprovat = null, $idperiodes_escolars = null, $idprofessors = null, $idmoduls = null, $idcurs = null) {
        $this->idprogramacio_general = $idprogramacio_general;
        $this->nom_document = $nom_document;
        $this->data_creacio = $data_creacio;
        $this->estrategies_metodologies = $estrategies_metodologies;
        $this->recursos = $recursos;
        $this->revisat = $revisat;
        $this->aprovat = $aprovat;
        $this->idperiodes_escolars = $idperiodes_escolars;
        $this->idprofessors = $idprofessors;
        $this->idmoduls = $idmoduls;
        $this->idcurs = $idcurs;
    }
    
    function guardarProgramacio() {
        $this->aprovat = "G";
        $this->revisat = "G";
        $this->data_creacio = "CURRENT_DATE";
        
        $sql = "SELECT MAX(idprogramacio_general) as 'maxim' FROM programacions_general";
        $rs = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($rs)) {
            $idprogramacio = $row;
        }
        
        $this->idprogramacio_general = ++$idprogramacio["maxim"];
        
        if ($this->idprogramacio_general == null) {
            $this->idprogramacio_general = 1;
        }
        
        $sql = "INSERT INTO programacions_general VALUES ($this->idprogramacio_general, '$this->nom_document', "
        . "$this->data_creacio, '$this->estrategies_metodologies', '$this->recursos', '$this->revisat', '$this->aprovat', "
        . "$this->idperiodes_escolars, $this->idprofessors, $this->idmoduls, $this->idcurs)";
        mysql_query($sql);
    }
    
    function existeixProgramacio() {
        $sql = "SELECT pg.idprogramacio_general FROM programacions_general pg, moduls m, periodes_escolars pe, curs c "
        . "WHERE pg.idperiodes_escolar = pe.idperiodes_escolars AND pe.actual = 'S' AND pg.idmoduls = $this->idmoduls AND pg.idmoduls = "
        . "m.idmoduls AND pg.idcurs = $this->idcurs AND pg.idcurs = c.idcurs";
        $rs = mysql_query($sql);
        $items = Array();
        
        while ($row = mysql_fetch_assoc($rs)) {
            $items[] = $row;
        }
        
        if (count($items) > 0) {
            $items = false;
        } else {
            $items = true;
        }
        
        return $items;
    }
    
    function importarProgramacio($nom_document) {
        $this->nom_document = $nom_document;
        
        $sql = "SELECT idperiodes_escolars FROM periodes_escolars WHERE actual = 'S'";
        $rs = mysql_query($sql);
        $items = Array();
        
        while ($row = mysql_fetch_assoc($rs)) {
            $items[] = $row;
        }
        
        $this->idperiodes_escolars = $items[0]["idperiodes_escolars"];
    }
    
}
