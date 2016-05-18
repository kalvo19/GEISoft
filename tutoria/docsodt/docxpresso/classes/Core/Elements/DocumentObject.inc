<?php

/**
 * Document object
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
use Docxpresso\Core\Charts as charts;
use Docxpresso\Core\Math as math;
use Docxpresso;

/**
 * Creates an object element node
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class DocumentObject extends ODFElement
{       
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
        if (!isset($options['style'])) {
            $options['style'] = '';
        }
        $node = $odfNode->getNode();
        $styleId = $odfNode->getStyleId();
        $target = $odfNode->getTarget();
        parent::__construct($node, $odfNode, $target);
        //types
        if ($type == 'chart') {
            $key = 'chart_' . uniqid();
            $chart = new charts\Chart($options);
            Docxpresso\CreateDocument::$charts[$key] = $chart; 
            $options['chart'] = true;
        } else if ($type == 'math') {
            $key = 'math_' . uniqid();
            $math = new math\Math($options);
            Docxpresso\CreateDocument::$math[$key] = $math; 
            Docxpresso\CreateDocument::$mathSettings[$key] = $math->settings();
        }
        
        //create the object node
        $objNode = $node->ownerDocument->createElement('draw:object');
        $objNode->setAttribute('xlink:href', $key . '/');
        $objNode->setAttribute('xlink:type', 'simple');
        $objNode->setAttribute('xlink:show', 'embed');
        $objNode->setAttribute('xlink:actuate', 'onLoad');
        $objNode->setAttribute('xml:id', $key);
        //check if there is already a frame
        if ($node->nodeName == 'draw:frame') {
            $node->appendChild($imageNode);
            $this->setNode($node);
            $this->setNodeType($node->nodeName);
        } else {
            $frame = $this->frame($options, true);
            $frameNode = $frame->getNode();
            $frameNode->appendChild($objNode);
            $this->setNode($frameNode);
            $this->setNodeType($frameNode->nodeName);
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