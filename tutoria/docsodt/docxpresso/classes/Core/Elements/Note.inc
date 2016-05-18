<?php

/**
 * Notes
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
 * Creates a footnote or endnote element
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class Note extends ODFElement
{
    /**
     * allowed parent elements
     * 
     * @var array
     * @access public
     * @static
     */
    public static $parentNodes = array(
        'text:a' => true, 
        'text:h' => true, 
        'text:meta' => true, 
        'text:meta-field' => true, 
        'text:p' => true, 
        'text:ruby-base' => true,
        'text:span' => true,
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
     * @param string $type
     * @param array $options
     * @access public
     */
    public function __construct($odfNode, $type, $options = array())
    {        
        $node = $odfNode->getNode();
        $styleId = $odfNode->getStyleId();
        $target = $odfNode->getTarget();
        parent::__construct($node, $odfNode, $target);
        if (isset(self::$parentNodes[$node->nodeName])) {
            //create a wrapping span so we can set the standard text styles to it
            $spanNode = $node->ownerDocument->createElement('text:span');
            $spanNode->setAttribute('text:style-name', '_note');
            $textNote = $node->ownerDocument->createElement('text:note');
            $textNote->setAttribute('text:note-class', $type);
            $citation = $node->ownerDocument
                             ->createElement('text:note-citation');
            if (isset($options['label'])) {
                $citation->setAttribute('text:label', $options['label']);
            }
            $textNote->appendChild($citation);
            $noteBody = $node->ownerDocument->createElement('text:note-body');
            $textNote->appendChild($noteBody);
            
            $spanNode->appendChild($textNote);
            $node->appendChild($spanNode);
            $this->_newNode = $noteBody;
            $this->setNode($this->_newNode);
            $this->setNodeType($this->_newNode->nodeName);
        } else {
            $message = 'You can not add a note to the parent element.';
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
        $defaultStyles = 'font-size: 10pt;';
        return $defaultStyles;
    }
   
}