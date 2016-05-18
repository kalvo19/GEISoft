<?php

/**
 * Table Row
 *
 * @copyright  Copyright(c)2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Core\Elements;

use Docxpresso\Commons as commons;
use Docxpresso;

/**
 * Creates a table row
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class Row extends ODFElement
{
    /**
     * acceptable parent nodes
     * 
     * @var array
     * @access public
     * @static
     */
    public static $parentNodes = array(
        'table:table' => true, 
        'table:table-header-rows' => true, 
        'table:table-row-group' => true,
        'table:table-rows' => true,
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
     * row id
     * 
     * @var string
     * @access private
     */
    private $_rowId;
    
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
        $this->_tableId = $odfNode->getTableId();
        $id = $this->_tableId;
        $table =& Docxpresso\CreateDocument::$tables[$id]['structure'];
        parent::__construct($node, $odfNode, $target);
        //update the $tables static array
        $num = \count($table);
        $this->_rowId = $id . '_' . $num;
        //check if there are lacking covered table cells in the previous row
        if ($num > 1) {
           $gridCount = \count($table[$num - 2]); 
           $prevCount = \count($table[$num - 1]);
           if ($prevCount < $gridCount) {
               //there are lacking entries in the previous row
               $lackingCells = $gridCount - $prevCount;
               for ($j = 0; $j < $lackingCells; $j++) {
                   $table[$num - 1][$prevCount + $j] = 
                   $table[$num - 2][$prevCount + $j] - 1;
               }
           }
        }
        //create the new row
        $table[$num] = array();
        if (isset(self::$parentNodes[$node->nodeName])) {
            $tableNS = 'urn:oasis:names:tc:opendocument:xmlns:table:1.0';
            $row = $node->ownerDocument->createElementNS($tableNS,
                                                         'table:table-row');
            if (!empty($styleId)){
                $ns = 'urn:oasis:names:tc:opendocument:xmlns:table:1.0';
                $row->setAttributeNS($ns, 'table:style-name', $styleId);
            }
            $row->setAttribute('xml:id', $this->_rowId);
            //if the 'header' option is set to true we have to insert the row
            //node within the table:table-header-rows element
            if (isset($options['header']) && $options['header']){
                $headerNodes = $node->getElementsByTagName('table-header-rows');
                if ($headerNodes->length > 0) {
                    $node = $node->getElementsByTagName('table-header-rows')
                                 ->item(0);
                } else {
                    //create the table-headers-row element
                    $headerRow = $node->ownerDocument
                                ->createElement('table:table-header-rows');
                    $node= $node->appendChild($headerRow);
                }
            }
            //check if there is a cursor within the node
            $cursors = $node->getElementsByTagName('customCursor');
            if ($cursors->length > 0) {
                $cursor = $cursors->item(0);
                $this->_newNode = $cursor->parentNode
                                         ->insertBefore($row, $cursor);
            } else {
                $this->_newNode = $node->appendChild($row);
            }
            $this->setNode($this->_newNode);
            $this->setNodeType($this->_newNode->nodeName);
        } else {
            $message = 'You can not add a row to the parent element.';
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
        $defaultStyles = '';
        return $defaultStyles;
    }
    
    /**
     * Returns the table row internal id
     *
     * @return string
     * @access public
     */
    public function getRowId()
    {
        return $this->_rowId;
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