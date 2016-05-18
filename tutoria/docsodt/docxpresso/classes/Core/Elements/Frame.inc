<?php

/**
 * Frame
 *
 * @copyright  Copyright(c)2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Core\Elements;

use Docxpresso\Commons as commons;
use Docxpresso\Parser as parser;
use Docxpresso;

/**
 * Creates a frame element
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class Frame extends ODFElement
{
    /**
     * allowed paernt elements
     * 
     * @var array
     * @access public
     * @static
     */
    public static $parentNodes = array(
        'draw:a' => true,
        'draw:g' => true,
        'draw:page' => true,
        'draw:text-box' => true,
        'office:image' => true,
        //'office:text' => true,//not supported by OpenOffice 4.1
        'presentation:notes' => true,
        'style:handout-master' => true,
        'style:master-page' => true,
        'table:covered-table-cell' => true,
        'table:shapes' => true,
        //'table:table-cell' => true,//not supported by OpenOffice 4.1
        'text:a' => true,
        'text:deletion' => true,
        'text:h' => true,
        'text:index-body' => true,
        'text:index-title' => true,
        'text:meta' => true,
        'text:meta-field' => true,
        'text:note-body' => true,
        'text:p' => true,
        'text:ruby-base' => true,
        'text:section' => true,
        'text:span' => true,
        //also include the custom office:fragment tag
        //'office:fragment' => true,////not supported by OpenOffice 4.1
    );
    
    /**
     * the elements that accept span childs
     * @var array
     * @access public
     * @static
     */
    public static $acceptSpans = array(
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
     * Construct
     *
     * @param ODFElement $odfNode
     * @param array $options
     * @access public
     */
    public function __construct($odfNode, $options = array())
    {
        if (!isset($options['style'])) {
            $options['style'] = '';
        }
        $node = $odfNode->getNode();
        $styleId = $odfNode->getStyleId();
        $target = $odfNode->getTarget();
        parent::__construct($node, $odfNode, $target);
        $data = self::parsePositioning($options, true);
        if (isset(self::$parentNodes[$node->nodeName])) {
            if (isset(self::$acceptSpans[$node->nodeName])) {
                $spanNode = $node->ownerDocument->createElement('text:span');
            } else {
                $spanNode = $node->ownerDocument->createElement('text:p');
            }
            $frameNode = $node->ownerDocument->createElementNS(
                    'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0',
                    'draw:frame');
            //set the required attributes
            if (!empty($styleId)){
                $ns = 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0';
                $frameNode->setAttributeNS($ns, 'draw:style-name', $styleId);
                $frameNode->setAttributeNS($ns, 
                                           'draw:text-style-name', 
                                           'text-' . $styleId);
            }
            $frameNode->setAttribute('draw:name', \uniqid());
            $textNS = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
            $frameNode->setAttributeNS($textNS, 
                                       'text:anchor-type', 
                                       $data['anchor']);
            //for ODF and PDF generation we need to know the page number for
            //absolutely positioned elements because Open Office does not follow
            //the standard!!
            if (isset($options['pageNumber'])) {
                $frameNode->setAttribute('text:anchor-page-number', 
                                         $options['pageNumber']);
            }
            $svg = 'urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0';
            if (!empty($data['width'])) {
                $frameNode->setAttributeNS($svg, 'svg:width', $data['width']);
            } else if (isset($options['chart']) && $options['chart']) {
                $frameNode->setAttributeNS($svg, 'svg:width', '8cm');
            }
            if (!empty($data['height'])) {
                $frameNode->setAttributeNS($svg, 'svg:height', $data['height']);
            } else if (isset($options['chart']) && $options['chart']) {
                $frameNode->setAttributeNS($svg, 'svg:height', '7cm');
            } 
            //if is set $x
            if (isset($data['x'])) {
               $frameNode->setAttributeNS($svg, 'svg:x', $data['x']); 
            }
            //if is set $y
            if (isset($data['y'])) {
               $frameNode->setAttributeNS($svg, 'svg:y', $data['y']); 
            }
            $spanNode->appendChild($frameNode);
            
            //check if there is a cursor within the node
            $cursors = $node->getElementsByTagName('customCursor');
            if ($cursors->length > 0) {
                $cursor = $cursors->item(0);
                $this->_newNode = $cursor->parentNode
                                         ->insertBefore($spanNode, $cursor);
            } else {
                $this->_newNode = $node->appendChild($spanNode);
            }
            //In this case we have to include an extra firstChild to overcome
            //the first wrapping span node
            $this->setNode($this->_newNode->firstChild);
            $this->setNodeType($this->_newNode->firstChild->nodeName);
        } else {
            $message = 'You can not add a frame to the parent element.';
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
        $defaultStyles = 'border: none;';
        return $defaultStyles;
    }
    
    /**
     * Parse frame styles
     *
     * @param array $options
     * @param bool $enforce
     * @param bool $external if true $options are set with the style method
     * @return array
     * @access public
     */
    public static function parsePositioning($options, 
                                            $enforce = true, 
                                            $external = false)
    {
        $data = array();
        //determine the anchor depending on the float and position properties
        //the possible values are:
        //  as-char: the frame is inline
        //  char: the anchor is relative to the previous character
        //  page: the anchor is relative to the page containing the previous 
        //  char
        if (\is_string($options['style'])) {
            $float = parser\CSSPropsLexer::extractSingleProperty('float', 
                                                             $options['style']);
        } else if (isset($options['style']['float'])) {
            $float = $options['style']['float'];
        }
        if (!empty($float)) {
            //RTF conversion fails if the anchor is not set up to paragraph
            //this is achived by the private CreateDocument method _repairFloats
            $anchor = 'char';
        } else if ($enforce) {
            $anchor = 'as-char';
        } 
        //overwrite the anchor value if there is absolute or relative 
        //positioning
        if (\is_string($options['style'])) {
            $pos = parser\CSSPropsLexer::extractSingleProperty('position', 
                                                           $options['style']);
        } else if (isset($options['style']['position'])) {
            $pos = $options['style']['position'];
        }
        if ( !empty($pos) && $pos == 'absolute') {
            $anchor = 'page';
        } else if ( !empty($pos) && $pos == 'relative') {
            $anchor = 'char';
        }
        if (!empty($anchor)){
            $data['anchor'] = $anchor;
        }
        
        //we use the top and left properties in a looser way that in strict
        //"CSS mode" in order to facilitate the precise positioning of elements
        if (\is_string($options['style'])) {
            $top = parser\CSSPropsLexer::extractSingleProperty('top', 
                                                           $options['style']);
        } else if (isset($options['style']['top'])) {
            $top = $options['style']['top'][0] . $options['style']['top'][1];
        }
        if (\is_string($options['style'])) {
            $left = parser\CSSPropsLexer::extractSingleProperty('left', 
                                                           $options['style']);
        } else if (isset($options['style']['left'])) {
            $left = $options['style']['left'][0] . $options['style']['left'][1];
        }
        if ( !empty($top)) {
            $data['y'] = $top;
        }
        if ( !empty($left)) {
            $data['x'] = $left;
        }
        
        //now let us parse the dimension properties
        if (\is_string($options['style'])) {
            $w = parser\CSSPropsLexer::extractSingleProperty('width', 
                                                         $options['style']);
        } else if (isset($options['style']['width'])) {
            $w = $options['style']['width'][0] . $options['style']['width'][1];
        }
        //if there is no width set we impose a default of 5cm
        if (empty($w) && !isset($options['math']) && !$external) {
           $w = '5cm'; 
        }
        if (\is_string($options['style'])) {
            $h = parser\CSSPropsLexer::extractSingleProperty('height', 
                                                         $options['style']);
        } else if (isset($options['style']['height'])) {
            $h = $options['style']['height'][0] . $options['style']['height'][1];
        }
        if (!empty($w)) {
            $data['width'] = $w;
        }
        if (!empty($h)) {
            $data['height'] = $h;
        }    
        return $data;
    }
   
}