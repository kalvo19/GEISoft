<?php

/**
 * Form control
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
 * Creates a form control element
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class Control extends ODFElement
{
    /**
     * allowed parent nodes
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
        //'office:text' => true,//not supported by OpenOffice 4.1
        'presentation:notes' => true,
        'style:handout-master' => true,
        'style:master-page' => true,
        'table:covered-table-cell' => true,
        'table:shapes' => true,
        'table:table-cell' => true,
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
        'office:fragment' => true,
    );
    
    /**
     * form control type
     * 
     * @var string
     * @access private
     * @static
     */
    private $_type;
       
    /**
     * Construct
     *
     * @param ODFElement $odfNode
     * @param string $name
     * @param string $type
     * @param array $options
     * @access public
     */
    public function __construct($odfNode, $name, $type, $options)
    {
        if (!isset($options['style'])) {
            $options['style'] = '';
        }
        $this->_type = $type;
        $node = $odfNode->getNode();
        $styleId = $odfNode->getStyleId();
        $target = $odfNode->getTarget();
        parent::__construct($node, $odfNode, $target);
        //determine the anchor depending on the float and position properties
        //the possible values are:
        //  as-char: the frame is inline
        //  char: the anchor is relative to the previous character
        //  page: the anchor is relative to the page containing the previous 
        //  char
        $float = parser\CSSPropsLexer::extractSingleProperty('float', 
                                                             $options['style']);
        if (!empty($float)) {
            $anchor = 'char';
        } else {
            $anchor = 'as-char';
        } 
        //overwrite the anchor value if there is absolute or relative 
        //positioning
        $pos = parser\CSSPropsLexer::extractSingleProperty('position', 
                                                           $options['style']);
        if ( !empty($pos) && $pos == 'absolute') {
            $anchor = 'page';
        } else if ( !empty($pos) && $pos == 'relative') {
            $anchor = 'char';
        }
        //we use the top and left properties in a looser way that in strict
        //"CSS mode" in order to facilitate the precise positioning of elements
        $top = parser\CSSPropsLexer::extractSingleProperty('top', 
                                                           $options['style']);
        $left = parser\CSSPropsLexer::extractSingleProperty('left', 
                                                            $options['style']);
        if ( !empty($top)) {
            $y = $top;
        }
        if ( !empty($left)) {
            $x = $left;
        }
        $ns = 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0';
        if (isset(self::$parentNodes[$node->nodeName])) {
            $spanNode = $node->ownerDocument->createElement('text:span');
            $controlNode = $node->ownerDocument->createElementNS(
                                                    $ns,
                                                    'draw:control');
            //set the required attributes
            if (!empty($styleId)){
                $controlNode->setAttributeNS($ns, 'draw:style-name', $styleId);
                $controlNode->setAttributeNS($ns, 
                                             'draw:text-style-name', 
                                             'text-' . $styleId);
            }
            $controlNode->setAttributeNS($ns, 'draw:control', $name);
            //Check if it is worth the extra request
            if ( isset($options['style']['z-index'])) {
                $controlNode->setAttributeNS($ns,
                                             'draw:z-index', 
                                             $options['style']['z-index']);
            } else {
                $controlNode->setAttributeNS($ns, 'draw:z-index', '0');
            }
            $nsText = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
            $controlNode->setAttributeNS($nsText, 'text:anchor-type', $anchor);
            //for odf and pdf generation we need to know the page number for
            //absolutely positioned elements because Open Office does not follow
            //the standard!!
            if (isset($options['pageNumber'])) {
                $controlNode->setAttribute('text:anchor-page-number', 
                                         $options['pageNumber']);
            }
            //now let us parse the dimension properties
            $w = parser\CSSPropsLexer::extractSingleProperty('width', 
                                                             $options['style']);
            $h = parser\CSSPropsLexer::extractSingleProperty('height', 
                                                             $options['style']);
            //if width and/or height are not explicitely given we estimate the
            //"best fit" size
            if (empty($w) || empty($h)) {
                $dimension = $this->_getDimensions($type, $options);
                if (empty($w)) {
                    $w = $dimension['width'];
                }
                if (empty($h)) {
                    $h = $dimension['height'];
                }
            }
            $nsSVG = 'urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0';
            $controlNode->setAttributeNS($nsSVG, 'svg:width', $w);
            $controlNode->setAttributeNS($nsSVG, 'svg:height', $h);
            //if is set $x
            if (isset($x)) {
               $controlNode->setAttributeNS($nsSVG, 'svg:x', $x); 
            }
            //if is set $y
            if (isset($y)) {
               $controlNode->setAttributeNS($nsSVG, 'svg:y', $y); 
            }
            $spanNode->appendChild($controlNode);
            //check if there is a cursor within the node
            $cursors = $node->getElementsByTagName('customCursor');
            if ($cursors->length > 0) {
                $cursor = $cursors->item(0);
                $this->_newNode = $cursor->parentNode
                                         ->insertBefore($spanNode, $cursor);
            } else {
                $this->_newNode = $node->appendChild($spanNode);
            }
            $this->setNode($this->_newNode->firstChild);
            $this->setNodeType($this->_newNode->firstChild->nodeName);
        } else {
            $message = 'You can not add a control to the parent element.';
            commons\DOCXPRESSOLogger::logger($message, 'critical');
        }
    }
    
    /**
     * Returns the default styles
     *
     * @param int $data
     * @param string $type
     * @return string
     * @access public
     */
    public static function getDefaultStyles($data, $type = '')
    {
        $defaultStyles = self::setDefaultStyles($data, $type);
        return $defaultStyles;
    }
    
    /**
     * Sets the default styles
     *
     * @param int $data
     * @param string $type
     * @return string
     * @access public
     */
    public static function setDefaultStyles($data, $type = '')
    {
        //set the default styles
        $defaultStyles = 'vertical-align: top;';
        if ($type == 'button') {
            $defaultStyles .= 'text-align: center;';
        }
        return $defaultStyles;
    }
    
    /**
     * Sets the default width and height for the control
     *
     * @param string $type
     * @param array $options
     * @return array
     * @access private
     */
    private function _getDimensions($type, $options)
    {
        if ($this->_type == 'checkbox' || $this->_type == 'radioButton') {
            $dimensions = array('width' => '0.370cm', 'height' => '0.500cm');
        } else if ($this->_type == 'inputField') {
            $dimensions = array('width' => '3.370cm', 'height' => '0.550cm');
        } else if ($this->_type == 'textArea') {
            $dimensions = array('width' => '5.370cm', 'height' => '1.380cm');
        } else if ($this->_type == 'select') {
            $dimensions = array('width' => '6.370cm', 'height' => '0.550cm');
        } else if ($this->_type == 'button') {
            $dimensions = array('width' => '3cm', 'height' => '1.200cm');
        } else {
            $dimensions = array('width' => '0cm', 'height' => '0cm');
        }
        return $dimensions;
    }
   
}