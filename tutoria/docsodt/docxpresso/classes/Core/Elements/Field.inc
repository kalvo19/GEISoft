<?php

/**
 * Field
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
 * Creates a field element
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class Field extends ODFElement
{
    /**
     * allowed parent nodes
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
            if (!empty($styleId)){
                $ns = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
                $spanNode->setAttributeNS($ns, 'text:style-name', $styleId);
            }
            $fieldNode = $node->ownerDocument->createElement('text:' . $type);
            if (isset($options['default-value'])) {
                $fieldNode->nodeValue = $options['default-value'];
            } else {
                $fieldNode->nodeValue = 'press F9 to update';
            }
            //take now care of special formatting properties for different field
            //types
            if ($type == 'bookmark-ref') {
                if (isset($options['reference-format'])) {
                    $fieldNode->setAttribute('text:reference-format', 
                                             $options['reference-format']);
                } else {
                    $fieldNode->setAttribute('text:reference-format', 'text');
                }
                if (isset($options['reference-name'])){
                    $fieldNode->setAttribute('text:ref-name', 
                                             $options['reference-name']);
                } else {
                    $message = 'Without a reference name this field is useless';
                    commons\DOCXPRESSOLogger::logger($message, 'info');
                }
            } else if ($type == 'page-count') {
                if (isset($options['number-format'])) {
                    $fieldNode->setAttribute('style:num-format', 
                                             $options['number-format']);
                } else {
                    $fieldNode->setAttribute('style:num-format', 1);
                }
            } else if ($type == 'page-number') {
                if (isset($options['number-format'])) {
                    $fieldNode->setAttribute('style:num-format', 
                                             $options['number-format']);
                } else {
                    $fieldNode->setAttribute('style:num-format', 1);
                }
                if (isset($options['offset'])) {
                    $fieldNode->setAttribute('style:page-adjust', 
                                             $options['offset']);
                }
            } else if ($type == 'date') {
                $stId = 'data_style_' . uniqid();
                $fieldNode->setAttribute('style:data-style-name', $stId);
                $dateNode = $node->ownerDocument
                                 ->createElement('number:date-style');
                $dateNode->setAttribute('style:name', $stId);
                if (!isset($options['date-format'])) {
                    $options['date-format'] = array('month', 
                                                    '/', 
                                                    'day', 
                                                    '/', 
                                                    'year');
                }
                foreach ($options['date-format'] as $value) {
                    if ($value == 'day') {
                        $number = $node->ownerDocument
                                       ->createElement('number:day');
                        $number->setAttribute('number:style', 'long');
                    } else if ($value == 'day-short') {
                        $number = $node->ownerDocument
                                       ->createElement('number:day');
                        $number->setAttribute('number:style', 'short');
                    } else if ($value == 'day-of-week') {
                        $number = $node->ownerDocument
                                       ->createElement('number:day-of-week');
                        $number->setAttribute('number:style', 'long');
                    } else if ($value == 'day-of-week-short') {
                        $number = $node->ownerDocument
                                       ->createElement('number:day-of-week');
                        $number->setAttribute('number:style', 'short');
                    } else if ($value == 'month') {
                        $number = $node->ownerDocument
                                       ->createElement('number:month');
                        $number->setAttribute('number:style', 'long');
                    } else if ($value == 'month-short') {
                        $number = $node->ownerDocument
                                       ->createElement('number:month');
                        $number->setAttribute('number:style', 'short');
                    } else if ($value == 'month-of-year') {
                        $number = $node->ownerDocument
                                       ->createElement('number:month');
                        $number->setAttribute('number:style', 'long');
                        $number->setAttribute('number:textual', 'true');
                    } else if ($value == 'month-of-year-short') {
                        $number = $node->ownerDocument
                                       ->createElement('number:month');
                        $number->setAttribute('number:style', 'short');
                        $number->setAttribute('number:textual', 'true');
                    } else if ($value == 'year') {
                        $number = $node->ownerDocument
                                       ->createElement('number:year');
                        $number->setAttribute('number:style', 'long');
                    } else if ($value == 'year-short') {
                        $number = $node->ownerDocument
                                       ->createElement('number:year');
                        $number->setAttribute('number:style', 'short');
                    }  else {
                        $number = $node->ownerDocument
                                       ->createElement('number:text');
                        $number->nodeValue = $value;
                    }
                    $dateNode->appendChild($number);
                }
                Docxpresso\CreateDocument::$automaticStyles[$stId] = $dateNode;
                Docxpresso\CreateDocument::$styleTypes[$stId] = 'DOMNode';
                Docxpresso\CreateDocument::$styleTarget[$stId] = $target;
            }
            $spanNode->appendChild($fieldNode);
            $this->_newNode = $node->appendChild($spanNode);
            $this->setNode($this->_newNode);
            $this->setNodeType($this->_newNode->nodeName);
        } else {
            $message = 'You can not add a field to the parent element.';
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