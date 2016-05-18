<?php

/**
 * List item
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
 * Creates a list item element
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class ListItem extends ODFElement
{
    /**
     * allowed parent nodes
     * 
     * @var array
     * @access public
     * @static
     */
    public static $parentNodes = array(
        'text:list' => true,
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
     * @param array $options
     * @access public
     */
    public function __construct($odfNode, $options = array())
    {
        $node = $odfNode->getNode();
        $styleId = $odfNode->getStyleId();
        $target = $odfNode->getTarget();
        parent::__construct($node, $odfNode, $target);
        if (isset(self::$parentNodes[$node->nodeName])) {
            $item = $node->ownerDocument->createElement('text:list-item');
            //TODO: check if we should implement the list-style-override 
            //attribute
            /*
            if (!empty($styleId)){
                $ns = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
                $item->setAttributeNS($ns, 'text:style-override', $styleId);
            }*/
            //check if there is a cursor within the node
            $cursors = $node->getElementsByTagName('customCursor');
            if ($cursors->length > 0) {
                $cursor = $cursors->item(0);
                $this->_newNode = $cursor->parentNode
                                         ->insertBefore($item, $cursor);
            } else {
                $this->_newNode = $node->appendChild($item);
            }
            $this->setNode($this->_newNode);
            $this->setNodeType($this->_newNode->nodeName);
        } else {
            $message = 'You can not add a paragraph to the parent element.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
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