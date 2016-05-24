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
    private $professorRevisio;
    private $professorAprovacio;
    
    function __construct($idprogramacio_general, $nom_document = null, $data_creacio = null, $estrategies_metodologies = null, $recursos = null, 
    $revisat = null, $aprovat = null, $idperiodes_escolars = null, $idprofessors = null, $idmoduls = null, $idcurs = null, 
    $professorRevisio = null, $professorAprovacio = null) {
        if ($idprogramacio_general == null) {
            $this->idprogramacio_general = $this->assignarIdprogramacio();
        } else {
            $this->idprogramacio_general = $idprogramacio_general;
        }
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
        $this->professorRevisio = $professorRevisio;
        $this->professorAprovacio = $professorAprovacio;
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
    
    function getProfessorRevisio() {
        return $this->professorRevisio;
    }

    function getProfessorAprovacio() {
        return $this->professorAprovacio;
    }

    function setProfessorRevisio($professorRevisio) {
        $this->professorRevisio = $professorRevisio;
    }

    function setProfessorAprovacio($professorAprovacio) {
        $this->professorAprovacio = $professorAprovacio;
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
    
    function assignarIdprogramacio() {
        $sql = "SELECT MAX(idprogramacio_general) as 'maxim' FROM programacions_general";
        $rs = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($rs)) {
            $idprogramacio = $row;
        }
        
        if ($idprogramacio["maxim"] == null) {
            $idprogramacio["maxim"] = 0;
        }
        
        return ++$idprogramacio["maxim"];
    }
    
    function guardarProgramacio() {
        $this->importarProgramacio();
        
        $sql = "INSERT INTO programacions_general VALUES ($this->idprogramacio_general, '$this->nom_document', "
        . "'$this->data_creacio', '$this->estrategies_metodologies', '$this->recursos', '$this->revisat', '$this->aprovat', "
        . "$this->idperiodes_escolars, $this->idprofessors, $this->idmoduls, $this->idcurs, null, null)";
        
        mysql_query($sql);
        
        $this->inserirModificacio();
    }
    
    static function existeixProgramacio($idmoduls, $idcurs) {
        $sql = "SELECT pg.idprogramacio_general FROM moduls m, curs c, programacions_general pg WHERE pg.idmoduls = m.idmoduls "
        . "AND pg.idcurs = c.idcurs AND pg.idmoduls = $idmoduls AND pg.idcurs = $idcurs AND pg.idperiodes_escolar"
        . " = (SELECT idperiodes_escolars FROM periodes_escolars WHERE actual = 'S')";
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
    
    static function eliminarProgramacio($idprogramacio_general) {
        $sql = "DELETE FROM programacions_general WHERE idprogramacio_general = $idprogramacio_general";
        mysql_query($sql);
    }
    
    function importarProgramacio() {
        $sql = "SELECT FROM periodes_escolars WHERE actual = 'S'";
        $items = getCursActual();
        
        $this->idperiodes_escolars = $items->idperiodes_escolars;
    }
    
    function inserirModificacio($descripcio = null) {
        if ($descripcio == null) {
            $descripcio = "Creació del document.";
        }
        
        $novaModificacio = new Modificacio($this->data_creacio, $descripcio, $this->idprogramacio_general, null);
        $novaModificacio->inserirModificacio();
        
    }
    
    static function getInformacioPortada($idmodul, $idcurs, $idperiodes_escolar) {
        $sql = "SELECT pe.Nom_plan_estudis, m.nom_modul, c.nom_curs, m.hores_finals, m.horeslliuredisposicio, p.Nom 
        FROM plans_estudis pe, moduls m, curs c, periodes_escolars p WHERE pe.idplans_estudis = m.idplans_estudis 
        AND m.idmoduls = $idmodul AND c.idcurs = $idcurs AND p.idperiodes_escolars = $idperiodes_escolar";
        
        $items = array();
        $rs = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($rs)) {
            $items[] = $row;
        }
        
        return $items;
    }
    
    static function getUFSModulCurs($idmodul, $idcurs) {
        $sql = "SELECT nom_uf, hores, horeslliuredisposicio FROM unitats_formatives WHERE idunitats_formatives IN (SELECT "
        . " id_mat_uf_pla FROM grups_materies WHERE id_grups IN (SELECT idgrups FROM grups WHERE idcurs = $idcurs) AND "
        . "idunitats_formatives IN (SELECT id_ufs FROM moduls_ufs WHERE id_moduls = $idmodul))";
        
        $items = array();
        $rs = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($rs)) {
            $items[] = $row;
        }
        
        return $items;
    }
    
    static function comprovarEstat($idprogramacio_general, $camp, $valor) {
        $sql = "SELECT pg.$camp FROM programacions_general pg WHERE pg.idprogramacio_general = $idprogramacio_general";
        $items = array();

        $rs = mysql_query($sql);

        while ($row = mysql_fetch_assoc($rs)) {
            $items[] = $row;
        }
        
        if ($items[0][$camp] != $valor) {
            return false;
        }
        
        return true;
    }
    
    static function enviarProgramacio($idprogramacio_general) {
        $correcte = Programacio_General::comprovarEstat($idprogramacio_general, "revisat", "G");
        
        if ($correcte) {
            Programacio_General::canviarEstat("E", "revisat", $idprogramacio_general);
        }
        
        return $correcte;
    }
    
    static function enviarRevisio($idprogramacio_general, $aprovat) {
        $correcte = comprovarEstat($idprogramacio_general, "revisat", "E");
        
        if ($correcte) {
            if ($aprovat) {
                $this->canviarEstat("S", "revisat", $idprogramacio_general);
            } else {
                $this->canviarEstat("N", "revisat", $idprogramacio_general);
            } 
        }
    }
    
    static function canviarEstat($estat, $camp, $idprogramacio_general) {
        $sql = "UPDATE programacions_general SET $camp = '$estat' WHERE idprogramacio_general = $idprogramacio_general";
        
        mysql_query($sql);
    }
    
}
