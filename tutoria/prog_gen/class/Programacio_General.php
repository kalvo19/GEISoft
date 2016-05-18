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
    
    function getIdprogramacio_general() {
        return $this->idprogramacio_general;
    }

    function getNom_document() {
        return $this->nom_document;
    }

    function getData_creacio() {
        return $this->data_creacio;
    }

    function getEstrategies_metodologies() {
        return $this->estrategies_metodologies;
    }

    function getRecursos() {
        return $this->recursos;
    }

    function getRevisat() {
        return $this->revisat;
    }

    function getAprovat() {
        return $this->aprovat;
    }

    function getIdperiodes_escolars() {
        return $this->idperiodes_escolars;
    }

    function getIdprofessors() {
        return $this->idprofessors;
    }

    function getIdmoduls() {
        return $this->idmoduls;
    }

    function getIdcurs() {
        return $this->idcurs;
    }

    function setIdprogramacio_general($idprogramacio_general) {
        $this->idprogramacio_general = $idprogramacio_general;
    }

    function setNom_document($nom_document) {
        $this->nom_document = $nom_document;
    }

    function setData_creacio($data_creacio) {
        $this->data_creacio = $data_creacio;
    }

    function setEstrategies_metodologies($estrategies_metodologies) {
        $this->estrategies_metodologies = $estrategies_metodologies;
    }

    function setRecursos($recursos) {
        $this->recursos = $recursos;
    }

    function setRevisat($revisat) {
        $this->revisat = $revisat;
    }

    function setAprovat($aprovat) {
        $this->aprovat = $aprovat;
    }

    function setIdperiodes_escolars($idperiodes_escolars) {
        $this->idperiodes_escolars = $idperiodes_escolars;
    }

    function setIdprofessors($idprofessors) {
        $this->idprofessors = $idprofessors;
    }

    function setIdmoduls($idmoduls) {
        $this->idmoduls = $idmoduls;
    }

    function setIdcurs($idcurs) {
        $this->idcurs = $idcurs;
    }
    
    function guardarProgramacio() {
        $this->aprovat = "G";
        $this->revisat = "G";
        $this->data_creacio = date("Y-m-d");
        
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
        . "'$this->data_creacio', '$this->estrategies_metodologies', '$this->recursos', '$this->revisat', '$this->aprovat', "
        . "$this->idperiodes_escolars, $this->idprofessors, $this->idmoduls, $this->idcurs)";
        mysql_query($sql);
        
        $this->inserirModificacio();
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
    
    /**
     * 
     * @param integer $idprogramacio_general
     * 
     * Elimina la programacio comuna passada com a paràmetre.
     */
    function eliminarProgramacio($idprogramacio_general) {
        $sql = "DELETE FROM programacions_general WHERE idprogramacio_general = $idprogramacio_general";
        mysql_query($sql);
    }
    
    /**
     * 
     * @param String $nom_document
     * 
     * Duplica una programacio comuna i la importa al curs actual.
     */
    function importarProgramacio($nom_document) {
        $this->nom_document = $nom_document;
        
        $sql = "SELECT  FROM periodes_escolars WHERE actual = 'S'";
        $items = getCursActual();
        
        $this->idperiodes_escolars = $items->idperiodes_escolars;
    }
    
    function inserirModificacio($descripcio = null) {
        $idmodificacions = "";
        $versio = "";
        
        $sql = "SELECT MAX(idmodificacions) as 'maxim' FROM modificacions";
        $rs = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($rs)) {
            $idmodificacions = $row["maxim"];
        }
        
        $sql = "SELECT MAX(versio) as 'versio' FROM modificacions WHERE idprogramacio_general = $this->idprogramacio_general";
        $rs = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($rs)) {
            $versio = $row["versio"];
        }
        
        if ($idmodificacions == null) {
            $idmodificacions = 1;
        } else {
            $idmodificacions++;
        }
        
        if ($versio == null) {
            $versio = 1;
        }
        
        if ($descripcio == null) {
            $descripcio = "Creació del document.";
        }
        
        $novaModificacio = new Modificacions($idmodificacions, $versio, $this->data_creacio, $descripcio, $this->idprogramacio_general, "null");
        $novaModificacio->inserirModificacio();
        
    }
    
}
