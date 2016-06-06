<?php

/**
 * Description of Continguts_RA
 *
 * @author kalvo19
 */

class Continguts_RA {
    private $idcontinguts_ra;
    private $codi_continguts;
    private $descripcio;
    private $idresultats_aprenentatge;
    
    function __construct($idcontinguts_ra, $codi_continguts, $descripcio, $idresultats_aprenentatge) {
        if ($idresultats_aprenentatge != null) {
            $this->idconitnguts_ra = $idcontinguts_ra;
        } else {
            $this->idcontinguts_ra = $this->assignarIdContinguts_ra();
        }
        $this->codi_continguts = $codi_continguts;
        $this->descripcio = $descripcio;
        $this->idresultats_aprenentatge = $idresultats_aprenentatge;
    }

    function getIdcontinguts_ra() {
        return $this->idcontinguts_ra;
    }

    function getCodi_continguts() {
        return $this->codi_continguts;
    }

    function getDescripcio() {
        return $this->descripcio;
    }

    function getIdresultats_aprenentatge() {
        return $this->idresultats_aprenentatge;
    }

    function setIdcontinguts_ra($idcontinguts_ra) {
        $this->idcontinguts_ra = $idcontinguts_ra;
    }

    function setCodi_continguts($codi_continguts) {
        $this->codi_continguts = $codi_continguts;
    }

    function setDescripcio($descripcio) {
        $this->descripcio = $descripcio;
    }

    function setIdresultats_aprenentatge($idresultats_aprenentatge) {
        $this->idresultats_aprenentatge = $idresultats_aprenentatge;
    }
    
    /**
     * Assigna al atribut '$this->idcontinguts_ra' un nou identificador per a poder inserir-lo a la base 
     * de dades.
     * 
     * @return Integer
     */
    function assignarIdContinguts_ra() {
        $sql = "SELECT MAX(idcontinguts_ra) as 'maxim' FROM continguts_ra";
        $rs = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($rs)) {
            $idcontinguts_ra = $row;
        }
        
        if ($idcontinguts_ra["maxim"] == null) {
            $idcontinguts_ra["maxim"] = 0;
        }
        
        mysql_free_result($rs);
        mysql_close();
        
        return ++$idcontinguts_ra["maxim"];
    }
    
    /**
     * Recull tota la informació de tots els continguts del resultat aprenentatge que té el identificador passat com a 
     * paràmetre.
     * 
     * @param String $resultats_aprenentatge
     * @return array
     */
    static function getContingutsRA($resultats_aprenentatge) {
        $sql = "SELECT * FROM continguts_ra WHERE idresultats_aprenentatge = $resultats_aprenentatge";
        $items = array();
        
        $rs = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($rs)) {
            $items[] = $row;
        }
        
        return $items;
    }

}
