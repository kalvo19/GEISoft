<?php

/**
 * Link
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
 * Creates a link element
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class Link extends ODFElement
{
    /**
     * allowed parent nodes
     * 
     * @var array
     * @access public
     * @static
     */
    public static $parentNodes = array( 
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
    public function __construct($odfNode, $options)
    {
        $node = $odfNode->getNode();
        $styleId = $odfNode->getStyleId();
        $target = $odfNode->getTarget();
        parent::__construct($node, $odfNode, $target);
        if (isset(self::$parentNodes[$node->nodeName])) {
            $linkNode = $node->ownerDocument->createElement('text:a');
            //for links always create the style name even if empty
            $ns = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
            $linkNode->setAttributeNS($ns, 'text:style-name', $styleId);
            if (isset($options['text'])) {
                $span = $node->ownerDocument
                             ->createElement('text:span', $options['text']);
                $span->setAttribute('text:style-name', $styleId);
                $linkNode->appendChild($span);  
                //$linkNode->nodeValue = $options['text']; 
            }
            
            if (!empty($options['url'])) {
                $linkNode->setAttribute('xlink:href', $options['url']);
                $linkNode->setAttribute('xlink:type', 'simple');
                $linkNode->setAttribute('xlink:show', 'new');
            }
            if (!empty($options['title'])) {
                $linkNode->setAttribute('office:title', $options['title']);
            }
            if (!empty($options['target'])) {
                $linkNode->setAttribute('office:target-frame-name', 
                                         $options['target']);
            } else if (empty($options['target'])) {
                $linkNode->setAttribute('office:target-frame-name', 
                                         '_blank');
            }
            //check if there is a cursor within the node
            $cursors = $node->getElementsByTagName('customCursor');
            if ($cursors->length > 0) {
                $cursor = $cursors->item(0);
                $this->_newNode = $cursor->parentNode
                                         ->insertBefore($linkNode, $cursor);
            } else {
                $this->_newNode = $node->appendChild($linkNode);
            }
            $this->setNode($this->_newNode);
            $this->setNodeType($this->_newNode->nodeName);
        } else {
            $message = 'You can not add text to the parent element.';
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
        $defaultStyles = 'color: #0000cc;';
        $defaultStyles .= 'text-decoration: underline;';
        return $defaultStyles;
    }
   
}