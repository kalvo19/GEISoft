<?php

/**
 * Textboxes
 *
 * @copyright  Copyright(c)2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Core\Elements;

/**
 * The TextBox class generates a textbox element
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class TextBox extends ODFElement
{    
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
        //create the text box node
        $textBox = $node->ownerDocument->createElement('draw:text-box');
        //check if there is already a frame
        if ($node->nodeName == 'draw:frame') {
            $node->appendChild($textBox);
            $this->setNode($textBox);
            $this->setNodeType($textBox->nodeName);
        } else {
            $frame = $this->frame($options, true);
            $frameNode = $frame->getNode();
            $frameNode->appendChild($textBox);
            $this->setNode($textBox);
            $this->setNodeType($textBox->nodeName);
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