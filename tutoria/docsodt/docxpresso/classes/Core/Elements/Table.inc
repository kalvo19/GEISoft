<?php

/**
 * Table
 * 
 * @copyright  Copyright(c)2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Core\Elements;

use Docxpresso\Commons as commons;
use Docxpresso\Themes as themes;
use Docxpresso;

/**
 * Creates a table element
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class Table extends ODFElement
{
    /**
     * allowed parent nodes
     * 
     * @var array
     * @access public
     * @static
     */
    public static $parentNodes = array(
        'chart:chart' => true,
        'draw:frame' => true,
        'draw:text-box' => true,
        'office:spreadsheet' => true,
        'office:text' => true,
        'style:footer' => true,
        'style:footer-left' => true,
        'style:header' => true,
        'style:header-left' => true,
        'table:covered-table-cell' => true,
        'table:dde-link' => true,
        'table:table-cell' => true,
        'text:deletion' => true,
        'text:index-body' => true,
        'text:index-title' => true,
        'text:note-body' => true,
        'text:section' => true,
        //also include the custom office:fragment tag
        'office:fragment' => true,
    );
    
    /**
     * reference to the created node
     * 
     * @var DOMNode
     * @access private
     */
    private $_newNode;
    
    /**
     * table id
     * 
     * @var string
     * @access private
     */
    private $_tableId;
       
    /**
     * Construct
     *
     * @param ODFElement $odfNode
     * @param array $options
     * @access public
     */
    public function __construct($odfNode, $options)
    {
        $node = $odfNode->getNode();
        $styleId = $odfNode->getStyleId();
        $target = $odfNode->getTarget();
        parent::__construct($node, $odfNode, $target);
        //create an enveloping table
        if (isset(self::$parentNodes[$node->nodeName])) {
            $tableNS = 'urn:oasis:names:tc:opendocument:xmlns:table:1.0';
            $table = $node->ownerDocument
                          ->createElementNS($tableNS, 'table:table');
            if (!empty($styleId)){
                $table->setAttributeNS($tableNS, 'table:style-name', $styleId);
            }
            //identify the table with a unique id
            $this->_tableId = commons\Utilities::generateId('table_');
            $id = $this->_tableId;
            $table->setAttribute('xml:id', $id); 
            //let us check if there is a theme defined and it applies to the
            //current table
            if (!empty(Docxpresso\CreateDocument::$theme)) {
                Docxpresso\CreateDocument::$tables[$id]['theme'] = ''; 
                if (isset($options['mask']) && \is_array($options['mask'])) {
                    Docxpresso\CreateDocument::$tables[$id]['mask'] = 
                        $options['mask'];
                } else {
                    Docxpresso\CreateDocument::$tables[$id]['mask'] = array();
                }
            }
            //if it has an associated class ovewrite the theme entry
            if (!empty(Docxpresso\CreateDocument::$theme) 
                && isset($options['style'])
                && isset(themes\ThemeManager::$CSSClasses[$options['style']])) {
                //update the CreateDocument::$tables array
                Docxpresso\CreateDocument::$tables[$id]['theme'] = 
                    $options['style'];
            } 
            //insert some info into the static $tables array
            //the border prop will let us know if the table has been defined 
            //with a border attribute (HTML embedding)
            Docxpresso\CreateDocument::$tables[$id]['border-width'] = 0;
            Docxpresso\CreateDocument::$tables[$id]['border-color'] = '#000000';
            //the structure array will contain info about cell row and col spans 
            Docxpresso\CreateDocument::$tables[$id]['structure'] = array();
            if (\is_array($options['grid'])){
                $ns = 'urn:oasis:names:tc:opendocument:xmlns:style:1.0';
                foreach ($options['grid'] as $value) {
                    $column = $node->ownerDocument
                                   ->createElementNS($tableNS, 
                                                     'table:table-column');
                    $colId = commons\Utilities::generateId();
                    $column->setAttributeNS($tableNS, 
                                            'table:style-name',
                                            $colId);
                    $st = $node->ownerDocument
                               ->createElementNS($ns,'style:style');
                    $st->setAttributeNS($ns, 'style:name', $colId);
                    $st->setAttributeNS($ns, 'style:family', 'table-column');
                    $stCol = $node
                                ->ownerDocument
                                ->createElementNS($ns, 
                                    'style:table-column-properties');
                    //Define the style of the column based on the grid values
                    if (strpos($value, '%') !== false) {
                        $value = str_replace('%', '*', $value);
                        $stCol->setAttributeNS($ns, 
                                               'style:rel-column-width',
                                               \trim($value));
                    } else {
                        $stCol->setAttributeNS($ns, 
                                               'style:column-width',
                                               \trim($value));
                    }
                    $table->appendChild($column);
                    //insert the styles into the style array
                    $st->appendChild($stCol);
                    Docxpresso\CreateDocument::$automaticStyles[$colId] = $st;
                    Docxpresso\CreateDocument::$styleTypes[$colId] = 'DOMNode';
                    Docxpresso\CreateDocument::$styleTarget[$colId] = $target;
                }
            } else {
                $column = $node->ownerDocument
                               ->createElementNS($tableNS, 
                                                 'table:table-column');
                $column->setAttributeNS($tableNS, 
                                        'table:number-columns-repeated',
                                        $options['grid']);
                $table->appendChild($column);
            }
            //check if there is a cursor within the node
            $cursors = $node->getElementsByTagName('customCursor');
            if ($cursors->length > 0) {
                $cursor = $cursors->item(0);
                $this->_newNode = $cursor->parentNode
                                         ->insertBefore($table, $cursor);
            } else {
                $this->_newNode = $node->appendChild($table);
            }
            $this->setNode($table);
            $this->setNodeType($table->nodeName);
        } else {
            $message = 'You can not add a table to the parent element.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
        }
    }  
    
    /**
     * Returns the default styles
     *
     * @param mixed $data
     * @return string
     * @access public
     * @static
     */
    public static function getDefaultStyles($data)
    {
        $defaultStyles = self::setDefaultStyles($data);
        return $defaultStyles;
    }
    
    /**
     * Sets the default styles
     *
     * @param mixed $data
     * @return string
     * @access public
     * @static
     */
    public static function setDefaultStyles($data)
    {
        //set the default styles
        $defaultStyles = 'border-collapse: collapse;';
        //try to compute the table width from the grid array
        $width = self::tableWidth($data);
        if (!empty($width)) {
            $defaultStyles .= 'width:' . $width . 'pt;';
        }
        return $defaultStyles;
    }
    
    /**
     * Computes the table width (in points) from the grid data
     *
     * @param mixed $data
     * @return int
     * @access public
     * @static
     */
    public static function tableWidth($data)
    {
        $width = 0;
        if (\is_array($data)) {
            foreach ($data as $value) {
               $val = commons\Utilities::convertUnits('pt', $value);
               if (!empty($val)) {
                   $val = \trim(\str_replace('pt', '', $val));
                   $width = $width + (float) $val;
               } else {
                   //In this case we assume that the table has no total width
                   //and should cover the whole page span
                   return;
               }
            }
        }
        return $width;
    }
    
    /**
     * Returns the table internal id
     *
     * @return string
     * @access public
     */
    public function getTableId()
    {
        return $this->_tableId;
    }
}