<?php

/**
 * HTML2ODF
 *
 * @copyright  Copyright(c)2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Parser;

use Docxpresso\Parser\HTML as html;
use Docxpresso\Parser\CSS as css;
use Docxpresso;
use Docxpresso\Core\Elements as elements;
use Docxpresso\Commons as commons;

/**
 * This class converts HTML into Open Document Format XML
 *
 * @package    Docxpresso
 * @subpackage Parser
 */

class HTML2ODF extends elements\ODFElement
{
    /**
     * This array controls the ODF tag that accept plain text as a child
     * 
     * @var array
     * @access public
     * @static
     */
    public static $acceptsPlainText = array('text:a' => true,
                                            'text:h' => true,
                                            'text:p' => true,
                                            'text:span' => true,
                                            );
    
    /**
     * Default global CSS properties
     * @var array
     * @access public
     * @static
     */
    public static $globalCSSProps = array( 'font-size' => array(12, 'pt'));
    
    /**
     * This array sets the tags that will be parsed by default as text:span
     * 
     * @var array
     * @access public
     * @static
     */
    public static $parseAsSpan = array( 'abbr' => true,
                                        'acronym' => true,
                                        'b' => true,
                                        'bdo' => true,
                                        'big' => true,
                                        'cite' => true,
                                        'code' => true,
                                        'command' => true,
                                        'datalist' => true,
                                        'del' => true,
                                        'details' => true,
                                        'dfn' => true,
                                        'dialog' => true,
                                        'em' => true,
                                        'font' => true,
                                        'i' => true,
                                        'ins' => true,
                                        'label' => true,
                                        'mark' => true,
                                        'meter' => false,
                                        'output' => true,
                                        'q' => true,
                                        'small' => true,
                                        'source' => true,
                                        'span' => true,
                                        'strike' => true,
                                        'strong' => true,
                                        'sub' => true,
                                        'sup' => true,
                                        'time' => true,
                                        'u' => true,
                                        'var' => true,
                                        );
    
    /**
     * This array sets the tags that will be parsed as table:table because there
     * is no "div" equivalent in the ODF format
     * 
     * @var array
     * @access public
     * @static
     */
    public static $parseAsTable = array('address' => true,
                                        'article' => true,
                                        'aside' => true,
                                        'center' => true,
                                        'div' => true,
                                        'fieldset' => true,
                                        'figure' => true,
                                        'hgroup' => true,
                                        'table' => true,
                                        );
    
    /**
     * This array sets the tags that will be parsed as text:p by default
     * 
     * @var array
     * @access public
     * @static
     */
    public static $parseAsP = array('blockquote' => true,
                                    'caption' => true,
                                    'dd' => true,
                                    'dt' => true,
                                    'figcaption' => true,
                                    'legend' => true,
                                    'nav' => true,
                                    'p' => true,
                                    'pre' => true,
                                    'samp' => true,
                                    'summary' => true,
                                    );
    
    /**
     * This array stores (as a reference) row border properties
     * 
     * @var array
     * @access public
     * @static
     */
    public static $rowBorders = array();
    
    /**
     * This array sets the tags that will stop the search for child nodes when
     * the HTML tree is parsed
     * 
     * @var array
     * @access public
     * @static
     */
    public static $stopTree = array('select' => true,
                                    'textarea' => true,
                                    'chart' => true,
                                    'math' => true,
                                    'toc' => true,
        );
    
    /**
     * This array stores (as a reference) table border properties
     * 
     * @var array
     * @access public
     * @static
     */
    public static $tableBorders = array();
    
    /**
     * Temporarily stores endnote info for correct tidy parsing
     * 
     * @var array
     * @access public
     * @static
     */
    public static $XMLendnotes = array();
    
    /**
     * Temporarily stores footnote info for correct tidy parsing
     * 
     * @var array
     * @access public
     * @static
     */
    public static $XMLfootnotes = array();
    
    /**
     * Autodetect base URL. If true the package will try to detect the base URL
     * 
     * @var boolean
     * @access private
     */
    private $_detectBaseURL;
    
    /**
     * base URL
     * 
     * @var string
     * @access private
     */
    private $_baseURL;
    
    /**
     * HTML body element
     * 
     * @var DOMNode
     * @access private
     */
    private $_bodyElement;
    
    /**
     * Context resource for HTTP requests
     * 
     * @var resource
     * @access private
     */
    private $_context;
    
    /**
     * HTML DOM
     * 
     * @var DOMDocument
     * @access private
     */
    private $_domHTML;
    
    /**
     * ODF DOM
     * 
     * @var DOMDocument
     * @access private
     */
    private $_domODF;
    
    /**
     * This array stores encoding options
     * 
     * @var array
     * @access private
     */
    private $_encoding;
    
    /**
     * Encoding detection method
     * 
     * @var string
     * @access private
     */
    private $_encodingDetectionMethod;
    
    /**
     * HTML attributes object used to parse HTML (legacy) attributes
     * 
     * @var HTMLAttributes2CSS
     * @access private
     */
    private $_HTMLattr;
    
    /**
     * Document root element
     * 
     * @var DOMNode
     * @access private
     */
    private $_rootElement;
    
    /**
     * Box sides
     * 
     * @var array
     * @access private
     */
    private $_sides = array('top', 'right', 'bottom', 'left');
    
    /**
     * Construct
     *
     * @param DOMNode $node
     * @access public
     */
    public function __construct($node)
    {          
        //this is the document root element for the parsed as ODF HTML content
        $this->_rootElement = $node;
        //by default the base URL for relative paths is set to an empty string
        $this->_baseURL = '';
        $this->_detectBaseURL = true;
        //create the instance of HTMLAttributes2CSS used to parse the HTML
        //(legacy) attributes
        $this->_HTMLattr = new html\HTMLAttributes2CSS();
    }
      
    /*Getters and Setters*/
       
    /**
     * Gets the base URL for relative paths
     *
     * @return string
     * @access public
     */
    public function getBaseURL() 
    {
        return $this->_baseURL;
    }
    
    
    /**
     * Gets the HTML DOMDocument
     *
     * @return DOMDocument
     * @access public
     */
    public function getHTMLDOM() 
    {
        return $this->_domHTML;
    }
    
    
    /**
     * Gets the current context used for HTTP requests
     *
     * @return resource
     * @access public
     */
    public function getHTTPContext() 
    {
        return $this->_context;
    }
    
    /**
     * This method allows to convert HTML5 code into Open Document format
     *
     * @param array $options the available keys and values are:
     *  'baseURL' => (string) if set enforces the base URL used for 
     *   relative paths, otherwise it will be autodetected (if possible).
     *  'encoding' => (string) if set it enforces the encoding to be used 
     *   otherwise it will be autodetected.
     *  'html' => (string) the string of HTML code.
     * @return void
     * @access public
     */
    public function loadHTML($options = array())
    {       
        $html = $options['html'];
        //parse options
        if (isset($options['baseURL'])) {
            $this->_baseURL = $options['baseURL'];
            $this->_detectBaseURL = false;
        } 
        if (isset($options['encoding'])) {
            $this->_encoding['manual'] = $options['encoding'];
        } else {
            $this->_encoding['manual'] = NULL;
        }        
        //normalize the HTML via TIDY
        $html = $this->_preprocessHTML($html);
        //detect encoding because we need a UTF-8 encoded string
        $this->_detectEncoding($html);
        //with all the information at hand we finally determine the encoding
        //following this hierarchy
        if(!empty($this->_encoding['manual'])){
            //the encoding was enforced by the user
            $encoding = $this->_encoding['manual'];
        } else if (!empty($this->_encoding['UTF8'])) {
            //the encoding was detected to be UTF-8 by the UTF8encoded method
            //the Utilities class
            $encoding = 'UTF-8';
        } else if (!empty($this->_encoding['http_response_header'])) {
            //the encoding was set in the HTTP headers
            $encoding = $this->_encoding['http_response_header'];
        }else if (!empty($this->_encoding['meta'])) {
            //the encoding was defined in the corresponding meta tag
            $encoding = $this->_encoding['meta'];
        } else if (!empty($this->_encoding['auto'])) {
            //the encoding was detected with mb_detect_encoding (not reliable)
            $encoding = $this->_encoding['auto'];
        } else {
            $message = 'It was not possible to detect the encoding.';
            $message = 'Please, set it manually';
            commons\DOCXPRESSOLogger::logger($message, 'critical');
        }
        
        if(\strtolower($encoding) == 'utf-8'
           || \strtolower($encoding) == 'utf8'
           || \strtolower($encoding) == 'utf 8') {
            //the HTML code is already UTF-8 encoded
        } else {
            //if the encoding is not UTF-8 we must convert it to UTF-8 for
            //prper rendering in ODF
            $html = iconv($encoding, 'UTF-8//TRANSLIT', $html); 
        }
        //before loading the HTML code into the DOM we need to remove the
        //namespaces that are not currently parsed
        $nsRegex = '/xmlns="([^"]*)?"/i';
        $html = \preg_replace($nsRegex, '', $html);
        //the $html code must be a properly pre-processed HTML5 string of code 
        $this->_domHTML = new \DOMDocument();
        //the following option tries to recover poorly formed documents
        $this->_domHTML->recover = true;
        $this->_domHTML->preserveWhiteSpace = false;
        $this->_domHTML->formatOutput = false;
        $this->_domHTML->loadXML($html);
        //check if there is a base URL tag in case that the user has not set it
        //by hand. If there is a <base> tag we will overwrite the base URL that
        //was obtained via the _extractBaseURL method
        if($this->_detectBaseURL){
            $baseNodes = $this->_domHTML->getElementsByTagName('base');
            if ($baseNodes->length >0) {
               $url = $baseNodes->item(0)->getAttribute('href');
               if(!empty($url)){
                   $this->_baseURL = $url;
               }
            }
        }
        //start the CSS parsing
        $this->_CSSParser = new CSSParser($this);
        $this->_CSSProperties = $this->_CSSParser->parse();
        //start the true HTML parsing
        $this->_bodyElement = $this->_domHTML->
                              getElementsByTagName('body')->item(0);
        //Start the HTML parsing with a recursive method
        $this->_parseHTMLTree($this->_bodyElement, $this->_rootElement);
    }
    
    /**
     * This method allows to convert a remote/local HTML file into Open
     * Document format
     *
     * @param array $options the available keys and values are:
     *  'baseURL' => (string) if set enforces the base URL used for 
     *   relative paths, otherwise it will be autodetected (if possible).
     *  'encoding' => (string) if set it enforces the encoding to be used 
     *   otherwise it will be autodetected.
     *  'context' => (array) this option is only taken into account if we need
     *   to fetch the HTML code via a http request. Its use is optional and if 
     *   not set standard values, valid in the majority of cases, will be used.
     *   This option sets the http context (headers) for  
     *   the http request. Depending on the server this option may be mandatory, 
     *   i.e. the server will not return anything unless it receives this 
     *   additional info. 
     *   The keys and values are(http://www.php.net/manual/en/context.http.php):
     *      'method': GET (default value) or POST.
     *      'header': an array with the required headers that may include among
     *       others: 'Referer', 'User-agent', 'Referer', 'Connection', ...
     *      'proxy':address of the proxy server
     *      'request_fulluri' true or false (default).
     *      'follow_location': Follow Location header redirects. Set to 0 
     *       to disable. Default value is 1.
     *      'max_redirects'The maximum number of redirects to follow. 
     *       1 or less means that no redirects are followed (default is 20).
     *      'protocol_version': HTTP protocol version (default is 1.0).
     *  'html' => (string) the path to the HTML file.
     * @return void
     * @access public
     */
    public function loadHTMLFromFile($options)
    {
        
        $path = $options['html'];
        //Check if the base URL is set and if it is so set the automatic
        //detection to false
        if (isset($options['baseURL'])) {
            $this->_baseURL = $options['baseURL'];
            $this->_detectBaseURL = false;
        }
        //build the $HTTPContext array from the supplied options array
        $HTTPContext = array();
        //method
        if (isset($options['context']['method']) 
            && $options['context']['method'] == 'POST') {
            $HTTPContext['method'] = $options['method'];
        } else {
            $HTTPContext['method'] = 'GET';
        }
        //header
        if (isset($options['context']['header']) 
            && is_array($options['context']['header'])) {
            $HTTPContext['header'] = '';
            foreach ($options['context']['header'] as $key => $value) {
                $HTTPContext['header'] .= '"' . $key . ': ' . $value . '\r\n"';
            }
        }
        //proxy
        if (isset($options['context']['proxy'])) {
            $HTTPContext['proxy'] = $options['context']['proxy'];
        }
        //request_fulluri
        if (isset($options['request_fulluri'])) {
            $HTTPContext['request_fulluri'] = $options['request_fulluri'];
        }
        //follow_location
        if (isset($options['context']['follow_location'])) {
            $HTTPContext['follow_location'] = 
                    $options['context']['follow_location'];
        }
        //max_redirects
        if (isset($options['context']['max_redirects'])) {
            $HTTPContext['max_redirects'] = 
                    $options['context']['max_redirects'];
        }
        //protocol_version
        if (isset($options['context']['protocol_version'])) {
            $HTTPContext['protocol_version'] = 
                    $options['context']['protocol_version'];
        }
        //create the context stream
        $this->_context = stream_context_create(array('http'=> $HTTPContext));       
        //get the contents of the HTML file
        try {
            $html = file_get_contents($path, false, $this->_context);
        } catch (Exception $e) {
            commons\DOCXPRESSOLogger::logger($e->getMessage(), 'critical');
        }
        //check that the result is not empty
        if (empty($html)) {
            $message = 'The loadHTMLFromFile method was unable to retrieve the';
            $message .= 'requested HTML file or that file was empty.';
            commons\DOCXPRESSOLogger::logger($message, 'critical');
        } else {
            if ($this->_detectBaseURL) {
                //extract the base URL path from the request. This value may be
                //overwritten later by setBaseURL or the metas of the HTML file
                $this->_baseURL = commons\Utilities::extractBaseURL($path);
            }
            //check if there is information about the encoding in the 
            //response HTTP headers
            if (isset($http_response_header)) {
                foreach($http_response_header as $header) {
                    $regex = '@Content-Type:\s*[\w/]+;\s*?charset=([^\s]+)@i';
                    if (preg_match($regex, $header, $matches)) {
                        $this->_encoding['http_response_header'] = 
                        strtoupper($matches[1]);
                        break;
                    }
                }
            }
            $options['html'] = $html;
            $this->loadHTML($options); 
        }
    }
    
    
    /**
     * Temporary method to check the generated ODF
     *
     * @return string
     * @access public
     */
    public function generateODF()
    {
        return $this->_domODF->saveXML();
    }
    
    /**
     * This method extracts the encoding from the meta charset or tries to
     * "guess it"
     *
     * @param string $html
     * @return void
     * @access private
     */
    private function _detectEncoding($html)
    {
        //seek for the encoding in the meta tags
        $regex = '@<meta(?!\s*(?:name|value)\s*=)(?:[^>]*?';
        $regex .= 'content\s*=[\s"\']*)?([^>]*?)[\s"\';]';
        $regex .= '*charset\s*=[\s"\']*([^\s"\'/>]*)@si';
        if (\preg_match($regex, $html, $matches) ){
            if (isset($matches[2])) {
                $this->_encoding['meta'] = $matches[2];
            } else {
                $this->_encoding['meta'] = NULL;
            }
        }
        if (commons\Utilities::UTF8Encoded($html)) {
            $this->_encoding['UTF8'] = 'UTF-8';
        } else {
            //try to detect the encoding using mb_detect_encoding
            //this method is not very reliable
            $this->_encoding['auto'] = mb_detect_encoding($str, 
                                                          mb_list_encodings(), 
                                                          true);
        }
        
    }
      
    /**
     * Preparses some extended HTML elements
     *
     * @param string $html
     * @return string
     * @access private
     */
    private function _preparseExtendedHTML($html)
    {
        $regex = '/<chart(.*?)<\/chart>/is';
        $matches = array();
        \preg_match_all($regex, $html, $matches);
        if(isset($matches[0])){
            foreach($matches[0] as $chartXML){
                $id = commons\Utilities::generateId('chart_');
                $html = str_replace($chartXML, 
                                    '<chart chartid="' . $id . '" />',
                                    $html);
                Docxpresso\CreateDocument::$XMLCharts[$id] = $chartXML;
            }
        }
        $regex = '/<math(.*?)<\/math>/is';
        $matches = array();
        \preg_match_all($regex, $html, $matches);
        if(isset($matches[0])){
            foreach($matches[0] as $mathXML){
                $id = commons\Utilities::generateId('math_');
                $html = str_replace($mathXML, 
                                    '<math mathid="' . $id . '" />',
                                    $html);
                Docxpresso\CreateDocument::$XMLMath[$id] = $mathXML;
            }
        }
        //If we wish that the footnotes and endnotes accept not only
        //inline content we need to parse them: they have to be inline elements
        //and tidy gets confused
        //footnotes
        $regex = '/<footnote(.*?)<\/footnote>/is';
        $matches = array();
        \preg_match_all($regex, $html, $matches);
        if(isset($matches[0])){
            foreach($matches[0] as $noteXML){
                $id = commons\Utilities::generateId('footnote_');
                $html = str_replace($noteXML, 
                                    '<footnote footnoteid="' . $id . '" />',
                                    $html);
                self::$XMLfootnotes[$id] = $noteXML;
            }
        }
        //endnotes
        $regex = '/<endnote(.*?)<\/endnote>/is';
        $matches = array();
        \preg_match_all($regex, $html, $matches);
        if(isset($matches[0])){
            foreach($matches[0] as $noteXML){
                $id = commons\Utilities::generateId('endnote_');
                $html = str_replace($noteXML, 
                                    '<endnote endnoteid="' . $id . '" />',
                                    $html);
                self::$XMLendnotes[$id] = $noteXML;
            }
        }
        return $html;
    }
    /**
     * Prepares the HTML code for parsing
     *
     * @param string $html
     * @return string
     * @access private
     */
    private function _preprocessHTML($html)
    {
        $html = $this->_preparseExtendedHTML($html);
        if(\class_exists('\tidy')){
            try{
                $tidy = new \tidy();
                $tmpBlockTags = array_keys(html\Resources::$newBlockLevelTags);
                $additionalBlockTags = implode(',', $tmpBlockTags);
                $tmpInlineTags = array_keys(html\Resources::$newInlineTags);
                $additionalInlineTags = implode(',', $tmpInlineTags);
                $config = array(
                    'wrap' => 0,
                    'indent' => false,
                    'enclose-block-text' => true, //usually useless with PHP
                    'enclose-text' => true, //usually useless with PHP
                    //take into account the non-standard tags introduced by the
                    //package to extend HTML5 functionality
                    'new-blocklevel-tags' => $additionalBlockTags,
                    'new-inline-tags' =>  $additionalInlineTags,
                    'new-empty-tags' => 'source',
                    'numeric-entities' => true,
                    'sort-attributes' => 'alpha',
                    'vertical-space' => false,
                    'output-xhtml' => true,
                    //'output-xml' => true // check what may be best   
                );
                $tidy = \tidy_parse_string($html, $config, 'utf8');
                $tidiedHTML = $tidy->html();
                $html = $tidiedHTML->value;
            }
            catch(Exception $e){
                throw new Exception('Tidy threw a fatal error.');
            }
        } else {
            //Tidy is not installed so the HTML code should be XHTML compliant
            if (\strpos('<body>', $html) === false) {
                $html = '<html><body>' . $html . '</body></html>';
            }
        }
        $html = \str_replace(PHP_EOL, '', $html);
        //recover the footnotes and endnotes for the standard HTML parsing
        foreach (self::$XMLfootnotes as $id => $value) {
            $base = '<footnote footnoteid="' . $id . '"></footnote>';
            $html = \str_replace($base, $value, $html);
        }
        foreach (self::$XMLendnotes as $id => $value) {
            $base = '<endnote endnoteid="' . $id . '"></endnote>';
            $html = \str_replace($base, $value, $html);
        }
        return $html;
    }
    
    /**
     * This is the main method that runs over the HTML tree
     *
     * @param DOMNode $HTMLNode
     * @param ODFElement $ODFNode
     * @return void
     * @access private
     */
    private function _parseHTMLTree($HTMLNode, $ODFNode)
    {
        $name = \strtolower($HTMLNode->nodeName);
        if((isset(html\Resources::$tags[$name]) && html\Resources::$tags[$name])
            || $name == '#text' 
            || $name == '#cdata-section') {
            $newNode = $this->_parseHTMLNode($HTMLNode, $ODFNode);
            //if there are childs run this method on them in a recursive fashion
            if($HTMLNode->hasChildNodes() 
               && !empty($newNode)
               && !isset(self::$stopTree[$name])){
                    $childs = $HTMLNode->childNodes;
                    foreach($childs as $child){
                            $this->_parseHTMLTree($child, $newNode);
                    }
            }
        }
    }
    
    /**
     * This method converts a single HTML node into ODF format
     *
     * @param DOMNode $HTMLNode
     * @param ODFElement $ODFNode
     * @return ODFElement
     * @access private
     */
    private function _parseHTMLNode($HTMLNode, $ODFNode)
    {
        $name = \strtolower($HTMLNode->nodeName);
        $ODFNodeName = $ODFNode->getNodeType();
        if($name == '#text' || $name == '#cdata-section'){
            //This gets a little messy because of the potential mixed content
            //of, for example, <div>'s and <td>'s
            if(isset(self::$acceptsPlainText[$ODFNodeName])){
                $ODFNode->plainText($HTMLNode->nodeValue);
            } else { 
                //check if the previous HTML node was an inline node or not
                $preNode = $HTMLNode->previousSibling;
                if (isset($preNode) 
                    && isset(html\Resources::$inlineTags[$preNode->nodeName])) {
                    //we should insert this text in the previous ODFNode
                    $lastChild = $ODFNode->getNode()->lastChild;
                    if (isset(Docxpresso\CreateDocument::$nodeMap[$lastChild])) {
                        $prev = Docxpresso\CreateDocument::$nodeMap[$lastChild];
                        $prev->text(array('text' => $HTMLNode->nodeValue));
                    } else {
                        $ODFNode->text(array('text' => $HTMLNode->nodeValue));
                    }
                } else {
                    $ODFNode->text(array('text' => $HTMLNode->nodeValue));
                }
           }
           //we return nothing because a text node can not have childs
           return;
        } else {
            $newNode = $this->_createODFNode($HTMLNode, $ODFNode);
            return $newNode;
        }
    }
    
    /**
     * This method parses the math formula and returns MathML
     *
     * @param string $math
     * @return array
     * @access private
     */
    private function _parseMath($math)
    {
        $mathSettings = array();
        $eq = '';
        $style = '';
        $mathDOM = new \DOMDocument();
        $mathDOM->loadXML($math);
        $mathNode = $mathDOM->getElementsByTagName('math')->item(0);
        $math = array('base-font-size' => true,
                      'rel-text-size' => true,
                      'rel-indexes-size' => true,
                      'rel-functions-size' => true,
                      'rel-operators-size' => true,
                      'rel-limits-size' => true,
                      'variables-font' => true,
                      'functions-font' => true,
                      'numbers-font' => true,
                      'text-font' => true,
                      'custom-serif-font' => true,
                      'custom-sans-serif-font' => true,
                      'custom-fixed-font' => true,
                   );
                    $attr= $mathNode->attributes;
                    $length = $attr->length;
                    for ($j = $length-1; $j >= 0; $j--) {
                        if (isset($math[$attr->item($j)->name])) {
                            $mathSettings[$attr->item($j)->name] =
                                    $attr->item($j)->value;
                        } else if ($attr->item($j)->name == 'style'){
                            $style = $attr->item($j)->value;
                        }
                        $mathNode->removeAttributeNode($attr->item($j));
                    }
        $eq = $mathDOM->saveXML($mathNode);
        $eq = \str_replace('<math', 
                           '<math xmlns="http://www.w3.org/1998/Math/MathML"',
                           $eq);
        $mathData['formula'] = $eq;
        $mathData['config'] = $mathSettings;
        $mathData['style'] = $style;
        return $mathData; 
    }
    /**
     * This method converts CSS relative units
     *
     * @param string $refFontSize
     * @param array $style
     * @return array
     * @access private
     */
    private function _parseRelativeSize($refFontSize, $style)
    {
        if (\is_array($refFontSize)) {
            $refFontSize = \implode($refFontSize);
        }
        $relativeUnits = array('em' => true, 'rem' => true, '%' => true);
        $regex = '/([0-9]+\.?[0-9]*)\s*(px|in|cm|mm|pt)?/i';
        \preg_match($regex, $refFontSize, $matches);
        $rawVal = $matches[1];
        if (isset($matches[2])) {
            $rawUnits = $matches[2];
        } else {
            $rawUnits = 'pt';
        }
        foreach ($style as $key => $value) {
            if (\is_array($value) && isset($relativeUnits[$value[1]])) {
                if ($value[1] == 'em' || $value[1] == 'rem') {
                    $style[$key][0] = $rawVal * $value[0];
                    $style[$key][1] = $rawUnits;
                } else if ($value[1] == '%' && $key == 'font-size') {
                    $style[$key][0] = $rawVal * $value[0]/100;
                    $style[$key][1] = $rawUnits;
                }
            }
        }
        return $style;
    }
    
    /**
     * Checks if the (top, right, bottom, left) border property is set
     *
     * @param array $style
     * @return boolean
     * @access private
     */
    private function _borderIsSet($style)
    {
        $border = false;
        foreach ($this->_sides as $side) {
            if (isset($style['border-' . $side . '-width'])
                && \is_array($style['border-' . $side . '-width'])
                && $style['border-' . $side . '-width'][0] > 0) {
                $border = true;
                break;
            }
        }
        return $border;
    }
    
    /**
     * This method pre-parses a single HTML element to generate the
     * corresponding Open Document Format XML node
     * Like the ODF node may depend on styling we also carry out all
     * the required style inheritance 
     *
     * @param DOMNode $HTMLNode
     * @param ODFElement $ODFNode
     * @return ODFElement
     * @access private
     */
    private function _createODFNode($HTMLNode, $ODFNode)
    {
        $name = $HTMLNode->nodeName;
        //get the referenceFontSize attribute of the parent HTML node so
        //we can handle relative units
        $refFontSize = $HTMLNode->parentNode->getAttribute('refFontSize');
        if(empty($refFontSize)){
            $refFontSize = self::$globalCSSProps['font-size'];
        }
        $attributes = $HTMLNode->attributes;
        //we need to consolidate all styles
        //the different sources are:
        //  1. the static array self::$globalCSSProps
        //  2. the default styles associated to a given tag
        //  3. the CSS props stored in $this->_CSSProperties['id'] where the
        //     id is extracted from the data-css-prop attribute
        //  4. the inherited CSS properties ($this->_CSSProperties['id'])
        //     which ids are extracted from data-inherited-prop
        //  5. the styles stored in the 'style' attribute
        //  6. (some of)the styles stored in legacy attributes (specificity = 0)
        $styleId = commons\Utilities::generateId();
        //extract inherited properties
        $idList = array();
        $inheritedPropsArray = $HTMLNode->getAttribute('data-inherited-prop');
        if (!empty($inheritedPropsArray)) {
            $idInherited = explode('_', $inheritedPropsArray);
            foreach($idInherited as $value){
                $idList[$value] = true;
            }
        }
        //extract properties
        $propsArray = $HTMLNode->getAttribute('data-css-prop');
        if (!empty($propsArray)) {
            $idNotInherited = explode('_', $propsArray);
            foreach($idNotInherited as $value){
                $idList[$value] = false;
            }
        }
        
        //merge the inherited and direct styles
        $combinedStyles = $this->_mergeStyles($idList);
        //flatten the combided styles so we get a list of properties without
        //specificity or "importance"
        $CSSStyles = $this->_flattenStyles($combinedStyles);
        //
        //get the styling included in the attributes
        $attrStyles = $this->_HTMLattr->parseAttributes($name, $attributes);
        //fusion al styles with the following precedence:
        //  1. CSS styles
        //  2. attribute styles
        //  3. styles associated with a tag
        //  3. globally defined styles
        if (isset(HTML\Resources::$tagStyles[$name])) {
            $style = \array_merge(self::$globalCSSProps,
                                  HTML\Resources::$tagStyles[$name],
                                  $attrStyles,
                                  $CSSStyles);
        } else {
            $style = \array_merge(self::$globalCSSProps,
                                  $attrStyles,
                                  $CSSStyles);
        }
        //the node that we have to insert will depend on different factors:
        //  1. the tag name
        //  2. the display property:
        //      2.1 if not set explictely via CSS  all elements belonging to
        //      html\Resources::$displayBlockTags will be assumed to be block
        //      and inline otherwise
        //  3. the pos property:
        //      3.1 if float equal right or left we will wrap the element with a 
        //      draw:frame with the corresponding positioning and we set 'pos' 
        //      to true
        //      3.2 if position is equal to absolute we also have to wrap the
        //      element with a draw:frame and set 'pos' to true
        $display = true;
        if (isset($style['display']) && $style['display'] == 'none') {
            $display = false;
        }
        if (isset($style['visibility'])
            && ($style['visibility'] == 'hidden' 
                || $style['visibility'] == 'collapse')
            ) {
            $display = false;
        }
        $pos = false;
        if (isset($style['float']) && $style['float'] != 'none') {
            $pos = true;
        } else if (isset($style['position']) 
                   && $style['position'] == 'absolute'){
            $pos = true;
        }
        if ($display) {
            //take care of relative units like em or %
            $style = $this->_parseRelativeSize($refFontSize, $style);
            //determine the reference font size and create the corresponding
            //attribute in the HTML node
            if (isset($style['font-size'])) {
                if (\is_array($style['font-size'])) {
                    $refFontSize = \implode($style['font-size']);
                } else {
                    $refFontSize = $style['font-size'];
                }
            }
            $HTMLNode->setAttribute('refFontSize', $refFontSize);
            $newNode = $this->_ODFElement($HTMLNode, 
                                          $ODFNode, 
                                          $styleId, 
                                          $style,
                                          $pos);
            return $newNode;
        } else {
            return NULL;
        }
    }
    
    /**
     * This method points out to the required node create method depending on
     * the name of the tag and the position property
     *
     * @param DOMNode $HTMLNode
     * @param ODFElement $ODFNode
     * @param string $styleId
     * @param array $style
     * @param bool $pos
     * returns DOMNode
     * @access private
     */
    private function _ODFElement($HTMLNode, $ODFNode, $styleId, $style, $pos)
    {            
        //wrapping frame
        if ($pos) {
            //when including a frame we should copy the relevant styles to 
            //include them as graphic styles
            $frStyleId = commons\Utilities::generateId();
            //create a new automatic style with that id and the relevant
            //CSS properties
            $frStyle = $this->_generateFrameStyle($style);
            Docxpresso\CreateDocument::$automaticStyles[$frStyleId] = $frStyle;
            Docxpresso\CreateDocument::$styleTypes[$frStyleId] = 'draw:frame';
            if (isset(Docxpresso\CreateDocument::$styleTarget[$styleId])) {
                Docxpresso\CreateDocument::$styleTarget[$frStyleId] =
                        Docxpresso\CreateDocument::$styleTarget[$styleId];
            }
            $ODFNode = $ODFNode->textBox(array('style' => $frStyle,
                                             'styleId' => $frStyleId));
        }
        $tag = \strtolower($HTMLNode->nodeName);
        $newNode = '';
        
        if(isset(self::$parseAsSpan[$tag])) {
            //here we may have the same problem in cells and divs as with 
            //the plain text case
            $ODFNodeName = $ODFNode->getNodeType();
            if (isset(self::$acceptsPlainText[$ODFNodeName])) {
                $newNode = $ODFNode->text(array('style' => $style,
                                                'styleId' => $styleId,
                                                'nestSpans' => true));
            } else {
               $preNode = $HTMLNode->previousSibling; 
               if (isset($preNode) 
		&& (isset(html\Resources::$inlineTags[$preNode->nodeName])
                    || $preNode->nodeName == '#text')
                        ) {
                    //we should insert this text in the previous ODFNode
                    $lastChild = $ODFNode->getNode()->lastChild;
                    if (isset(Docxpresso\CreateDocument::$nodeMap[$lastChild])) {
                        $prev = Docxpresso\CreateDocument::$nodeMap[$lastChild];
                        $newNode =$prev->text(array('style' => $style,
                                                    'styleId' => $styleId,
                                                    'nestSpans' => true));
                    } else {
                        $newNode = $ODFNode->text(array('style' => $style,
                                                        'styleId' => $styleId,
                                                        'nestSpans' => true));
                    }
                } else {
                        $newNode =$ODFNode->text(array('style' => $style,
                                                       'styleId' => $styleId,
                                                       'nestSpans' => true));
                }
            }
        } else if(isset(self::$parseAsTable[$tag])) {
            if ($tag == 'table') {
                $newNode = $ODFNode->table(array('style' => $style,
                                                 'styleId' => $styleId));
                $tableId = $newNode->getTableId();
                $tableData =& Docxpresso\CreateDocument::$tables[$tableId] ;
                $tableData['html'] = true;
                //handle the case where the table has a border attribute
                //the following data will be used in the parsing of the 
                //td & th childs
                if (isset($style['table-border'])){ 
                    foreach($this->_sides as $side) {
                        $tableData['border-' . $side . '-width'] = 
                                $style['border-width'];
                        if (isset($style['border-top-color'])) {
                            $tableData['border-' . $side . '-color'] = 
                                $style['border-top-color'];
                        } else {
                            $tableData['border-' . $side . '-color'] = 
                                '#000000'; 
                        }
                    }
                }
                //there is no table equivalent of the border attribute for
                //tables so we have to store the table border information so it
                //is taken into account in the corresponding table cell 
                //properties
                if ($this->_borderIsSet($style)) {
                    $borderId = commons\Utilities::generateId('border_');
                    self::$tableBorders[$borderId] =& $style;
                    $rowNodes = $HTMLNode->getElementsByTagName('tr');
                    //we need to now the column count taking into account
                    //colspans
                    $colCount = 0;
                    $firstRowCols = $rowNodes->item(0)->childNodes;
                    foreach ($firstRowCols as $col) {
                        $colval = $col->getAttribute('colspan');
                        if (!empty($colval)) {
                            $colCount += $col->getAttribute('colspan');
                        } else {
                            $colCount++;
                        }
                    } 
                    $rowCount = $rowNodes->length;
                    $counterRow = 1;
                    foreach ($rowNodes as $rowNode) {
                        $colNodes = $rowNode->childNodes;
                        $counterCol = 1;
                        foreach ($colNodes as $colNode) {
                            $colNode->setAttribute('tableBorder', $borderId);
                            $colspan = $colNode->getAttribute('colspan');
                            if (empty($colspan)) {
                                $colspan = 1;
                            }
                            $rowspan = $colNode->getAttribute('rowspan');
                            if (empty($rowspan)) {
                                $rowspan = 1;
                            }    
                            if ($counterRow == 1) {
                                $colNode->setAttribute('firstRow', 1);
                            }
                            if ($counterRow == $rowCount
                                || ($counterRow + $rowspan) - 1 == $rowCount) {
                                $colNode->setAttribute('LastRow', 1);
                            }
                            if ($colNode->previousSibling === NULL) {
                                $colNode->setAttribute('firstCol', 1);
                            }
                            if ($colNode->nextSibling === NULL
                                && ($counterCol + $colspan) - 1 == $colCount) {
                                $colNode->setAttribute('lastCol', 1);
                            }
                            $counterCol++;
                        }
                        $counterRow++;
                    }
                }
            } else {
                $newNode = $ODFNode->table(array('grid' => 1,
                                                 'style' => $style,
                                                 'styleId' => $styleId))
                                   ->row()
                                   ->cell();
            }
        } else if(isset(self::$parseAsP[$tag])) {
            $newNode = $ODFNode->paragraph(array('style' => $style,
                                                 'styleId' => $styleId));
        } else if ($tag == 'a') {
            $href = $HTMLNode->getAttribute('href');
            $href = commons\Utilities::generateURL($this->_baseURL, $href);
            $title = $HTMLNode->getAttribute('title');
            $newNode = $ODFNode->link(array('url' => $href,
                                            'title' => $title,
                                            'style' => $style,
                                            'styleId' => $styleId,
                                            'reference' => true));
        } else if ($tag == 'br' || $tag == 'wbr') {
            $newNode = $ODFNode->lineBreak();
        } else if ($tag == 'button') {
            $newNode = $ODFNode->button(array('style' => $style,
                                              'styleId' => $styleId));
        } else if ($tag == 'chart') {
            $cId = $HTMLNode->getAttribute('chartid');
            $newNode = $ODFNode
                        ->XMLChart(Docxpresso\CreateDocument::$XMLCharts[$cId]);
        } else if ($tag == 'date') {
            $options = array();
            $type = 'date';
            $format = $HTMLNode->getAttribute('format');
            if (!empty($format)) {
               $regex = '/\'(.*?)\'/is';
               $matches = array();
               \preg_match_all($regex, $format, $matches);
               $options['date-format'] = $matches[1];
            }
            $newNode = $ODFNode->field($type, $options);
        } else if ($tag == 'endnote') {
            $newNode = $ODFNode->endnote(array('style' => $style,
                                               'styleId' => $styleId))
                                    ->paragraph(array('style' => $style,
                                                      'styleId' => $styleId));
        } else if ($tag == 'footnote') {
            $newNode = $ODFNode->footnote(array('style' => $style,
                                                'styleId' => $styleId));
        } else if ($tag == 'footer') {
            $newNode = $ODFNode->footer(array('style' => $style,
                                              'styleId' => $styleId));
        } else if ($tag == 'form') {
            $name = $HTMLNode->getAttribute('name');
            $id = $HTMLNode->getAttribute('id');
            if (empty($name) && !empty($id)){
                $name = $id;
            } else if (empty($name) && empty($id)) {
                $name = 'name_' . uniqid();
            }
            $action = $HTMLNode->getAttribute('action');
            $method = $HTMLNode->getAttribute('method');
            if (empty($method)) {
                $method = 'get';
            }
            $target = $HTMLNode->getAttribute('target');
            if (empty($target)) {
                $target = '_blank';
            }
            $styleCell = $style;
            $styleIdCell = commons\Utilities::generateId();
            $newNode = $ODFNode
                        ->table(array('style' => $style,
                                      'styleId' => $styleId))
                            ->row()
                                ->cell(array('style' => $styleCell,
                                             'styleId' => $styleIdCell))          
                                    ->form(array('name' => $name,
                                                'method' => $method,
                                                'target' => $target,
                                                'action' => $action,
                                                'style' => $style,
                                                'styleId' => $styleId));
        } else if ($tag == 'frame') {
            $newNode = $ODFNode->frame(array('style' => $style,
                                             'styleId' => $styleId));
        } else if ($tag == 'h1') {
            $newNode = $ODFNode->heading(array('level' => 1,
                                               'style' => $style,
                                               'styleId' => $styleId));
        } else if ($tag == 'h2') {
            $newNode = $ODFNode->heading(array('level' => 2,
                                               'style' => $style,
                                               'styleId' => $styleId));
        } else if ($tag == 'h3') {
            $newNode = $ODFNode->heading(array('level' => 3,
                                               'style' => $style,
                                               'styleId' => $styleId));
        } else if ($tag == 'h4') {
            $newNode = $ODFNode->heading(array('level' => 4,
                                               'style' => $style,
                                               'styleId' => $styleId));
        } else if ($tag == 'h5') {
            $newNode = $ODFNode->heading(array('level' => 5,
                                               'style' => $style,
                                               'styleId' => $styleId));
        } else if ($tag == 'h6') {
            $newNode = $ODFNode->heading(array('level' => 6,
                                               'style' => $style,
                                               'styleId' => $styleId));
        } else if ($tag == 'header') {
            $newNode = $ODFNode->header(array('style' => $style,
                                              'styleId' => $styleId));
        } else if ($tag == 'img') {
            $src = $HTMLNode->getAttribute('src');
            if (isset($options['src']) &&
                \substr($options['src'],0,10) == 'data:image') {
                $src = $options['src'];
            } else {
                $src = commons\Utilities::generateURL($this->_baseURL, $src);
            }
            $imageData = array('src' => $src,
                               'style' => $style,
                               'styleId' => $styleId);
            $attrTitle = $HTMLNode->getAttribute('title');
            if (!empty($attrTitle)) {
                $imageData['title'] = $HTMLNode->getAttribute('title');
            }
            $attrAlt = $HTMLNode->getAttribute('alt');
            if (!empty($attrAlt)) {
                $imageData['description'] = $HTMLNode->getAttribute('alt');
            }
            //we have now to unset the float and other positioning elements
            //that have been taking into accountwith a wrapping textbox
            unset($imageData['style']['float']);
            unset($imageData['style']['poistion']);
            unset($imageData['style']['top']);
            unset($imageData['style']['left']);
            unset($imageData['style']['right']);
            unset($imageData['style']['bottom']);
            $newNode = $ODFNode->image($imageData);
        } else if ($tag == 'input') {
            $name = $HTMLNode->getAttribute('name');
            $value = $HTMLNode->getAttribute('value');
            $type = $HTMLNode->getAttribute('type');
            $checked = $HTMLNode->getAttribute('checked');
            if(empty($type)) {
                $type = 'text';  
            }
            //the input height is not rescaled automatically with the size of 
            //the font so whenever there is no specific height property set we
            //have to make an "educated guess" regarding the height for text
            //inputs
            if (!isset($style['height']) 
                && isset($style['font-size'])
                && $type == 'text') {
                $style['height'][0] = $style['font-size'][0] * 1.559055;
                $style['height'][1] = $style['font-size'][1];
            }
            if(empty($checked)) {
                $checked = false;  
            } else {
                $checked = true;
            }
            if ($type == 'checkbox') {
                $newNode = $ODFNode->checkbox(array('name' => $name,
                                                    'value' => $value,
                                                    'checked' => $checked,
                                                    'style' => $style,
                                                    'styleId' => $styleId));
            } else if ($type == 'radio') {
                $newNode = $ODFNode->radioButton(array('name' => $name,
                                                       'value' => $value,
                                                       'selected' => $checked,
                                                       'style' => $style,
                                                       'styleId' => $styleId));
            } else if ($type == 'button' || $type == 'submit') {
                $newNode = $ODFNode->button(array('name' => $name,
                                                  'value' => $value,
                                                  'style' => $style,
                                                  'styleId' => $styleId));
            } else {
                $newNode = $ODFNode->inputField(array('name' => $name,
                                                      'value' => $value,
                                                      'style' => $style,
                                                      'styleId' => $styleId));
            }
        } else if ($tag == 'hr') {
            if (isset($style['height'])) {
                $style['font-size'] = $style['height'];
            }
            $newNode = $ODFNode->hr(array('style' => $style,
                                          'styleId' => $styleId));
        } else if ($tag == 'math') {
            $mId = $HTMLNode->getAttribute('mathid');
            $data = $this->_parseMath(Docxpresso\CreateDocument::$XMLMath[$mId]);
            $newNode = $ODFNode->math($data['formula'], 
                                      array('math-settings' => $data['config'],
                                            'style' => $data['style']));
        } else if ($tag == 'page') {
            $options = array();
            $type = 'page-number';
            $t = $HTMLNode->getAttribute('type');
            if (!empty($t)) {
               $type = 'page-' . $t; 
            }
            $format = $HTMLNode->getAttribute('format');
            if (!empty($format)) {
               $options['number-format'] = $format; 
            }
            $offset = $HTMLNode->getAttribute('offset');
            if (!empty($offset)) {
               $options['number-offset'] = $linked; 
            }
            $newNode = $ODFNode->field($type, $options);
        } else if ($tag == 'ref') {
            $type = 'bookmark-ref';
            $options = array();
            $options['reference-format'] = 'number';
            $format = $HTMLNode->getAttribute('format');
            if (!empty($format)) {
               $options['reference-format'] = $format; 
            }
            $options['reference-name'] = '#';
            $name = $HTMLNode->getAttribute('name');
            if (!empty($name)) {
               $options['reference-name'] = '#' . $name; 
            }
            $newNode = $ODFNode->field($type, $options);
        } else if ($tag == 'section') {
            $newNode = $ODFNode->section(array('style' => $style,
                                               'styleId' => $styleId));
        } else if ($tag == 'select') {
            //the select height is not rescaled automatically with the size of 
            //the font so whenever there is no specific height property set we
            //have to make an "educated guess" regarding the height
            if (!isset($style['height']) 
                && isset($style['font-size'])) {
                $style['height'][0] = $style['font-size'][0] * 1.559055;
                $style['height'][1] = $style['font-size'][1];
            }
            //we should now get the options of the select element to feed
            //the select method
            $items = array();
            $sel = '';
            $opts = $HTMLNode->getElementsByTagName('option');
            foreach($opts as $opt){
                $key = $opt->nodeValue;
                $value = $opt->getAttribute('value');
                $selected = $opt->getAttribute('selected');
                if (!empty($key)) {
                    if (!empty($value)) {
                        $items[$key] = $value;
                    } else {
                        $items[$key] = $key;
                    }
                }
                if (!empty($selected)) {
                   $sel = $key; 
                }
            }
            $newNode = $ODFNode->select(array( 'items' => $items,
                                               'selected' => $sel,
                                               'style' => $style,
                                               'styleId' => $styleId));
        } if ($tag == 'tab') {
            $options = array();
            $type = $HTMLNode->getAttribute('type');
            if (!empty($type)) {
               $options['type'] = $type; 
            }
            $character = $HTMLNode->getAttribute('character');
            if (!empty($character)) {
               $options['character'] = $character; 
            }
            $leader = $HTMLNode->getAttribute('leader');
            if (!empty($leader)) {
               $options['leader'] = $leader; 
            }
            $position = $HTMLNode->getAttribute('position');
            if (!empty($position)) {
               $options['position'] = $position; 
            }
            $newNode = $ODFNode->tab($options);
        } else if ($tag == 'td' || $tag == 'th') {
            //auxiliary array for border properties
            $bprops = array('width', 'style', 'color');
            //check for wrapping row borders
            $rowBorder = $HTMLNode->getAttribute('rowBorder');
            if (!empty($rowBorder)) {
                $br =& self::$rowBorders[$rowBorder];
                foreach ($bprops as $bprop) {
                    if (isset($br['border-top-' . $bprop])
                        && !isset($style['border-top-' . $bprop])) {
                        $style['border-top-' . $bprop] = 
                                $br['border-top-' . $bprop];
                    }
                    if (isset($br['border-bottom-' . $bprop])
                        && !isset($style['border-bottom-' . $bprop])) {
                        $style['border-bottom-' . $bprop] = 
                                $br['border-bottom-' . $bprop];
                    }
                    if ($HTMLNode->getAttribute('firstRowCol') == 1){
                        if (isset($br['border-left-' . $bprop])
                            && !isset($style['border-left-' . $bprop])) {
                            $style['border-left-' . $bprop] = 
                                    $br['border-left-' . $bprop];
                        }
                    }
                    if ($HTMLNode->getAttribute('lastRowCol') == 1){
                        if (isset($br['border-right-' . $bprop])
                            && !isset($style['border-right-' . $bprop])) {
                            $style['border-right-' . $bprop] = 
                                    $br['border-right-' . $bprop];
                        }
                    }
                }
            }
            //check for wrapping table borders
            $tableBorder = $HTMLNode->getAttribute('tableBorder');
            if (!empty($tableBorder)) {
                $bt =& self::$tableBorders[$tableBorder];
                foreach ($bprops as $bprop) {
                    if ($HTMLNode->getAttribute('firstRow') == 1){
                        if (isset($bt['border-top-' . $bprop])
                            && !isset($style['border-top-' . $bprop])) {
                            $style['border-top-' . $bprop] = 
                                    $bt['border-top-' . $bprop];
                        }
                    }
                    if ($HTMLNode->getAttribute('LastRow') == 1){
                        if (isset($bt['border-bottom-' . $bprop])
                            && !isset($style['border-bottom-' . $bprop])) {
                            $style['border-bottom-' . $bprop] = 
                                    $bt['border-bottom-' . $bprop];
                        }
                    }
                    if ($HTMLNode->getAttribute('firstCol') == 1){
                        if (isset($bt['border-left-' . $bprop])
                            && !isset($style['border-left-' . $bprop])) {
                            $style['border-left-' . $bprop] = 
                                    $bt['border-left-' . $bprop];
                        }
                    }
                    if ($HTMLNode->getAttribute('lastCol') == 1){
                        if (isset($bt['border-right-' . $bprop])
                            && !isset($style['border-right-' . $bprop])) {
                            $style['border-right-' . $bprop] = 
                                    $bt['border-right-' . $bprop];
                        }
                    }
                }
            }
            //check for global border table properties
            $tableId = $ODFNode->getTableId();
            $tData =& Docxpresso\CreateDocument::$tables[$tableId];
            if (isset($tData['border-width'])) {
                $style = \array_merge($tData, $style);
            }
            unset($style['html']);
            unset($style['structure']);
            //manage col and row spans
            if (isset($style['colspan'])) {
                $colspan = $style['colspan'];
            } else {
                $colspan = 1;
            }
            if (isset($style['rowspan'])) {
                $rowspan = $style['rowspan'];
            } else {
                $rowspan = 1;
            }
            $newNode = $ODFNode->cell(array('colspan' => $colspan,
                                            'rowspan' => $rowspan,
                                            'style' => $style,
                                            'styleId' => $styleId));
            //update the $tableData array to include the required grid data
            $curCol = $newNode->getColumn();
            $curRow = $newNode->getRow();
            if ($curRow == 0) {
                //populate the grid array from the first row
                if (isset($style['width'])){
                    $cW = \round($style['width'][0]/$colspan) . 
                          $style['width'][1];
                } else {
                    $cW = NULL;
                }
                for ($j = 0; $j < $colspan; $j++) {
                    $tData['grid'][$curCol + $j] = $cW;
                    $tData['span'][$curCol + $j] = $colspan;
                }
            } else {
                //modify the grid array if previous widths have not been set
                //or the width variable has higher specificity
                if (isset($style['width'])) {
                    $cW = \round($style['width'][0]/$colspan) . 
                          $style['width'][1];
                    for ($j = 0; $j < $colspan; $j++) {
                        if ($tData['grid'][$curCol + $j] === NULL
                            || $tData['span'][$curCol + $j] > $colspan) {
                            $tData['grid'][$curCol + $j] = $cW;
                            $tData['span'][$curCol + $j] = $colspan;
                        }
                    }
                }  
            }     
        } else if ($tag == 'textarea') {
            $name = $HTMLNode->getAttribute('name');
            $value = $HTMLNode->nodeValue;
            $newNode = $ODFNode->textArea(array('name' => $name,
                                                'value' => $value,
                                                'scroll' => true,
                                                'style' => $style,
                                                'styleId' => $styleId));
        } else if ($tag == 'toc') {
            $options = array();
            $title = $HTMLNode->getAttribute('title');
            if (!empty($title)) {
               $options['title'] = $title; 
            }
            $leader = $HTMLNode->getAttribute('leader');
            if (!empty($leader)) {
               $options['leader-char'] = $leader; 
            }
            $linked = $HTMLNode->getAttribute('linked');
            if (!empty($linked)) {
               $options['linked'] = $linked; 
            }
            $level = $HTMLNode->getAttribute('level');
            if (!empty($level)) {
               $options['outline-level'] = $level; 
            }
            $outlines = $HTMLNode->getElementsByTagName('outline');
            if ($outlines->length > 0){
               $options['style'] = array();
               foreach ($outlines as $outline) {
                   $level = $outline->getAttribute('level');
                   $CSSStyle = $outline->getAttribute('style');
                   $options['style'][$level] = $CSSStyle;
               }
            }
            $newNode = $ODFNode->toc($options);
        } else if ($tag == 'tr') {
            //check if it is a header type row
            $header = false;
            $cellNodes = $HTMLNode->childNodes;
            foreach ($cellNodes as $child) {
                if ($child->nodeName == 'th') {
                    $header = true;
                    break;
                }
            }
            $newNode = $ODFNode->row(array('header' => $header,
                                           'style' => $style,
                                           'styleId' => $styleId));
            //there is no row equivalent of the border attribute for
            //table rows so we have to store the row border information so it
            //is taken into account in the corresponding table cell 
            //properties
            if ($this->_borderIsSet($style)) {
                $borderRowId = commons\Utilities::generateId('borderRow_');
                self::$rowBorders[$borderRowId] =& $style;
                $colNodes = $HTMLNode->childNodes;
                foreach ($colNodes as $colNode) {
                    $colNode->setAttribute('rowBorder', $borderRowId);
                    if ($colNode->previousSibling === NULL) {
                        $colNode->setAttribute('firstRowCol', 1);
                    }
                    if ($colNode->nextSibling === NULL) {
                        $colNode->setAttribute('lastRowCol', 1);
                    }
                }
            }
        } else if ($tag == 'ol') {
            $newNode = $ODFNode->orderedList(array('style' => $style,
                                                  'styleId' => $styleId));
        } else if ($tag == 'ul' || $tag == 'menu') {
            $newNode = $ODFNode->unorderedList(array('style' => $style,
                                                     'styleId' => $styleId));
        } else if ($tag == 'li' || $tag == 'menuitem') {
            $newNode = $ODFNode->listItem()
                               ->paragraph(array('style' => $style,
                                                 'styleId' => $styleId));
        }
        //In case the tag has an id attribute include a bookmark for further
        //reference
        $idAttribute = $HTMLNode->getAttribute('id');
        if (!empty($idAttribute)) {
            $name = $idAttribute;
            if ($newNode != '') {//make sure that the node has been parsed
                $newNode = $newNode->bookmark(array('name' => $name), false);
            }
        }

        if ($newNode != '') {
            return $newNode;
        }else {
            return $ODFNode;
        }
    }
    
    /**
     * This method returns an array of styles with all the cascading 
     * taken already into account
     *
     * @param array $styles
     * @return array
     * @access private
     */
    private function _flattenStyles($styles)
    {
        $result = array();
        if (isset($styles['properties'])) {
            foreach ($styles['properties'] as $key => $value) {
                $result[$key] = $value;
            }
        }
        //overwrite normal props with the important props if any
        if (isset($styles['importantProps'])) {
            foreach ($styles['importantProps'] as $key => $value) {
                $result[$key] = $value;
            }
        }
        return $result;
    }
    
    /**
     * This method returns an array of selected frame styles extracted from a
     * general style array
     *
     * @param array $style
     * @return array
     * @access private
     */
    private function _generateFrameStyle($style)
    {
        $parsedProps = array(
            'float'         => true,
            'position'      => true,
            'top'           => true,
            'left'          => true,
            'width'         => true,
            'height'        => true,
            'min-width'     => true,
            'min-height'    => true,
        );
        
        $props = array();
        foreach ($parsedProps as $key => $value) {
            if (isset($style[$key])) {
                $props[$key] = $style[$key];
            }
        }
        //set the border explicitely to none because OPenOffice adds a border
        //by default
        $props['border-top-style'] = 'none';
        $props['border-right-style'] = 'none';
        $props['border-bottom-style'] = 'none';
        $props['border-left-style'] = 'none';
        return $props;
    }
    
    /**
     * This method merges the different arrays of styles taking into account:
     *  1. the order in which they are loaded
     *  2. the specificity of the selector
     *  3. if they have been set as !important
     *  4. if the property is 'inheritable' 
     *
     * @param array $styles
     * @param boolean $inherited
     * @return array
     * @access private
     */
    private function _mergeStyles($styles)
    {
        $preStyles = array();
        foreach($styles as $id => $inherited){
            //first go over the normal properties taking care of the specificity
            if (!$inherited && !empty($id)) {
                foreach ($this->_CSSProperties[$id]['properties'] as 
                         $key => $value){
                   if (isset($preStyles['properties'][$key])) { 
                       //check specificity
                       if ($this->_CSSProperties[$id]['specificity'] >=
                           $preStyles['specificity']) {
                            $preStyles['properties'][$key] =  $value;
                            $preStyles['specificity'] = 
                            $this->_CSSProperties[$id]['specificity'];
                       }

                   } else {
                        $preStyles['properties'][$key] =  $value;
                        $preStyles['specificity'] = 
                        $this->_CSSProperties[$id]['specificity'];     
                   }
                }
                //run over the important properties
                foreach ($this->_CSSProperties[$id]['importantProps'] as 
                         $key => $value){
                    $preStyles['importantProps'][$key] =  $value;
                }
            } else if (!empty($id)){
                //if $inherited is true we have to further check that the
                //CSS property is inheritable
                foreach ($this->_CSSProperties[$id]['properties'] as 
                         $key => $value){
                    if (isset(css\Resources::$inheritedCSSProperties[$key])) {
                        if (isset($preStyles['properties'][$key])) { 
                            //check specificity
                            if ($this->_CSSProperties[$id]['specificity'] >=
                                $preStyles['specificity']) {
                                $preStyles['properties'][$key] =  $value;
                                $preStyles['specificity'] = 
                                $this->_CSSProperties[$id]['specificity'];
                            }

                        } else {
                            $preStyles['properties'][$key] =  $value;
                            $preStyles['specificity'] = 
                            $this->_CSSProperties[$id]['specificity'];     
                        }
                    }
                }
                //run over the important properties
                foreach ($this->_CSSProperties[$id]['importantProps'] as 
                         $key => $value){
                    if (isset(css\Resources::$inheritedCSSProperties[$key])) {
                        $preStyles['importantProps'][$key] =  $value;
                    }
                }
            }
        }
        return $preStyles;
    }

}