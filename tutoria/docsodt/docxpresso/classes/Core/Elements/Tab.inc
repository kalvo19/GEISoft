<?php

/**
 * Tabs
 *
 * @copyright  Copyright(c)2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Core\Elements;

use Docxpresso as Docxpresso;
use Docxpresso\Commons as commons;

/**
 * Creates a tab element
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class Tab extends ODFElement
{
    /**
     * allowed parent nodes
     * @var array
     * @access public
     * @static
     */
    public static $parentNodes = array(
        //we will allow only text:p and text:h parents
        //to make sure that the tab elements are included in the parent
        //paragraph or heading
        //'text:a' => true, 
        'text:h' => true, 
        //'text:meta' => true, 
        //'text:meta-field' => true, 
        'text:p' => true, 
        //'text:ruby-base' => true,
        //'text:span' => true,
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
        $styleId = $odfNode->getCurrentStyleId();
        $target = $odfNode->getTarget();
        $options['target'] = $target;
        parent::__construct($node, $odfNode, $target);
        if (isset(self::$parentNodes[$node->nodeName])) {  
            $tabNode = $node->ownerDocument->createElement('text:tab');
            if (!empty($styleId)){
                Docxpresso\CreateDocument::$tabStops[$styleId][] = $options;
            }
            $node->appendChild($tabNode);
        } else {
            $message = 'You can not add a tab to the parent element.';
            commons\DOCXPRESSOLogger::logger($message, 'critical');
        }
    }
   
}