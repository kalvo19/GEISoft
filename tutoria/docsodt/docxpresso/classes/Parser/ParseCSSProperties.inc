<?php

/**
 * ParseCSSProperties
 *
 * @copyright  Copyright (c) 2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Parser;

use Docxpresso\Parser\HTML as html;
use Docxpresso\Parser\CSS as css;

/**
 * This class converts into an array the CSS properties associated with a 
 * particular CSS Selector
 *
 * @package    Docxpresso
 * @subpackage Parser
 */

class ParseCSSProperties
{  
    /**
     * CSSPropslexer instance
     * @var CSSPropsLexer
     * @access private
     */
    private $_CSSPropsLexer;
    /**
     *
     * @var array parsedCSSProps
     * @access private
     */
    private $_importantProps;
    /**
     *
     * @var array parsedCSSProps
     * @access private
     */
    private $_parsedCSSProps;
    
    /**
     * Construct
     *
     * @access public
     */
    public function __construct()
    {
        //create an instance of CSSPropsLexer that we will use to tokenize
        //compund properties
        $this->_CSSPropsLexer = new CSSPropsLexer();
    }
    
    
    /**
     * Parses the CSS properties asoociated with a particular CSS selector
     *
     * @param string $CSSProperties
     * @return array with the parsed properties in a standard form
     * @access public
     */
    public function parseCSS($CSSProperties)
    {      
        //initialize the arrays that will hold the parsed CSS properties
        $this->_parsedCSSProps = array();
        $this->_importantProps = array();
        //the individual properties should be separated by ";"
        $tmpCSSProps = \explode(';', $CSSProperties);
        $tmpLength = count($tmpCSSProps);
        for($j = 0; $j < $tmpLength; $j++){
            if (\strpos($tmpCSSProps[$j], ':') !== false) {
                $this->_parseSingleCSSProp($tmpCSSProps[$j]);
            }
        }
        return array($this->_parsedCSSProps, $this->_importantProps);
    }
    

    /**
     * Normailzes a size property like width or height returning an array
     * with the numeric data and the used unit. The units returned are
     * pt, % , em or rem (all other units are converted to one of these types).
     * The case 'auto' is treated separately to handle the centering of tables 
     * and other block level elements
     *
     * @param string $prop containing the single raw CSS properies
     * @return void
     * @access private
     */
    private function _normalizedSizeProp($prop)
    {
        if (\trim($prop) == 'auto') {
            return array( 'auto', '');
        }
        $matches = array();
        $regex = '/([0-9\-]+\.?[0-9]*)\s*(px|em|rem|ex|%|in|cm|mm|pt|pc)?/i';
        \preg_match($regex, $prop, $matches);
        if (empty($matches)) {
           return; 
        }
        if (isset($matches[1])) {
            $rawVal = $matches[1];
        }
        if (isset($matches[2])) {
            $rawUnits = $matches[2];
        } else {
            $rawUnits = 'pt';
        }
        if ($rawUnits == 'pt'
            || $rawUnits == 'mm'
            || $rawUnits == 'cm'
            || $rawUnits == 'in'
            || $rawUnits == 'em'
            || $rawUnits == 'rem'
            || $rawUnits == '%') {
            return array( $rawVal, $rawUnits);
        } else if ($rawUnits == 'px') {
            //px is the acronym for 'pixel' that corresponds to 0.75 points
            return array( $rawVal * 0.75, 'pt');
        } else if ($rawUnits == 'pc') {
            //pc is the acronym for 'pica' that corresponds to 12 points
            return array( \floor($rawVal * 12), 'pt');
        }  else if ($rawUnits == 'ex') {
            //ex is the acronym for 'x-height' aproximately half em
            return array( \floor($rawVal * 0.5), 'em');
        } else if (!isset($rawUnits)) {
            //we assume that the number the value is relative to the current
            //font size so we use em units
            return array( $rawVal, 'em');
        } 
    }
    /**
     * Parses the CSS properties asoociated with a particular CSS selector
     *
     * @param string $prop containing the single raw CSS properies
     * @return void
     * @access private
     */
    private function _parseSingleCSSProp($prop)
    {
        //get the property name and its raw value
        $tmp = \explode(':', $prop);
        $key = \strtolower(\trim($tmp[0]));
        $value = \trim($tmp[1]);
        //check if there is an !important flag
        $newValue = \preg_replace('/!important/i', '', $value);
        //now we should call to the adequate single property parser
        if(isset(css\Resources::$CSSProperties[$key])){
            $keyUnderscore = str_replace('-', '_', $key);
            $methodName = '_parse_' . $keyUnderscore;
            if($value == $newValue){
                $this->$methodName($key, $newValue, false);
            } else {
                $this->$methodName($key, $newValue, true);
            }
        }
    }
    
    /**
     * Parses the CSS color property
     * This property is inherited
     * Its default value is #000000
     * 
     * It may be given in different formats:
     * 1. Hexadecimal: #FF0000
     * 2. RGB: rgb(255, 0, 0)
     * 3. RGBA: rgba(255,0,0,0.3)
     * 4. CMYK: device-cmyk(0.5, 0.1, 0.0, 0.2)
     * 5. HSL: hsl(120,100%,50%);
     * 6. HSLA: hsla(120,100%,50%,0.3)
     * 7. "transparent"
     * 8. string: red, ...
     * Other possible values are: inherited or initial that are ignored
     * The corresponding ODF attributes are
     *  paragraphs-> does not apply
     *  text-> fo:color
     *  tables-> does not apply
     *  rows-> does not apply
     *  frames-> does not apply
     * Whenever the opacity may apply it corresponds to attributes like
     * svg:stroke-opacity or style:background-transparency
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_color($key, $value, $important = false)
    {
        $colorArray = $this->_process_color($key, $value);
        if($colorArray['color'] !== NULL) {
            if ($important) {
                $this->_importantProps[$key] = $colorArray['color'];
            } else {
                $this->_parsedCSSProps[$key] = $colorArray['color'];
            }
        }
        //for this particular CSS property we ignore the opacity
        /*
        if($colorArray['opacity'] !== NULL) {
            if ($important) {
                $this->_importantProps['opacity'] = $colorArray['opacity'];
            } else {
                $this->_parsedCSSProps['opacity'] = $colorArray['opacity'];
            }
        }*/
    }
    
    /**
     * Parses the CSS opacity property
     * The value should be higher than 0 and smaller than 1
     * The corresponding ODF attribute is given by style:background-transparency
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_opacity($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS background property
     * The available properties are: 
     *  background-color, 
     *  background-position, 
     *  background-size, (not parsed)
     *  background-repeat, 
     *  background-origin, (not parsed)
     *  background-clip, (not parsed)
     *  background-attachment, (not parsed)
     *  background-image.
     * The corresponding ODF attributes are described in the corresponding
     * specific CSS properties like 'background-color'
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_background($key, $value, $important = false)
    {
        //Extract the independent background properties
        $backgroundProps = $this->_CSSPropsLexer
                           ->tokenizeBackgroundProp($value);
        if (isset($backgroundProps['color'])) {
            $this->_parse_background_color($key . '-color', 
                                           $backgroundProps['color'], 
                                           $important);
        }
        if (isset($backgroundProps['position'])) {
            $this->_parse_background_position($key . '-position', 
                                              $backgroundProps['position'], 
                                              $important);
        }
        if (isset($backgroundProps['repeat'])) {
            $this->_parse_background_repeat($key . '-repeat', 
                                            $backgroundProps['repeat'], 
                                            $important);
        }
        if (isset($backgroundProps['image'])) {
            $this->_parse_background_image($key . '-image', 
                                           $backgroundProps['image'], 
                                           $important);
        }    
    }
    
    /**
     * Parses the CSS background-color property
     * It may be given in different formats:
     * 1. Hexadecimal: #FF0000
     * 2. RGB: rgb(255, 0, 0)
     * 3. RGBA: rgba(255,0,0,0.3)
     * 4. CMYK: device-cmyk(0.5, 0.1, 0.0, 0.2)
     * 5. HSL: hsl(120,100%,50%);
     * 6. HSLA: hsla(120,100%,50%,0.3)
     * 7. "transparent"
     * 8. string: red, ...
     * Other possible values are: inherited or initial that are not parsed
     * The corresponding ODF attributes are
     *  paragraphs-> does not apply directly but via style:text-properties
     *  text-> fo:background-color
     *  tables-> fo:background-color
     *  rows-> fo:background-color
     *  cells-> fo:background-color
     *  frames-> fo:background-color
     * Regarding the opacity it applies to:
     *  paragraphs-> style:background-transparency
     * 
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_background_color($key, $value, $important = false)
    {
        $colorArray = $this->_process_color($key, $value);
        if($colorArray['color'] !== NULL) {
            if ($important) {
                $this->_importantProps[$key] = $colorArray['color'];
            } else {
                $this->_parsedCSSProps[$key] = $colorArray['color'];
            }
        }
        if($colorArray['opacity'] !== NULL) {
            if ($important) {
                $this->_importantProps['opacity'] = $colorArray['opacity'];
            } else {
                $this->_parsedCSSProps['opacity'] = $colorArray['opacity'];
            }
        }
    }
    
    /**
     * Parses the CSS background-image property
     * It should have the form url(path to the image)
     * The corresponding ODF attributes are stored in the style:background-image
     * element in the xlink:href attribute and it applies to:
     *  graphics
     *  layout
     *  paragraphs
     *  sections
     *  tables
     *  rows
     *  table cells 
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_background_image($key, $value, $important = false)
    {
        //remove all whitespace or carraige returns
        $value = \preg_replace('/\s+/', '', $value);
        $src = \substr($value, 4 , -1);
        if ($important) {
            $this->_importantProps[$key] = $src;
        } else {
            $this->_parsedCSSProps[$key] = $src;
        }
    }
    
    /**
     * Parses the CSS background-position property
     * The corresponding ODF attributes are stored in the style:background-image
     * element in the style:position attribute and it applies to:
     *  graphics
     *  layout
     *  paragraphs
     *  sections
     *  tables
     *  rows
     *  table cells
     * It follows the standard CSS syntax
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_background_position($key, $value, 
                                                $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS background-repeat property
     * The parsed values are repeat and no-repeat (there is no support for
     * repeat-x or repeat-y)
     * The corresponding ODF attributes are stored in the style:background-image
     * element in the style:position attribute and it applies to:
     *  graphics
     *  layout
     *  paragraphs
     *  sections
     *  tables
     *  rows
     *  table cells
     * It follows the standard CSS syntax
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_background_repeat($key, $value, $important = false)
    {
        if ($value == 'repeat' || $value == 'no-repeat') {
            if ($important) {
                $this->_importantProps[$key] = $value;
            } else {
                $this->_parsedCSSProps[$key] = $value;
            }
        }
    }
    
    /**
     * Parses the CSS border property
     * Delegates into:
     *  _parse_border_bottom
     *  _parse_border_left
     *  _parse_border_right
     *  _parse_border_top
     * It applies to basically all ODF elements and it follows the standard
     * CSS syntax
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border($key, $value, $important = false)
    {
        $this->_parse_border_bottom($key . '-bottom', $value, $important);
        $this->_parse_border_left($key . '-left', $value, $important);
        $this->_parse_border_right($key . '-top', $value, $important);
        $this->_parse_border_top($key . '-right', $value, $important);
        
    }
    
    /**
     * Parses the CSS border-bottom property
     * Delegates into:
     *  _parse_border_bottom_color
     *  _parse_border_bottom_style
     *  _parse_border_bottom_width
     * The corresponding ODF property is fo:border that follows the standard CSS
     * syntax
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_bottom($key, $value, $important = false)
    {
        //Extract the independent border properties
        $borderProps = $this->_CSSPropsLexer->tokenizeBorderProp($value);
        if (isset($borderProps['color'])) {
            $this->_parse_border_bottom_color($key . '-color', 
                                              $borderProps['color'], 
                                              $important);
        }
        if (isset($borderProps['style'])) {
            $this->_parse_border_bottom_style($key . '-style', 
                                              $borderProps['style'], 
                                              $important);
        }
        if (isset($borderProps['width'])) {
            $this->_parse_border_bottom_width($key . '-width', 
                                              $borderProps['width'], 
                                              $important);
        }
    }
    
    /**
     * Parses the CSS border-bottom-color property
     * This has no direct ODF translation and it should be compund to build a 
     * shorthand border_bottom property with standard CSS syntax
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_bottom_color($key, $value, 
                                                $important = false)
    {
        $colorArray = $this->_process_color($key, $value);
        if($colorArray['color'] !== NULL) {
            if ($important) {
                $this->_importantProps[$key] = $colorArray['color'];
            } else {
                $this->_parsedCSSProps[$key] = $colorArray['color'];
            }
        }
        if($colorArray['opacity'] !== NULL) {
            if ($important) {
                $this->_importantProps['opacity'] = $colorArray['opacity'];
            } else {
                $this->_parsedCSSProps['opacity'] = $colorArray['opacity'];
            }
        }
    }
    
    /**
     * Parses the CSS border-bottom-style property
     * This has no direct ODF translation and it should be compund to build a 
     * shorthand border_bottom property with standard CSS syntax
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_bottom_style($key, $value, 
                                                $important = false)
    {
        if (isset(CSSPropsLexer::$borderStyles)) {
            if ($important) {
                $this->_importantProps[$key] = $value;
            } else {
                $this->_parsedCSSProps[$key] = $value;
            }
        }
    }
    
    /**
     * Parses the CSS border-bottom-width property
     * This has no direct ODF translation and it should be compund to build a 
     * shorthand border_bottom property with standard CSS syntax
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_bottom_width($key, $value, 
                                                $important = false)
    {
        //normalize the width property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS border-color property
     * This has no direct ODF translation and it should be compund to build a 
     * shorthand border property with standard CSS syntax
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_color($key, $value, $important = false)
    {
        $colorArray = $this->_process_color($key, $value);
        if($colorArray['color'] !== NULL) {
            if ($important) {
                $this->_importantProps[$key] = $colorArray['color'];
            } else {
                $this->_parsedCSSProps[$key] = $colorArray['color'];
            }
        }
        if($colorArray['opacity'] !== NULL) {
            if ($important) {
                $this->_importantProps['opacity'] = $colorArray['opacity'];
            } else {
                $this->_parsedCSSProps['opacity'] = $colorArray['opacity'];
            }
        }
    }
    
    /**
     * Parses the CSS border-left property
     * Delegates into:
     *  _parse_border_left_color
     *  _parse_border_left_style
     *  _parse_border_left_width
     * @see _parse_border
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_left($key, $value, $important = false)
    {
        //Extract the independent border properties
        $borderProps = $this->_CSSPropsLexer->tokenizeBorderProp($value);
        if (isset($borderProps['color'])) {
            $this->_parse_border_left_color($key . '-color', 
                                            $borderProps['color'],
                                            $important);
        }
        if (isset($borderProps['style'])) {
            $this->_parse_border_left_style($key . '-style', 
                                            $borderProps['style'],
                                            $important);
        }
        if (isset($borderProps['width'])) {
            $this->_parse_border_left_width($key . '-width', 
                                            $borderProps['width'],
                                            $important);
        }
    }
    
    /**
     * Parses the CSS border-left-color property
     * This has no direct ODF translation and it should be compund to build a 
     * shorthand border_left property with standard CSS syntax
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_left_color($key, $value, $important = false)
    {
        $colorArray = $this->_process_color($key, $value);
        if($colorArray['color'] !== NULL) {
            if ($important) {
                $this->_importantProps[$key] = $colorArray['color'];
            } else {
                $this->_parsedCSSProps[$key] = $colorArray['color'];
            }
        }
        if($colorArray['opacity'] !== NULL) {
            if ($important) {
                $this->_importantProps['opacity'] = $colorArray['opacity'];
            } else {
                $this->_parsedCSSProps['opacity'] = $colorArray['opacity'];
            }
        }
    }
    
    /**
     * Parses the CSS border-left-style property
     * This has no direct ODF translation and it should be compund to build a 
     * shorthand border_left property with standard CSS syntax
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_left_style($key, $value, $important = false)
    {
        if (isset(CSSPropsLexer::$borderStyles)) {
            if ($important) {
                $this->_importantProps[$key] = $value;
            } else {
                $this->_parsedCSSProps[$key] = $value;
            }
        }
    }
    
    /**
     * Parses the CSS border-left-width property
     * This has no direct ODF translation and it should be compund to build a 
     * shorthand border_left property with standard CSS syntax
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_left_width($key, $value, $important = false)
    {
        //normalize the width property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS border-radius property
     * We only parsed a global value for every corner with no left/right values
     * The equivalent ODF property is:
     *  'draw:corner-radius'
     * and we only apply it to ODF text-boxes
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_radius($key, $value, $important = false)
    {
        //normalize the border-radius property assuming a single global value
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS border-right property
     * Delegates into:
     *  _parse_border_right_color
     *  _parse_border_right_style
     *  _parse_border_right_width
     * @see _parse_border
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_right($key, $value, $important = false)
    {
        //Extract the independent border properties
        $borderProps = $this->_CSSPropsLexer->tokenizeBorderProp($value);
        if (isset($borderProps['color'])) {
            $this->_parse_border_right_color($key . '-color', 
                                             $borderProps['color'],
                                             $important);
        }
        if (isset($borderProps['style'])) {
            $this->_parse_border_right_style($key . '-style', 
                                             $borderProps['style'],
                                             $important);
        }
        if (isset($borderProps['width'])) {
            $this->_parse_border_right_width($key . '-width', 
                                             $borderProps['width'],
                                             $important);
        }
    }
    
    /**
     * Parses the CSS border-right-color property
     * This has no direct ODF translation and it should be compund to build a 
     * shorthand border_right property with standard CSS syntax
     * 
     * @param string $key
     * @param string $value
     * @param bool $important
     * @return void
     * @access private
     */
    private function _parse_border_right_color($key, $value, $important = false)
    {
        $colorArray = $this->_process_color($key, $value);
        if($colorArray['color'] !== NULL) {
            if ($important) {
                $this->_importantProps[$key] = $colorArray['color'];
            } else {
                $this->_parsedCSSProps[$key] = $colorArray['color'];
            }
        }
        if($colorArray['opacity'] !== NULL) {
            if ($important) {
                $this->_importantProps['opacity'] = $colorArray['opacity'];
            } else {
                $this->_parsedCSSProps['opacity'] = $colorArray['opacity'];
            }
        }
    }
    
    /**
     * Parses the CSS border-right-style property
     * This has no direct ODF translation and it should be compund to build a 
     * shorthand border_right property with standard CSS syntax
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_right_style($key, $value, $important = false)
    {
        if (isset(CSSPropsLexer::$borderStyles)) {
            if ($important) {
                $this->_importantProps[$key] = $value;
            } else {
                $this->_parsedCSSProps[$key] = $value;
            }
        }
    }
    
    /**
     * Parses the CSS border-right-width property
     * This has no direct ODF translation and it should be compund to build a 
     * shorthand border_right property with standard CSS syntax
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_right_width($key, $value, $important = false)
    {
        //normalize the width property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS border-style property
     * This has no direct ODF translation and it should be compund to build a 
     * shorthand border property with standard CSS syntax
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_style($key, $value, $important = false)
    {
        if (isset(CSSPropsLexer::$borderStyles)) {
            if ($important) {
                $this->_importantProps[$key] = $value;
            } else {
                $this->_parsedCSSProps[$key] = $value;
            }
        }
    }
    
    /**
     * Parses the CSS border-top property
     * Delegates into:
     *  _parse_border_top_color
     *  _parse_border_top_style
     *  _parse_border_top_width
     * @see _parse_border
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_top($key, $value, $important = false)
    {
        //Extract the independent border properties
        $borderProps = $this->_CSSPropsLexer->tokenizeBorderProp($value);
        if (isset($borderProps['color'])) {
            $this->_parse_border_top_color($key . '-color', 
                                           $borderProps['color'],
                                           $important);
        }
        if (isset($borderProps['style'])) {
            $this->_parse_border_top_style($key . '-style', 
                                           $borderProps['style'],
                                           $important);
        }
        if (isset($borderProps['width'])) {
            $this->_parse_border_top_width($key . '-width', 
                                           $borderProps['width'],
                                           $important);
        }
    }
    
    /**
     * Parses the CSS border-top-color property
     * This has no direct ODF translation and it should be compund to build a 
     * shorthand border_top property with standard CSS syntax
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_top_color($key, $value, $important = false)
    {
        $colorArray = $this->_process_color($key, $value);
        if($colorArray['color'] !== NULL) {
            if ($important) {
                $this->_importantProps[$key] = $colorArray['color'];
            } else {
                $this->_parsedCSSProps[$key] = $colorArray['color'];
            }
        }
        if($colorArray['opacity'] !== NULL) {
            if ($important) {
                $this->_importantProps['opacity'] = $colorArray['opacity'];
            } else {
                $this->_parsedCSSProps['opacity'] = $colorArray['opacity'];
            }
        }
    }
    
    /**
     * Parses the CSS border-top-style property
     * This has no direct ODF translation and it should be compund to build a 
     * shorthand border_top property with standard CSS syntax
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_top_style($key, $value, $important = false)
    {
        if (isset(CSSPropsLexer::$borderStyles)) {
            if ($important) {
                $this->_importantProps[$key] = $value;
            } else {
                $this->_parsedCSSProps[$key] = $value;
            }
        }
    }
    
    /**
     * Parses the CSS border-top-width property
     * This has no direct ODF translation and it should be compund to build a 
     * shorthand border_top property with standard CSS syntax
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_top_width($key, $value, $important = false)
    {
        //normalize the width property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS border-width property
     * This has no direct ODF translation and it should be compund to build a 
     * shorthand border_top property with standard CSS syntax
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_width($key, $value, $important = false)
    {
        //normalize the width property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS box-shadow property
     * The equivalent ODF attribute is:
     *  style:shadow
     * This property follows the CSS standard
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_box_shadow($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS bottom property
     * WARNING: There is no equivalent ODF attribute
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_bottom($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS clear property
     * WARNING: There is no qequivalent ODF attribute
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_clear($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS clip property
     * The equivalent ODF attribute is:
     *  fo:clip
     * This property follows the CSS standard
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_clip($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS display property
     * This property has no ODF equivalent but it used in the
     * parsing of the HTML tree
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_display($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS float property
     * This property has structural consequences and it is not directly parsed
     * into a ODF property
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_float($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS height property
     * The corresponding ODF attributes are
     *  svg:height
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_height($key, $value, $important = false)
    {
        //normalize the height property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS left property
     * This does not translate directly into an ODF property
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_left($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS padding property
     * This property is parse via padding-bottom, padding-left, padding-right
     * and padding-top
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_padding($key, $value, $important = false)
    {
        $res = $this->_process_shorthand($value);
        if(!empty($res)){
            $this->_parse_padding_bottom($key . '-bottom', $res[0], $important);
            $this->_parse_padding_left($key . '-left', $res[1], $important);
            $this->_parse_padding_right($key . '-right', $res[2], $important);
            $this->_parse_padding_top($key . '-top', $res[3], $important);
        }
    }
    
    /**
     * Parses the CSS padding-bottom property
     * The corresponding ODF property is:
     *  fo:padding-bottom
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_padding_bottom($key, $value, $important = false)
    {
        //normalize the padding property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS padding-left property
     * The corresponding ODF property is:
     *  fo:padding-left
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_padding_left($key, $value, $important = false)
    {
        //normalize the padding property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS padding-right property
     * The corresponding ODF property is:
     *  fo:padding-right
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_padding_right($key, $value, $important = false)
    {
        //normalize the padding property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS padding-top property
     * The corresponding ODF property is:
     *  fo:padding-top
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_padding_top($key, $value, $important = false)
    {
        //normalize the padding property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses an internal DOCXPRESSO property
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_parentstyleid($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS position property
     * This is not directly parsed into an ODF property but has structural
     * implications
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_position($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS right property
     * This is not directly parsed into an ODF property but has structural
     * implications
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_right($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS top property
     * This is not directly parsed into an ODF property but has structural
     * implications
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_top($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS visibility property
     * This is not directly parsed into an ODF property but it is usesd to build
     * the HTML tree.
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_visibility($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS width property
     * The corresponding ODF attributes are
     *  svg:width
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_width($key, $value, $important)
    {
        //normalize property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS vertical-align property
     * The corresponding ODF attributes are
     *  style:vertical-align
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_vertical_align($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS z-index property
     * The corresponding ODF attributes are
     *  draw:z-index
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_z_index($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS margin property
     * This property is parse via margin-bottom, margin-left, margin-right
     * and margin-top
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_margin($key, $value, $important = false)
    {
        if (\trim($value) == 'auto'){
            $res = array('auto', 'auto', 'auto', 'auto');
        } else {
            $res = $this->_process_shorthand($value);
        }
        if(!empty($res)){
            $this->_parse_margin_bottom($key . '-bottom', $res[0], $important);
            $this->_parse_margin_left($key . '-left', $res[1], $important);
            $this->_parse_margin_right($key . '-right', $res[2], $important);
            $this->_parse_margin_top($key . '-top', $res[3], $important);
        }
    }
    
    /**
     * Parses the CSS margin-bottom property
     * The corresponding ODF property is:
     *  fo:margin-bottom
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_margin_bottom($key, $value, $important = false)
    {
        //normalize the margin property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS margin-left property
     * The corresponding ODF property is:
     *  fo:margin-left
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_margin_left($key, $value, $important = false)
    {
        //normalize the margin property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS margin-right property
     * The corresponding ODF property is:
     *  fo:margin-right
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_margin_right($key, $value, $important = false)
    {
        //normalize the margin property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS margin-top property
     * The corresponding ODF property is:
     *  fo:margin-top
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_margin_top($key, $value, $important = false)
    {
        //normalize the margin property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS max-height property
     * The corresponding ODF attributes are
     *  fo:max-height
     *  style:footnote-max-height
     * that corresponds to the XSL standard
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_max_height($key, $value, $important = false)
    {
        //normalize property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS max-width property
     * The corresponding ODF attributes are
     *  fo:max-width
     * that corresponds to the XSL standard
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_max_width($key, $value, $important = false)
    {
        //normalize property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS min-height property
     * The corresponding ODF attributes are
     *  fo:min-height
     * that corresponds to the XSL standard
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_min_height($key, $value, $important = false)
    {
        //normalize property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS min-width property
     * The corresponding ODF attributes are
     *  fo:min-width
     * that corresponds to the XSL standard
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_min_width($key, $value, $important = false)
    {
        //normalize property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS hyphens property
     * The parsed values are none or auto (manual has no ODF equivalent)
     * The corresponding ODF attributes are
     *  fo:hyphenate
     * that corresponds to the XSL standard
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_hyphens($key, $value, $important = false)
    {
        if ($value == 'none') {
            if ($important) {
                $this->_importantProps[$key] = 'false';
            } else {
                $this->_parsedCSSProps[$key] = 'false';
            }
        } else {
            if ($important) {
                $this->_importantProps[$key] = 'true';
            } else {
                $this->_parsedCSSProps[$key] = 'true';
            }
        }
    }
    
    /**
     * Parses the CSS letter-spacing property
     * The corresponding ODF attributes are
     *  fo:letter-spacing
     * that corresponds to the XSL standard
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_letter_spacing($key, $value, $important = false)
    {
        //normalize property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS line-break property
     * The corresponding ODF attributes are
     *  style:line-break
     * It only applies to paragraphs
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_line_break($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS line-height property
     * The corresponding ODF attributes are
     *  fo:line-height
     * It only applies to paragraphs and follows the XSL standard
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_line_height($key, $value, $important = false)
    {
        //normalize property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS text-align property
     * The corresponding ODF attributes are
     *  fo:text-align
     * It only applies to paragraphs and follows the XSL standard
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_text_align($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS text-align-last property
     * The corresponding ODF attributes are
     *  fo:text-align-last
     * It is ignored if it is not accompanied with a fo:text-align attribute
     * It only applies to paragraphs and follows the XSL standard
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_text_align_last($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS text-transform property
     * The parsed values are: capitalize, uppercase or lowercase
     * The ODF equivalent property is fo:text-trasform
     * fo:text-transform and fo:font-variant attributes are mutually 
     * exclusive
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_text_transform($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS text-decoration property
     * The parsed values are: underline and line-trough
     * Depending on its value this maps into the ODF attributes
     *  style:text-underline-type (single)
     *  style:text-line-through-type (single)
     *  style:text-overline-type (single)
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_text_decoration($key, $value, $important = false)
    {
        if ($important &&
            ($value == 'underline' 
             || $value == 'line-trough'
             || $value == 'overline')) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS text-decoration-color property
     * Depending on the value of the text-decoration property
     * this maps into the ODF attributes
     *  style:text-underline-color(single)
     *  style:text-line-through-color (single)
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_text_decoration_color($key, $value, 
                                                  $important = false)
    {
        $colorArray = $this->_process_color($key, $value);
        
        if($colorArray['color'] !== NULL) {
            if ($important) {
                $this->_importantProps[$key] = $colorArray['color'];
            } else {
                $this->_parsedCSSProps[$key] = $colorArray['color'];
            }
        }
    }
    
    /**
     * Parses the CSS text-decoration-line property
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_text_decoration_line($key, $value, 
                                                 $important = false)
    {
        $this->_parse_text_decoration('text-decoration', $value, $important);
    }
    
    /**
     * Parses the CSS text-decoration-style property
     * Determines the line style defined trough the text-decoration-line
     * property
     * The possible values are: double, dotted, dashed, wavy
     * Depending on the value the text-decoration-line property this maps into 
     * the ODF attributes
     *  style:text-underline-type (double)
     *  style:text-line-through-type (double)
     *  style:text-underline-style (single)
     *  style:text-line-through-style (single)
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_text_decoration_style($key, $value, 
                                                  $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS text-align property
     * The corresponding ODF attributes are
     *  fo:text-indent
     * It only applies to paragraphs and follows the XSL standard
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_text_indent($key, $value, $important = false)
    {
        //normalize property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS text-shadow property
     * The corresponding ODF attributes are
     *  fo:text-shadow
     * It only applies to text and follows the XSL standard
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_text_shadow($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS font property and returns the following CSS properties
     * if defined: font-style, font-variant, font-weight, font size, line-height
     * and font-family
     * 
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_font($key, $value, $important = false)
    {
        $fontProps = $this->_CSSPropsLexer->tokenizeFontProp($value);
        if (isset($fontProps['style'])) {
            $this->_parse_font_style($key . '-style', 
                                           $fontProps['style'],
                                           $important);
        }
        if (isset($fontProps['variant'])) {
            $this->_parse_font_variant($key . '-variant', 
                                           $fontProps['variant'],
                                           $important);
        }
        if (isset($fontProps['weight'])) {
            $this->_parse_font_weight($key . '-weight', 
                                           $fontProps['weight'],
                                           $important);
        }
        if (isset($fontProps['size'])) {
            $this->_parse_font_size($key . '-size', 
                                           $fontProps['size'],
                                           $important);
        }
        if (isset($fontProps['family'])) {
            $this->_parse_font_style($key . '-family', 
                                           $fontProps['family'],
                                           $important);
        }
        if (isset($fontProps['line-height'])) {
            $this->_parse_line_height('line-height', 
                                           $fontProps['line-height'],
                                           $important);
        }
        
    }
    
    /**
     * Parses the CSS font-family property
     * The corresponding ODF attributes are
     *  fo:font-family
     * It only applies to text and follows the XSL standard
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_font_family($key, $value, $important = false)
    {
        //in case there is more than one option get the first one
        $fontFamilyArray = \explode(',', $value);
        $fontFamily = \str_replace('"', '', $fontFamilyArray[0] );
        if (\strtolower($fontFamily) == 'sans-serif') {
            $fontFamily = 'Arial';
        } else if (\strtolower($fontFamily) == 'serif') {
            $fontFamily = 'Times New Roman';
        } else if (\strtolower($fontFamily) == 'monospace') {
            $fontFamily = 'Courier New';
        }
        if ($important) {
            $this->_importantProps[$key] = $fontFamily;
        } else {
            $this->_parsedCSSProps[$key] = $fontFamily;
        }
    }
    
    /**
     * Parses the CSS font-kerning property it could be auto, normal or none
     * The corresponding ODF attributes are
     *  style:letter-kerning
     * It only applies to text and follows the XSL standard
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_font_kerning($key, $value, $important = false)
    {
        if ($value == 'none') {
            $value = false;
        } else if ($value == 'auto' || $value == 'normal') {
            $value = true;
        } else {
            return;
        }
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS font-size property
     * The parsed values include: medium, xx-small, x-small, small, large, 
     * x-large, xx-large, smaller, larger and length (10pt, 12px, 1.0em, ...)
     * The correspnding ODF attributes are:
     *  fo:font-size
     *  fo:font-size-asian
     *  fo:font-size-complex
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_font_size($key, $value, $important = false)
    {
        $sizes = array(
            "xx-small" => '0.6em',
            "x-small"  => '0.75em',
            "small"    => '0.9em',
            "medium"   => '1em',
            "large"    => '1.2em',
            "x-large"  => '1.5em',
            "xx-large" => '2.0em',
            "smaller"  => '0.85em',
            "larger"   => '1.15em'
          );
        if (isset($sizes[$key])) {
            $value = $sizes[$key];
        }
        //normalize property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS font-style property
     * The parsed variables are normal, italic and oblique
     * The correspnding ODF attributes are:
     *  fo:font-style
     *  svg:font-style
     * It only applies to text and follows the XSL standard
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_font_style($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS font-variant property
     * The parsed variables are normaland small-caps
     * The correspnding ODF attributes are:
     *  fo:font-variant
     *  svg:font-variant
     * It only applies to text and follows the XSL standard
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_font_variant($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS font-weight property
     * All CSS values are parse but bolder and lighter
     * The correspnding ODF attributes are:
     *  fo:font-weight
     *  fo:font-weight-asian
     *  fo:font-weight-complex
     *  svg:font-weight
     * It only applies to text and follows the XSL standard
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_font_weight($key, $value, $important = false)
    {
        if ($value == 'bolder' || $value == 'lighter') {
           //this values are ignored because they do not have a ODF equivalent 
        } else {
            if ($important) {
                $this->_importantProps[$key] = $value;
            } else {
                $this->_parsedCSSProps[$key] = $value;
            }
        }
    }
    
    /**
     * Parses the CSS direction property
     * The parsed values are ltr and rtl
     * The correspnding ODF attributes are:
     *  style:writing-mode
     * It only applies to text and follows the XSL standard
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_direction($key, $value, $important = false)
    {
        if ($value == 'ltr') {
            $value = 'lr';
        } else if ($value == 'rtl') {
            $value = 'rl';
        } else {
            return;
        }
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS writing-mode property
     * The parsed values are lr-tb, rl-tb, tb-rl, tb-lr, lr, rl, tb
     * The correspnding ODF attributes are:
     *  style:writing-mode
     * It only applies to text and follows the XSL standard
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_writing_mode($key, $value, $important = false)
    {
        $parsedValues = array('lr-tb', 'rl-tb', 'tb-rl', 'tb-lr', 
                              'lr', 'rl', 'tb');
        if (!\in_array($value, $parsedValues)) {
            return;
        }
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS transform property
     * Warning: only text rotations are taking into account
     * The corresponding ODF property is
     *  style:text-rotation angle
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_transform($key, $value, $important = false)
    {    
        $regex = '/rotate\s*\(\s*([0-9\-]+)\s*deg\s*\)/i';
        \preg_match($regex, $value, $matches);
        if(isset($matches[0])) {
            $numResults = count($matches[0]);
            if ($numResults == 0) {
                //not parsed
            } else if ($numResults == 1) {
                if ($important) {
                    $this->_importantProps[$key] = $matches[1];
                } else {
                    $this->_parsedCSSProps[$key] = $matches[1];
                }
            }
        }
    }
    
    /**
     * Parses the CSS border-collapse property
     * The parsed values are separate and collapse
     * This property only applies to tables.
     * The ODF equivalent is:
     * table:border-model (collapsing or separating)
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_collapse($key, $value, $important = false)
    {
        if ($value == 'separate') {
            $value = 'separating';
        } else {
            $value = 'collapsing';
        }
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS border-spacing property
     * TODO: is not yet clear how to parse this property
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_border_spacing($key, $value, $important = false)
    {
        /*$res = $this->_process_shorthand($value);
        if(!empty($res)){
            $this->_parse_border_spacing_bottom($key . '-bottom', $res[0], $important);
            $this->_parse_border_spacing_left($key . '-left', $res[1], $important);
            $this->_parse_border_spacing_right($key . '-right', $res[2], $important);
            $this->_parse_border_spacing_top($key . '-top', $res[3], $important);
        }*/
    }
    
    /**
     * Parses the CSS empty-cells property
     * The parsed values are show (default) or hide
     * There is not a corresponding ODF property but we will take into account
     * this property when rendering a particular empty table cell
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_empty_cells($key, $value, $important = false)
    {
        if ($value == 'hide') {
            if ($important) {
                $this->_importantProps[$key] = $value;
            } else {
                $this->_parsedCSSProps[$key] = $value;
            }
        }
    }
    
    /**
     * Parses the CSS table-layout property
     * The parsed values include auto and fixed
     * The corresponding ODF style property is:
     *  'style:use-optimal-column-width'
     * that applies to table columns only
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_table_layout($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS counter-increment property
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_counter_increment($key, $value)
    {
        //do something here
        $this->_parsedCSSProps[$key] = $value;
    }
    
    /**
     * Parses the CSS counter-reset property
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_counter_reset($key, $value)
    {
        //do something here
        $this->_parsedCSSProps[$key] = $value;
    }
    
    /**
     * Parses the CSS list-style property
     * This property may include
     *  list-style-type
     *  list-style-position
     *  list-style-image
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_list_style($key, $value, $important = false)
    {
        //Extract the independent background properties
        $listProps = $this->_CSSPropsLexer
                           ->tokenizeListProp($value);
        if (isset($listProps['type'])) {
            $this->_parse_list_style_type($key . '-type', 
                                           $listProps['type'], 
                                           $important);
        }
        if (isset($listProps['position'])) {
            //no ODF equivalent
        }
        if (isset($listProps['image'])) {
            $this->_parse_list_style_image($key . '-image', 
                                           $listProps['image'], 
                                           $important);
        }
    }
    
    /**
     * Parses the CSS list-style-image property
     * The corresponding ODF property goes under the xlink:href attribute of the
     * text:list-level-style-image element
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_list_style_image($key, $value, $important = false)
    {
        //remove all whitespace or carraige returns
        $value = \preg_replace('/\s+/', '', $value);
        $src = \substr($value, 4 , -1);
        if ($important) {
            $this->_importantProps[$key] = $src;
        } else {
            $this->_parsedCSSProps[$key] = $src;
        }
    }
    
    /**
     * Parses the CSS list-style-type property
     * The parsed values are:
     *  disc
     *  circle
     *  decimal
     *  decimal-leading-zero
     *  lower-alpha
     *  lower-greek
     *  lower-latin
     *  lower-roman
     *  none
     *  square
     *  upper-alpha
     *  upper-latin
     *  upper-roman
     * The corresponding ODF properties are organized in a completely
     * different manner.
     * The numberings go under the style:num-format attribute of the the 
     * text:list-level-style-number element with possible 
     * values: 1, i, I, a or A
     * The bullets go under the text:bullet-char attribute of the
     * text:list-level-bullet-style element as an unicode character
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_list_style_type($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS outline property
     * Warning: it is parsed as standard border property
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_outline($key, $value, $important = false)
    {
        //overwrite the property to border
        $key = 'border';
        $this->_parse_border_bottom($key . '-bottom', $value, $important);
        $this->_parse_border_left($key . '-left', $value, $important);
        $this->_parse_border_right($key . '-top', $value, $important);
        $this->_parse_border_top($key . '-right', $value, $important);
    }
    
    /**
     * Parses the CSS outline-color property
     * Warning: it is parsed as standard border-color property
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_outline_color($key, $value, $important = false)
    {
        //overwrite the property to border-color
        $key = 'border-color';
        $colorArray = $this->_process_color($key, $value);
        if($colorArray['color'] !== NULL) {
            if ($important) {
                $this->_importantProps[$key] = $colorArray['color'];
            } else {
                $this->_parsedCSSProps[$key] = $colorArray['color'];
            }
        }
        if($colorArray['opacity'] !== NULL) {
            if ($important) {
                $this->_importantProps['opacity'] = $colorArray['opacity'];
            } else {
                $this->_parsedCSSProps['opacity'] = $colorArray['opacity'];
            }
        }
    }
    
    /**
     * Parses the CSS outline-offset property
     * WARNING: not parsed used padding instead
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_outline_offset($key, $value, $important = false)
    {
        //This property is not directly parsed
        //Use padding properties instead
    }
    
    /**
     * Parses the CSS outline-style property
     * Warning: it is parsed as standard border-style property
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_outline_style($key, $value, $important = false)
    {
        //overwrite the property to border-style
        $key = 'border-style';
        if (isset(CSSPropsLexer::$borderStyles)) {
            if ($important) {
                $this->_importantProps[$key] = $value;
            } else {
                $this->_parsedCSSProps[$key] = $value;
            }
        }
    }
    
    /**
     * Parses the CSS outline-width property
     * Warning: it is parsed as standard border-width property
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_outline_width($key, $value, $important = false)
    {
        //overwrite the property to border-width
        $key = 'border-width';
        //normalize the width property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS break-after property
     * This property is mainly used for multi-column layouts
     * The parsed values are auto, (avoid), always,left or right
     * The corresponding ODF attributes are
     *  paragraphs-> fo:break-after (page or column)
     *  tables-> fo:break-after (page or column)
     *  rows-> fo:break-after (page or column)
     *  frames-> not directly supported
     * If the value is 'avoid' use the Keep with Next property instead
     *  paragraphs-> fo:keep-with-next (always)
     *  tables-> fo:keep-with-next (always)
     *  rows-> fo:keep-together (always)
     *  frames-> not directly supported
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_break_after($key, $value, $important = false)
    {
        if ($value == 'always'
            || $value == 'avoid'
            || $value == 'page'
            || $value == 'column'
            || $value == 'left' 
            || $value == 'right') {
            if ($important) {
                $this->_importantProps[$key] = $value;
            } else {
                $this->_parsedCSSProps[$key] = $value;
            }
        }
    }
    
    /**
     * Parses the CSS break-before property
     * This property is mainly used for multi-column layouts
     * The parsed values are auto, always,page, column, left or right
     * The avoid option has no ODF equivalent use instead
     * break-after: avoid with the previous paragraph or element
     * The corresponding ODF attributes are
     *  paragraphs-> fo:break-before (page or column)
     *  tables-> fo:break-before (page or column)
     *  rows-> fo:break-before (page or column)
     *  frames-> not directly supported
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_break_before($key, $value, $important = false)
    {
        if ($value == 'always'
            || $value == 'page'
            || $value == 'column'
            || $value == 'left' 
            || $value == 'right') {
            if ($important) {
                $this->_importantProps[$key] = $value;
            } else {
                $this->_parsedCSSProps[$key] = $value;
            }
        }
    }
    
    /**
     * Parses the CSS break-inside property
     * This property is mainly used for multi-column layouts
     * The parsed values are auto, avoid, avoid-page or avoid-column
     * The corresponding ODF attributes are
     *  paragraphs-> fo:keep-together (always)
     *  tables-> style:may-break-between-rows (boolean)
     *  rows-> fo:keep-together (always)
     *  frames->do not apply
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_break_inside($key, $value, $important = false)
    {
        if ($value == 'avoid'
            || $value ==  'avoid-page'
            || $value == 'avoid-column') {
            if ($important) {
                $this->_importantProps[$key] = $value;
            } else {
                $this->_parsedCSSProps[$key] = $value;
            }
        }
    }
    
    /**
     * Parses the CSS column-count property
     * This value is an integer (number of columns)
     * The corresponding ODF attribute is:
     *  fo:column-count
     * that belongs to the element style:columns
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_column_count($key, $value, $important = false)
    {
        if (is_numeric($value)) {
            if ($important) {
                $this->_importantProps[$key] = $value;
            } else {
                $this->_parsedCSSProps[$key] = $value;
            }
        }
    }
    
    /**
     * Parses the CSS column-gap property
     * This value is given as a length
     * The corresponding ODF attributes are:
     *  fo:start-indent
     *  fo:end-indent
     * the gap should be splitted between both elements
     * that belongs to the element style:column
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_column_gap($key, $value, $important = false)
    {
        //normalize the width property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS column-rule property
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_column_rule($key, $value, $important = false)
    {
        //Extract the independent border properties
        $borderProps = $this->_CSSPropsLexer->tokenizeBorderProp($value);
        if (isset($borderProps['color'])) {
            $this->_parse_column_rule_color($key . '-color', 
                                           $borderProps['color'],
                                           $important);
        }
        if (isset($borderProps['style'])) {
            $this->_parse_column_rule_style($key . '-style', 
                                           $borderProps['style'],
                                           $important);
        }
        if (isset($borderProps['width'])) {
            $this->_parse_column_rule_width($key . '-width', 
                                           $borderProps['width'],
                                           $important);
        }
    }
    
    /**
     * Parses the CSS column-rule-color property
     * This corresponds to the ODF attribute:
     *  style:color
     * of the style:column-sep element
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_column_rule_color($key, $value, $important = false)
    {
        $colorArray = $this->_process_color($key, $value);
        if($colorArray['color'] !== NULL) {
            if ($important) {
                $this->_importantProps[$key] = $colorArray['color'];
            } else {
                $this->_parsedCSSProps[$key] = $colorArray['color'];
            }
        }
    }
    
    /**
     * Parses the CSS column-rule-style property
     * Only the dashed, dotted and solid values are parsed
     * This corresponds to the ODF attribute:
     *  style:style
     * of the style:column-sep element
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_column_rule_style($key, $value, $important = false)
    {
        if($value == 'dashed' || $value == 'dotted') {
            //these values have corresponding ODF values
        } else {
            $value = 'solid';
        }
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS column-rule-width property
     * This corresponds to the ODF attribute:
     *  style:width
     * of the style:column-sep element
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_column_rule_width($key, $value, $important = false)
    {
        //normalize the width property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS column-width property
     * This corresponds to the ODF attribute:
     *  style:rel-width
     * of the style:column element
     * 
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_column_width($key, $value, $important = false)
    {
        //normalize the width property
        if ($important) {
            $this->_importantProps[$key] = $this->_normalizedSizeProp($value);
        } else {
            $this->_parsedCSSProps[$key] = $this->_normalizedSizeProp($value);
        }
    }
    
    /**
     * Parses the CSS columns property
     * @see _parse_column_count
     * @see _parse_column_width
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_columns($key, $value, $important = false)
    {
        if (\strpos($value, 'auto') !== false) {
            $value = \str_replace('auto', '', $value);
            $this->_parse_column_count($key . '-count', 
                                       \trim($value), 
                                       $important);
        } else {
            $regex = '/([0-9]+\.?[0-9]*)\s*(px|em|rem|ex|%|in|cm|mm|pt|pc)?/i';
            \preg_match($regex, $prop, $matches);
            if (isset($matches[0])) {
                $this->_parse_column_width($key . '-count',
                                           \trim($matches[0]),
                                           $important);
                $value = \str_replace($matches[0], '', $value);
                $this->_parse_column_count($key . '-count', 
                                           \trim($value), 
                                           $important);
            }
        }
    }
    
    /**
     * Parses the CSS widows property
     * This property stablishes the minimum number of lines that should contain
     * the first paragraph of a page when it is the continuation of a paragraph 
     * of the previous page
     * The corresponding ODF attributes are
     *  paragraphs-> fo:widows (positive integer value)
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_widows($key, $value)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS orphans property
     * This property stablishes the minimum number of lines that should be left
     * in the last paragraph of a page
     * The corresponding ODF attributes are
     *  paragraphs-> fo:orphans (positive integer value)
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_orphans($key, $value, $important = false)
    {
        if ($important) {
            $this->_importantProps[$key] = $value;
        } else {
            $this->_parsedCSSProps[$key] = $value;
        }
    }
    
    /**
     * Parses the CSS page-break-after property
     * The parsed values are auto, (avoid), always,left or right
     * The corresponding ODF attributes are
     *  paragraphs-> fo:break-after (page)
     *  tables-> fo:break-after (page)
     *  rows-> fo:break-after (page)
     *  frames-> not directly supported
     * If the value is 'avoid' use the Keep with Next property instead
     *  paragraphs-> fo:keep-with-next (always)
     *  tables-> fo:keep-with-next (always)
     *  rows-> fo:keep-together (always)
     *  frames-> not directly supported
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_page_break_after($key, $value, $important = false)
    {
        if ($value == 'always'
            || $value == 'avoid'
            || $value == 'left' 
            || $value == 'right') {
            if ($important) {
                $this->_importantProps[$key] = 'page';
            } else {
                $this->_parsedCSSProps[$key] = 'page';
            }
        }
    }
    
    /**
     * Parses the CSS page-break-before property
     * The parsed values are auto, always,left or right
     * The avoid option has no ODF equivalent use instead
     * page-break-after: avoid with the previous paragraph or element
     * The corresponding ODF attributes are
     *  paragraphs-> fo:break-before (page)
     *  tables-> fo:break-before (page)
     *  rows-> fo:break-before (page)
     *  frames-> not directly supported
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_page_break_before($key, $value, $important = false)
    {
        if ($value == 'always' 
            || $value == 'left' 
            || $value == 'right') {
            if ($important) {
                $this->_importantProps[$key] = 'page';
            } else {
                $this->_parsedCSSProps[$key] = 'page';
            }
        }
    }
    
    /**
     * Parses the CSS page-break-inside property
     * The parsed values are auto or avoid
     * The corresponding ODF attributes are
     *  paragraphs-> fo:keep-together (always)
     *  tables-> style:may-break-between-rows (boolean)
     *  rows-> fo:keep-together (always)
     *  frames are always within a page
     *
     * @param string $key
     * @param string $value
     * @param boolean $important
     * @return void
     * @access private
     */
    private function _parse_page_break_inside($key, $value, $important = false)
    {
        if ($value == 'avoid') {
            if ($important) {
                $this->_importantProps[$key] = $value;
            } else {
                $this->_parsedCSSProps[$key] = $value;
            }
        }
    }
    
    /**
     * Process the CSS color property
     * 
     * The color may be in diffrent formats:
     * 1. Hexadecimal: #FF0000
     * 2. RGB: rgb(255, 0, 0)
     * 3. RGBA: rgba(255,0,0,0.3)
     * 4. CMYK: device-cmyk(0.5, 0.1, 0.0, 0.2)
     * 5. HSL: hsl(120,100%,50%);
     * 6. HSLA: hsla(120,100%,50%,0.3)
     * 7. "transparent"
     * 8. string: red, ...
     * Other possible values are: inherited or initial
     * 
     * @param string $key
     * @param string $value
     * @return void
     * @access private
     */
    private function _process_color($key, $value)
    {
        $processedColor = NULL; 
        $processedOpacity = NULL; 
        //detect the color type
        //remove all whitespace or carraige returns
        $value = \preg_replace('/\s+/', '', $value);
        //first check if is an hexadecimal color
        if($value[0] == '#'){
            //check if the color is of the form 'FFF' or 'FFFFFF'
            if (\strlen($value) == 7) {
                $processedColor = $value;
            } else if (\strlen($value) == 4) {
                $color = '#' . $value[1] . $value[1] . $value[2] . $value[2];
                $color .= $value[3] .$value[3];
                $processedColor = $color;
            }
        }
        //now check if the format is rgb, regba, hsl, hsla or cmyk
        if (\substr($value, 0, 4) == 'rgb(') {
            $colorData = \substr($value, 4, -1);
            $colorArray = explode(',', $colorData);
            if (count($colorArray) == 3) {
               $color = $this->_rgb2hex($colorArray);
               $processedColor = $color;
            }
        } else if (\substr($value, 0, 5) == 'rgba(') {
            $colorData = \substr($value, 5, -1);
            $colorArray = explode(',', $colorData);
            if (count($colorArray) == 4) {
               $opacity = \array_pop($colorArray);
               $color = $this->_rgb2hex($colorArray);
               $processedColor = $color;
               $processedOpacity = $opacity;
            }
        } else if (\substr($value, 0, 4) == 'hsl(') {
            $colorData = \substr($value, 4, -1);
            $colorArray = explode(',', $colorData);
            if (count($colorArray) == 3) {
               $color = $this->_hsl2hex($colorArray);
               $processedColor = $color;
            }
        } else if (\substr($value, 0, 4) == 'hsla(') {
            $colorData = \substr($value, 5, -1);
            $colorArray = explode(',', $colorData);
            if (count($colorArray) == 4) {
               $opacity = \array_pop($colorArray);
               $color = $this->_rgb2hex($colorArray);
               $processedColor = $color;
               $processedOpacity = $opacity;
            }
        } else if (\substr($value, 0, 11) == 'device-cmyk') {
            $colorData = \substr($value, 11, -1);
            $colorArray = explode(',', $colorData);
            if (count($colorArray) == 4) {
               $color = $this->_cmyk2hex($colorArray);
               $processedColor = $color;
            }
        } else if ($value == 'transparent') {
            $processedColor = $value;
        } else if (isset(css\Resources::$CSScolors[\strtolower($value)])) { 
            $color = css\Resources::$CSScolors[\strtolower($value)];
            $processedColor = $color;
        }
        
        return array('color' => $processedColor,'opacity' => $processedOpacity);
    }
    
    /**
     * Process shorthand properties for margin and padding
     *
     * 
     * @param string $value
     * @return array
     * @access private
     */
    private function _process_shorthand($value)
    {
        $regex = '/([0-9\-]+\.?[0-9]*)\s*(px|em|rem|ex|%|in|cm|mm|pt|pc)?/i';
        \preg_match_all($regex, $value, $matches);
        $numResults = count($matches[0]);
        if ($numResults == 0) {
            return NULL;
        } else if ($numResults == 1) {
            return array($matches[0][0],  //bottom
                         $matches[0][0],  //left
                         $matches[0][0],  //right
                         $matches[0][0]); //top
        } else if ($numResults == 2) {
            return array($matches[0][0],  //bottom
                         $matches[0][1],  //left
                         $matches[0][1],  //right
                         $matches[0][0]); //top
        } else if ($numResults == 3) {
            return array($matches[0][2],  //bottom
                         $matches[0][1],  //left
                         $matches[0][1],  //right
                         $matches[0][0]); //top
        } else if ($numResults == 4) {
            return array($matches[0][2],  //bottom
                         $matches[0][3],  //left
                         $matches[0][1],  //right
                         $matches[0][0]); //top
        }
    }
    
    /**
     * Converts rgb colors to hexadecimal
     * red: 0-255
     * green: 0-255
     * blue: 0-255
     * @param array $color
     * @return string
     * @access private
     */
    private function _rgb2hex($color) 
    {
        for ($j = 0; $j < 3; $j++) {
            //we check if the rgb is given as a percentage
            if(\strpos($color[$j], '%') !== false){
                $color[$j] = str_replace('%', '', $color[$j]);
                $color[$j] = floor( $color[$j] = $color[$j] * 2.55 );
            }
        }
        $hexColor = "#";
        $hexColor.= str_pad(dechex($color[0]), 2, "0", STR_PAD_LEFT);
        $hexColor.= str_pad(dechex($color[1]), 2, "0", STR_PAD_LEFT);
        $hexColor.= str_pad(dechex($color[2]), 2, "0", STR_PAD_LEFT);

        return $hexColor;
    }
    
    /**
     * Converts hsl colors to hexadecimal
     * @param array $color
     * @return string
     * @access private
     */
    private function _hsl2hex($color)
    {
        $hue = $color[0];
        $saturation = $color[1];
        $lightness = $color[2];
        
	$c = (1 - abs(2*$lightness - 1 ))*$saturation;
	$x = $c * (1 - abs(fmod(($hue/60), 2) - 1 ));
	$m = $lightness - ( $c / 2 );

	switch ($hue) {
            case ($hue < 60):
                $red = $c;
		$green = $x;
		$blue = 0;
                break;
            case ($hue < 120):
                $red = $x;
		$green = $c;
		$blue = 0;
                break;
            case ($hue < 180):
                $red = 0;
		$green = $c;
		$blue = $x;
                break;
            case ($hue < 240):
                $red = 0;
		$green = $x;
		$blue = $c;
                break;
            case ($hue < 300):
                $red = $x;
		$green = 0;
		$blue = $c;
                break;
            default:
                $red = $c;
		$green = 0;
		$blue = $x;    
        }

	$rgb[0] = floor(($red + $m) * 255);
	$rgb[1] = floor(($green + $m) * 255);
	$rgb[2] = floor(($blue + $m) * 255);

        
        $hexColor= $this->_rgb2hex($rgb);
        return $hexColor;
    }
    
    /**
     * Converts cmyk colors to hexadecimal
     * @param array $color
     * @return string
     * @access private
     */
    public function _cmyk2hex($color)
    {  
        $c = $color[0]/100;
        $m = $color[1]/100;
        $y = $color[2]/100;
        $k = $color[3]/100;

        $nc = ($c * (1-$k) + $k);
        $nm = ($m * (1-$k) + $k);
        $ny = ($y * (1-$k) + $k);

        $rgb[0] = floor((1-$nc) * 255);
        $rgb[1] = floor((1-$nm) * 255);
        $rgb[2] = floor((1-$ny) * 255);

        $hexColor = $this->_rgb2hex($red, $green, $blue);

        return $hexColor;
    }

}