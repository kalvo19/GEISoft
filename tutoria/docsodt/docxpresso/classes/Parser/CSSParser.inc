<?php

/**
 * CSS parser
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
use Docxpresso\Commons as commons;

/**
 * This class extracts and parse all the CSS content
 *
 * @package    Docxpresso
 * @subpackage Parser
 */

class CSSParser
{
    
    /**
     * Base URL
     * @var string
     * @access private
     */
    private $_baseURL;
    
    /**
     * CSS string
     * @var string
     * @access private
     */
    private $_CSS;
    
    /**
     * CSS properties
     * @var array
     * @access private
     */
    private $_CSSProps;
    
    /**
     * Document DOM
     * 
     * @var DOMDocument
     * @access private
     */
    private $_dom;
    
    /**
     * Document XPath
     * 
     * @var DOMXPath
     * @access private
     */
    private $_docxpath;
    
    /**
     * Stores relevant selector associated properties
     * 
     * @var array
     * @access private
     */
    private $_properties;
    
    /**
     * Construct
     *
     * @param HTML2ODF $obj
     * @access public
     */
    public function __construct($obj)
    {
        $this->_CSS = '';
        $this->_CSSProps = array();
        $this->_dom = $obj->getHTMLDOM();
        $this->_docxpath = new \DOMXPath($this->_dom);
        $this->_baseURL = $obj->getBaseURL();
        $this->_context = $obj->getHTTPContext(); 
        $this->_properties = array();
    }
    
    /**
     * Extracts the rules or directives from the CSS code and stores them into
     * an array
     *
     * @return void
     * @access public
     */
    public function parse()
    {
        //search for external stylesheets
        $_href = array();
        $linkTags = $this->_dom->getElementsByTagName('link');
        if($linkTags->length > 0){
            foreach ($linkTags as $link) {
                $media = \strtolower($link->getAttribute('media'));
                //by the time being we do not support but the most basic 
                //media queries  in the link tag.
                if (empty($media) 
                    || \stripos($media, 'screen') !== false 
                    || \stripos($media, 'print') !== false
                    || \stripos($media, 'all') !== false ) {
                    $rel = strtolower($link->getAttribute('rel'));
                    $type = strtolower($link->getAttribute('type'));
                    if ($rel == 'stylesheet' || $type == 'text/css') {
                        $_href[] = strtolower($link->getAttribute('href'));
                    }
                } 
            }
        }
        //load the external stylesheets if any
        foreach ($_href as $link) {
            $url = commons\Utilities::generateURL($this->_baseURL, $link);
            try {
                if (is_resource($this->_context)) {
                    $css = file_get_contents($url, false, $this->_context);
                } else {
                    $css = file_get_contents($url);
                }
            } catch (Exception $e) {
                commons\DOCXPRESSOLogger::logger($e->getMessage(), 'warning');
            }
            //we add the external CSS to our stylesheet
            if (!empty($css)) {
                $this->_CSS .= $css;
            }
        }
        
        //search for internal stylesheets
        //WARNING: we are assuming that all the external stylesheets are loaded
        //if  this is not the case the order of selectors will be changed with
        //possible undesirable results
        //before defining any internal stylesheet
        $styleTags = $this->_dom->getElementsByTagName('style');
        if($styleTags->length > 0){
            foreach ($styleTags as $style) {
                $this->_CSS .= $style->nodeValue;
            }
        }
        
        //remove CSS comments, line breaks and extra whitespace and BOM
        $removeComments = '@/\*[^*]*\*+([^/][^*]*\*+)*/@s';
        $this->_CSS = preg_replace( $removeComments, ' ' , $this->_CSS);
        $lineBreaks = array("\r\n", "\r", "\n", "\t");
        $this->_CSS = str_replace($lineBreaks, '', $this->_CSS);
        $this->_CSS = preg_replace('/\s\s+/', ' ', $this->_CSS);
        $this->_CSS = preg_replace('/^\xEF\xBB\xBF/', '', $this->_CSS);
        
        //let us prepend the default CSS styles that follow closely the MS Word
        //defaults for standard documents
        $this->_CSS = HTML\Resources::$defaultCSS . $this->_CSS;
        //now parse the CSS rules
        //by the time being we just remove them
        $this->_parseCSSRules();
        
        //we extract the individual CSS props in an array
        $tmpCSS = explode('}', $this->_CSS);
        foreach($tmpCSS as $value){
            $tmpProp = explode('{' , $value);
            if(isset($tmpProp[1])){
                //for multiple (comma separated) selectors the CSS specificity
                //may vary so they have to be dealt separately
                $selectorArray = explode(',', $tmpProp[0]);
                foreach ($selectorArray as $singleSelector) {
                    if (isset($this->_CSSProps[\trim($singleSelector)])) {
                        $this->_CSSProps[\trim($singleSelector)] .= 
                                \trim($tmpProp[1]);
                    }else {
                        $this->_CSSProps[\trim($singleSelector)] = 
                            \trim($tmpProp[1]);
                    }
                }
            }
        }

        //out from $this->_CSSProps we have to build an array that will hold:
        //  1. A unique identifier as key associated with the given selector
        //  2. The associated XPath query
        //  3. The normalized CSS properties so they can be used in the ODF doc
        //instantiate the  selector lexer
        $lexer = new CSSSelector2XPath();
        //instantiate the parser of single CSS properties
        $propParser = new ParseCSSProperties();
        //run over the array of CSS properties
        foreach ($this->_CSSProps as $key => $value) {
            $id = \uniqid('', true);
            $this->_properties[$id]['selector'] = $key;
            //generate the corresponding XPath expression and compute the
            //specificity of the selector
            $parsedQuery = $lexer->generateXPathQuery($key);
            $this->_properties[$id]['XPathQuery'] = $parsedQuery[0];
            $this->_properties[$id]['specificity'] = $parsedQuery[1];
            $parsedProps = $propParser->parseCSS($value);
            $this->_properties[$id]['properties'] = $parsedProps[0];
            $this->_properties[$id]['importantProps'] = $parsedProps[1];
            //run the generated XPathQuery
            //first make sure that no query starts with /*
            //this may happen if we have just a nth-child pseudoselector
            if (\substr($this->_properties[$id]['XPathQuery'], 0, 2) == '/*') {
                $this->_properties[$id]['XPathQuery'] = '//*' . 
                        $this->_properties[$id]['XPathQuery'];
            }
            try {
                if (!empty($this->_properties[$id]['XPathQuery']) 
                     && $this->_properties[$id]['XPathQuery'] != '//*') {
                    $selectedNodes = 
                        $this->_docxpath
                             ->query($this->_properties[$id]['XPathQuery']);
                } else if ($this->_properties[$id]['XPathQuery'] == '//*') {
                    //we treat this case separately to improve performance
                    $selectedNodes = false;
                    HTML2ODF::$globalCSSProps = 
                    \array_merge(HTML2ODF::$globalCSSProps, 
                            $this->_properties[$id]['properties']);
                    HTML2ODF::$globalCSSProps = 
                    \array_merge(HTML2ODF::$globalCSSProps,
                            $this->_properties[$id]['importantProps']);
                }
                
                if ($selectedNodes === false) {
                    $message = 'The query did not yield any result.';
                    commons\DOCXPRESSOLogger::logger($message, 'info'); 
                } else {
                    foreach ($selectedNodes as $node) {
                        //create a custom attribute "data-css-prop" to hold a 
                        //reference to the applied CSS properties
                        $data_css_prop = $node->getAttribute('data-css-prop');
                        if (empty($data_css_prop)) {
                            $data_css_prop = $id;
                        } else {
                            $data_css_prop .= '_' . $id;
                        }
                        $node->setAttribute('data-css-prop', $data_css_prop);
                        //run now over all childs in order to handle inherited
                        //properties.
                        if($node->hasChildNodes()){
                            $childs = $node->childNodes;
                            foreach($childs as $child){
                                $this->_inheritedProperties($child, $id);
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                commons\DOCXPRESSOLogger::logger($e->getMessage(), 'info');
            }
        }
        //we should also take into account inline properties that are also
        //inherited and that enjoy the highest specificity (1000000 with our
        //conventions)
        $inlineStylesQuery = '//*[@style]';
        $inlineStyleNodes = $this->_docxpath->query($inlineStylesQuery);
        foreach ($inlineStyleNodes as $inlineStyleNode) {
            $id = commons\Utilities::generateId('');
            $inlineProps = $inlineStyleNode->getAttribute('style');
            $inlineParser = new ParseCSSProperties();
            $inlineCSSProps = $propParser->parseCSS($inlineProps);
            $this->_properties[$id]['specificity'] = 1000000;
            $this->_properties[$id]['properties'] = $inlineCSSProps[0];
            $this->_properties[$id]['importantProps'] = $inlineCSSProps[1];
            //include the id in the list of css props
            $data_css_prop = $inlineStyleNode->getAttribute('data-css-prop');
            if (empty($data_css_prop)) {
                $data_css_prop = $id;
            } else {
                $data_css_prop .= '_' . $id;
            }
            $inlineStyleNode->setAttribute('data-css-prop', $data_css_prop);
            //run now over all childs in order to handle inherited properties.
            if($inlineStyleNode->hasChildNodes()){
                $childs = $inlineStyleNode->childNodes;
                foreach($childs as $child){
                    $this->_inheritedProperties($child, $id);
                }
            }
        }
        //return the final props array
        return $this->_properties;       
    }
    /**
     * Runs over all the "non text" childs of a node to set the "ancestor style"
     * as inherited style
     *
     * @param DOMNode $node
     * @param string $id
     * @return void
     * @access public
     */
    private function _inheritedProperties($node, $id)
    {
        $name = $node->nodeName;
        if((isset(html\Resources::$tags[$name]) 
            && html\Resources::$tags[$name])){
            $data_inherited_prop = $node->getAttribute('data-inherited-prop');
            if (empty($data_inherited_prop)) {
                $data_inherited_prop = $id;
                $node->setAttribute('data-inherited-prop', 
                                    $data_inherited_prop);
            } else {
                if(strpos($data_inherited_prop, $id) === false){
                    //only include the property if it was not 
                    //previously inherited
                    $data_inherited_prop .= '_' . $id;
                    $node->setAttribute('data-inherited-prop', 
                                        $data_inherited_prop);
                }
            }
        }
        if($node->hasChildNodes()){
            $childs = $node->childNodes;
            foreach($childs as $child){
                $this->_inheritedProperties($child, $id);
            }
        }
    }
    
    /**
     * Extracts the rules or directives from the CSS code and stores them into
     * an array
     *
     * @return void
     * @access public
     */
    private function _parseCSSRules()
    {
        //run over the rules defined in the CSS Resources class
        foreach (css\Resources::$CSSrules as $key => $value) {
            if ($value) {
                $this->_parseRule($key);
            }
        }
        //remove the vendor dependent rules that start with "@-"
        $this->_parseRule('@-');
    }  
    
    /**
     * Extracts the CSS associated with a single rule and prepares the CSS
     * for further processing
     *
     * @param string $rule
     * @return void
     * @access public
     */
    private function _parseRule($rule)
    {
        $this->_CSSRules[$rule] = array();
        $start = 0;
        while (($start = \strpos($this->_CSS, $rule, $start)) !== false) {
            // depending on the rule we may have a set of CSS props that are
            //enclosed by curly brackets or just text until we hit a ;
            $firstBracket = \strpos($this->_CSS, "{", $start);
            $firstSemicolon = \strpos($this->_CSS, ";", $start);
            if ($firstSemicolon < $firstBracket) {
                //the media rule is contained between $start and $firstSemicolon
                $this->_CSSRules[$rule][] = \substr($this->_CSS, 
                                                   $start, 
                                                   ($firstSemicolon+1) -$start);
                //by the time being we just remove these rules from the CSS
                \substr_replace($this->_CSS, 
                                '', 
                                $start, 
                                ($firstSemicolon + 1) - $start);
            } else {
                //we are dealing with a rule enclose by curly brackets
                //count open brackets until they get balanced with closed ones
                $openBrackets = 1;
                $k = $firstBracket +1;

                while ($openBrackets > 0) {
                    if ($this->_CSS[$k] == "{"){
                        $openBrackets++;
                    } else if ($this->_CSS[$k] == "}") {
                        $openBrackets--;
                    }
                    $k++;
                }
                //get the chunk and store it in the array of rules
                $this->_CSSRules[$rule][] = \substr($this->_CSS, 
                                                   $start, 
                                                   $k - $start);
                //by the time being we just remove these rules from the CSS
                $this->_CSS = \substr_replace($this->_CSS, 
                                              '', 
                                              $start, 
                                              $k - $start);
                
            }
        }
    } 
    
}