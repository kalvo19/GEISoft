<?php

/**
 * Classe que conté els metòdes necessaris per a generar un document ODT (Libre Office). Depenent el tipus de document 
 * que s'hagi de generar.
 *
 * @author kalvo19
 */
class DocumentODT {
    //put your code here
    
    private $odt;
    private $programacio;
    
    function __construct($programacio) {
        $this->odt = new Docxpresso\CreateDocument();
        $this->programacio = $programacio;
    }
    
    function generarProgramacioComuna($ruta) {
        $this->taulaModificacions();
        $this->odt->render($ruta . '.odt');
    }
    
    /**
     * Creació de la taula de modificacions del document.
     */
    function taulaModificacions() {
        $titleStyle = "font-family: 'Arial'; font-size: 18px; text-decoration: underline;";
        $this->odt->paragraph()
                ->text(array('text' => '1. GESTIÓ DE LA PROGRAMACIÓ', 'style' => $titleStyle));
        //Estil del text que hi haurà a la fila capçalera de la taula.
        $headerTextTableStyle = "font-family: 'Arial'; font-size: 14px; font-weight: bold; margin: 1px;";
        
        //Estil de les cel·les de la taula.
        $cellTableStyle = "border: 1px solid #000000;";
        
        //Esti de les cel·les capçaleres de la taula.
        $headerCellTableStyle = "background-color: #F0F0F0; border: 1px solid #000000;";
        
        //Estil del text que hi ha a les cel·les de la tuala.
        $textTableStyle = "font-family: 'Arial; margin: 1px;'";
        
        //Estil de la primera cel·la de la taula.
        $firstTableCell = "border-style: hidden;";
        
        //Creació de la taula que mostra l'autor del document, el professor que ha revisat i aprovat el document.
        $this->odt->table(array('grid' => '4'))
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
        
        //Creació de la llista de modificacions del document
        $this->odt->table(array('grid' => '4'))
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
    }
    
    /**
     * Creació del index del document.
     */
    function index() {
        
    }
    
}
