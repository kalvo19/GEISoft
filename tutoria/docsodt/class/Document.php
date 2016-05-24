<?php

/**
 * Classe que conté els metòdes necessaris per a generar un document ODT (Libre Office) o PDF, segons sigui una programació 
 * general del mòdul o bé una programació d'aula.
 *
 * @author kalvo19
 */
class Document {
    
    private $document;
    private $programacio;
    
    function __construct($programacio) {
        //Fitxer sobre el que es creara el document ODT o PDF.
        $this->document = new Docxpresso\CreateDocument();
        
        //Objecte de tipus Programacio_General o bé Programacio_Aula sobre la qual s'anirà generant el document.
        $this->programacio = $programacio;
    }
    
    function propietatsPagina() {
        $this->document->pageLayout(array('style' => "margin: 85px 75px"));
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
        
        //PORTADA DEL DOCUMENT
        $this->portadaPG($informacio_portada_general, $ufs_moduls);
        
        //GESTIO DE LA PROGRAMACIÓ
        $this->taulaModificacionsPG($informacio_modificacions);
        
        //RELACIÓ D'UNITATS FORMATIVES 
        $this->relacioUFS($ufs_moduls);
        
        //ESTRATÈGIES METODOLOGIQUES I ORGANITZACIÓ DEL MÒDUL PROFESSIONAL
        
        //AVALUACIÓ/QUALIFICACIÓ/RECUPERACIÓ DEL MP
        $this->avaluacioMP($ufs_moduls);
        
        //ESPAIS, EQUIPAMENTS I RECURSOS DEL MP
        
        //PROGRAMACIONS UNITATS FORMATIVES
        
        //Guarda el document a la ruta passada com a paràmetre.
        $this->guardarProgramcio($ruta);
    }
    
    function guardarProgramcio($ruta) {
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
        
        $this->document->table(array('grid' => '1'))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Nom del Cicle Formatiu", 'style' => $headerTextTableStyle))
                ->row()
                    ->cell(array('style' => $cellTableStyle))
                         ->paragraph(array('text' => $contingut[0]["Nom_plan_estudis"] , 'style' => $textTableStyle));
        
        $this->document->table(array('grid' => '1'))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Nom del mòdul professional", 'style' => $headerTextTableStyle))
                ->row()
                    ->cell(array('style' => $cellTableStyle))
                         ->paragraph(array('text' => $contingut[0]["nom_modul"], 'style' => $textTableStyle));
        
        $this->document->table(array('grid' => '1'))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Curs del cicle", 'style' => $headerTextTableStyle))
                ->row()
                    ->cell(array('style' => $cellTableStyle))
                         ->paragraph(array('text' => $contingut[0]["nom_curs"] . "(" . $ufs . ")", 'style' => $textTableStyle));
        
        $this->document->table(array('grid' => '1'))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Durada", 'style' => $headerTextTableStyle))
                ->row()
                    ->cell(array('style' => $cellTableStyle))
                         ->paragraph(array('text' => $contingut[0]["hores_finals"] . " hores + " . $contingut[0]["horeslliuredisposicio"] . " lliure disposició", 'style' => $textTableStyle));
        
        $this->document->table(array('grid' => '1'))
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
        
    }
    
    /**
     * Creació de la taula de modificacions del document.
     */
    function taulaModificacionsPG($contingut) {
        $this->document->pageBreak();
        
        $titleStyle = "font-family: 'Arial'; font-size: 18px; text-decoration: underline;";
        $this->document->paragraph()
                ->text(array('text' => "1. GESTIÓ DE LA PROGRAMACIÓ", 'style' => $titleStyle));
       
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
                        ->paragraph(array('text' => "" , 'style' => $textTableStyle))
                    ->cell(array('style' => $cellTableStyle))
                        ->paragraph(array('text' => "", 'style' => $textTableStyle))
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
    }   
    
    
    
    function relacioUFS($ufs_moduls) {
        $this->document->pageBreak();
        
        $titleStyle = "font-family: 'Arial'; font-size: 18px; text-decoration: underline;";
        $this->document->paragraph()
                ->text(array('text' => "3. RELACIÓ D'UNITATS FORMATIVES", 'style' => $titleStyle));
        
        //Estil del text que hi haurà a la fila capçalera de la taula.
        $headerTextTableStyle = "font-family: 'Arial'; font-size: 14px; font-weight: bold; margin: 1px;";
        
        //Estil de les cel·les de la taula.
        $cellTableStyle = "border: 1px solid #000000;";
        
        //Estil de les cel·les capçaleres de la taula.
        $headerCellTableStyle = "background-color: #F0F0F0; border: 1px solid #000000;";
        
        //Estil del text que hi ha a les cel·les de la tuala.
        $textTableStyle = "font-family: 'Arial; margin: 1px; font-size: 14px;'";
        
        $taula_ufs = $this->document->table(array('grid' => '3'))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle, 'colspan' => 3))
                        ->paragraph(array('text' => "Relació d'unitats formatives", 'style' => $headerTextTableStyle))
                ->row()
                    ->cell(array('style' => $headerCellTableStyle, 'colspan' => 3))
                        ->paragraph(array('text' => "Nom de la Unitat formativa", 'style' => $headerTextTableStyle));
        
        $totalHoresUFS = 0;
        
        for ($i = 0; $i < count($ufs_moduls); $i++) {
            $totalHoresUFS += $ufs_moduls[$i]["hores"] + $ufs_moduls[$i]["horeslliuredisposicio"];
            $taula_ufs
                    ->row()
                        ->cell(array('style' => $cellTableStyle))
                            ->paragraph(array('text' => $ufs_moduls[$i]["nom_uf"], 'style' => $textTableStyle))
                        ->cell(array('style' => $cellTableStyle))
                            ->paragraph(array('text' => $ufs_moduls[$i]["hores"] . "+" . $ufs_moduls[$i]["horeslliuredisposicio"], 'style' => $textTableStyle))
                        ->cell(array('style' => $cellTableStyle))
                            ->paragraph(array('text' => $ufs_moduls[$i]["hores"] + $ufs_moduls[$i]["horeslliuredisposicio"], 'style' => $textTableStyle));                      
        }
        
        $taula_ufs
                ->row()
                    ->cell()
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => "Totals", 'style' => $headerTextTableStyle))
                    ->cell(array('style' => $headerCellTableStyle))
                        ->paragraph(array('text' => $totalHoresUFS . "h", 'style' => $headerTextTableStyle));
    }
    
    function estrategiesMetodologiques() {
        
    }
    
    function avaluacioMP($ufs_moduls) {
        $titleStyle = "font-family: 'Arial'; font-size: 18px; text-decoration: underline;";
        $this->document->paragraph()
                ->text(array('text' => "5. AVALUACIÓ/QUALIFICACIÓ/RECUPERACIÓ DEL MP", 'style' => $titleStyle))
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
        $textTableStyle = "font-family: 'Arial'; margin: 1px; font-size: 14px; font-weight: bold;";
        
        //Estil del text secondari que conté la cel.la.
        $secondaryTextTableStyle = "font-family: 'Arial'; margin: 1px; font-size: 6px; font-weight: bold;";
        
        //Patró que agafarà la nomenclatura de la unitat formativa, és a dir UF1, UF2, UF3 ...
        $patro = "/UF[0-9]+/";
        $coincidencies = array();
        
        $taulaAvaluacio = $this->document->table(array('grid' => '1'))
                ->row()
                    ->cell(array('style' =>  $headerCellTableStyle))
                        ->paragraph()
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
    }
    
    /**
     * ############################## MÈTODES PER A LA CREACIÓ DE LA PROGRAMACIÓ D'AULA ################################
     */
    
    
}
