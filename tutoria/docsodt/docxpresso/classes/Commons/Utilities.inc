<?php

/**
 * Utilities
 *
 * @copyright  Copyright (c) 2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Commons;

use Docxpresso;
use Docxpresso\Commons as commons;
/**
 * Utilities
 *
 * @package    Docxpresso
 * @subpackage Commons
 */

class Utilities
{
    
    /**
     * generates an array from the attributes of a DOM element
     *
     * @param DOMNode $node
     * @return array
     * @access public
     * @static
     */
    public static function attributes2Array($node)
    {
        $attributes = array();
        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                $name = $attr->nodeName;
                $value = $attr->nodeValue;
                $attributes[$name] = $value;
            }
        }
        return $attributes;
    }
    
    /**
     * generates a file name from its path
     *
     * @param string $path
     * @return string
     * @access public
     * @static
     */
    public static function cleanPath($path)
    {
        return \str_replace(array('/', '\\', ':', '..'), '', $path);
    }
    
    /**
     * gets a CSS property in arbitrary units and return a numeric value
     * in the requested units
     *
     * @param string $unit
     * @param string $prop
     * @return float
     * @access public
     * @static
     */
    public static function convertUnits($unit, $prop)
    {   
        if (empty($prop)) {
            return 0;
        }
        //This method does not really support relative units:
        // 1. percentages are ignored
        // 2. ems and rems are assumed to be 10pt (a reasonable estimate)
        $matches = array();
        $regex = '/([0-9]+\.?[0-9]*)\s*(px|em|rem|ex|in|cm|mm|pt|pc)?/i';
        \preg_match($regex, $prop, $matches);
        $rawVal = $matches[1];
        if (isset($matches[2])) {
            $rawUnits = $matches[2];
        } else {
            $rawUnits = 'pt';
        }
        if (!isset($rawVal)) {
            //regex failed
            return 0;
        }
        //normalize to points
        if ($rawUnits == 'pt') {
            $val = $rawVal;
        } else if ($rawUnits == 'px') {
            //px is the acronym for 'pixel' that corresponds to 0.75 points
            $val = $rawVal * 0.75;
        } else if ($rawUnits == 'cm') {
            //cm is the acronym for 'centimeter' that corresponds to 28.3465 pt
            $val = $rawVal * 28.3465;
        } else if ($rawUnits == 'mm') {
            //mm is the acronym for 'millimeter' that corresponds to 2.83465 pt
            $val = $rawVal * 283.465;
        } else if ($rawUnits == 'in') {
            //in is the acronym for 'inch' that corresponds to 72 pt
            $val = $rawVal * 72;
        } else if ($rawUnits == 'pc') {
            //pc is the acronym for 'pica' that corresponds to 12 points
            $val = $rawVal * 12;
        }  else if ($rawUnits == 'em') {
            //em: this is just an estimate
            $val = $rawVal * 10;
        } else if ($rawUnits == 'ex') {
            //ex is the acronym for 'x-height' aproximately half em
            $val = $rawVal * 5;
        } 
        //return numeric value in the requested units
        if ($unit == 'pt') {
            return $val;
        } else if ($unit == 'px') {
            //px is the acronym for 'pixel' that corresponds to 0.75 points
            return  $val/0.75;
        } else if ($unit == 'cm') {
            //cm is the acronym for 'centimeter' that corresponds to 28.3465 pt
            return  $val/28.3465;
        } else if ($unit == 'mm') {
            //mm is the acronym for 'millimeter' that corresponds to 283.465 pt
            return  $val/2.83465;
        } else if ($unit == 'in') {
            //in is the acronym for 'inch' that corresponds to 72 pt
            return  $val/72;
        } else if ($unit == 'pc') {
            //pc is the acronym for 'pica' that corresponds to 12 points
            return  $val/12;
        }  else if ($unit == 'em') {
            //em: this is just an estimate
            return  $val/10;
        } else if ($unit == 'ex') {
            //ex is the acronym for 'x-height' aproximately half em
            return  $val/5;
        } else {
            return 0;
        }
    }
    
    /**
     * Extracts the base URL from a http path
     *
     * @param string $path
     * @return string
     * @access public
     * @static
     */
    public static function extractBaseURL($path)
    {
        $urlData = \parse_url($path);
        if ($urlData === false) {
            //this is unlikely if we were able to retrieve the contents of the 
            //HTML file
            return '';
        } else if (isset($urlData['path'])) {          
            $extension = \substr($urlData['path'], -5);
            $dotpos = \strpos($extension, '.');
            if ($dotpos === false) {
                //there is no extension
                if (\substr($path, -5) != '/') {
                   $urlData['path'] =  $urlData['path'] . '/'; 
                } else {
                   //there is noting to do
                }
            } else {
                //there is a file extension so we have to remove all the
                //chars after the last '/'
                $tmp = \explode('/', $urlData['path']);
                \array_pop($tmp);
                $urlData['path'] = \implode('/', $urlData['path']);
            }
        }
        //return the resulting url
        $url = $urlData['scheme'] . '://' . $urlData['host'];
        if (isset($urlData['path'])) {
            $url .= $urlData['path'];
        }
        return $url;
    }
    
    /**
     * generates a unique id
     *
     * @param string $prefix
     * @param int $length
     * @return string
     * @access public
     * @static
     */
    public static function generateId($prefix = 'style_')
    {
        Docxpresso\CreateDocument::$counter++;
        $prefix = Docxpresso\CreateDocument::$unid . '-' . $prefix;
        $id = $prefix . Docxpresso\CreateDocument::$counter;
        return $id;
    }
    
    /**
     * builds a valid URL
     *
     * @param string $baseURL
     * @param string $path
     * @return string
     * @access public
     * @static
     */
    public static function generateURL($baseURL, $path)
    {
       if(\strtolower(\substr($path, 0, 5)) == 'http:'
          || \strtolower(\substr($path, 0, 6)) == 'https:'){
           return $path;
       } else {
            if (!empty($baseURL)) {
                return $baseURL . '/' . $path;
            } else {
                return $path;
            }
       }
    }
    
    /**
     * fetches the background image and returns a local path
     *
     * @param string $path
     * @return string
     * @access public
     * @static
     */
    public static function getBackgroundImage($path)
    {
        //get image
        $image = @\file_get_contents($path);
        $name = 'media/' . self::cleanPath($path);
        if (empty($image)) {
            $message = 'There was an error retrieving image data.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            return;
        } else {
            Docxpresso\CreateDocument::$media[$name] = $image;
            return $name;
        }
    }
    
    /**
     * gets the image size
     *
     * @param string $imageData
     * @return array
     * @access public
     * @static
     */
    public static function getImgSize($imageData)
    {
        if (!function_exists('\getimagesizefromstring')) {     
            $uri = 'data://application/octet-stream;base64,'  
                   . base64_encode($imageData);
            return \getimagesize($uri);
        } else {
            return \getimagesizefromstring($imageData);
        }
    }
    /**
     * gets the resolution of an image
     *
     * @param string $imageData
     * @param string $extension
     * @return array
     * @access public
     * @static
     */
    public static function getImgResolution($imageData, $extension)
    {
        if ($extension == 'jpg' || $extension == 'jpeg'){
            $resolution = self::jpgResolution($imageData);
        } else if ($extension == 'png') {
            $resolution = self::pngResolution($imageData);
        } else {
            $dpi = Docxpresso\CreateDocument::$config['images']['dpi'];
            $resolution = array($dpi, $dpi);
        }
        return $resolution;
    }
    
    /**
     * inserts a DOM node as first child of a given node
     *
     * @param DOMNode $node the receptor node
     * @param DOMNode $child the node we wish to insert
     * @return array
     * @access public
     * @static
     */
    public static function insertAsFirstChild($node, $child)
    {
        if($node->hasChildNodes() === true) {
            $firstNode = $node->firstChild;
            $node->insertBefore($child, $firstNode);
        } else {
            $node->appendChild($child);
        }
    }
    
    /**
     * checks if a string contains HTML code
     *
     * @param string $string
     * @return boolean
     * @access public
     * @static
     */
    public static function isHTML($string)
    {
        $html = preg_match("/<[^<]+>/",$string);
        if (empty($html)) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * gets the resolution of an jpg|jpeg image in dots per inch
     *
     * @param string $imageData
     * @return array
     * @access public
     * @static
     */
    public static function jpgResolution($imageData)
    { 
        $dataType = \hexdec(\bin2hex(\substr($imageData, 13, 1)));
        $imageData = \bin2hex(\substr($imageData, 14, 4));
        if ($dataType == 1) {
            //the resolution is given in inches
            return array(
                \hexdec(\substr($imageData, 0, 4)), 
                \hexdec(\substr($imageData, 4, 4))
                );
        } else if ($dataType == 2) {
            //the resolution is given in cm
            //a cm has o.394 inches aprox.
            return array(
                (int) \floor(\hexdec(\substr($imageData, 0, 4)) * 0.394), 
                (int) \floor(\hexdec(\substr($imageData, 4, 4)) * 0.394)
                );
        } else {
            $dpi = Docxpresso\CreateDocument::$config['images']['dpi'];
            return array($dpi, $dpi);
        }
        
    }
    
    /**
     * multiplies two give matrices
     *
     * @param array $matrix_1
     * @param array $matrix_2
     * @return array
     * @access public
     * @static
     */
    public static function matrixMultiplication($matrix_1, $matrix_2)
    {
        //first check that the number of columns of the first matrix equals
        //the nuember of rows of the second matrix
        $rows_1 = count($matrix_1);
        $cols_1 = count($matrix_1[0]);
        $rows_2 = count($matrix_2);
        $cols_2 = count($matrix_2[0]);        
        
        if ($cols_1 != $rows_2) {
            $message = 'the number of cols of the first matrix does not match';
            $message = 'the number of rows of the second.';
            DOCXPRESSOLogger::logger($message, 'critical');   
        }
        
        $result = array();
        for($i= 0; $i < $rows_1; $i++) {
            for($j = 0; $j < $cols_2; $j++) {
                $result[$i][$j] = 0; 
                for($k = 0; $k < $cols_1; $k++) {
                    $result[$i][$j] += $matrix_1[$i][$k] * $matrix_2[$k][$j];
                }
            }           
        }
        return $result;
    }
    
    /**
     * gets the resolution of a png image in dots per inch
     *
     * @param string $imageData
     * @return array
     * @access public
     * @static
     */
    public static function pngResolution($imageData)
    {
        $phys = \strpos($imageData, 'pHYs');
        if ($phys > 0){
            $dataType = \hexdec(\bin2hex(\substr($imageData, $phys + 20, 1)));
        }
        if ($phys >0 && $dataType = 1){
            //the resolution is given in dots per meter.
            // an inch is 0.0254 m aprox.
            $xyResolution = \bin2hex(\substr($imageData, $phys + 4, 16));
            return array(
                \floor(\hexdec(\substr($xyResolution, 0, 8)) * 0.0254), 
                \floor(\hexdec(\substr($xyResolution, 8, 8)) * 0.0254)
                );
        }else{
            $dpi = Docxpresso\CreateDocument::$config['images']['dpi'];
            $resolution = array($dpi, $dpi);
            return $resolution;
        }
    }
    
    /**
     * sums the given values taken into account their dimensions
     *
     * @param array $data
     * @param string $unit
     * @param boolean $number
     * @return string
     * @access public
     * @static
     */
    public static function sum($data, $unit, $number = true)
    {
        $raw = array();
        foreach ($data as $element){
            $raw[] = \floatval(self::convertUnits($unit, $element));
        }
        if ($number) {
            return array_sum($raw);
        } else {
            return array_sum($raw) . $unit;
        }
    }
    
        /**
     * updates the value of a DOMNode
     *
     * @param string $nodeName the name of the node we want to update
     * @param string $value
     * @param DOMNode $parentNode the parent node of the updated node
     * @return void
     * @access public
     * @static
     */
    public static function updateNodeValue($nodeName, $value, $parentNode)
    {
        $tmp = \explode(':', $nodeName);
        $name = \array_pop($tmp);
        $elements = $parentNode->getElementsByTagName($name);
        if ($elements->length > 0) {
            $elements->item(0)->nodeValue = $value;
        } else {
            $new = $parentNode->ownerDocument->createElement($nodeName, $value);
            $parentNode->appendChild($new);
        }
    }
    
    /**
     * checks if a string is UTF-8 encoded
     * 
     * @access public
     * @param string $str
     * @static
     * @return boolean
     */
    public static function UTF8Encoded($str)
    {

        $str = preg_replace("#[\x09\x0A\x0D\x20-\x7E]#", "", $str);
        $str = preg_replace("#[\xC2-\xDF][\x80-\xBF]#", "", $str);
        $str = preg_replace("#\xE0[\xA0-\xBF][\x80-\xBF]#", "", $str);
        $str = preg_replace("#[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}#", "", $str);
        $str = preg_replace("#\xED[\x80-\x9F][\x80-\xBF]#", "", $str);
        $str = preg_replace("#\xF0[\x90-\xBF][\x80-\xBF]{2}#", "", $str);
        $str = preg_replace("#[\xF1-\xF3][\x80-\xBF]{3}#", "", $str);
        $str = preg_replace("#\xF4[\x80-\x8F][\x80-\xBF]{2}#", "", $str);

        if ($str == '') {
            return true;
        } else {
            return false;
        }
    }
    
}