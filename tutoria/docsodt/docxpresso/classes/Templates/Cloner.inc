<?php

/**
 * Cloner
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
 * This class is the responsible of cloning template content
 *
 * @package    Docxpresso
 * @subpackage Templates
 */

class Cloner
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
     * The manifest.xml DOMDocument
     * 
     * @var DOMDocument
     * @access private
     */
    private $_manifest;
    
    /**
     * An instance of the ODFQuery class  required to build the queries
     * 
     * @var ODFQuery
     * @access private
     */
    private $_ODFQuery;
    
    /**
     * The template data
     * 
     * @var array
     * @access private
     */
    private $_template;
    
    /**
     * Construct
     *
     * @param array $options
     * @access public
     */
    public function __construct($options)
    {
        $this->_dom = $options['target'];
        $this->_manifest = $options['manifest'];
        $this->_template =& $options['template'];
        $this->_docxpath = new \DOMXPath($this->_dom);
        $this->_manifestxpath = new \DOMXPath($this->_manifest);
        $this->_ODFQuery = new ODFQuery();
        
        $this->_clone($options);
    }
    
    /**
     * selects the adequate cloning method depending on options
     *
     * @param array $options
     * @return void
     * @access private
     */
    private function _clone($options)
    { 
        if ($options['element'] == 'chart' 
            || $options['element'] == 'image') {
            $this->_cloneObject($options);
        } else if ($options['element'] == 'heading') {
            $this->_cloneGroup('heading', $options);
        } else if ($options['element'] == 'textbox') {
            $this->_cloneTextbox($options);
        } else if ($options['element'] == 'bookmark') {
            $this->_cloneGroup('bookmark', $options);
        }else if ($options['needle'] instanceof \DOMNode) {
            //for internal use we may pass this method directly a DOMNode
            $this->_cloneSingleNode($options['needle'], 
                                    $options['position'],
                                    $options['repeat']);
        } else if ($options['needle'] instanceof \DOMNodeList) {
            //for internal use we may pass this method directly a DOMNodeList
            $referenceNode = $options['needle']->item(0);
            for ($j = 0; $j < $options['repeat']; $j++ ) {
                foreach ($options['needle'] as $node) {
                    $this->_cloneSingleNode($node, 
                                        $referenceNode,
                                        1);
                }
            }
        }else {
            $this->_cloneElement($options);
        }
    }
    
    
    /**
     * clones a generic document element
     *
     * @param array $options
     * @return void
     * @access private
     */
    private function _cloneElement($options)
    {        
        $nodes = $this->_docxpath
                      ->query($this->_ODFQuery->elementQuery($options));
        if ($nodes->length > 0) {
            $node = $nodes->item(0);
            //do the cloning
            $this->_cloneSingleNode($node, 
                                    $options['position'],
                                    $options['repeat']);
        } else {
            $message = 'There was found no node to be cloned.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
        }
    }
    
    /**
     * clones a document chapter
     *
     * @param string $type
     * @param array $options
     * @return void
     * @access private
     */
    private function _cloneGroup($type, $options)
    {        
        if ($type == 'heading') {
            $nodes = $this->_docxpath
                          ->query($this->_ODFQuery->headingQuery($options));
        } else if ($type == 'bookmark') {
            $nodes = $this->_docxpath
                          ->query($this->_ODFQuery->bookmarkQuery($options));
        } else {
            return;
        }
        if ($nodes->length > 0) {
            if (isset($options['position']) 
                && $options['position'] == 'cursor') {
                $referenceNode = 'cursor';
            } else {
                $referenceNode = $nodes->item(0);
            }
            for ($j = 0; $j < $options['repeat']; $j++) {
                foreach($nodes as $node) {
                    if ($type == 'bookmark') {
                        //we have to skip paragraphs that have a bookmark-end
                        //tag as first child because they have not been choosen 
                        //by the user. This is typical from odts generated out 
                        //of Word
                        if ($node->hasChildNodes()) {
                            $fc = $node->firstChild->nodeName;
                        } else {
                            $fc = '';
                        }
                        if ($fc != 'text:bookmark-end') {
                            //do the cloning but first remove bookmarks
                            $removeQ = './text:bookmark-start | ';
                            $removeQ .= './text:bookmark-end';
                            $bmks = $this->_docxpath->query($removeQ, $node);
                            foreach ($bmks as $bmk) {
                            //TODO: check why we have removed bookmarks before
                            //this does not allows for recursive cloning so 
                            //it should be avoided?
                            //$bmk->parentNode->removeChild($bmk);
                            }
                            $this->_cloneSingleNode($node, 
                                                    $referenceNode,
                                                    1);
                        }
                    } else { 
                        $this->_cloneSingleNode($node, 
                                                $referenceNode,
                                                1);
                    }
                }
            }
        } else {
            $message = 'There was found no node to be cloned.';
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
    private function _cloneObject($options, $iteration = 1)
    {
        $nodes = $this->_docxpath
                      ->query($this->_ODFQuery->objectQuery($options, 'clone'));
        
        if($nodes->length == 0 && $iteration == 1 && !$options['container']){
            //this is just to make sure that the object has not been included
            //without a wrapping span
            $options['container'] = true;
            $this->_cloneObject($options, 2);
        } else {
            if ($nodes->length > 0) {
                $node = $nodes->item(0);
                //do the cloning
                $this->_cloneSingleNode($node, 
                                        $options['position'],
                                        $options['repeat']);
            } else {
                $message = 'There was found no node to be cloned.';
                commons\DOCXPRESSOLogger::logger($message, 'info');
            }
        }
    }
    
    /**
     * clones a textbox node
     *
     * @param array $options
     * @param int $iteration
     * @return void
     * @access private
     */
    private function _cloneTextbox($options, $iteration = 1)
    {
        $nodes = $this->_docxpath
                      ->query($this->_ODFQuery->tboxQuery($options, 'clone'));
        
        if($nodes->length == 0 && $iteration == 1 && !$options['container']){
            //this is just to make sure that the textbox has not been included
            //without a wrapping span
            $options['container'] = true;
            $this->_cloneTextbox($options, 2);
        } else {
            if ($nodes->length > 0) {
                $node = $nodes->item(0);
                //do the cloning
                $this->_cloneSingleNode($node, 
                                        $options['position'],
                                        $options['repeat']);
            } else {
                $message = 'There was found no node to be cloned.';
                commons\DOCXPRESSOLogger::logger($message, 'info');
            }
        }
    }
    
    /**
     * clones an element
     *
     * @param DOMNode $node
     * @param string $position
     * @param int $repeat
     * @return void
     * @access private
     */
    private function _cloneSingleNode($node, $position = 'after', $repeat = 1)
    {
        //before cloning we should take into account images, charts and math
        //subnodes which content has to be replicated
        //images (we exclude images used as replacements for objects)
        $query = './/draw:image[not(./preceding-sibling::draw:object)]';
        $images = $this->_docxpath->query($query, $node);
        //charts & math
        $query = './/draw:object';
        $objects = $this->_docxpath->query($query, $node);
        for ($j = 0; $j < $repeat; $j++) {
            //clone the DOMNode
            $cloned = $node->cloneNode(true);
            $this->_replicateNodeContent($node, $cloned, $images, $objects);
            //insert now the node into the document
            if ($position == 'self') {
                $node->parentNode->insertBefore($cloned, $node);
            } else if ($position == 'cursor') {
                $cursors = $this->_dom->getElementsByTagName('customCursor');
                if ($cursors->length > 0) {
                    $cursor = $cursors->item(0);
                    $cursor->parentNode->insertBefore($cloned, $cursor);
                } else {
                    //the cursor could not be found
                    $message = 'There cursor could not be found. ';
                    $message .= 'The cloned nodes are inserted in place.';
                    commons\DOCXPRESSOLogger::logger($message, 'info');
                    $node->parentNode->insertBefore($cloned, $node);
                }
            } else if ($position instanceof \DOMNode) {
                $position->parentNode->insertBefore($cloned, $position);
            }
        }
    }
    
    /**
     * copy and rename an image file
     *
     * @param string $src
     * @param string $newSrc
     * @return void
     * @access private
     */
    private function _copyImg($src, $newSrc)
    {
        //include the new file into the manifest
        $query = '//manifest:file-entry[@manifest:full-path="' . $src . '"]';
        $image = $this->_manifestxpath->query($query)->item(0);
        $newImage = $image->cloneNode(true);
        $newImage->setAttribute('manifest:full-path', $newSrc);
        $this->_manifest->documentElement->appendChild($newImage);
        //copy the image file
        $this->_template[$newSrc] = $this->_template[$src];
    }
    
    /**
     * copy and rename all the files associated with an object
     *
     * @param string $src
     * @param string $newSrc
     * @return void
     * @access private
     */
    private function _copyObj($src, $newSrc)
    {
        //remove relative paths if any
        $src = \str_replace('./', '', $src);
        $newSrc = \str_replace('./', '', $newSrc);
        //search for object files in the manifest
        $query = '//manifest:file-entry';
        $query .= '[starts-with(@manifest:full-path, "' . $src . '/")]';
        $objs = $this->_manifestxpath->query($query);
        foreach ($objs as $obj) {
            $newObj = $obj->cloneNode(true);
            $objAttr = $obj->getAttribute('manifest:full-path');
            $newAttr = \str_replace($src, $newSrc, $objAttr);
            $newObj->setAttribute('manifest:full-path', $newAttr);
            $this->_manifest->documentElement->appendChild($newObj);
            //copy the image file
            if (isset($this->_template[$objAttr])) {
                $this->_template[$newAttr] = $this->_template[$objAttr];
            }
        }
    }
    
    /**
     * replicates content
     *
     * @param DOMNode $node
     * @param DOMNode $cloned
     * @param DOMNodeList $images
     * @param DOMNodeList $objects
     * @return void
     * @access private
     */
    private function _replicateNodeContent($node, $cloned, $images, $objects)
    {
        foreach ($images as $img) {
            $src = $img->getAttribute('xlink:href');
            $srcA = \pathinfo($src);
            if (!empty($srcA['extension'])){
                $ext = $srcA['extension'];
            } else {
                $ext = '';
            }
            $ext = '.' . $ext;
            $next = commons\Utilities::generateId('_docxpresso_') . $ext;
            $newSrc = \str_replace($ext, $next, $src);
            $this->_copyImg($src, $newSrc);
            //update the attribute in the cloned node
            $imgQuery = './/draw:image[@xlink:href="' . $src . '"]';
            $clonedImg = $this->_docxpath->query($imgQuery, $cloned);
            $clonedImg->item(0)->setAttribute('xlink:href', $newSrc);
        }
        
        foreach ($objects as $obj) {
            $src = $obj->getAttribute('xlink:href');
            $new = commons\Utilities::generateId('_docxpresso_');
            $newSrc = $src . $new;
            $this->_copyObj($src, $newSrc);
            //update the attribute in the cloned node
            $objQuery = './/draw:object[@xlink:href="' . $src . '"]';
            $clonedObj = $this->_docxpath->query($objQuery, $cloned);
            $clonedObj->item(0)->setAttribute('xlink:href', $newSrc);
        } 
    }

}