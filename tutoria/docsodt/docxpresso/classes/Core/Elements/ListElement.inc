<?php

/**
 * List Element
 *
 * @copyright  Copyright(c)2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Core\Elements;

use Docxpresso\Commons as commons;


/**
 * Creates a list element
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class ListElement extends ODFElement
{
    /**
     * allowed parent nodes
     * 
     * @var array $parentNodes
     * @access public
     * @static
     */
    public static $parentNodes = array(
        'draw:caption' => true,
        'draw:circle' => true,
        'draw:connector' => true,
        'draw:custom-shape' => true,
        'draw:ellipse' => true,
        'draw:image' => true,
        'draw:line' => true,
        'draw:measure' => true,
        'draw:path' => true,
        'draw:polygon' => true,
        'draw:polyline' => true,
        'draw:rect' => true,
        'draw:regular-polygon' => true,
        'draw:text-box' => true,
        'office:annotation' => true,
        'office:text' => true,
        'style:footer' => true,
        'style:footer-left' => true,
        'style:header' => true,
        'style:header-left' => true,
        'table:covered-table-cell' => true,
        'table:table-cell' => true,
        'text:deletion' => true,
        'text:index-body' => true,
        'text:index-title' => true,
        'text:list-header' => true,
        'text:list-item' => true,
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
     * Construct
     *
     * @param ODFElement $odfNode
     * @param string $listId
     * @param int $level
     * @access public
     */
    public function __construct($odfNode, $listId, $level)
    {
        $node = $odfNode->getNode();
        $target = $odfNode->getTarget();
        parent::__construct($node, $odfNode, $target);
        if (isset(self::$parentNodes[$node->nodeName])) {
            $list = $node->ownerDocument->createElement('text:list');
            $ns = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
            $list->setAttributeNS($ns, 'text:style-name', $listId);
            $list->setAttribute('xml:id', $listId . '_' . $level);
            //check if there is a cursor within the node
            $cursors = $node->getElementsByTagName('customCursor');
            if ($cursors->length > 0) {
                $cursor = $cursors->item(0);
                $this->_newNode = $cursor->parentNode
                                         ->insertBefore($list, $cursor);
            } else {
                $this->_newNode = $node->appendChild($list);
            }
            $this->setNode($this->_newNode);
            $this->setNodeType($this->_newNode->nodeName);
        } else {
            $message = 'You can not add a list to the parent element.';
            commons\DOCXPRESSOLogger::logger($message, 'critical');
        }
    }  
    
    /**
     * Returns the default styles
     *
     * @param int $data
     * @return string
     * @access public
     */
    public static function getDefaultStyles($data)
    {
        $defaultStyles = self::setDefaultStyles($data);
        return $defaultStyles;
    }
    
    /**
     * Sets the default styles
     *
     * @param int $data
     * @return string
     * @access public
     */
    public static function setDefaultStyles($data)
    {
        //set the default styles
        $defaultStyles = '';
        return $defaultStyles;
    }
}