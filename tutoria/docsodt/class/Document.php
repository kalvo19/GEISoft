<?php

include_once "../prog_gen/class/Resultats_aprenentatge.php";
include_once "../prog_gen/class/Continguts_RA.php";
include_once "../prog_gen/class/Criteris_Avaluacio.php";

/**
 * Classe que conté els metòdes necessaris per a generar un document ODT (Libre Office) o PDF, segons sigui una programació 
 * general del mòdul o bé una programació d'aula.
 *
 * @author kalvo19
 */
class Document {
    
    private $document;
    private $programacio;
    private $pagines;
    private $titols;
    private $index;
    private $bookmark;
    
    function __construct($programacio) {
        //Fitxer sobre el que es creara el document ODT o PDF.
        $this->document = new Docxpresso\CreateDocument();
        
        //Objecte de tipus Programacio_General o bé Programacio_Aula sobre la qual s'anirà generant el document.
        $this->programacio = $programacio;
        
        //Vector que conté les pàgines on es troben els diferents apartats.
        $this->pagines = array();
        
        $this->index = 1;
        
        //Vector que conté tots els títols dels diferents apartats de la pàgina per a després introduir-los al index.
        $this->titols = array();
        
    }
    
    function propietatsPagina() {
        $this->document->pageLayout(array('style' => "margin: 85px 75px"));
    }
    
    function headerPG() {
        
        $headerStyle = array('style' => 'min-height: 3cm; border: 1px solid #FFFFFF');
        
        //Estil de la taula de la capçalera del document
        $cellTableStyle = "border: 1px solid #FFFFFF";
        
        $imageStyle = "margin: 0; padding: 0; width: 642px; height: 85px";
        
        $this->document->header($headerStyle)
            ->table(array('grid', '1'))
                ->row()
                    ->cell(array('style' => $cellTableStyle))
                        ->paragraph()->image(array('src' => "../docsodt/images/header.png", 'style' => $imageStyle));
    }
    
    function footerPG() {
        //Estil de les cel·les capçalera.
        $headerCellTableStyle = "background-color: #F0F0F0; border: 1px solid #000000;";
        
        //Estil de les cel·les de la taula.
        $cellTableStyle = "border: 1px solid #000000;";
        
        //Estil del text de la capçalera.
        $headerTableText = "font-size: 10px; font-weight: bold; margin: 1px;";
        
        //Estil del text que hi ha a la cel·les.
        $tableText = "font-size: 10px; margin: 1px;";
        
        $this->document->footer()
                ->table(array('grid' => 3))
                    ->row()
                        ->cell(array('style' => $headerCellTableStyle))
                            ->paragraph(array('text' => "Procès: ", 'style' => $headerTableText))
                        ->cell(array('style' => $headerCellTableStyle))
                            ->paragraph(array('text' => "Document: ", 'style' => $headerTableText))
                        ->cell(array('style' => $headerCellTableStyle))
                            ->paragraph(array('text' => "Data: " . $this->programacio->getData_Creacio(), 'style' => $headerTableText))
                    ->row()
                        ->cell(array('style' => $cellTableStyle))
                            ->paragraph(array('text' => "xxx ", 'style' => $tableText))
                        ->cell(array('style' => $cellTableStyle))
                            ->paragraph(array('text' => $this->programacio->getNom_document(), 'style' => $tableText))
                        ->cell(array('style' => $cellTableStyle))
                            ->paragraph()
                            ->text(array('text' => "Pàgina ", 'style' => $tableText))
                            ->field('page-number')
                            ->text(array('text' => "de ", 'style' => $tableText))
                            ->field('page-count')
                    ->row()
                        ->cell(array('style' => $cellTableStyle))
                            ->paragraph(array('text' => "Elaborat: ", 'style' => $tableText))
                        ->cell(array('style' => $cellTableStyle))
                            ->paragraph(array('text' => "Revisat: ", 'style' => $tableText))
                        ->cell(array('style' => $cellTableStyle))
                            ->paragraph(array('text' => "Aprovat: ", 'style' => $tableText));
    }
    
    function incrementarIndex() {
        $this->index++;
    }
    
    function generarProgramacioComuna($ruta) {
        
        //Informació que s'utlilitzarà per a crear la portada del document.
        $informacio_portada_general = Programacio_General::getInformacioPortada($this->programacio->getIdmoduls(), $this->programacio->getIdcurs(), $this->programacio->getIdperiodes_escolars());

        //Unitats formatives sobre les que s'aplicarà el document.
        $ufs_moduls = Programacio_General::getUFSModulCurs($this->programacio->getIdmoduls(), $this->programacio->getIdcurs());

        //Informacio que s'utililtzarà per a completar el llistat de modificacions de la programacio.
        $informacio_modificacions = Modificacio::getModificacions(1, $this->programacio->getIdprogramacio_general());
        
        //Preparació del disseny de la pàgina
        $this->propietatsPagina();
        $this->footerPG();
        $this->headerPG();
        
        //PORTADA DEL DOCUMENT
        $this->portadaPG($informacio_portada_general, $ufs_moduls);
        
        //INDEX 
        $this->indexPG();
        
        //GESTIO DE LA PROGRAMACIÓ
        $this->taulaModificacionsPG($informacio_modificacions);
        
        //RELACIÓ D'UNITATS FORMATIVES 
        $this->relacioUFS($ufs_moduls);
        
        //ESTRATÈGIES METODOLOGIQUES I ORGANITZACIÓ DEL MÒDUL PROFESSIONAL
        $this->estrategiesMetodologiques();
        
        //AVALUACIÓ/QUALIFICACIÓ/RECUPERACIÓ DEL MP
        $this->avaluacioMP($ufs_moduls);
        
        //ESPAIS, EQUIPAMENTS I RECURSOS DEL MP
        $this->recursosModul();
        
        //PROGRAMACIONS UNITATS FORMATIVES
        for ($i = 0; $i < count($ufs_moduls); $i++) {
            $this->programacioUFSPG($ufs_moduls, $i);
        }
        
        //Guarda el document a la ruta passada com a paràmetre.
        $this->guardarProgramacio($ruta);
    }
    
    function guardarProgramacio($ruta) {
        $this->document->render($ruta);
    }
    
    function generarProgramacioAula($ruta) {
        // (per omplir)
    }
    
    /**
     * ############################## MÈTODES PER A LA CREACIÓ DE LA PROGRAMACIÓ COMUNA ################################
     */
    
    /**
     * 
     * @param Array $contingut
     * @param Array $ufs_moduls
     */
    function portadaPG($contingut, $ufs_moduls) {
        
        $this->document->pageBreak();
        
        $table = "width: 300px; margin: auto; margin-top: 25px;";
        
        $mainTitleText = "font-family: 'Arial'; font-size: 24px; font-weight: bold; text-align: center;";
        
        //Estil del text que hi haurà a la fila capçaleres de la taules.
        $headerTextTableStyle = "font-family: 'Arial'; font-size: 14px; font-weight: bold; margin: 1px; text-align: center;";
        
        //Estil de la cel·la de les taules
        $cellTableStyle = "border: 1px solid #000000;";
        
        //Estil de les cel·les capçaleres de les taules.
        $headerCellTableStyle = "background-color: #F0F0F0; border: 1px solid #000000;";
        
        //Estil del text que hi ha a les cel·la de les taules.
        $textTableStyle = "font-family: 'Arial; margin: 1px; text-align: center;'";
        
        //Taula que conté el titol general de totes les programacions.
        $this->document->table(array('grid' => '1'))
                ->row()
                    ->cell()
                        ->paragraph(array('text' => 'PROGRAMACIÓ DIDÀCTICA GENERAL DE MÒDUL PROFESSIONAL', 'style' => $mainTitleText));
        
        /**
         * Taules que contenen informació en relació a la programació.
         * - Nom del Cicle formatiu
         * - Nom del Mòdul professional
         * - Curs del cicle
         * - Durada del Mòdul + les hores de lliure disposició
         * - Periode escolar (Ex. 2014/2015 o 2015/2016)
         */
        
        //Variable que conté les Unitats Formatives del Mòdul de la programació.
        $ufs = "";
        $coincidencies = array();
        
        //Patró que agafarà la nomenclatura de la unitat formativa, és a dir UF1, UF2, UF3 ...
        $patro = "/UF[0-9]+/";
        
        for ($i = 0; $i < count($ufs_moduls); $i++) { 
            if ($i != 0) {
                $ufs .= "-";
            }
            preg_match($patro, $ufs_moduls[$i]["nom_uf"], $coincidencies);
            $ufs .= $coincidencies[0];
        }
        
        $this->document->table(array('grid' => '1', 'style' => $table))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Nom del Cicle Formatiu", 'style' => $headerTextTableStyle))
                ->row()
                    ->cell(array('style' => $cellTableStyle))
                         ->paragraph(array('text' => $contingut[0]["Nom_plan_estudis"] , 'style' => $textTableStyle));
        
        $this->document->table(array('grid' => '1', 'style' => $table))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Nom del mòdul professional", 'style' => $headerTextTableStyle))
                ->row()
                    ->cell(array('style' => $cellTableStyle))
                         ->paragraph(array('text' => $contingut[0]["nom_modul"], 'style' => $textTableStyle));
        
        $this->document->table(array('grid' => '1', 'style' => $table))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Curs del cicle", 'style' => $headerTextTableStyle))
                ->row()
                    ->cell(array('style' => $cellTableStyle))
                         ->paragraph(array('text' => $contingut[0]["nom_curs"] . "(" . $ufs . ")", 'style' => $textTableStyle));
        
        $this->document->table(array('grid' => '1', 'style' => $table))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Durada", 'style' => $headerTextTableStyle))
                ->row()
                    ->cell(array('style' => $cellTableStyle))
                         ->paragraph(array('text' => $contingut[0]["hores_finals"] . " hores + " . $contingut[0]["horeslliuredisposicio"] . " lliure disposició", 'style' => $textTableStyle));
        
        $this->document->table(array('grid' => '1', 'style' => $table))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Curs", 'style' => $headerTextTableStyle))
                ->row()
                    ->cell(array('style' => $cellTableStyle))
                         ->paragraph(array('text' => $contingut[0]["Nom"], 'style' => $textTableStyle));
    }
    
    /**
     * Creació del index del document.
     */
    function indexPG() {
        $title = "ÍNDEX";
        
        $titleStyle = "font-weight: bold; font-family: 'Arial Black'; font-size: 20px; margin: 5px;";
        
        $this->document->pageBreak();
        $this->bookmark = $this->document->paragraph(array('style' => 'text-align: left;'))
                ->text(array('text' => $title, 'style' => $titleStyle))
                ->lineBreak();
    }
    
    function indexarContingut($title, $bookmark, $bookmarkName) {
        $this->bookmark
                ->text(array('text' => $title . "...")) 
                ->link(array('url' => "#$bookmarkName", 'title' => $title, 'style' => "text-align: left;"))->field('bookmark-ref', $bookmark)->end('link')
                ->lineBreak();
    }
    
    /**
     * Creació de la taula de modificacions del document.
     */
    function taulaModificacionsPG($contingut) {
        $title = "GESTIÓ DE LA PROGRAMACIÓ";
        
        $bookmarkName = "modificacions";
        $index = array('reference-name' => $bookmarkName, 'reference-format' => 'page');
        $this->indexarContingut($title, $index, $bookmarkName);
        
        $this->document->pageBreak();
        
        $titleStyle = "font-family: 'Arial'; font-size: 18px; text-decoration: underline;";
        $this->document->paragraph()
                    ->bookmark(array('name' => $bookmarkName))
                    ->text(array('text' => "$this->index . $title", 'style' => $titleStyle));
       
        //Estil del text que hi haurà a la fila capçalera de la taula.
        $headerTextTableStyle = "font-family: 'Arial'; font-size: 12px; font-weight: bold; margin: 1px;";
        
        //Estil de les cel·les de la taula.
        $cellTableStyle = "border: 1px solid #000000;";
        
        //Estil de les cel·les capçaleres de la taula.
        $headerCellTableStyle = "background-color: #F0F0F0; border: 1px solid #000000;";
        
        //Estil del text que hi ha a les cel·les de la tuala.
        $textTableStyle = "font-family: 'Arial; margin: 1px; font-size: 12px;'";
        
        //Estil de la primera cel·la de la taula.
        $firstTableCell = "border-style: hidden;";
        
        //Creació de la taula que mostra l'autor del document, el professor que ha revisat i aprovat el document.
        $this->document->table(array('grid' => '4'))
                ->row()
                    ->cell(array('style' => $firstTableCell))
                        ->paragraph(array('text' => ""))
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Realitzat per: ", 'style' => $headerTextTableStyle))
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Revisat per: ", 'style' => $headerTextTableStyle))
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Aprovat per: ", 'style' => $headerTextTableStyle))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Nom i cognoms", 'style' => $headerTextTableStyle))
                    ->cell(array('style' => $cellTableStyle))
                        ->paragraph(array('text' => getProfessor($this->programacio->getIdProfessors(), 1) . " " . getProfessor($this->programacio->getIdProfessors(), 4) , 'style' => $textTableStyle))
                    ->cell(array('style' => $cellTableStyle))
                        ->paragraph(array('text' => Programacio_General::getNomProfessorRevisio($this->programacio->getIdprogramacio_General()), 'style' => $textTableStyle))
                    ->cell(array('style' => $cellTableStyle))
                        ->paragraph(array('text' => Programacio_General::getNomProfessorAprovacio($this->programacio->getIdprogramacio_General()), 'style' => $textTableStyle))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Càrrec", 'style' => $headerTextTableStyle))
                    ->cell(array('style' => $cellTableStyle))
                        ->paragraph(array('text' => "" , 'style' => $textTableStyle))
                    ->cell(array('style' => $cellTableStyle))
                        ->paragraph(array('text' => "" , 'style' => $textTableStyle))
                    ->cell(array('style' => $cellTableStyle))
                        ->paragraph(array('text' => "", 'style' => $textTableStyle))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Data", 'style' => $headerTextTableStyle))
                    ->cell(array('style' => $cellTableStyle))
                        ->paragraph(array('text' => $this->programacio->getData_creacio() , 'style' => $textTableStyle))
                    ->cell(array('style' => $cellTableStyle))
                        ->paragraph(array('text' => "" , 'style' => $textTableStyle))
                    ->cell(array('style' => $cellTableStyle))
                        ->paragraph(array('text' => "", 'style' => $textTableStyle))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Signatura", 'style' => $headerTextTableStyle))
                    ->cell(array('style' => $cellTableStyle, 'rowspan' => 3))
                    ->cell(array('style' => $cellTableStyle, 'rowspan' => 3))
                    ->cell(array('style' => $cellTableStyle, 'rowspan' => 3));
        
        //Creació del llistat de modificacions del document.
        $taula_modificacions = $this->document->table(array('grid' => '4'))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle, 'colspan' => 4))
                        ->paragraph(array('text' => "Llistat de les modificacions", 'style' => $headerTextTableStyle . "text-align: center"))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Versió", 'style' => $headerTextTableStyle))
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Revisió" , 'style' => $headerTextTableStyle))
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Descripció de la modificació" , 'style' => $headerTextTableStyle))
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Aprovació", 'style' => $headerTextTableStyle));
        
        for ($i = 0; $i < count($contingut); $i++) {
            $taula_modificacions
                ->row()
                    ->cell(array('style' => $cellTableStyle))
                        ->paragraph(array('text' => $contingut[$i]["versio"], 'style' => $textTableStyle))
                    ->cell(array('style' => $cellTableStyle))
                        ->paragraph(array('text' => $contingut[$i]["data_modificacio"] , 'style' => $textTableStyle))
                    ->cell(array('style' => $cellTableStyle))
                        ->paragraph(array('text' => $contingut[$i]["descripcio"] , 'style' => $textTableStyle))
                    ->cell(array('style' => $cellTableStyle))
                        ->paragraph(array('text' => "", 'style' => $textTableStyle));
        }
        
        $this->incrementarIndex();
        
    }   
    
    
    
    function relacioUFS($ufs_moduls) {
        $this->document->pageBreak();
        
        $title = "RELACIÓ D'UNITATS FORMATIVES";
        
        //Indexa el contingut
        $bookmarkName = "relacio_ufs";   
        $index = array('reference-name' => $bookmarkName, 'reference-format' => 'page');
        $this->indexarContingut($title, $index, $bookmarkName);
        
        $titleStyle = "font-family: 'Arial'; font-size: 18px; text-decoration: underline;";
        $this->document->paragraph()
                ->bookmark(array('name' => $bookmarkName))
                ->text(array('text' => "$this->index . $title", 'style' => $titleStyle));
        
        //Estil del text que hi haurà a la fila capçalera de la taula.
        $headerTextTableStyle = "font-family: 'Arial'; font-size: 14px; font-weight: bold; margin: 1px;";
        
        //Estil de les cel·les de la taula.
        $cellTableStyle = "border: 1px solid #000000;";
        
        //Estil de les cel·les capçaleres de la taula.
        $headerCellTableStyle = "background-color: #F0F0F0; border: 1px solid #000000;";
        
        //Estil del text que hi ha a les cel·les de la tuala.
        $textTableStyle = "font-family: 'Arial; margin: 1px; font-size: 14px;'";
        
        $taula_ufs = $this->document->table(array('grid' => '5'))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle, 'colspan' => 5))
                        ->paragraph(array('text' => "Relació d'unitats formatives", 'style' => $headerTextTableStyle))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle, 'colspan' => 3))
                        ->paragraph(array('text' => "Nom de la Unitat formativa", 'style' => $headerTextTableStyle))
                    ->cell(array('style' => $headerCellTableStyle))
                            ->paragraph(array('text' => "H. mín + hlld", 'style' => $headerTextTableStyle))
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Durada", 'style' => $headerTextTableStyle));
        
        $totalHoresUFS = 0;
        
        for ($i = 0; $i < count($ufs_moduls); $i++) {
            $totalHoresUFS += $ufs_moduls[$i]["hores"] + $ufs_moduls[$i]["horeslliuredisposicio"];
            $taula_ufs
                    ->row()
                        ->cell(array('style' => $cellTableStyle, 'colspan' => 3))
                            ->paragraph(array('text' => $ufs_moduls[$i]["nom_uf"], 'style' => $textTableStyle . "width: "))
                        ->cell(array('style' => $cellTableStyle))
                            ->paragraph(array('text' => $ufs_moduls[$i]["hores"] . "+" . $ufs_moduls[$i]["horeslliuredisposicio"], 'style' => $textTableStyle))
                        ->cell(array('style' => $cellTableStyle))
                            ->paragraph(array('text' => $ufs_moduls[$i]["hores"] + $ufs_moduls[$i]["horeslliuredisposicio"], 'style' => $textTableStyle));                      
        }
        
        $taula_ufs
                    ->row()
                        ->cell(array('colspan' => 3))
                        ->cell(array('style' => $headerCellTableStyle))
                            ->paragraph(array('text' => "Totals", 'style' => $headerTextTableStyle))
                        ->cell(array('style' => $headerCellTableStyle))
                            ->paragraph(array('text' => $totalHoresUFS . "h", 'style' => $headerTextTableStyle));
        
        $this->incrementarIndex();
    }
    
    function estrategiesMetodologiques() {
        $title = "ESTRATEGIES METODOLÒGIQUES I ORGANITZACIÓ DEL MÒDUL";
        
        //Indexa el contingut
        $bookmarkName = "estrategies";
        $index = array('reference-name' => $bookmarkName, 'reference-format' => 'page');     
        $this->indexarContingut($title, $index, $bookmarkName);
        
        $titleStyle = "font-family: 'Arial'; font-size: 18px; text-decoration: underline;";
        $this->document->paragraph()
                ->bookmark(array('name' => $bookmarkName))
                ->text(array('text' => "$this->index. $title", 'style' => $titleStyle))
                ->lineBreak();
        
        $textStyle = "<style>p {font-size: 14px; font-family: 'Arial';} li{font-size: 14px; font-family: 'Arial';}</style>";
        $html = $textStyle . mostrarAccents($this->programacio->getEstrategies_metodologies());
        $this->document->html(array('html' => $html));
        
        $this->incrementarIndex();
    }
    
    function avaluacioMP($ufs_moduls) {
        $title = "AVALUACIÓ/QUALIFICACIÓ/RECUPERACIÓ DEL MP";
        
        //Indexa el contingut
        $bookmarkName = "avaluacio";
        $index = array('reference-name' => $bookmarkName, 'reference-format' => 'page');
        $this->indexarContingut($title, $index, $bookmarkName);
        
        $titleStyle = "font-family: 'Arial'; font-size: 18px; text-decoration: underline;";
        $this->document->paragraph()
                ->bookmark(array('name' => $bookmarkName))
                ->text(array('text' => "$this->index. $title", 'style' => $titleStyle))
                ->lineBreak();
        $total_ufs = count($ufs_moduls);
        
        //Estil text paràgrafs
        $paragraphText = "font-family: 'Arial'; font-size: 14px;";
        
        //Primer paràgraf
        $contingut = "Per superar el mòdul professional cal superar independentment les/la $total_ufs UUFF. La qualificació "
        . "del mòdul professional s'obté segons la següent ponderació:"; 
        
        $this->document->paragraph()
                ->text(array('text' => $contingut, 'style' => $paragraphText))
                ->lineBreak();
        
        //Contingut de la cel·la de la taula
        
        //Estil de la cel·la de la taula.
        $headerCellTableStyle = "vertical-align: middle; background-color: #F0F0F0; border: 1px solid #000000;";
        
        //Estil del text que conté la cel·la.
        $textTableStyle = "font-family: 'Arial'; margin: 1px; font-size: 16px; font-weight: bold;";
        
        //Estil del text secondari que conté la cel.la.
        $secondaryTextTableStyle = "font-family: 'Arial'; margin: 1px; font-size: 8px; font-weight: bold;";
        
        //Patró que agafa la nomenclatura de la unitat formativa, és a dir UF1, UF2, UF3 ...
        $patro = "/UF[0-9]+/";
        $coincidencies = array();
        
        $taulaAvaluacio = $this->document->table(array('grid' => '1'))
                ->row()
                    ->cell(array('style' =>  $headerCellTableStyle))
                        ->paragraph(array('style' => "text-align: center;"))
                            ->text(array('text' => "Q", 'style' => $textTableStyle))
                            ->text(array('text' => "MP", 'style' => $secondaryTextTableStyle))
                            ->text(array('text' => " = (", 'style' => $textTableStyle));
        for ($i = 0; $i < count($ufs_moduls); $i++) {
            preg_match($patro, $ufs_moduls[$i]["nom_uf"], $coincidencies);
            $uf = $coincidencies[0];
            $taulaAvaluacio
                            ->text(array("text" => "h", "style" => $textTableStyle))
                            ->text(array("text" => $uf, 'style' => $secondaryTextTableStyle))
                            ->text(array("text" => "*Q", "style" => $textTableStyle))
                            ->text(array("text" => $uf, 'style' => $secondaryTextTableStyle));
            if ($i < (count($ufs_moduls) - 1)) {         
                 $taulaAvaluacio
                            ->text(array("text" => " + ", "style" => $textTableStyle));
            }
        } 
            $taulaAvaluacio
                        ->text(array("text" => ")/Hores totals MP", 'style' => $textTableStyle));
            
        //Segòn parregraf    
        $contingut = "Si un alumne no supera qualsevol UF (nota inferior a 5) haurà de presentar-se a una prova extraordinària "
        . "de recuperació que constarà d'un examen de tots els continguts impartits en aquella UF."; 
        
        $this->document->paragraph()
                ->lineBreak()
                ->text(array('text' => $contingut, 'style' => $paragraphText))
                ->lineBreak();
        
        $this->incrementarIndex();
    }
    
    function recursosModul() {
        $title = "ESPAIS, EQUIPAMENTS I RECURSOS DEL MP";
        
        $bookmarkName = "recursos";
        
        $index = array('reference-name' => $bookmarkName, 'reference-format' => 'page');
        
        $this->indexarContingut($title, $index, $bookmarkName);
        
        $titleStyle = "font-family: 'Arial'; font-size: 18px; text-decoration: underline;";
        $this->document->paragraph()
                ->bookmark(array('name' => $bookmarkName))
                ->text(array('text' => "$this->index. $title", 'style' => $titleStyle))
                ->lineBreak();
        
        $textStyle = "<style>p {font-size: 14px; font-family: 'Arial';} li{font-size: 14px; font-family: 'Arial';}</style>";
        $html = $textStyle . mostrarAccents($this->programacio->getRecursos());
        $this->document->html(array('html' => $html));
        
        $this->incrementarIndex();
    }
    
    function programacioUFSPG($ufs_moduls, $index) {
        //Patró que agafa el número de la unitat formativa
        $patro = "/[0-9]+/";
        preg_match($patro, $ufs_moduls[$index]["nom_uf"], $coincidencies);
        $num_uf = $coincidencies[0];
        
        $this->document->pageBreak();
        
        //Indexa el contingut
        $title = "PROGRAMACIÓ D'UNITAT FORMATIVA $num_uf";
        $bookmarkName = "programacio_uf" . $num_uf;
        $indexBookmark = array('reference-name' => $bookmarkName, 'reference-format' => 'page');
        $this->indexarContingut($title, $indexBookmark, $bookmarkName);
        
        
        //Titol del apartat
        $titleStyle = "font-family: 'Arial'; font-size: 18px; text-decoration: underline;";
        $this->document->paragraph()
                ->bookmark(array('name' => $bookmarkName))
                ->text(array('text' => "$this->index. $title", 'style' => $titleStyle));
        
        //Subtitòl del apartat
        $subtitleStyle = "font-family: 'Arial'; font-size: 16px;";
        $this->document->paragraph()
                ->text(array('text' => "$this->index.1 Resultats d'aprenentatge, Criteris d'avaluació i continguts ", 'style' => $subtitleStyle));
        
        /**
         * Taula que conté els resultats d'aprenentatge, els criteris d'avaluació i els continguts de la UF del index passat
         * com a paràmetre.
         */
         
        //Estil de les cel·les capçaleres de les taules.
        $headerCellTableStyle = "background-color: #F0F0F0; border: 1px solid #000000;";
        
        //Estil de les cel·les de la taula.
        $cellTableStyle = "border: 1px solid #000000; font-family: 'Arial'";
        
        //Estil del text que hi haurà a la fila capçaleres de la taules.
        $headerTextTableStyle = "font-family: 'Arial'; font-size: 14px; font-weight: bold; margin: 1px;";
        
        //Estil del text que hi ha dintre de cada cel·la.
        $textTable = "font-family: 'Arial'; font-size: 14px; margin: 1px";
        
        $taulaContinguts = $this->document->table(array('grid' => 1))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array("text" => $ufs_moduls[$index]["nom_uf"], 'style' => $headerTextTableStyle));
        
        //Resultats d'aprenentatge de la UF actual
        $resultats_aprenentatge = Resultats_Aprenentatge::getResultatsAprenetatge($ufs_moduls[$index]["idunitats_formatives"]);
        
        //Escriu tots els resultats d'aprenentatge dintre de la taula 'taulaContinguts'
        for ($i = 0; $i < count($resultats_aprenentatge); $i++) {
            $taulaContinguts
                ->row()
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array("text" => $resultats_aprenentatge[$i]["codi_ra"] . ". " . $resultats_aprenentatge[0]["descripcio"], 'style' => $headerTextTableStyle));
            
            //Conté els criteris d'avaluació del resultat d'aprenentatge de la posició '$i'
            $criteris_avaluacio = Criteris_Avaluacio::getCriterisAvaluacio($resultats_aprenentatge[$i]["idresultats_aprenentatge"]);
            
            //Escriu tots els continguts del resultat d'aprenentatge actual dintre de la taula 'taulaContinguts'
            $taulaContinguts
                ->row()
                    ->cell(array('style' => $cellTableStyle))
                        ->paragraph(array("text" => "Criteris d'avaluació"));
            for ($m = 0; $m < count($criteris_avaluacio); $m++) {
                $taulaContinguts
                        ->row()
                            ->cell(array('style' => $cellTableStyle))
                                ->paragraph(array("text" => $criteris_avaluacio[$m]["codi_ca"] . ") " . $criteris_avaluacio[$m]["descripcio"], 'style' => $textTable));
            }
            
            //Conté els continguts del resultat d'aprenentatge de la posició '$i'
            $continguts_ra = Continguts_RA::getContingutsRA($resultats_aprenentatge[$i]["idresultats_aprenentatge"]);
            
            //Escriu tots els continguts del resultat d'aprenentatge actual dintre de la taula 'taulaContinguts'
            $taulaContinguts
                ->row()
                    ->cell(array('style' => $cellTableStyle))
                        ->paragraph(array("text" => "Continguts"));
            for ($j = 0; $j < count($continguts_ra); $j++) {
                $taulaContinguts
                        ->row()
                            ->cell(array('style' => $cellTableStyle))
                                ->paragraph(array("text" => $continguts_ra[$j]["codi_continguts"] . ") " . $continguts_ra[$j]["descripcio"], 'style' => $textTable));
            } 
            
        }
        
        $this->incrementarIndex();
                    
    }
    
    /**
     * ############################## MÈTODES PER A LA CREACIÓ DE LA PROGRAMACIÓ D'AULA ################################
     */
    
    
}
