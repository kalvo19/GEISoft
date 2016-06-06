<?php

/**
 * Description of Resultats_Aprenentatge
 *
 * @author kalvo19
 */

class Resultats_Aprenentatge {
    
    private $idresultats_aprenentatge;
    private $codi_ra;
    private $descripcio;
    private $idunitats_formatives;
    
    function __construct($idresultats_aprenentatge, $codi_ra, $descripcio, $idunitats_formatives) {
        if ($idresultats_aprenentatge != null) {
            $this->idresultats_aprenentatge = $idresultats_aprenentatge;
        } else {
            $this->idresultats_aprenentatge = $this->assignarIdResultats_aprenentatge();
        }
        $this->codi_ra = $codi_ra;
        $this->descripcio = $descripcio;
        $this->idunitats_formatives = $idunitats_formatives;
    }
    
    /**
     * Assigna al atribut '$this->idresultats_aprenentatge' un nou identificador per a poder inserir-lo a la base 
     * de dades.
     * 
     * @return Integer
     */
    function assignarIdResultats_aprenentatge() {
        $sql = "SELECT MAX(idresultats_aprenentatge) as 'maxim' FROM resultats_aprenentatge";
        $rs = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($rs)) {
            $idresultats_aprenentatge = $row;
        }
        
        if ($idresultats_aprenentatge["maxim"] == null) {
            $idresultats_aprenentatge["maxim"] = 0;
        }
        
        mysql_free_result($rs);
        mysql_close();
        
        return ++$idresultats_aprenentatge["maxim"];
    }
    
    function getIdresultats_aprenentatge() {
        return $this->idresultats_aprenentatge;
    }

    function getCodi_ra() {
        return $this->codi_ra;
    }

    function getDescripcio() {
        return $this->descripcio;
    }

    function getIdunitats_formatives() {
        return $this->idunitats_formatives;
    }

    function setIdresultats_aprenentatge($idresultats_aprenentatge) {
        $this->idresultats_aprenentatge = $idresultats_aprenentatge;
    }

    function setCodi_ra($codi_ra) {
        $this->codi_ra = $codi_ra;
    }

    function setDescripcio($descripcio) {
        $this->descripcio = $descripcio;
    }

    function setIdunitats_formatives($idunitats_formatives) {
        $this->idunitats_formatives = $idunitats_formatives;
    }
    
    /*
     * Recull informació de tots els resultats d'aprenentatge que correspongui amb el identificador de la unitat 
     * formativa passada com a paràmetre.
     * 
     * @param (String) $unitat_formativa 
     */
    static function getResultatsAprenetatge($unitat_formativa) {
        $sql = "SELECT * FROM resultats_aprenentatge WHERE idunitats_formatives = $unitat_formativa";
        $items = array();
        
        $rs = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($rs)) {
            $items[] = $row;
        }
        
        return $items;
    }
    
}
