<?php

/**
 * Theme manager
 *
 * @copyright  Copyright (c) 2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Themes;

use Docxpresso;
use Docxpresso\Commons as commons;
use Docxpresso\Core\Elements as elements;
use Docxpresso\Parser as parser;

/**
 * This class is the responsible of replacing template variables
 *
 * @package    Docxpresso
 * @subpackage Themes
 */

class ThemeManager
{
    /**
     * This array stores the class names used in the theme
     * 
     * @var array
     * @access public
     * @static
     */
    public static $CSSClasses;   
    /**
     * This array stores the preparsed CSS theme properties
     * 
     * @var array
     * @access public
     * @static
     */
    public static $CSSProps;
    /**
     * The CSS associated with the selected theme
     * 
     * @var string
     * @access private
     */
    private $_CSS;
    
    /**
     * Construct
     *
     * @param CreateDocument $doc
     * @param string $theme path to the file that stores the theme CSS props
     * @access public
     */
    public function __construct($doc, $theme)
    {
        if (empty($theme)) {
            $theme = dirname(__FILE__) . '/../../themes/default.css';
        }
        $this->_CSS = \file_get_contents($theme);
        if (empty($this->_CSS)) {
            $message = 'There was a problem loading the theme.';
            commons\DOCXPRESSOLogger::logger($message, 'critical');
        }
        Docxpresso\CreateDocument::$theme = $theme;
        //initialize the CSS props array
        self::$CSSProps = array();
        self::$CSSClasses = array();
        $this->_parseTheme();
    }
    
    /**
     * searches for a class name within a selector
     * 
     * @param $selector the selector name we want to parse
     *          
     * @return string
     * @access public
     */
    private function _extractClassName($selector) 
    {
        $reserved = array('NE'            => true,
                          'NW'            => true,
                          'SE'            => true,
                          'SW'            => true,
                          'firstRow'      => true,
                          'lastRow'       => true,
                          'bandedRow'     => true,
                          'firstCol'      => true,
                          'lastCol'       => true,
                          'bandedCol'     => true,
                          );
        $regex = '/[a-zA-Z0-9_\-]*\.([^\.\s{]*)/i';
        \preg_match($regex, $selector, $matches);
        if (count($matches) == 0) {
            return NULL;
        }
        $class = \array_pop($matches);
        //if there are no real matches it returns NULL
        if (!isset($reserved[$class])) {
            return $class;
        } else {
            return NULL;
        }
    }
    
    /**
     * checks for a single property
     * 
     * @param $selector the selector we want to extract
     *          
     * @return string
     * @access public
     */
    private function _extractSingleCSSRule($selector) 
    {
        $regex = '/' . $selector . '\s*{(.*)}/i';
        \preg_match_all($regex, $this->CSS, $matches);
        $last = \array_pop($matches);
        //if there are no matches it returns NULL
        return \array_pop($last);
    }
    
    /**
     * parses theme styles
     * 
     *          
     * @return string
     * @access public
     */
    private function _parseTheme() 
    {
        //remove CSS comments, line breaks and extra whitespace and BOM
        $removeComments = '@/\*[^*]*\*+([^/][^*]*\*+)*/@s';
        $this->_CSS = preg_replace( $removeComments, ' ' , $this->_CSS);
        $lineBreaks = array("\r\n", "\r", "\n", "\t");
        $this->_CSS = str_replace($lineBreaks, '', $this->_CSS);
        $this->_CSS = preg_replace('/\s\s+/', ' ', $this->_CSS);
        $this->_CSS = preg_replace('/^\xEF\xBB\xBF/', '', $this->_CSS);
        
        //we extract the individual CSS props in an array
        $tmpCSS = explode('}', $this->_CSS);
        foreach($tmpCSS as $value){
            $tmpProp = explode('{' , $value);
            if(isset($tmpProp[1])){
                $selectorArray = explode(',', $tmpProp[0]);
                foreach ($selectorArray as $singleSelector) {
                    $singleSelector = \trim($singleSelector);
                    $singleSel = preg_replace('/\s+/', ' ', $singleSelector);
                    if (isset(self::$CSSProps[$singleSel])) {
                        self::$CSSProps[$singleSel] .= 
                                \trim($tmpProp[1]) . ';';
                    }else {
                        self::$CSSProps[$singleSel] = 
                            \trim($tmpProp[1]) . ';';
                    }
                    $class = $this->_extractClassName($singleSel);
                    if (!empty($class)) {
                        self::$CSSClasses[$class] = true;
                    }
                }
            }
        }
    }
    

}