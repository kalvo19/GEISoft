<?php

/**
 * SelectorLexer
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
 * This lexer will let us to extract the relevant tokens
 * from our input string: a CSS selector
 *
 * @package    Docxpresso
 * @subpackage Parser
 */

class SelectorLexer
{
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
     * Breaks the selector into tokens of six different types:
     *  1. literals: that correspond to names of classes and ids
     *  2. numbers
     *  3. metachars: that correspond to the special characters used within CSS3
     *  selectors like ' ', '#', '.', '>', etcetera
     *  4. HTML tags: like 'p', 'table', etcetera
     *  5. pseudoclasses: like 'link', 'hover', etcetera
     *  6. pseudoelements: like 'first-letter', 'first-line', etcetera
     * 
     * @param string $selector
     * @param boolean $valXPath if true will check for non-aceptable
     * XPath pesudoclass and pseudoelements
     * @return array|NULL an array with all tokens together with their types or 
     * NULL if $validXPathSelector has been set to true and the CSS selector has
     * XPath equivalent
     * @access public
     */
    public function tokenize($selector, $valXPath = false)
    {
        //trim the input string
        $selector = \trim($selector);
        //define the array that will hold the resulting tokens
        $tokens = array();
        //calculate the length of the string to loop over its chars
        $length = strlen($selector);
        //start to run over the chars contained in the selector
        $tok = ''; //the variable that holds the token
        $whitespace = false; //helps us to collapse white space
        $counter = 0;
        for ($i = 0; $i < $length; $i++) {
            if (isset(css\Resources::$metaChars[$selector[$i]])) {
                if ($tok != ''){
                    //$tok is a literal|tag|pseudoclass|pseudoelement 
                    $tokens[$counter]['token'] = $tok;
                    //determine its type
                    if(isset($tokens[$counter - 1]['token'])){
                        $type = $this->_detectTokenType($tok, 
                                                        $valXPath, 
                                                        $tokens[$counter - 1]['token']);
                    } else {
                        $type = $this->_detectTokenType($tok, $valXPath);
                    }
                    if ($type === NULL) {
                        return NULL;
                    } else {
                        $tokens[$counter]['type'] = $type;
                    }
                    $counter++;
                }
                if ($selector[$i] != ' '
                    && $selector[$i] != '"'
                    && $selector[$i] != '\'') {
                    //$tok is a metachar although we are ignoring "s and 's
                    $tokens[$counter]['token'] = $selector[$i];
                    $tokens[$counter]['type'] = 'metachar';
                    $whitespace = false;
                    $counter++;
                } else if ($selector[$i] == ' ') {
                    if(!$whitespace 
                       && $this->_meaningfulWhitespace($i, $selector)
                    ){
                        $tokens[$counter]['token'] = 'whitespace';
                        $tokens[$counter]['type'] = 'metachar';
                        $whitespace = true;
                        $counter++;
                    }
                }
                $tok = '';
            } else {
                $tok .= $selector[$i];
                $whitespace = false;       
            }
        }
        //there may be a trailing token
        if ($tok != '') {
            $tokens[$counter]['token'] = $tok;
            //determine its type
            if(isset($tokens[$counter - 1]['token'])){
                $type = $this->_detectTokenType($tok, 
                                                $valXPath, 
                                                $tokens[$counter - 1]['token']);
            } else {
                $type = $this->_detectTokenType($tok, $valXPath);
            }
            if ($type === NULL) {
                return NULL;
            } else {
                $tokens[$counter]['type'] = $type;
            }
        }
        //return the resulting array
        return $tokens;
    }
    
    /**
     * Classify the tokens in 4 different types:
     *  1. literals: that correspond to names of classes and ids
     *  2. numbers
     *  3. HTML tags: like 'p', 'table', etcetera
     *  4. pseudoclasses: like 'link', 'hover', etcetera
     *  5. pseudoelements: like 'first-letter', 'first-line', etcetera
     * 
     *
     * @param string $tok the token that we wish to classify
     * @param boolean $valXPath if true
     * @param string $previous
     * @return string possible values are 'literal', 'tag', 'pseudoclass' and
     * 'pseudoelement'
     * @access private
     */
    private function _detectTokenType($tok, $valXPath, $previous = '')
    {
        //everything that comes after a '.' or a '#' is a literal
        //although a bad practice it is not forbidden to define a .table class
        if (isset($previous) && ($previous == '.' || $previous == '#')) {
            return 'literal';
        }
        //otherwise
        if (isset(html\Resources::$tags[$tok])) {
            return 'tag';
        } else if (isset(css\Resources::$pseudoclasses[$tok])) {
            if (css\Resources::$pseudoclasses[$tok]) {
                return 'pseudoclass';
            } else {
                if (!$valXPath) {
                    return 'pseudoclass';
                } else {
                    return NULL;
                }
            }
        } else if (isset(css\Resources::$pseudoelements[$tok])) {
            if (!$valXPath) {
                return 'pseudoelement';
            } else {
                return NULL;
            }
        } else {
            if (\is_numeric($tok)) {
                return 'number';
            } else {
                return 'literal';
            }
        }
    }
    
    /**
     * Checks if the whitespace within the selector is meaningful
     *
     * @param integer $k the current char in the $selector
     * @param string $selector the selector we are parsing
     * @return boolean
     * @access private
     */
    private function _meaningfulWhitespace($k, $selector)
    {
        //whitespace should not be relevant after these operators
        $bef = array(
            '[' => true,
            '(' => true,
            ']' => true,
            ')' => true,
            '>' => true,
            //':' => true, It can be relevant because it may be equivalent to *:
            '~' => true,
            '!' => true,
            '+' => true,
            '=' => true,
            '"' => true,
            '\'' => true,
            '^' => true,
            '$' => true,
            '|' => true,
        );
        //whitespace should not be relevant before these operators
        $aft = array(
            ' ' => true,
            '[' => true,
            '(' => true,
            '.' => false,
            '#' => false,
            '>' => true,
            ':' => true,
            '~' => true,
            '!' => true,
            '+' => true,
            '=' => true,
            '"' => true,
            '\'' => true,
            '^' => true,
            '$' => true,
            '|' => true,
        );
        if ( isset($selector[$k - 1]) && isset($aft[$selector[$k - 1]])) {
            return false;
        } else if ( isset($selector[$k + 1]) && isset($bef[$selector[$k + 1]])){
            return false;     
        } else {
            return true;
        }  
    }

}