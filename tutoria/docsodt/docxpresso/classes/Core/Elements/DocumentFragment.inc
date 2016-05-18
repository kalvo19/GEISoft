<?php

/**
 * Document Fragment
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
 * Creates a document fragment
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class DocumentFragment extends ODFElement
{    
       
    /**
     * Construct
     *
     * @param ODFElement $odfNode
     * @param string $styleId
     * @access public
     */
    public function __construct($odfNode)
    {
        $node = $odfNode->getNode();
        $target = $odfNode->getTarget();
        parent::__construct($node, $odfNode, $target); 
        $fragment = $node->ownerDocument->createElement('office:fragment');
        $this->setNode($fragment);
        $this->setNodeType($fragment->nodeName);
        return $fragment;
    }   
}