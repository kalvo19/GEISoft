<?php

/**
 * Table cell
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
 * Creates a table cell element
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class Cell extends ODFElement
{
    /**
     * allowed parent nodes
     * 
     * @var array
     * @access public
     * @static
     */
    public static $parentNodes = array(
        'table:table-row' => true,
        //also include the custom office:fragment tag
        'office:fragment' => true,
    );
    
    /**
     * number of columns
     * 
     * @var int
     * @access private
     */
    private $_col;
    
    /**
     * a reference to the new created DOMNode
     * 
     * @var DOMNode
     * @access private
     */
    private $_newNode;
    
    /**
     * the corresponding table id
     * 
     * @var string
     * @access private
     */
    private $_tableId;
    
    /**
     * number of rows 
     * 
     * @var int
     * @access private
     */
    private $_row;
       
    /**
     * Construct
     *
     * @param ODFElement $odfNode
     * @param array $options
     * @access public
     */
    public function __construct($odfNode, $options = array())
    {
        $node = $odfNode->getNode();
        $styleId = $odfNode->getStyleId();
        $target = $odfNode->getTarget();
        $this->_tableId = $odfNode->getTableId();
        $id = $this->_tableId;
        $table =& Docxpresso\CreateDocument::$tables[$id]['structure'];
        if (isset($options['rowspan'])) {
            $rowspan = $options['rowspan'];
        } else {
            $rowspan = 1;
        }
        if (isset($options['colspan'])) {
            $colspan = $options['colspan'];
        } else {
            $colspan = 1;
        }
        parent::__construct($node, $odfNode, $target);
        //check the $tables static array
        //determine the current row and column
        $row = \count($table) -1;
        $col = \count($table[$row]);
        $this->_row = $row;
        $this->_col = $col;
        //if there is a previous row we should determine if there were some
        //cells with rowspan
        if ($row > 0) {
            while ($table[$row - 1][$col] > 1) {
                //insert a covered cell
                if (isset(self::$parentNodes[$node->nodeName])) {
                    $tableNS = 'urn:oasis:names:tc:opendocument:xmlns:table:1.0';
                    $cc = $node->ownerDocument
                               ->createElementNS($tableNS,
                                                 'table:covered-table-cell');
                    //check if there is a cursor within the node
                    $cursors = $node->getElementsByTagName('customCursor');
                    if ($cursors->length > 0) {
                        $cursor = $cursors->item(0);
                        $cursor->parentNode->insertBefore($cc, $cursor);
                    } else {
                        $node->appendChild($cc);
                    }
                }
                //update the array
                $table[$row][$col] = $table[$row - 1][$col] -1;
                $col++;
            }
        }
        //insert the actual cell
        $table[$row][$col] = $rowspan;
        if (isset(self::$parentNodes[$node->nodeName])) {
            $tableNS = 'urn:oasis:names:tc:opendocument:xmlns:table:1.0';
            $cell = $node->ownerDocument->createElementNS($tableNS,
                                                          'table:table-cell');
            if (!empty($styleId)){
                $ns = 'urn:oasis:names:tc:opendocument:xmlns:table:1.0';
                $cell->setAttributeNS($ns, 'table:style-name', $styleId);
            }
            if ($colspan > 1) {
                $cell->setAttribute('table:number-columns-spanned', $colspan);
            }
            if ($rowspan > 1) {
                $cell->setAttribute('table:number-rows-spanned', $rowspan);
            }
            //check if there is a cursor within the node
            $cursors = $node->getElementsByTagName('customCursor');
            if ($cursors->length > 0) {
                $cursor = $cursors->item(0);
                $this->_newNode = $cursor->parentNode
                                         ->insertBefore($cell, $cursor);
            } else {
                $this->_newNode = $node->appendChild($cell);
            }
            $this->setNode($this->_newNode);
            $this->setNodeType($this->_newNode->nodeName);
            //update the $tables static array and insert covered cells as needed
            for ($j = 0; $j < $colspan; $j++) {
                $table[$row][$col] = $rowspan;
                $col++;
                //insert a covered cell
                if ($j > 0) {
                    $tableNS = 'urn:oasis:names:tc:opendocument:xmlns:table:1.0';
                    $cc = $node->ownerDocument
                               ->createElementNS($tableNS,
                                                 'table:covered-table-cell');
                    //check if there is a cursor within the node
                    $cursors = $node->getElementsByTagName('customCursor');
                    if ($cursors->length > 0) {
                        $cursor = $cursors->item(0);
                        $cursor->parentNode->insertBefore($cc, $cursor);
                    } else {
                        $node->appendChild($cc);
                    }
                }
            }
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
        $defaultStyles = '';
        return $defaultStyles;
    }
    
    /**
     * Returns the current column number (1 based)
     *
     * @return string
     * @access public
     */
    public function getColumn()
    {
        return $this->_col;
    }
    
    /**
     * Returns the table internal id
     *
     * @return string
     * @access public
     */
    public function getTableId()
    {
        return $this->tableId;
    }
    
    /**
     * Returns the current row number (0 based)
     *
     * @return string
     * @access public
     */
    public function getRow()
    {
        return $this->_row;
    }
    
}