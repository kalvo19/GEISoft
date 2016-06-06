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
    
    /**
     * Assigna al atribut '$this->idprogramacio_general' una nou identificador per a poder inserir-lo a la base 
     * de dades.
     * 
     * @return array
     */
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
    
    /**
     * Amb la funció 'importarProgramacio()' seteja l'atribut '$this->idperiode_escolar' amb la id del periode 
     * escolar actual i seguidament guarda la programació a la base de dades amb les dades que hi han als atributs.
     */
    function guardarProgramacio() {
        $this->importarProgramacio();
        
        $sql = "INSERT INTO programacions_general VALUES ($this->idprogramacio_general, '$this->nom_document', "
        . "'$this->data_creacio', '$this->estrategies_metodologies', '$this->recursos', '$this->revisat', '$this->aprovat', "
        . "$this->idperiodes_escolars, $this->idprofessors, $this->idmoduls, $this->idcurs, null, null)";
        
        mysql_query($sql);
      
    }
    
    /**
     * Modifica tots els camps de la programació que correspont al identificador que té l'atribut 
     * '$this->idprogramacio_general' amb les dades dels atributs del objecte.
     */
    function modificarProgramacio(){
        $this->importarProgramacio();
        $sql = ("UPDATE programacions_general SET nom_document='$this->nom_document',estrategies_metodologies='$this->estrategies_metodologies',"
        . " recursos='$this->recursos', revisat='$this->revisat' WHERE idprogramacio_general=$this->idprogramacio_general");
        
        mysql_query($sql);
    }
    
    /**
     * Comprova a la base de dades que no hi hagi guardada una programació del curs i del mòdul passats com a 
     * paràmetre, en el periode escolar actual.
     * 
     * @param Integer $idmoduls
     * @param Integer $idcurs
     * @return boolean
     */
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
    
    /**
     * Elimina la programació que correspongui amb el identificador passat com a paràmetre, de la base de dades.
     * 
     * @param Integer $idprogramacio_general
     */
    static function eliminarProgramacio($idprogramacio_general) {
        $sql = "DELETE FROM programacions_general WHERE idprogramacio_general = $idprogramacio_general";
        mysql_query($sql);
    }
    
    /**
     * Assigna l'atribut '$this->idperiodes_actual' amb el identificador del periode escolar actual.
     */
    function importarProgramacio() {
        $items = getCursActual();
        
        $this->idperiodes_escolars = $items->idperiodes_escolars;
    }
    
    /**
     * Recull la informació necessaria per a generar la portada del document que es genera a la classe 'Document'.
     * Selecciona el nom del pla d'estudi, nom del mòdul, nom del curs, durada del mòdul, hores de lliure disposició 
     * del mòdul i el nom del periode escolar per a la que esta feta la programació.
     * 
     * @param Integer $idmodul
     * @param Integer $idcurs
     * @param Integer $idperiodes_escolar
     * @return array
     */
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
    
    /**
     * Recull la informació necessaria de les UFS i del curs que corresponen al mòdul i al curs passats com a paràmetre 
     * per a poder utilitzar-la a la classe 'Document' i generar el document. Selecciona el identificador de les 
     * UFS, nom de la UF i les hores de lliure disposició de la UF.
     * 
     * @param Integer $idmodul
     * @param Integer $idcurs
     * @return array
     */
    static function getUFSModulCurs($idmodul, $idcurs) {
        $sql = "SELECT idunitats_formatives, nom_uf, hores, horeslliuredisposicio FROM unitats_formatives WHERE idunitats_formatives IN (SELECT "
        . " id_mat_uf_pla FROM grups_materies WHERE id_grups IN (SELECT idgrups FROM grups WHERE idcurs = $idcurs) AND "
        . "idunitats_formatives IN (SELECT id_ufs FROM moduls_ufs WHERE id_moduls = $idmodul))";
        
        $items = array();
        $rs = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($rs)) {
            $items[] = $row;
        }
        
        return $items;
    }
    
    /**
     * Comprova que la programació passada com a paràmetre tingui el valor passat en el camp marcat.
     * 
     * @param Integer $idprogramacio_general
     * @param Integer $camp
     * @param Integer $valor
     * @return boolean
     */
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
    
    /**
     * Comprova primer que el camp revisat de la programació passada com a pàrametre tingui el valor 'G' (Guardat), 
     * amb la funció 'comprovarEstat()'. Si el valor retornat per la funció es true, canvia el camp 'revisat' de la 
     * programació amb el valor 'E' (Enviada) utilitzant la funció 'canviarEstat()'.
     * 
     * @param Integer $idprogramacio_general
     * @return boolean
     */
    static function enviarProgramacio($idprogramacio_general) {
        $correcte = Programacio_General::comprovarEstat($idprogramacio_general, "revisat", "G");
        
        if ($correcte) {
            Programacio_General::canviarEstat("E", "revisat", $idprogramacio_general);
        }
        
        return $correcte;
    }
    
    /**
     * Si el valor '$aprovat' es true:
     * 
     * 1. Canvia el camp 'revisat' de la programació passada com a paràmetre amb el valor 'A' (Aprovada) utilitzant 
     * la funció 'canviarEstat().'
     * 
     * 2. Canvia el camp 'aprovat' de la programació passada com a paràmetre amb el valor 'E' (Enviada) utilitzant la 
     * funció 'canviarEstat()'.
     * 
     * 3. Canvia el camp 'professorRevisio' de la programació passada com a paràmetre per el identificador del professor 
     * passat també com a paràmetre de la funció.
     * 
     * Si el valor '$aprovat' es false, simplement canvia el valor del camp 'revisat' de la programació passada 
     * per paràmetre per una 'D' (Declinada).
     * 
     * 
     * @param Integer $idprogramacio_general
     * @param boolean $aprovat
     * @param Integer $idprofessor
     */
    static function enviarRevisio($idprogramacio_general, $aprovat, $idprofessor) {
        
        if ($aprovat == "true") {
            Programacio_General::canviarEstat("A", "revisat", $idprogramacio_general, $idprofessor);
            Programacio_General::canviarEstat("E", "aprovat", $idprogramacio_general);
            Programacio_General::canviarEstat($idprofessor, "professorRevisio", $idprogramacio_general);
        } else {
            Programacio_General::canviarEstat("D", "revisat", $idprogramacio_general, $idprofessor);
        } 
    }
    
    /**
     * Si el valor '$aprovat' es true:
     * 
     * 1. Canvia el camp 'aprovat' de la programació passada com a paràmetre amb el valor 'A' (Aprovada) utilitzant 
     * la funció 'canviarEstat().'
     * 
     * 2. Canvia el camp 'professorAprovacio' de la programació passada com a paràmetre per el identificador del professor 
     * passat també com a paràmetre de la funció.
     * 
     * Si el valor '$aprovat' es false, simplement canvia el valor del camp 'aprovat' de la programació passada 
     * per paràmetre per una 'D' (Declinada).
     * 
     * 
     * @param Integer $idprogramacio_general
     * @param boolean $aprovat
     * @param Integer $idprofessor
     */
    static function enviarAprovacio($idprogramacio_general, $aprovat, $idprofessor) {
        if ($aprovat == "true") {
            Programacio_General::canviarEstat("A", "aprovat", $idprogramacio_general, $idprofessor);
            Programacio_General::canviarEstat($idprofessor, "professorAprovacio", $idprogramacio_general);
        } else {
            Programacio_General::canviarEstat("D", "aprovat", $idprogramacio_general, $idprofessor);
        } 
    }
    
    /**
     * Canvia el camp passat com a paràmetre per el valor passat com a paràmetre de la programació passada també com 
     * a paràmetre de la funció. 
     * 
     * @param String $estat
     * @param String $camp
     * @param Integer $idprogramacio_general
     * @param Integer $idprofessor
     */
    static function canviarEstat($estat, $camp, $idprogramacio_general, $idprofessor = null) {
        $sql = "UPDATE programacions_general SET $camp = '$estat' WHERE idprogramacio_general = $idprogramacio_general";
        mysql_query($sql);
        
        if ($estat == "S") {
            if ($camp == "revisat") {
                $sql = "UPDATE programacions_general SET professorRevisio = $idprofessor WHERE idprogramacio_general = $idprogramacio_general";
            } else if ($camp == "aprovat") {
                $sql = "UPDATE programacions_general SET professorAprovacio = $idprofessor WHERE idprogramacio_general = $idprogramacio_general";
            }
            mysql_query($sql);
        } 
    }
    
    /*
     * Retorna alguns atributs en format json
     */
    function to_json() {
        return 
                json_encode([
               'idprogramacio_general' =>$this->getIdprogramacio_general(),
               'nomDocument' => $this->getNom_document(),
               'estrategies' => $this->getEstrategies_metodologies(),
               'recursos' => $this->getRecursos(),
               'data_creacio' => $this->getData_creacio(),
               'idperiodes_escolars' => $this->getIdperiodes_escolars(),
               'idprofessors' => $this->getIdprofessors(),
               'idmoduls' => $this->getIdmoduls(),
               'idcurs' => $this->getIdcurs()
               ]);
    }   
    
    /**
     * Funció que retorna el nom i cognoms del usuari que ha revisat la programació passada com a paràmetre. Aquesta 
     * informació s'utilitzarà per a omplir el document que es generà a la classe 'Document'.
     * 
     * @param Integer $idprogramacio_general
     * @return string
     */
    static function getNomProfessorRevisio($idprogramacio_general) {
        $sql = "SELECT cp.Valor AS 'nom' FROM contacte_professor cp, programacions_general pg WHERE cp.id_professor = pg.professorRevisio "
        . "AND pg.idprogramacio_general = $idprogramacio_general AND cp.id_tipus_contacte = 1";
        
        $items = array();

        $rs = mysql_query($sql);

        while ($row = mysql_fetch_assoc($rs)) {
            $items[] = $row;
        }
        
        if (isset($items[0]['nom'])) {
            $sql = "SELECT cp.Valor AS 'cognom' FROM contacte_professor cp, programacions_general pg WHERE cp.id_professor = pg.professorRevisio "
            . "AND pg.idprogramacio_general = $idprogramacio_general AND cp.id_tipus_contacte = 4";

            $rs = mysql_query($sql);

            while ($row = mysql_fetch_assoc($rs)) {
                $items[0]['nom'] .= " " . $row['cognom'];
            }

            return $items[0]['nom'];
        } else {
            return null;
        }
    }
    
    /**
     * Funció que retorna el nom i cognoms del usuari que ha aprovat la programació passada com a paràmetre. Aquesta 
     * informació s'utilitzarà per a omplir el document que es generà a la classe 'Document'.
     * 
     * @param Integer $idprogramacio_general
     * @return string
     */
    static function getNomProfessorAprovacio($idprogramacio_general) {
        $sql = "SELECT cp.Valor AS 'nom' FROM contacte_professor cp, programacions_general pg WHERE cp.id_professor = pg.professorAprovacio "
        . "AND pg.idprogramacio_general = $idprogramacio_general AND cp.id_tipus_contacte = 1";
        
        $items = array();

        $rs = mysql_query($sql);

        while ($row = mysql_fetch_assoc($rs)) {
            $items[] = $row;
        }
        
        if (isset($items[0]['nom'])) {
            $sql = "SELECT cp.Valor AS 'cognom' FROM contacte_professor cp, programacions_general pg WHERE cp.id_professor = pg.professorAprovacio "
            . "AND pg.idprogramacio_general = $idprogramacio_general AND cp.id_tipus_contacte = 4";

            $rs = mysql_query($sql);

            while ($row = mysql_fetch_assoc($rs)) {
                $items[0]['nom'] .= " " . $row['cognom'];
            }
        
            return $items[0]['nom'];
        } else {
            return null;
        }
    }
    
}
