<?php

/**
 * ODF Math
 * 
 * @copyright  Copyright(c)2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Core\Math;

use Docxpresso\Core\Elements as elements;
use Docxpresso\Parser as parser;
use Docxpresso\Commons as commons;

/**
 * This class creates a math equation
 *
 * @package    Docxpresso
 * @subpackage Math
 */

class Math
{
    /**
     * math formats
     * @var array
     * @access public
     * @static
     */
    public static $mathTypes = array(
        'mathml'        => true,
        'starmath'    => true,
    );
    
    /**
     * base XML
     * @var string
     * @access private
     */
    private $_baseXML;
    
    /**
     * math options
     * @var array
     * @access private
     */
    private $_options;
    
    
    /**
     * Construct
     * 
     * @param array $options
     * @access public
     */
    public function __construct($options)
    {
        if (!isset(self::$mathTypes[\strtolower($options['type'])])) {
            $message = 'Unsupported math format: ' . $options['type'] .'.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            return;
        }
        $this->_options = $options;
        $this->_baseXML = '<math xmlns="http://www.w3.org/1998/Math/MathML">';
        if (\strtolower($options['type']) == 'mathml') {
            //remove the <math ....> and </math> nodes
            $equation = $options['equation'];
            $regex_1 = '/(<math[^>]*?>)/i';
            $equation = preg_replace($regex_1, '', $equation);
            $regex_2 = '/(<\/math\s*>)/i';
            $equation = preg_replace($regex_2, '', $equation);
            $this->_baseXML .= $equation;
        } else {
            $this->_baseXML .= '<annotation encoding="StarMath 5.0">';
            $this->_baseXML .= $options['equation'];
            $this->_baseXML .= '</annotation>';
        }   
        $this->_baseXML .= '</math>';   
    }  
    
    /**
     * settings
     * 
     * @access public
     * @return string
     */
    public function settings()
    {
        $defaultSettings = array(
            'base-font-size'                => 12,
            'rel-text-size'                 => 100,
            'rel-indexes-size'              => 60,
            'rel-functions-size'            => 100,
            'rel-operators-size'            => 100,
            'rel-limits-size'               => 60,
            'variables-font'                => 'Times New Roman',
            'functions-font'                => 'Times New Roman',
            'numbers-font'                  => 'Times New Roman',
            'text-font'                     => 'Times New Roman',
            'custom-serif-font'             => 'Times New Roman',
            'custom-sans-serif-font'        => 'Arial',
            'custom-fixed-font'             => 'Courier New',
        );
        $settings = '<office:document-settings 
        xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" 
        xmlns:xlink="http://www.w3.org/1999/xlink" 
        xmlns:config="urn:oasis:names:tc:opendocument:xmlns:config:1.0" 
        xmlns:ooo="http://openoffice.org/2004/office" office:version="1.2">
            <office:settings>
                <config:config-item-set config:name="ooo:configuration-settings"> 
                    <config:config-item config:name="BaseFontHeight" config:type="short">base-font-size</config:config-item>
                    <config:config-item config:name="RelativeFontHeightText" config:type="short">rel-text-size</config:config-item>
                    <config:config-item config:name="RelativeFontHeightIndices" config:type="short">rel-indexes-size</config:config-item>
                    <config:config-item config:name="RelativeFontHeightFunctions" config:type="short">rel-functions-size</config:config-item> 
                    <config:config-item config:name="RelativeFontHeightOperators" config:type="short">rel-operators-size</config:config-item> 
                    <config:config-item config:name="RelativeFontHeightLimits" config:type="short">rel-limits-size</config:config-item>
                    <config:config-item config:name="FontNameVariables" config:type="string">variables-font</config:config-item>
                    <config:config-item config:name="FontNameFunctions" config:type="string">functions-font</config:config-item>
                    <config:config-item config:name="FontNameNumbers" config:type="string">numbers-font</config:config-item>
                    <config:config-item config:name="FontNameText" config:type="string">text-font</config:config-item> 
                    <config:config-item config:name="CustomFontNameSerif" config:type="string">custom-serif-font</config:config-item> 
                    <config:config-item config:name="CustomFontNameSans" config:type="string">custom-sans-serif-font</config:config-item>
                    <config:config-item config:name="CustomFontNameFixed" config:type="string">custom-fixed-font</config:config-item>         
                </config:config-item-set>
            </office:settings>
        </office:document-settings>';
        
        foreach ($defaultSettings as $key => $value){
            if (isset($this->_options['math-settings'][$key])) {
                $data = $this->_options['math-settings'][$key];
            } else {
                $data = $value;
            }
            $settings = str_replace($key, $data, $settings);
        }
        
        return $settings;
    }
    
    /**
     * magic method to string
     * 
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->_baseXML;
    }

}