<?php

/**
 * Image
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
use Docxpresso;

/**
 * Creates an image element
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class Image extends ODFElement
{
    /**
     * allowed image extensions
     * 
     * @var array
     * @access public
     * @static
     */
    public static $allowedImageExtensions = array(
        'jpg' => true,
        'jpeg' => true,
        'png' => true,
        'gif' => true,
        'bmp' => true,
        );
    
    /**
     * current image extension
     * 
     * @var string
     * @access private
     */
    private $_extension;
    
    /**
     * image size array (width an height)
     * 
     * @var array
     * @access private
     */
    private $_imgSize;
    
    /**
     * image resolution
     * 
     * @var array
     * @access private
     */
    private $_imgResolution;
    
    /**
     * auxiliary image size array (width an height)
     * @var array
     * @access private
     */
    private $_size;
       
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
        //get image name
        if (empty($options['src'])) {
            $message = 'You need to set the image source in the options array.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            return;
        } else if (\substr($options['src'],0,10) == 'data:image') {
            $base64 = true;
        }
        if (isset($base64) && $base64) {
            $dataArray = \explode(';', $options['src']);
            $this->_extension = \str_replace('data:image/', '', $dataArray[0]);
            $this->_file = \uniqid() . '.' . $this->_extension;
            $data = \str_replace('base64,', '', $dataArray[1]);
            $this->_imageData = @\base64_decode($data);
        } else {
            $this->_file = commons\Utilities::cleanPath($options['src']);
            $this->_extension = \pathinfo($this->_file, PATHINFO_EXTENSION );
            //get image
            $this->_imageData = @\file_get_contents($options['src']);
        }
        if (!isset(self::$allowedImageExtensions[$this->_extension])) {
            $message = 'This image extension: ' . $this->_extension 
                        . 'is not allowed.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            return;
        }
        
        if (empty($this->_imageData)) {
            $message = 'There was an error retrieving image data.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            return;
        } else {
            Docxpresso\CreateDocument::$media['media/' . $this->_file] = 
                $this->_imageData;
        }
        //image dimensions
        $width = parser\CSSPropsLexer::extractSingleProperty('width', 
                                                             $options['style']);
        $height = parser\CSSPropsLexer::extractSingleProperty('height', 
                                                              $options['style']);   
        $data = array();
        if (!empty($width)) {
            $data['width'] = parser\CSSPropsLexer::tokenizeUnits($width);
        }
        if (!empty($height)) {
            $data['height'] = parser\CSSPropsLexer::tokenizeUnits($height);
        }
        $this->_getDimensions($data);
        if ( !empty($width) 
             && empty($height)) {
            if (!empty($this->_size[0])) {
                $ratio  = $this->_size[1]/$this->_size[0];
                $this->_imgSize['height'][0] = 
                    $this->_imgSize['width'][0] * $ratio;
                //set the same units
                $this->_imgSize['height'][1] = 
                    $this->_imgSize['width'][1];
            }
        } else if ( empty($width) 
                    && !empty($height)) {
            if (!empty($this->_size[1])) {
                $ratio  = $this->_size[0]/$this->_size[1];
                $this->_imgSize['width'][0] = 
                    $this->_imgSize['height'][0] * $ratio;
                //set the same units
                $this->_imgSize['width'][1] = 
                    $this->_imgSize['height'][1];
            }
        }
        if (\is_string($options['style'])) {
            $options['style'] .= ';width: ' . $this->_imgSize['width'][0]; 
            $options['style'] .= $this->_imgSize['width'][1] . ';';
            $options['style'] .= 'height: ' . $this->_imgSize['height'][0];
            $options['style'] .= $this->_imgSize['height'][1] . ';';
        } else if (\is_array($options['style'])) {
            if (!isset($options['style']['width'])) {
                $options['style']['width'] = $this->_imgSize['width'];
            }
            if (!isset($options['style']['height'])) {
                $options['style']['height'] = $this->_imgSize['height'];
            }
        }
                
        //create the image node
        $imageNode = $node->ownerDocument->createElement('draw:image');
        $imageNode->setAttribute('xlink:href', 'media/' . $this->_file);
        $imageNode->setAttribute('xlink:type', 'simple');
        $imageNode->setAttribute('xlink:show', 'embed');
        $imageNode->setAttribute('xlink:actuate', 'onLoad');
        //check if there is already a frame
        if ($node->nodeName == 'draw:frame') {
            $node->appendChild($imageNode);
            $this->_accesibility($node, $options);
            $this->setNode($node);
            $this->setNodeType($node->nodeName);
        } else {
            $frame = $this->frame($options, true);
            $frameNode = $frame->getNode();
            $frameNode->appendChild($imageNode);
            $this->_accesibility($node, $options);
            $this->setNode($frameNode);
            $this->setNodeType($frameNode->nodeName);
        }

        
    }
    
    /**
     * appends a title and description elements to the image to improve
     * accessibility
     * 
     * @param DOMNode $node
     * @param array $options
     * @return void
     * @access private
     */
    private function _accesibility ($node, $options) 
    {
        if (isset($options['title'])) {
            $title = $node->ownerDocument->createElement('svg:title', 
                                                         $options['title']);
            $node->getElementsByTagName('frame')->item(0)->appendChild($title);
        }
        if (isset($options['description'])) {
            $dsc = $node->ownerDocument->createElement('svg:desc', 
                                                       $options['description']);
            $node->getElementsByTagName('frame')->item(0)->appendChild($dsc);
        }
    }
    
    /**
     * returns image width and height
     * 
     * @param array $data
     * @return string
     * @access private
     */
    private function _getDimensions ($data) 
    {
        $dimension = array('width' => 0, 'height' => 1);
        foreach ($dimension as $key => $value) {
            if (isset($data[$key])) {
                //the dimension is given
                if ($data[$key][1] != 'px') {
                    //the width is set in standard printing units
                    $this->_imgSize[$key][0] = $data[$key][0]; 
                    $this->_imgSize[$key][1] = $data[$key][1];
                } else {
                    //we need to detect the dot density in order to get the
                    //image size in cm or inches
                    $this->_imgResolution = 
                        commons\Utilities::getImgResolution($this->_imageData,
                                                            $this->_extension); 
                    $this->_imgSize[$key][0] = 
                        ($data[$key][0]/$this->_imgResolution[$value]);
                    $this->_imgSize[$key][1] = 'in';
                }
            } else {
                //the dimensions has not been set explicitely or has been 
                // declared to be auto detected
                //we need to extract the size info from $this->_imageData
                $this->_size = commons\Utilities::getImgSize($this->_imageData);
                $this->_imgResolution = 
                        commons\Utilities::getImgResolution($this->_imageData,
                                                            $this->_extension);
                $this->_imgSize[$key][0] = 
                    ($this->_size[$value]/$this->_imgResolution[$value]);
                $this->_imgSize[$key][1] = 'in';

            }
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
        $defaultStyles = 'border: none;';
        return $defaultStyles;
    }
   
}