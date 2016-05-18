<?php

/**
 * CSSPropsLexer
 *
 * @copyright  Copyright (c) 2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Parser;

Use Docxpresso\Parser\HTML as html;
Use Docxpresso\Parser\CSS as css;

/**
 * This lexer will let us to extract the relevant tokens
 * from a compound CSS property
 *
 * @package    Docxpresso
 * @subpackage Parser
 */

class CSSPropsLexer
{
    
    /**
     * CSS background-clip and origin property
     * 
     * @access public
     * @static
     * @var array
     */
    public static $backgroundClipOrigin = array(
        'padding-box',
        'border-box',
        'content-box',
        'initial',
        'inherit',
    );
    
    /**
     * CSS position
     * 
     * @access public
     * @static
     * @var array
     */
    public static $backgroundPosition = array(
        'bottom',
        'center',
        'left',
        'top',
        'right',
    );
    
    /**
     * CSS background-repeat property
     * 
     * @access public
     * @static
     * @var array
     */
    public static $backgroundRepeat = array(
        'repeat',
        'repeat-x',
        'repeat-y',
        'no-repeat',
    );
    
    /**
     * CSS border styles
     * 
     * @access public
     * @static
     * @var array
     */
    public static $borderStyles = array(
        'none',
        'hidden',
        'dotted',
        'dashed',
        'solid',
        'double',
        'groove',
        'ridge',
        'inset',
        'outset',
    );
    
    /**
     * CSS list style types
     * 
     * @access public
     * @static
     * @var array
     */
    public static $listStyles = array(
        'disc',
        'armenian',
        'circle',
        'cjk-ideographic',
        'decimal',
        'decimal-leading-zero',
        'georgian',
        'hebrew',
        'hiragana',
        'hiragana-iroha',
        'katakana',
        'katakana-iroha',
        'lower-alpha',
        'lower-greek',
        'lower-latin',
        'lower-roman',
        'none',
        'square',
        'upper-alpha',
        'upper-latin',
        'upper-roman',
    );
         
    /**
     * Construct
     *
     * @access public
     */
    public function __construct()
    {
        //Do nothing    
    }
    
    /**
     * Extracts the value of the last ocurrence of a CSS property or the one
     * that is tagged as !important if the property is given as a string,
     * otherwise the property is read from the corresponding array entry.
     *
     * @param string $prop the property we wish to extract
     * @param string $CSS the chain of CSS properties
     * @return string
     * @access public
     * @static
     */
    public static function extractSingleProperty($prop, $CSS)
    {
        $value = '';
        if (\is_string($CSS)) {
            //first check if there is an important property
            $regex = '/([^-]|^)' . $prop . '\s*:\s*([^;]*?)\s*!important/i';
            \preg_match_all($regex, $CSS, $matches);
            $results = count($matches);
            $value = \array_pop($matches[2]);
            $value = \trim($value);
            if (empty($value)) {
                //there is no !important value
                $regex = '/([^-]|^)' . $prop . '\s*:\s*([^;]*?)\s*(;|$)/i';
                \preg_match_all($regex, $CSS, $matches);
                $value = \array_pop($matches[2]);
                $value = \trim($value);
            }
        } else if (\is_array($CSS)) {
            if (isset($CSS[$prop]) && \is_string($CSS[$prop])) {
                $value = $CSS[$prop];
            } else if (isset($CSS[$prop]) && \is_array($CSS[$prop])) {
                $value = \implode('', $CSS[$prop]);
            }
        }
        
        return $value;
    }
    
    /**
     * separates value from units and returns an array
     *
     * @param string $dimension the chain with the CSS units
     * @return array
     * @access public
     * @static
     */
    public static function tokenizeUnits($dimension)
    {
        $regex = '/([0-9\.\-]+)\s*(px|em|rem|ex|%|in|cm|mm|pt|pc)*/i';
        \preg_match_all($regex, $dimension, $matches);
        $data[0] = $matches[1][0];
        $data[1] = $matches[2][0];
        return $data;
    }
    
    /**
     * Classify the tokens in 4 different types:
     *  color, 
     *  position, 
     *  (size, ignored)
     *  repeat, 
     *  (origin, ignored)
     *  (clip, ignored)
     *  (attachment, ignored)
     *  image.
     * 
     * @param string $value the property that we want to parse
     * @return array with keys: 'color', 'position','repeat', 'image'
     * @access public
     */
    public function tokenizeBackgroundProp($value)
    {       
        $tokens = array();        
        //search for the background image url
        $regex = '/url\s*\(.*?\)/i';
        \preg_match($regex, $value, $matches);
        if(isset($matches[0])){
            $tok = \preg_replace('/\s+/', '', $matches[0]);
            $tokens['image'] = $tok;
        }
        //We should now remove the url part because the path may contain
        //keywords that we are now going to search like left, top, repeat, ...
        $value = \preg_replace($regex, '', $value);
        //search for hexadecimal color attribute
        $regex = '/#\s*[0-9a-fA-F]+/i';
        \preg_match($regex, $value, $matches);
        if(isset($matches[0])){
            $tok = \preg_replace('/\s+/', '', $matches[0]);
            $tokens['color'] = $tok;
        }
        if(!isset($tokens['color'])){
            //search for other format color attribute
            $regex = '/(rgb|rgba|hsl|hsla|device-cmyk)\s*\([0-9.,% ]+\)/i';
            \preg_match($regex, $value, $matches);
            if(isset($matches[0])){
                $tok = \preg_replace('/\s+/', '', $matches[0]);
                $tokens['color'] = $tok;
            }
        }
        if(!isset($tokens['color'])){
            //search for colors by name
            //clean the strings that are not colors
            $cleanedValue = \str_replace(self::$backgroundPosition, 
                                         '', $value);
            $cleanedValue = \str_replace(self::$backgroundRepeat, 
                                         '', $cleanedValue);
            $cleanedValue = \str_replace(self::$backgroundClipOrigin, 
                                         '', $cleanedValue);
            $cleanedValue = \str_replace(array('url','rem'), '', $cleanedValue);
            $regex = '/[a-zA-Z]{3,20}/i';
            \preg_match($regex, $cleanedValue, $matches);
            if(isset($matches[0]) 
               && isset(css\Resources::$CSScolors[$matches[0]])){
                $tokens['color'] = css\Resources::$CSScolors[$matches[0]];
            }
        }
        //search for the repeat property
        $regex = '/(repeat|repeat-x|repeat-y|no-repeat)/i';
        \preg_match($regex, $value, $matches);
        if(isset($matches[0])){
            $tokens['repeat'] = $matches[0];
        }
        //search for the position property, format: top, left center, ...
        $regex = '/left|right|center|top|bottom/i';
        \preg_match_all($regex, $value, $matches);
        $numberOfMatches = count($matches[0]);
        if($numberOfMatches == 1){
           if($matches[0][0] == 'left' 
              || $matches[0][0] == 'right' 
              || $matches[0][0] == 'center'){
               $tokens['position'] = $matches[0][0] . ' center';
           } else if ($matches[0][0] == 'top' 
              || $matches[0][0] == 'bottom'){
               $tokens['position'] = 'center ' . $matches[0][0];
           }
        } else if($numberOfMatches == 2){
            $tokens['position'] = $matches[0][0] . ' ' . $matches[0][1];
        } 
        if(!isset($tokens['position'])){
            //search for the position property in number format
            $regex = '/[0-9\-]+\.?([0-9]+)?\s*(px|em|rem|ex|%|in|cm|mm|pt|pc)+/i';
            \preg_match_all($regex, $value, $matches);
            $numberOfMatches = count($matches[0]);
            $position = array();
            if($numberOfMatches > 0){
                $counter = 0;
                foreach($matches[0] as $pos){
                    $position[$counter] = \preg_replace('/\s+/', '', $pos);
                    $counter++;
                }
            }
            if (count($position) == 1) {
                $tokens['position'] = $position[0] . ' 50%';
            } else if (count($position) == 1) {
                $tokens['position'] = $position[0] . ' ' . $position[1];
            }
        }
        //return array
        return $tokens;  
    }
    
    /**
     * Classify the tokens in 3 different types:
     *  width, 
     *  style, 
     *  color 
     *
     * @param string $value the property that we want to parse
     * @return array with keys: width, style and color
     * @access public
     */
    public function tokenizeBorderProp($value)
    {
        $tokens = array();
        //search for the width attribute
        $regex = '/([0-9]+\.?[0-9]*)\s*(px|em|rem|ex|%|in|cm|mm|pt|pc)?/i';   
        \preg_match($regex, $value, $matches);
        if(isset($matches[0])){
            $tok = \preg_replace('/\s+/', '', $matches[0]);
            $tokens['width'] = $tok;
        }
        //the width can also be given as thin, medium or thick
        if(!isset($tokens['width'])){
            $regex = '/(thin|medium|thick)/i';
            \preg_match($regex, $value, $matches);
            if(isset($matches[0])){
                if ($matches[0] == 'thin') {
                    $tokens['width'] = '1px';
                } else if ($matches[0] == 'medium') {
                    $tokens['width'] = '3px';
                } else if ($matches[0] == 'thick') {
                    $tokens['width'] = '5px';
                }
            }
        }
        //search for the style attribute
        $regex = '/(none|hidden|dotted|dashed|solid|double|groove|';
        $regex .= 'ridge|inset|outset)/i';
        \preg_match($regex, $value, $matches);
        if(isset($matches[0])){
            $tokens['style'] = $matches[0];
        }
        //search for hexadecimal color attribute
        $regex = '/#\s*[0-9a-fA-F]+/i';
        \preg_match($regex, $value, $matches);
        if(isset($matches[0])){
            $tok = \preg_replace('/\s+/', '', $matches[0]);
            $tokens['color'] = $tok;
        }
        if(!isset($tokens['color'])){
            //search for other format color attribute
            $regex = '/(rgb|rgba|hsl|hsla|device-cmyk)\s*\([0-9.,% ]+\)/i';
            \preg_match($regex, $value, $matches);
            if(isset($matches[0])){
                $tok = \preg_replace('/\s+/', '', $matches[0]);
                $tokens['color'] = $tok;
            }
        }
        if(!isset($tokens['color'])){
            //search for colors by name
            //clean the border styles and other sources of strings of length 3
            $cleanedValue = \str_replace(self::$borderStyles, '', $value);
            $cleanedValue = \str_replace('rem', '', $cleanedValue);
            $regex = '/[a-zA-Z]{3,20}/i';
            \preg_match($regex, $cleanedValue, $matches);
            if(isset($matches[0]) 
               && isset(css\Resources::$CSScolors[$matches[0]])){
                $tokens['color'] = css\Resources::$CSScolors[$matches[0]];
            }
        }
        
        //return the array
        return $tokens;
    } 
    
    /**
     * Classify the tokens in different types:
     *  style : normal, italique, oblique, initial and inherit , 
     *  variant: normal, small-caps, initial and inherit 
     *  weight: normal, bold, bolder, lighter, integer initial and inherit 
     *  size,
     *  line-height,
     *  family
     * 
     * @param string $value the property that we want to parse
     * @return array with keys: 'color', 'position','repeat', 'image'
     * @access public
     */
    public function tokenizeFontProp($value)
    {       
        $tokens = array();        
                
        //check if there is a line-height property
        $detectSlash = \strpos($value,'/');
        if(!empty($detectSlash)){
            $tmp = \explode('/', $value);
            $regex = '/([0-9]+\.?[0-9]*)\s*(px|em|rem|ex|%|in|cm|mm|pt|pc)?/i';   
            \preg_match($regex, $tmp[1], $matches);
            if(isset($matches[0])){
                $lh = $matches[0];
                $tokens['line-height'] = $lh;
            }
            //remove from $value the line height property and the /
            $value = \str_replace($lh, '', $value);
            $value = \str_replace('/', '', $value);
        } 
        
        //check if the font size is a predefined one
        //protect 'small' because it may come from the font-variant prop
        $regex = '/xx-small|x-small|small |small\/|medium|large|';
        $regex .= 'x-large|xx-large|smaller|larger/';
        \preg_match($regex, $value, $matches);
        if(isset($matches[0])){
            $tok = $matches[0];
            $tokens['size'] = $tok;
        }
        if (!isset($tokens['size'])) {
            //the size has not yet been detected so it is probably given
            //as a length
            $regex = '/([0-9]+\.?[0-9]*)\s*(px|em|rem|ex|%|in|cm|mm|pt|pc)?/i';   
            \preg_match_all($regex, $value, $matches);
            //we used preg_match_all because the font weight can also be given
            //as an integer
            if(isset($matches[0])){
                if (\count($matches[0]) > 1 && isset($matches[0][1])) {
                    $tok = $matches[0][1];
                    $tokens['size'] = $tok;
                } else if (isset($matches[0][0])) {
                    $tok = $matches[0][0];
                    $tokens['size'] = $tok;
                }
            }
        }
        if (!isset($tokens['size'])) {
            //the font size property is compulsory so there may be an error
            //in the CSS prop
            return;
        }
        //break the string by the size token
        $tmp = \explode($tok, $value);
        $family = \array_pop($tmp);
        $tokens['family'] = \trim($family);
        $prop = \str_replace($tok, '', $value);
        $prop = \trim(\str_replace($family, '', $prop));
        
        if(!empty($prop)){
            //parse the remaining properties if any
            $regex = '/[a-zA-Z0-9\-]+/i';
            \preg_match_all($regex, $prop, $matches);
            if (isset($matches[0])) {
                foreach ($matches[0] as $key => $value) {
                    if ($value == 'italic' || $value == 'oblique' ) {
                        $tokens['style'] = $value;
                    } else if ($value == 'small-caps') {
                        $tokens['variant'] = $value;
                    } else if (\is_numeric($value)
                               || $value == 'bold'
                               || $value == 'bolder'
                               || $value == 'lighter') {
                        $tokens['weight'] = $value;
                    } else if ($value == 'normal'
                               || $value == 'initial'
                               || $value == 'inherit') {
                        if ($key == 0){
                            $tokens['style'] = $value;
                        } else if ($key == 1) {
                            $tokens['variant'] = $value;
                        } else if ($key == 2) {
                            $tokens['weight'] = $value;
                        }
                    }
                }
            }
        }    
        //return array
        return $tokens;  
    }
    
    /**
     * Classify the tokens in 3 different types:
     *  type, 
     *  position
     *  image.
     * 
     * @param string $value the property that we want to parse
     * @return array with keys: 'color', 'position','repeat', 'image'
     * @access public
     */
    public function tokenizeListProp($value)
    {       
        $tokens = array(); 
        //search for the style property, format: 
        $listStyles = implode('|', self::$listStyles);
        $regex = '/' . $listStyles . '/i';
        \preg_match($regex, $value, $matches);
        if(isset($matches[0])){
            $tok = \preg_replace('/\s+/', '', $matches[0]);
            $tokens['style'] = $tok;
        }
        //search for the position property, format: inside or outside
        $regex = '/inside|outside/i';
        \preg_match($regex, $value, $matches);
        if(isset($matches[0])){
            $tok = \preg_replace('/\s+/', '', $matches[0]);
            $tokens['position'] = $tok;
        }
        //search for the background image url
        $regex = '/url\s*\(.*?\)/i';
        \preg_match($regex, $value, $matches);
        if(isset($matches[0])){
            $tok = \preg_replace('/\s+/', '', $matches[0]);
            $tokens['image'] = $tok;
        }
        //return array
        return $tokens;  
    }

}