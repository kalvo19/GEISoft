<?php

/**
 * Remove
 *
 * @copyright  Copyright (c) 2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    2.0
 * @since      2.0
 */

namespace Docxpresso\Templates;

use Docxpresso\Commons as commons;

/**
 * This class is the responsible of removing content from the template
 *
 * @package    Docxpresso
 * @subpackage Templates
 */

class Remove
{
    
    /**
     * The DOMDOcument we wish to manipulate
     * 
     * @var DOMDocument
     * @access private
     */
    private $_dom;
    
    /**
     * The required DOMXPath object
     * 
     * @var DOMXPath
     * @access private
     */
    private $_docxpath;
    
    /**
     * An instance of the ODFQuery class required to build the queries
     * @var ODFQuery
     * @access private
     */
    private $_ODFQuery;
    
    /**
     * Construct
     *
     * @param DOMDocument $dom
     * @param array $options
     * @access public
     */
    public function __construct($dom, $options)
    {
        $this->_dom = $dom;
        $this->_docxpath = new \DOMXPath($this->_dom);
        $this->_ODFQuery = new ODFQuery();
        
        if ($options['element'] == 'text') {
            $this->_removeText($options);           
        } else if ($options['element'] == 'chart' 
                   || $options['element'] == 'image') {
            $this->_removeObject($options);
        } else if ($options['element'] == 'textbox') {
            $this->_removeTextbox($options);
        } else if ($options['element'] == 'heading') {
            $this->_removeHeading($options);
        } else if ($options['element'] == 'bookmark') {
            $this->_removeBookmark($options);
        } else {
            $this->_removeElement($options);
        }
    }
    
    /**
     * removes bookmarked content
     *
     * @param array $options
     * @return void
     * @access private
     */
    private function _removeBookmark($options)
    {        
        $nodes = $this->_docxpath
                      ->query($this->_ODFQuery->bookmarkQuery($options));
        foreach ($nodes as $node) {
            if (!empty($node)) {//make sure it has not been already removed
                $node->parentNode->removeChild($node);
            }
        }
        if ($nodes->length == 0) {
            $message = 'There was found no node to be removed.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
        }
    }
    
    /**
     * removes a generic document element
     *
     * @param array $options
     * @return void
     * @access private
     */
    private function _removeElement($options)
    {        
        $nodes = $this->_docxpath
                      ->query($this->_ODFQuery->elementQuery($options));
        foreach ($nodes as $node) {
            if (!empty($node)) {//make sure it has not been already removed
                $node->parentNode->removeChild($node);
            }
        }
        if ($nodes->length == 0) {
            $message = 'There was found no node to be removed.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
        }
    }
    
    /**
     * removes all content falling under a heading
     *
     * @param array $options
     * @return void
     * @access private
     */
    private function _removeHeading($options)
    {        
        if (empty($options['heading-level'])) {
            $options['heading-level'] = 1;
        }
        $nodes = $this->_docxpath
                      ->query($this->_ODFQuery->headingQuery($options));
        foreach ($nodes as $node) {
            if (!empty($node)) {//make sure it has not been already removed
                $node->parentNode->removeChild($node);
            }
        }
        if ($nodes->length == 0) {
            $message = 'There was found no nodes to be removed.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
        }
    }
    
    /**
     * removes an object/image node
     *
     * @param array $options
     * @param int $iteration
     * @return void
     * @access private
     */
    private function _removeObject($options, $iteration = 1)
    {
        $nodes = $this->_docxpath
                      ->query($this->_ODFQuery->objectQuery($options));
        
        if($nodes->length == 0 && $iteration == 1 && !$options['container']){
            //this is just to make sure that the object has not been included
            //without a wrapping span
            $options['container'] = true;
            $this->_removeObject($options, 2);
        } else {
            foreach ($nodes as $node) {
                if (!empty($node)) {//make sure it has not been already removed
                    $node->parentNode->removeChild($node);
                }
            }
            if ($nodes->length == 0) {
                $message = 'There was found no node to be removed.';
                commons\DOCXPRESSOLogger::logger($message, 'info');
            }
        }
    }
    
    /**
     * removes a text node
     *
     * @param array $options
     * @return void
     * @access private
     */
    private function _removeText($options)
    {
        $nodes = $this->_docxpath
                      ->query($this->_ODFQuery->textQuery($options));
        foreach ($nodes as $node) {
            $text = $node->nodeValue;
            $text = \str_replace($options['needle'], '', $text);
            $node->nodeValue = $text;
        }
        if ($nodes->length == 0) {
            $message = 'There was found no text to be removed.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
        }
    }
    
    /**
     * removes a textbox node
     *
     * @param array $options
     * @param int $iteration
     * @return void
     * @access private
     */
    private function _removeTextbox($options, $iteration = 1)
    {
        $nodes = $this->_docxpath
                      ->query($this->_ODFQuery->tboxQuery($options));
        
        if($nodes->length == 0 && $iteration == 1 && !$options['container']){
            //this is just to make sure that the textbox has not been included
            //without a wrapping span
            $options['container'] = true;
            $this->_removeTextbox($options, 2);
        } else {
            foreach ($nodes as $node) {
                if (!empty($node)) {//make sure it has not been already removed
                    $node->parentNode->removeChild($node);
                }
            }
            if ($nodes->length == 0) {
                $message = 'There was found no node to be removed.';
                commons\DOCXPRESSOLogger::logger($message, 'info');
            }
        }
    }

}