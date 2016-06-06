<?php

/**
 * Descripció of Modificacions
 * 
 * Classe que conté els atributs i mètodes necessaris per a gestionar les modificacions que es fan de les 
 * diferents programacions guardades.
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
        $this->data_modificacio = $data_modificacio;
        $this->descripcio = $descripcio;
        $this->idprogramacio_general = $idprogramacio_general;
        $this->idprogramacio_aula = $idprogramacio_aula;
        $this->versio = $this->assignarVersio();
    }
    
    /**
     * Insereix una modificació pertanyent a una programació comuna o una programació d'aula.
     */
    function inserirModificacio() {
        $sql = "";
        $this->descripcio = str_replace("'", "´", $this->descripcio);
        
        if (empty($this->idprogramacio_general)) {
            $sql = "INSERT INTO modificacions VALUES($this->idmodificacions, $this->versio, '$this->data_modificacio', '$this->descripcio', null, $this->idprogramacio_aula)";
        } else if (empty($this->idprogramacio_aula)) {
            $sql = "INSERT INTO modificacions VALUES($this->idmodificacions, $this->versio, '$this->data_modificacio', '$this->descripcio', $this->idprogramacio_general, null)";
        }      
        
        mysql_query($sql);
    }
    
    /**
     * Assigna al atribut '$this->idmodificacions' un nou identificador per a poder inserir-lo a la base 
     * de dades.
     * 
     * @return Integer
     */
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
    
    /**
     * Comprova la versió més recent de la programació que hi ha a l'atribut del objecte '$this->idprogramacio_general', i 
     * incrementa el valor obtingut per a que pugui ser inserida a la base de dades sense crear conflictes.
     * 
     * @return Integer
     */
    function assignarVersio() {
        $sql = "SELECT MAX(versio) as 'versio' FROM modificacions WHERE idprogramacio_general = $this->idprogramacio_general";
        $rs = mysql_query($sql);
        
        $versio = "";
        
        if (!$rs) {
            $versio = 0;
        } else {
            while ($row = mysql_fetch_assoc($rs)) {
                $versio = $row["versio"];
            }
        }
        
        return ++$versio;
    }
    
    /**
     * Elimina totes les modificacions registrades corresponents a la programació passada com a paràmetre.
     * 
     * @param Integer $tipusProgramacio
     * @param Integer $idprogramacio
     */
    static function eliminarModificacionsProgramacio($tipusProgramacio, $idprogramacio) {
        if ($tipusProgramacio == 1) {
            $sql = "DELETE FROM modificacions WHERE idprogramacio_general = $idprogramacio";
        } else if ($tipusProgramacio == 2) {
            $sql = "DELETE FROM modificacions WHERE idprogramacio_aula = $idprogramacio";
        }
        
        mysql_query($sql);
    }
    
    /**
     * Obté tota la informació de totes les modificacions que s'han realitzat sobre la programació passada com a 
     * paràmetre. S'utilitzarà per a omplir el document que es generà a la classe 'Document'.
     * 
     * @param Integer $tipusProgramacio
     * @param Integer $idprogramacio
     * @return array
     */
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
