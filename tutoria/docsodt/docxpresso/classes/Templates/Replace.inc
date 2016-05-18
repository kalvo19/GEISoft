<?php

/**
 * Replace
 *
 * @copyright  Copyright (c) 2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    2.0
 * @since      2.0
 */

namespace Docxpresso\Templates;

use Docxpresso\Core\Elements as core;
use Docxpresso\Commons as commons;
use Docxpresso\Core\Elements as elements;

/**
 * This class is the responsible of replacing template variables
 *
 * @package    Docxpresso
 * @subpackage Templates
 */

class Replace
{
    /**
     * The ODF element associated with the $_dom document element
     * @var ODFElement
     * @access private
     */
    private $_baseODF;
    
    /**
     * The required CreateDocument object
     * 
     * @var CreateDocument
     * @access private
     */
    private $_doc;
    
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
     * List of nodes to be removed
     * 
     * @var array
     * @access private
     */
    private $_removeNodes;
    
    /**
     * An instance of the ODFQuery class required to build the queries
     * @var ODFQuery
     * @access private
     */
    private $_ODFQuery;
    
    /**
     * Construct
     *
     * @param CreateDocument $doc
     * @param array $vars
     * @param array $options
     * @access public
     */
    public function __construct($doc, $vars, $options)
    {
        $this->_removeNodes = array();
        if ($options['target'] == 'header' || $options['target'] == 'footer') {
            $this->_dom = $options['DOMDoc'];
            $this->_doc = $doc;
            $base = $this->_dom->documentElement;
            $this->_baseODF = new elements\ODFElement($base, 
                                                      NULL, 
                                                      'header-footer');
        } else {
            $this->_dom = $options['DOMDoc'];
            $this->_doc = $doc;
            $this->_baseODF = $doc->contentODF;
        }
        $this->_docxpath = new \DOMXPath($this->_dom);
        $this->_ODFQuery = new ODFQuery();
        if (isset($options['docType']) 
            && $options['docType'] == 'spreadsheet') {
            $this->_replaceSpreadSheetNumericValues($vars, $options);
        } else {
            $this->_replace($vars, $options);
        }
        //check if there are "block nodes" to be removed
        foreach($this->_removeNodes as $node){
            $node->parentNode->removeChild($node);
        }
    }
    
    /**
     * parses image dimensions
     *
     * @param mixed $d
     * @return integer
     * @access private
     */
    private function _dimension($d)
    {
        $newD = commons\Utilities::convertUnits('pt', $d);
        return $newD . 'pt';
    }
    
    /**
     * counts the maximun number of entries of each subarray
     *
     * @param array $vars
     * @return integer
     * @access private
     */
    private function _maxCountVars($vars)
    {
        $number = 1;
        foreach($vars as $var => $val) {
            if (\is_array($val['value'])) {
                $tmp = \count($val['value']);
            }
            if ($tmp > $number) {
               $number = $tmp; 
            }
        }
        return $number;
    }
    
    /**
     * selects the adequate replacing method depending on options
     *
     * @param array $vars
     * @param array $options
     * @return void
     * @access private
     */
    private function _replace($vars, $options)
    { 
        if ($options['element'] == 'text') {
            $this->_replaceText($vars, $options);
        } else if ($options['element'] == 'paragraph') {
            $this->_replaceParagraph($vars, $options, 'text:p');
        } else if ($options['element'] == 'list') {
            $this->_replaceParagraph($vars, $options, 'text:list-item');
        } else if ($options['element'] == 'table') {
            $this->_replaceTable($vars, $options);
        } else if ($options['element'] == 'chart') {
            $this->_replaceChart($vars, $options);
        } else if ($options['element'] == 'image') {
            $this->_replaceText($vars, $options);
        } else {
            $this->_replaceText($vars, $options);
        }
    }
    
    /**
     * replaces variables by numeric values within a spreadsheet
     *
     * @param array $vars
     * @param array $options
     * @return void
     * @access private
     */
    private function _replaceSpreadSheetNumericValues($vars, $options)
    { 
        foreach ($vars as $var => $value) {
            $val = $value;
            if (!is_array($value['value'])) {
               $val['value'] = array($value['value']);
            }
            $search = $options['format'][0] . $var . $options['format'][1];
            $data = array();
            $data['needle'] = $search;
            if (isset($value['match'])) {
                $data['match'] = $value['match'];
            }
            $query = $this->_ODFQuery->spreadsheetQuery($data);
            $nodes = $this->_docxpath->query($query);
            $mod = \count($val['value']);
            $j = 0;
            foreach ($nodes as $node) {
                $res = $val['value'][$j % $mod];
                $this->_replaceSpreadSheetValue($node, $search, $res, $options);
                $j++;
            }
        }
    }
    
    /**
     * replaces the data within a chart
     *
     * @param array $vars
     * @param array $options
     * @return void
     * @access private
     */
    private function _replaceChart($vars, $options)
    { 
        //start the search of variables
        $varKeys = \array_keys($vars);
        $var = $varKeys[0];
        if (!empty($var)) {
            $search = $options['format'][0] . $var . $options['format'][1];
        } else {
            $search = '';
        }
        $opts = array();
        $opts['element'] = 'chart';
        $opts['needle'] = $search;
        $query = $this->_ODFQuery->objQuery($opts, 'clone');
        $nodes = $this->_docxpath->query($query);
        foreach ($nodes as $node) {
            $obj = $node->getElementsByTagName('object')->item(0);
            $img = $node->getElementsByTagName('image');
            foreach ($img as $image) {
               $image->parentNode->removeChild($image);
            }
            $path = $obj->getAttribute('xlink:href');
            $path = \str_replace('./', '', $path);
            $this->_replaceChartData($path, $vars[$var]);
        }
    }
    
    /**
     * replaces the chart data in its corresponding content.xml
     *
     * @param string $path
     * @param array $data
     * @return void
     * @access private
     */
    private function _replaceChartData($path, $data)
    { 
        $dom = new \DOMDocument();
        $path = $path . '/content.xml';
        $path = \str_replace('//', '/', $path);
        try {
            $loadDOM = $dom->loadXML($this->_doc->template[$path]);
            if ($loadDOM === false) {
                throw new \Exception('Error while trying to open the chart');
            }
        } catch (\Exception $e){
            commons\DOCXPRESSOLogger::logger($e->getMessage(), 'info');
            return;
        }
        $xpath = new \DOMXPath($dom);
        //change the actual data
        if (isset($data['series'])) {
            unset($data['series']);
        }
        foreach ($data as $key => $value) {
            $query = '//table:table-row[table:table-cell[.="' . $key . '"]]';
            $q = './table:table-cell[@office:value-type="float"]';
            $rowNodes = $xpath->query($query);
            if ($rowNodes->length > 0) {
                $row = $rowNodes->item(0);
                $cellNodes = $xpath->query($q, $row);
                if(\is_numeric($value) && $cellNodes->length > 0) {
                    $cellNodes->item(0)->setAttribute('office:value', $value);
                    $cellNodes->item(0)->firstChild->nodeValue = $value;
                } else if (\is_array($value)){
                    foreach ($value as $key => $val) {
                        $cellemp = $cellNodes->item($key);
                        if (!empty($cellemp)) {
                           $cellNodes->item($key)->setAttribute('office:value', 
                                                                $val);
                            if ($cellNodes->item($key)->hasChildNodes()) {
                                $cellNodes->item($key)
                                          ->firstChild->nodeValue = $val;
                            } else {
                               $cellNodes->item($key)->nodeValue = $val;
                            }
                        }
                    }
                }
            }
        }
        //save the modified XML data
        $this->_doc->template[$path] = $dom->saveXML();
    }
    
    /**
     * replace the image source
     *
     * @param DOMNode $node
     * @param array $data
     * @return void
     * @access private
     */
    private function _replaceImageSrc($node, $data)
    {
        if (\substr($data['value'],0,10) == 'data:image'){
            $imgArray = \explode(',', $data['value']);
            try {
                $img = \base64_decode($imgArray[1]);
                if (empty($img)) {
                    throw new Exception('Error parsing the image source');
                }
            } catch (Exception $e) {
                commons\DOCXPRESSOLogger::logger($e->getMessage(), 'info');
                return;
            }
        } else {
            try {
                $img = \file_get_contents($data['value']);
                if (empty($img)) {
                    throw new Exception('Error opening image source');
                }
            } catch (Exception $e) {
                commons\DOCXPRESSOLogger::logger($e->getMessage(), 'info');
                return;
            }
        }
        //we should get the picture path inside the zip
        $query = './draw:image';
        $path = $this->_docxpath
                     ->query($query, $node)
                     ->item(0)
                     ->getAttribute('xlink:href');
        //Now replace the old image by the new image
        $this->_doc->template[$path] = $img;
        //if the width and height are set we need to change the dimensions
        if (isset($data['width'])) {
            $width = $this->_dimension($data['width']);
            if ($width != '0pt') {
                $node->setAttribute('svg:width', $width);
            }
        }
        if (isset($data['height'])) {
            $height = $this->_dimension($data['height']);
            if ($height != '0pt') {
                $node->setAttribute('svg:height', $height);
            }
        }
    }
    
    /**
     * replaces paragraph type variables
     *
     * @param array $vars
     * @param array $options
     * @param string $tag
     * @return void
     * @access private
     */
    private function _replaceParagraph($vars, $options, $tag)
    { 
        //first we have to locate the paragraph nodes that include all the
        //requested variables
        $data = array();
        $data['search'] = \array_keys($vars);
        foreach ($data['search'] as $key => $value) {
            $data['search'][$key] = $options['format'][0];
            $data['search'][$key] .= $value;
            $data['search'][$key] .= $options['format'][1];
        }
        if (isset($options['match'])) {
            $data['match'] = $options['match'];
        }
        $query = $this->_ODFQuery->paragraphQuery($data, $tag);
        $nodes = $this->_docxpath->query($query);
        //check how may times we need to clone the paragraph node
        //depending of the length of the replacement arrays
        $number = $this->_maxCountVars($vars) -1;
        foreach ($nodes as $node) {
            $data = array();
            $data['needle'] = $node;
            $data['repeat'] = $number;
            $this->_doc->cloneContent($data);
        }
        //now we may proceed to do a normal replacement
        $options['element'] = 'text';
        $this->_doc->replace($vars, $options);
    }
    
    /**
     * replace a single variable
     *
     * @param array $var
     * @param mixed $value
     * @param array $options
     * @return void
     * @access private
     */
    private function _replaceSingleVariable($var, $value, $options)
    {        
        //preset some input data
        if (!isset($value['html'])) {
            $value['html'] = true;
        }
        if (!isset($value['parse-line-breaks'])) {
            $value['parse-line-breaks'] = true;
        }
        if (!isset($value['block-type'])) {
            $value['block-type'] = false;
        }
        if (!isset($value['image'])) {
            $value['image'] = false;
        }
        $val = $value;
        if (!is_array($value['value'])) {
           $val['value'] = array($value['value']);
        }
        //start the search of variables
        $search = $options['format'][0] . $var . $options['format'][1];
        if ($value['image'] == true) {
            $opts = array();
            $opts['element'] = 'image';
            $opts['needle'] = $search;
            $query = $this->_ODFQuery->objQuery($opts, 'clone');
        } else {
            $data = array();
            $data['needle'] = $search;
            if (isset($value['match'])) {
                $data['match'] = $value['match'];
            }
            $query = $this->_ODFQuery->textQuery($data);
        }
        $nodes = $this->_docxpath->query($query);
        $mod = \count($val['value']);
        if ($mod == 0) {
            //there were no values to replace so leave and send error to the log
            $message = 'There were no values to replace for:' . $var;
            commons\DOCXPRESSOLogger::logger($message, 'info');
        }
        $j = 0;
        foreach ($nodes as $node) {
            //there is an unusual case that we need to take care of, i.e. when
            //the same text node has twice the variable in it
            $text = $node->nodeValue;
            $count = \substr_count($text, $search);
            for ($k = 0; $k < $count; $k++){
                $res = $val;
                //we only get the $j entry. The mod is just in case we want to
                //replace multiple instances of the same variable by the same
                //value
                $res['value'] = $val['value'][$j % $mod];
                $this->_replaceValue($node, $search, $res, $options);
                $j++;
            }
        }
    }
    
    /**
     * replaces table type variables
     *
     * @param array $vars
     * @param array $options
     * @return void
     * @access private
     */
    private function _replaceTable($vars, $options)
    { 
        //first we have to locate the table nodes that include all the
        //requested variables
        $data = array();
        $data['search'] = \array_keys($vars);
        foreach ($data['search'] as $key => $value) {
            $data['search'][$key] = $options['format'][0];
            $data['search'][$key] .= $value;
            $data['search'][$key] .= $options['format'][1];
        }
        if (isset($options['match'])) {
            $data['match'] = $options['match'];
        }
        $query = $this->_ODFQuery->tableQuery($data);
        $nodes = $this->_docxpath->query($query);
        //check how may times we need to clone the table row groups
        //depending of the length of the replacement arrays
        $number = $this->_maxCountVars($vars) - 1;
        foreach ($nodes as $node) {
            //we have to select the "affected rows"
            $query = $this->_ODFQuery->rowQuery($data);
            $rows = $this->_docxpath->query($query, $node);
            $data = array();
            $data['needle'] = $rows;
            $data['repeat'] = $number;
            foreach ($rows as $row) {
            }
            if ($rows->length > 0){
                $this->_doc->cloneContent($data);
            } else {
                $message = 'There were no rows found.';
                commons\DOCXPRESSOLogger::logger($message, 'info');
            }
        }
        //now we may proceed to do a normal replacement
        $options['element'] = 'text';
        $this->_doc->replace($vars, $options);
    }
    
    /**
     * replaces text type variables
     *
     * @param array $vars
     * @param array $options
     * @return void
     * @access private
     */
    private function _replaceText($vars, $options)
    { 
        foreach ($vars as $var => $value) {
            $this->_replaceSingleVariable($var, $value, $options);
        }
    }
    
    /**
     * replaces a numeric value in a spreadsheet
     *
     * @param DOMNode $node
     * @param string $var
     * @param string $value
     * @param array $options
     * @return void
     * @access private
     */
    private function _replaceSpreadSheetValue($node, $var, $value, $options)
    {                
        //first set the office:value-type to float
        $node->setAttribute('office:value-type', 'float');
        //and now the numeric value
        $node->setAttribute('office:value', $value);
        //now replace the text within the speadsheet cell
        $preValue = $node->nodeValue;
        $pos = \strpos($preValue, $var);
        if ($pos !== false) {
            $newValue = \substr_replace($preValue, 
                                        $value, 
                                        $pos, 
                                        \strlen($var));
            $node->nodeValue = $newValue;
        }
    }
    
    /**
     * replaces the value
     *
     * @param DOMNode $node
     * @param string $var
     * @param mixed $value
     * @param array $options
     * @return void
     * @access private
     */
    private function _replaceValue($node, $var, $value, $options)
    {        
        if ($value['image'] == true && \is_string($value['value'])){
            $this->_replaceImageSrc($node, $value);
        } else if ($value['value'] instanceof elements\DocumentFragment) {
            $this->_replaceValueByDocFragment($node, $var, $value, $options);
        } else if (\is_string($value['value'])) {
            $this->_replaceValueByString($node, $var, $value, $options);
        } else {
            $message = 'Not possible to replace the var by the given value.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
        }
    }
    
    /**
     * replaces the value by a document fragment
     *
     * @param DOMNode $node
     * @param string $var
     * @param DocumentFragment $value
     * @param array $options
     * @return void
     * @access private
     */
    private function _replaceValueByDocFragment($node, $var, $value, $options)
    {               
        //In this case we have to take into account the block-type option
        if ($value['block-type'] == true) {
            //insert the document fragment just before the node and then
            //remove it
            $query = $this->_ODFQuery->getParent(array('text:p', 'text:h'));
            $p = $this->_docxpath->query($query, $node)->item(0);
            
            $this->_doc->setCursor($p->parentNode, $p);
            //create an ODFElement out of the $node->parentNode
            $odfNode = new elements\ODFElement($p->parentNode);
            $odfNode->insertDocumentFragment($value['value']);
            //we store the node in the list of nodes to be removed
            $this->_removeNodes[] = $p;
        } else {
            $preValue = $node->nodeValue;
            $content = \explode($var, $preValue);
            //get the first entry
            $pre = \array_shift($content);
            $post = \implode($var, $content);
            $text = $node->ownerDocument->createTextNode($pre);
            $node->parentNode->insertBefore($text, $node);
            //only the first appearance is replaced
            $span = $node->ownerDocument
                    ->createElement('text:span');
            $ref = $node->parentNode->insertBefore($span, $node);
            $odfNode = new elements\ODFElement($ref);
            $odfNode->insertDocumentFragment($value['value']);
            if (empty($post)) {
                $node->parentNode->removeChild($node);
            } else {
                $node->nodeValue = $post;
            }
        }
    }
    
    /**
     * replaces the value by a string
     *
     * @param DOMNode $node
     * @param string $var
     * @param string $value
     * @param array $options
     * @return void
     * @access private
     */
    private function _replaceValueByString($node, $var, $value, $options)
    {        
        //first we need to check the html and parse-line-breaks options
        if ($value['html']) {
            if (commons\Utilities::isHTML($value['value'])){
                $base = new elements\DocumentFragment($this->_baseODF);
                $df = $base->html(array('html' => $value['value']));
                $value['value'] = $df;
                $this->_replaceValueByDocFragment($node, $var, $value, $options);
                return;
            } else {
                if ($value['parse-line-breaks']){
                    $value['value'] = \nl2br($value['value']);
                    $value['parse-line-breaks'] = false;
                    $this->_replaceValueByString($node, $var, $value, $options);
                    return;
                }
            }
        } else if ($value['parse-line-breaks']){
            $value['value'] = \nl2br($value['value']);
            $value['parse-line-breaks'] = false;
            $this->_replaceValueByString($node, $var, $value, $options);
            return;
        }
        $preValue = $node->nodeValue;
        //we only replace the first match to control better multiple matches
        $pos = \strpos($preValue, $var);
        if ($pos !== false) {
            $newValue = \substr_replace($preValue, 
                                        $value['value'], 
                                        $pos, 
                                        \strlen($var));
            $node->nodeValue = $newValue;
        }
    }

}