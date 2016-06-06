<?php

/**
 * Description of Criteris_Avaluacio
 *
 * @author kalvo19
 */

class Criteris_Avaluacio {
   
    private $idcriteris_avaluacio;
    private $codi_ca;
    private $descripcio;
    private $idresultats_aprenentatge;
    
    function __construct($idcriteris_avaluacio, $codi_ca, $descripcio, $idresultats_aprenentatge) {
        if ($idresultats_aprenentatge != null) {
            $this->idcriteris_avaluacio = $idcriteris_avaluacio;
        } else {
            $this->idcontinguts_ra = $this->assignarIdCriteris_avaluacio();
        }
        $this->codi_ca = $codi_ca;
        $this->descripcio = $descripcio;
        $this->idresultats_aprenentatge = $idresultats_aprenentatge;
    }
    
    function getIdcriteris_avaluacio() {
        return $this->idcriteris_avaluacio;
    }

    function getCodi_ca() {
        return $this->codi_ca;
    }

    function getDescripcio() {
        return $this->descripcio;
    }

    function getIdresultats_aprenentatge() {
        return $this->idresultats_aprenentatge;
    }

    function setIdcriteris_avaluacio($idcriteris_avaluacio) {
        $this->idcriteris_avaluacio = $idcriteris_avaluacio;
    }

    function setCodi_ca($codi_ca) {
        $this->codi_ca = $codi_ca;
    }

    function setDescripcio($descripcio) {
        $this->descripcio = $descripcio;
    }

    function setIdresultats_aprenentatge($idresultats_aprenentatge) {
        $this->idresultats_aprenentatge = $idresultats_aprenentatge;
    }
    
    /**
     * Assigna al atribut '$this->idcriteris_avaluacio' un nou identificador per a poder inserir-lo a la base 
     * de dades.
     * 
     * @return Integer
     */
     function assignarIdCriteris_avaluacio() {
        $sql = "SELECT MAX(idcriteris_avaluacio) as 'maxim' FROM criteris_avaluacio";
        $rs = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($rs)) {
            $idcriteris_avaluacio = $row;
        }
        
        if ($idcriteris_avaluacio["maxim"] == null) {
            $idcriteris_avaluacio["maxim"] = 0;
        }
        
        mysql_free_result($rs);
        mysql_close();
        
        return ++$idcriteris_avaluacio["maxim"];
    }
    
    /**
     * Recull tota la informació de tots els criteris d'avaluació que corresponen al resultat aprenentatge que tenen
     * un identificador igual al passat com a paràmetre.
     * 
     * @param String $resultats_aprenentatge
     * @return array
     */
    function getCriterisAvaluacio($resultats_aprenentatge) {
        $sql = "SELECT * FROM criteris_avaluacio WHERE idresultats_aprenentatge = $resultats_aprenentatge";
        $items = array();
        
        $rs = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($rs)) {
            $items[] = $row;
        }
        
        return $items;
    }
    
}
