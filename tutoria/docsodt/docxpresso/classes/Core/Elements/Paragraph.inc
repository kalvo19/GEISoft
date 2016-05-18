<?php

/**
 * Paragraphs
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
 * Creates a paragraph element
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class Paragraph extends ODFElement
{
    /**
     * allowed parent elements
     * 
     * @var array
     * @access public
     * @static
     */
    public static $parentNodes = array(
        'chart:data-label'  => true,
        'chart:equation'  => true,
        'chart:footer'  => true,
        'chart:label-separator'  => true,
        'chart:legend'  => true,
        'chart:subtitle'  => true,
        'chart:title'  => true,
        'draw:caption'  => true,
        'draw:circle'  => true,
        'draw:connector'  => true,
        'draw:custom-shape'  => true,
        'draw:ellipse'  => true,
        'draw:image'  => true,
        'draw:line'  => true,
        'draw:measure'  => true,
        'draw:path'  => true,
        'draw:polygon'  => true,
        'draw:polyline'  => true,
        'draw:rect'  => true,
        'draw:regular-polygon'  => true,
        'draw:text-box'  => true,
        'form:textarea'  => true,
        'office:annotation'  => true,
        'office:change-info'  => true,
        'office:text'  => true,
        'style:footer'  => true,
        'style:footer-left'  => true,
        'style:header'  => true,
        'style:header-left'  => true,
        'style:region-center'  => true,
        'style:region-left'  => true,
        'style:region-right'  => true,
        'table:change-track-table-cell'  => true,
        'table:covered-table-cell'  => true,
        'table:error-message'  => true,
        'table:help-message'  => true,
        'table:table-cell'  => true,
        'text:deletion'  => true,
        'text:index-body'  => true,
        'text:index-title'  => true,
        'text:list-header'  => true,
        'text:list-item'  => true,
        'text:note-body'  => true,
        'text:numbered-paragraph' => true,
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
     * @param array $options
     * @access public
     */
    public function __construct($odfNode, $options)
    {
        $type = $options['type'];
        //check if it is a heading or a normal paragraph
        if (isset($options['level'])) {
            $level = $options['level'];
        } else {
            $level = 0;
        }
        $node = $odfNode->getNode();
        $styleId = $odfNode->getStyleId();
        $target = $odfNode->getTarget();
        parent::__construct($node, $odfNode, $target);
        if (isset(self::$parentNodes[$node->nodeName])) {
            $paragraph = $node->ownerDocument
                              ->createElement('text:' . $type);
            if (!empty($styleId)){
                $ns = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
                $paragraph->setAttributeNS($ns, 'text:style-name', $styleId);
            }
            if (!empty($level) 
                && \is_int($level) 
                && $level > 0
                && $type = 'h') {
                $ns = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
                $paragraph->setAttributeNS($ns, 'text:outline-level', $level);
            }
            //check if there is a cursor within the node
            $cursors = $node->getElementsByTagName('customCursor');
            if ($cursors->length > 0) {
                $cursor = $cursors->item(0);
                $this->_newNode = $cursor->parentNode
                                         ->insertBefore($paragraph, $cursor);
            } else {
                $this->_newNode = $node->appendChild($paragraph);
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
     * @param mixed $data
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
     * @param mixed $data
     * @return string
     * @access public
     */
    public static function setDefaultStyles($data)
    {
        //set the default styles
        //we have removed the background-color: transparent property because
        //it spoiled docx conversion
        $defaultStyles = 'padding: 0pt;'; 
        if (!empty($data) && \is_int($data) && $data > 0) {
            $level = $data;
            $defaultStyles .= 'keep-with-next: always;';
            $defaultStyles .= 'keep-together: always;';
            $defaultStyles .= 'margin-top: 0.0277in;';
            $defaultStyles .= 'margin-bottom: 0in;';
            if ($level == 1) {
                $defaultStyles .= 'color: #2E74B5;';
                $defaultStyles .= 'font-size: 16pt;';
            } else if ($level == 2) {
                $defaultStyles .= 'color: #2E74B5;';
                $defaultStyles .= 'font-size: 13pt;';
            } else if($level == 3) {
                $defaultStyles .= 'color: #1F4D78;';
                $defaultStyles .= 'font-size: 12pt;';
            } else if($level == 4) {
                $defaultStyles .= 'color: #2E74B5;';
                $defaultStyles .= 'font-size: 11pt;';
                $defaultStyles .= 'font-style: italic;';
            } else if($level == 5) {
                $defaultStyles .= 'color: #2E74B5;';
                $defaultStyles .= 'font-size: 11pt;';
            } else if($level == 6) {
                $defaultStyles .= 'color: #1F4D78;';
                $defaultStyles .= 'font-size: 11pt;';
            }
        }
        return $defaultStyles;
    }
}