<?php

/**
 * Descripció of Programacio_Aula
 * 
 * Classe que conté els atributs i mètodes necessaris per a la gestió de les programacions d'aula
 * dels diferents professors que els crean.
 *
 * @author kalvo19
 */

class Programacio_Aula {
    private $idprogramacio_aula;
    private $nom_document;
    private $data_creacio;
    private $revisat;
    private $aprovat;
    private $idprofessors;
    private $idprogramacio_general;
    private $professorRevisio;
    private $professorAprovacio;
    
    function __construct($nom_document, $data_creacio, $revisat, $aprovat, $idprofessors, $idprogramacio_general, $professorRevisio, $professorAprovacio) {
        $this->idprogramacio_aula = $this->assignarIdprogramacio();
        $this->nom_document = $nom_document;
        $this->data_creacio = $data_creacio;
        $this->revisat = $revisat;
        $this->aprovat = $aprovat;
        $this->idprofessors = $idprofessors;
        $this->idprogramacio_general = $idprogramacio_general;
        $this->professorRevisio = $professorRevisio;
        $this->professorAprovacio = $professorAprovacio;
    }
    
    function getIdprogramacio_aula() {
        return $this->idprogramacio_aula;
    }

    function getNom_document() {
        return $this->nom_document;
    }

    function getData_creacio() {
        return $this->data_creacio;
    }

    function getRevisat() {
        return $this->revisat;
    }

    function getAprovat() {
        return $this->aprovat;
    }

    function getIdprofessors() {
        return $this->idprofessors;
    }

    function getIdprogramacio_general() {
        return $this->idprogramacio_general;
    }

    function getProfessorRevisio() {
        return $this->professorRevisio;
    }

    function getProfessorAprovacio() {
        return $this->professorAprovacio;
    }

    function setIdprogramacio_aula($idprogramacio_aula) {
        $this->idprogramacio_aula = $idprogramacio_aula;
    }

    function setNom_document($nom_document) {
        $this->nom_document = $nom_document;
    }

    function setData_creacio($data_creacio) {
        $this->data_creacio = $data_creacio;
    }

    function setRevisat($revisat) {
        $this->revisat = $revisat;
    }

    function setAprovat($aprovat) {
        $this->aprovat = $aprovat;
    }

    function setIdprofessors($idprofessors) {
        $this->idprofessors = $idprofessors;
    }

    function setIdprogramacio_general($idprogramacio_general) {
        $this->idprogramacio_general = $idprogramacio_general;
    }

    function setProfessorRevisio($professorRevisio) {
        $this->professorRevisio = $professorRevisio;
    }

    function setProfessorAprovacio($professorAprovacio) {
        $this->professorAprovacio = $professorAprovacio;
    }

        
    function assignarIdprogramacio() {
        $sql = "SELECT MAX(idprogramacio_aula) as 'maxim' FROM programacions_aula";
        $rs = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($rs)) {
            $idprogramacio = $row;
        }
        
        if ($idprogramacio["maxim"] == null) {
            $idprogramacio["maxim"] = 0;
        }
        
        return ++$idprogramacio["maxim"];
    }

}
