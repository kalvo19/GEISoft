<?php

/**
 * Docxpresso main class
 *
 * @copyright  Copyright (c) 2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    2.0
 * @since      1.0
 */

namespace Docxpresso;

use Docxpresso\Commons as commons;
use Docxpresso\Core as core;
use Docxpresso\Core\Elements as elements;
use Docxpresso\Parser as parser;
use Docxpresso\Core\Charts as chart;
use Docxpresso\Templates as templates;
use Docxpresso\Themes as themes;
use Docxpresso\ODF2HTML5 as Odf2html5;

require_once dirname(__FILE__) . '/classes/AutoLoader.inc';
AutoLoader::load();

/**
 * creates the document
 *
 * @package    Docxpresso
 */

class CreateDocument
{
    /**
     * This array holds all the required style information
     * 
     * @var array
     * @access public
     * @static
     */
    public static $automaticStyles;
    
    /**
     * The base font size that is used to compute relative units
     * 
     * @var array
     * @access public
     * @static
     */
    public static $baseFontSize;
    
    /**
     * holds the info about an "open current break"
     * it may be: none, line, page or column
     * @var string
     * @access public
     * @static
     */
    public static $break;
    
    /**
     * this array holds all the required chart info
     * 
     * @var array
     * @access public
     * @static
     */
    public static $charts;
    
    /**
     * this array holds the data parsed from the config.ini file
     * 
     * @var array
     * @access public
     * @static
     */
    public static $config;
    
    /**
     * keeps a reference to $this
     * 
     * @var CreateDocument
     * @access public
     * @static
     */
    public static $currentDocument;
    
    /**
     * endnote counter
     * 
     * @var int
     * @access public
     * @static
     */
    public static $currentEndnote;
    
    /**
     * footnote counter
     * 
     * @var int
     * @access public
     * @static
     */
    public static $currentFootnote;
    
    /**
     * reference to the current "open form"
     * 
     * @var string
     * @access public
     * @static
     */
    public static $currentForm;
    
    /**
     * Specifies if the used template is a custom one or not
     * 
     * @var boolean
     * @access public
     * @static
     */
    public static $customTemplate;
    
    /**
     * output format
     * it may be pdf, odt, doc, docx or rtf
     * 
     * @var string
     * @access public
     * @static
     */
    public static $format = 'pdf';
    
    /**
     * stores the document forms ids
     * @var array
     * @access public
     * @static
     */
    public static $forms;
    
    /**
     * section counter
     * 
     * @var int
     * @access public
     * @static
     */
    public static $counter;
    
    /**
     * if true the fields should be enforced to be updated
     * 
     * @var boolean
     * @access public
     * @static
     */
    public static $fields;
    
    /**
     * stores the lists ids
     * 
     * @var array
     * @access public
     * @static
     */
    public static $list;
    
    /**
     * manifest document element
     * 
     * @var DOMNode
     * @access public
     */
    public $manifest;
    
    /**
     * stores the info about math formulas
     * 
     * @var array
     * @access public
     * @static
     */
    public static $math;
    
    /**
     * stores the info about math settings for each equation
     * 
     * @var array
     * @access public
     * @static
     */
    public static $mathSettings;
    
    /**
     * stores the information about master styles
     * 
     * @var array
     * @access public
     * @static
     */
    public static $masterStyles;
    
    /**
     * stores the information about inserted images
     * 
     * @var array
     * @access public
     * @static
     */
    public static $media;
    
    /**
     * This "array" stores the ODFElement associated with a DOMNode
     * The DMONode is the "key" and the ODFElement is the $value
     * 
     * @var splObjectStorage
     * @access public
     * @static
     */
    public static $nodeMap;
    
    /**
     * This array keeps track of how many times a given clonable block
     * appears in a document
     * 
     * @var array
     * @access public
     * @static
     */
    public static $parsedClonableBlocks;
    
    /**
     * stores the template placeholder variables
     * 
     * @var array
     * @access public
     * @static
     */
    public static $placeholderVariables;
    
    /**
     * store the section ids
     * 
     * @var array
     * @access public
     * @static
     */
    public static $sectionIds;
    
    /**
     * stores the style info for footers
     * 
     * @var array
     * @access public
     * @static
     */
    public static $styleFooter;
    
    /**
     * stores the style info for headers
     * 
     * @var array
     * @access public
     * @static
     */
    public static $styleHeader;
    
    /**
     * stores the target document for each style
     * it can be: document, header-footer or style
     * @var array
     * @access public
     * @static
     */
    public static $styleTarget;
    
    /**
     * stores the style type
     * possible values include: paragraph, text, table, ...
     * @var array
     * @access public
     * @static
     */
    public static $styleTypes;
    
    /**
     * 
     * This array holds all the required structural info of tha tabs inserted
     * into the document
     * 
     * @var array
     * @access public
     * @static
     */
    public static $tabStops;
    
    /**
     * This array holds all the required structural info of tha tables inserted
     * into the document
     * 
     * @var array
     * @access public
     * @static
     */
    public static $tables;
    
    /**
     * the name of the current theme if any
     * @var string
     * @access public
     * @static
     */
    public static $theme;
    
    /**
     * specifies if the document contains a TOC or not
     * 
     * @var boolean
     * @access public
     * @static
     */
    public static $toc;
    
    /**
     * unique id
     * 
     * @var string
     * @access public
     * @static
     */
    public static $unid;
    
    /**
     * specifies if the document contains fields that need to be updated
     * 
     * @var array
     * @access public
     * @static
     */
    public static $updateFields;
    
    /**
     * specifies if the document contains math equations that need to be updated
     * 
     * @var array
     * @access public
     * @static
     */
    public static $updateMath;
    
    /**
     * hold the ids of the parsed extended HTML5 <math> tag
     * 
     * @var array
     * @access public
     * @static
     */
    public static $XMLMath;
    
    /**
     * stores all the structural chart data
     * 
     * @var array
     * @access public
     * @static
     */
    public static $XMLCharts;
    
    /**
     * holds the document element as an ODFElement
     * @var ODFElement
     * @access public
     */
    public $contentODF;
    
    /**
     * holds all the XMLs and media of the document zip file
     * @var array
     * @access public
     */
    public $template;
    
    /**
     * holds all the DOMDocument instances of the XMLs of the document
     * @var array
     * @access private
     */
    private $_dom;
    
    /**
     * holds a reference to an instance of the styleProcessor class
     * @var StyleProcessor
     * @access private
     */
    private $_styleProcessor;
    
    /**
     * Construct
     *
     * @param array $params the posible keys and values are:
     *  'template' => (string) path to the template we wish to use
     *  'base-font-size' => (int) The default document font size (in points) 
     *   that will be used for the computation of relative units (em, rem). 
     *   The default value can be globally set in the config.ini file
     * @access public
     */
    public function __construct($params = array())
    {
        //initialize certain required variables
        self::$automaticStyles = array();
        self::$break = 'none';
        self::$counter = 1;
        self::$currentDocument = $this;
        self::$currentEndnote = 0;
        self::$currentFootnote = 0;
        self::$currentForm = '';
        self::$list = array();
        self::$masterStyles = array();
        self::$media = array();
        self::$nodeMap = new \splObjectStorage();
        self::$charts = array();
        self::$math = array();
        self::$mathSettings = array();
        self::$fields = false;
        self::$forms = array();
        self::$placeholderVariables = array();
        self::$sectionIds = array();
        self::$styleFooter = array();
        self::$styleHeader = array();
        self::$styleTypes = array();
        self::$styleTarget = array();
        self::$tabStops = array();
        self::$tables = array();
        self::$toc = false;
        self::$unid = 's' . substr(sha1(rand(999, 9999999)), 3, 9);
        self::$updateFields = array();
        self::$updateMath = array();
        self::$XMLMath = array();
        self::$XMLCharts = array();
        self::$theme = '';
        //parse the config.ini file
        self::$config = \parse_ini_file( 'config.ini', true); 
        //extract the relevant data from the template
        if (isset($params['template'])) {
           $path = $params['template'];
           self::$customTemplate = true;
           $extension = substr($path, -4);
           if ($path == 'default_A3'
               || $path == 'default_A3_landscape'
               || $path == 'default_letter'
               || $path == 'default_letter_landscape'
               || $path == 'default_legal'
               || $path == 'default_legal_landscape') {
               self::$sectionIds[] = 'section_01';
           }
        } else {
           $path = dirname(__FILE__);
           $path .= '/' . self::$config['layout']['template'];
           self::$customTemplate = false; 
           self::$sectionIds[] = 'section_01';
        }
        //set the base font size
        if (isset($params['base-font-size'])) {
            self::$baseFontSize = $params['base-font-size'];
        } else {
            self::$baseFontSize = self::$config['layout']['baseFontSize'];
        }
        //extract into memory all files from the template
        $template = new \ZipArchive();
        try {
            $openZip = $template->open($path);
            if ($openZip !== true) {
                throw new \Exception('Error while trying to open the template');
            }
        } catch (\Exception $e) {
            commons\DOCXPRESSOLogger::logger($e->getMessage(), 'critical');
        }
        $this->template = self::extractTemplateFiles($template);
        $template->close();
        //process the relevant files
        //content.xml
        $this->_dom['content.xml'] = new \DOMDocument();
        $this->_dom['content.xml']->loadXML($this->template['content.xml']);
        //style.xml
        $this->_dom['styles.xml'] = new \DOMDocument();
        $this->_dom['styles.xml']->loadXML($this->template['styles.xml']);
        //META-INF/manifest.xml
        $this->_dom['META-INF/manifest.xml'] = new \DOMDocument();
        $this->_dom['META-INF/manifest.xml']
             ->loadXML($this->template['META-INF/manifest.xml']);  
        //get the relevant nodes for processing
        $ns = 'urn:oasis:names:tc:opendocument:xmlns:office:1.0';
        if (self::$customTemplate && $extension == '.ods') {
            $this->content = $this->_dom['content.xml']
                                  ->getElementsByTagNameNS($ns, 'spreadsheet')
                                  ->item(0);
        } else {
            $this->content = $this->_dom['content.xml']
                                  ->getElementsByTagNameNS($ns, 'text')
                                  ->item(0);
        }
        //locate the cursor at the end of the document
        $this->setCursor($this->content);
        $this->contentODF = new elements\ODFElement($this->content);
        $this->_styles = $this->_dom['content.xml']
                             ->getElementsByTagNameNS($ns, 'automatic-styles')
                             ->item(0);
        $this->fonts = $this->_dom['styles.xml']
                            ->getElementsByTagNameNS($ns, 'font-face-decls')
                            ->item(0);
        $this->_mainStyles = $this->_dom['styles.xml']
                            ->getElementsByTagNameNS($ns, 'automatic-styles')
                            ->item(0);
        $this->_styleStyles = $this->_dom['styles.xml']
                            ->getElementsByTagNameNS($ns, 'styles')
                            ->item(0);
        $ns = 'urn:oasis:names:tc:opendocument:xmlns:manifest:1.0';
        $this->manifest = $this->_dom['META-INF/manifest.xml']
                               ->getElementsByTagNameNS($ns, 'manifest')
                               ->item(0);
        //create an instance of the StyleProcessor class
        $this->_styleProccesor = new parser\StyleProcessor($this->_styles,
                                                           $this->_mainStyles,
                                                           $this->_styleStyles);
        if (self::$customTemplate) {
            $this->_processTemplate();
        }

    }
    
    /* Setters and getters */
    
    /**
     * returns the array that holds reference to the associated 
     * DOMDocument objects (content, styles, metas, manifest)
     * 
     * @return array
     * @access public
     */
    public function getDOM() 
    {
        return $this->_dom;
    }
    
    /**
     * adds border information to a style
     * 
     * @param string $style (passed by reference)
     * @param string $border
     * @return void
     * @access public
     * @static
     */
    public static function addBorder(&$style, $border) 
    {        
        if (\is_string($style) && \strpos($style, 'theme:default') !==false) {
           $style = \str_replace('theme:default', 
                                 ';' . $border . 'theme:default',
                                 $style);
       } else if (\is_string(\is_string($style))) {
           $style = $border . $style;
       }
    }
    
    /**
     * adds a bookmark
     * If we try to insert directly a bookmark into the document 
     * a wrapping paragraph  with default styles will be automatically created
     * 
     * @param array $options with the following keys and values
     *      'name' => (string) the bookmark name needed for cross references
     *      'text' => (string)  the text to insert (can be empty)
     *      'style' => (string) a list of properties in CSS format
     * @param bool $reference if true returns a reference to the wrapping 
     * paragraph element so we can keep nesting. The default value is false, 
     * i.e. no nested element
     * 
     * @return ODFElement
     * @access public
     */
    public function bookmark($options, $reference = false) 
    {
        $element = $this->contentODF
                        ->paragraph(array(), $reference)
                        ->bookmark($options, false);//no nesting within bookmark
        if ($reference) {
            return $element;
        } 
    }
    
    /**
     * adds a button associated with a form within the document
     * 
     * @param array $options with the following keys and values
     *      'form-name' => (string) the name of the form where we want to insert 
     *      this control
     *      'name' => (string) the control name
     *      'value' => (string) the associated value
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     * @param bool $reference if true returns a reference to the frame element
     * so further elements can be nested. The default value is true.
     * 
     * @return ODFElement
     * @access public
     */
    public function button($options, $reference = true) 
    {
        if (!isset($options['form-name'])) {
            $options['form-name'] = self::$currentForm;
        }
        $element = $this->contentODF->button($options, $reference);
        if ($reference) {
            return $element;
        }
    }
    
    /**
     * adds a chart to the document
     * The charts can be inserted inline (default) or floating to the left,
     * center or right
     * 
     * @param string $type the available types are:
     *  2D: column, bar, pie, donut, area, line, scatter, bubble, radar, 
     *  filled-radar, column-line
     *  3D: 3Dcolumn, 3Dbar, 3Dpie, 3Ddonut, 3Darea, 3Dline, 3Dscatter
     * @param array $options with the following keys and values
     *      'data' => (array) it can be an array with different formats:
     *          pie and donuts charts:
     *              array( 'category_1' => 3,
     *                     'category_2' => 5,
     *                     'category_3' => 4.3,
     *                    )
     *          bar, column, area , line, scatter, (filled-)radar 
     *          and column-line charts:
     *              array( 'series' => array('ser_1', 'ser_2'), 
     *                     'category_1' => array(20,40), 
     *                     'category_2' => array(30,10),
     *                     'category_3' => array(12.5, 54),
     *                    )
     *          buble charts
     *              array( array(2, 5 7), 
     *                     array(4.3, 12, 3.5), 
     *                     array(6, 3, 5),
     *                    )
     *      'chart-properties' => (array) with keys and values:
     *          'data-label-number' => (string) none, value or percentage.
     *          'label-position' => (string) avoid-overlap, center,
     *           top, top-right, right, bottom-right, bottom, bottom-left, left,
     *           top-left, inside, outside or near-origin.
     *          'label-position-negative' => (string) it only applies if 
     *           the value is negative. If not given the value for 
     *           data-label-position will be used
     *           top-left, inside, outside or near-origin.
     *          'hole-size' => (int) specifies the diameter of the inner hole 
     *           of a ring chart as percentage of the outer diameter of the 
     *           outermost ring.
     *          'pie-offset' => (int) specifies the distance of a segment from 
     *           the center of the circle in case of circle charts. The offset 
     *           is given as an integer which is interpreted as a percentage of 
     *           the radius of the circle. In case of ring charts specifies an 
     *           additional distance of a segment from the center of the circle. 
     *           The distance is given as percentage of the thickness of the 
     *           ring.
     *          'angle-offset' => (int) it specifies in degrees a counter 
     *           clockwise rotation of a polar coordinate in a circle, ring or
     *           polar chart.
     *          'stacked' => (boolean) default value is false. it specifies the 
     *           accumulation of the series values per category. Each value is 
     *           in addition to the other values in the same category.
     *          'chart-interpolation' => (string) none (if points are to be
     *           connected by a straight line, b-spline or cubic-spline.
     *          'spline-resolution' => (int) a positive inetger. It only applies
     *           if the chart-interpolation option is not equal to none.
     *          'deep' => (boolean) if true the series will be shown
     *           in 3D one behind the other and not side by side. It only 
     *           applies to 3D charts.
     *          'solid-type' => (string) cuboid (default), cylinder, cone or 
     *           pyramid. It only applies to 3D bar charts.
     *      'style' => (string) a list of properties in CSS format that only
     *       set the global properties of the chart. In order to customize the
     *       different chart components you should use the associated
     *       chart methods.
     * @return ODFElement
     * @access public
     */
    public function chart($type, $options = array()) 
    {
        $element = $this->contentODF->paragraph()->chart($type, $options);
        return $element;
    }
    
    /**
     * inserts a chart legend into a chart. It can not be directly used on a 
     * document
     * 
     * @return ODFElement
     * @access public
     */
    public function chartLegend() 
    {
        //one can only include a chart legend within a chart
        $message = 'You can only add a legend to a chart.';
        commons\DOCXPRESSOLogger::logger($message, 'info');
        return $this;
    }
    
    /**
     * inserts a chart title into a chart. It can not be directly used on a 
     * document
     * 
     * @return ODFElement
     * @access public
     */
    public function chartTitle() 
    {
        //one can only include a chart title within a chart
        $message = 'You can only add a chart title to a chart.';
        commons\DOCXPRESSOLogger::logger($message, 'info');
        return $this;
    }
    
    /**
     * clones the chosen content and replicates it the requested number of times 
     * in the chosen location
     * 
     * @param array $options an array with the following keys and values:
     *  'needle' => (string) the text to be searched in order to select the 
     *   content to be cloned or the bookmark name if the element option is set
     *   to be equal to bookmark.
     *  'element' => (string) the type of element to be cloned. possible values 
     *   are: paragraph (default), list, list-item, table, table-row, image, 
     *   chart, heading, section and bookmark. 
     *  'match' => (int) if there is more than one resulting element its order 
     *   of ocurrence. The default is 1 (first match).
     *  'repeat' => (int) The number of times that the cloned element will be 
     *   repeated. Deafault is 1.
     *  'position' => (mixed) specifies were the clone nodes should be
     *   inserted. Possible values are a string:
     *      self: (default) just before the chosen element 
     *      cursor: just before the current cursor location 
     *   or a DOMNode (the cloned nodes will be inserted before it).
     *   In the case of list-element and table-row the last option
     *   will be ignored.
     *  'heading-level' => (int) only applies if 'heading' is the chosen element
     *   value. Default value is 1.
     *  'container' => (bool) if true (default is false) the container paragraph
     *   (if any) will be cloned in the case of charts, images and textboxes.
     *  'target' => (string) it can be document (default), header or footer.
     * @return void
     * @access public
     */
    public function cloneContent($options) 
    {
        $clonable = array ('paragraph'     => true,
                           'heading'       => true,
                           'list'          => true,
                           'list-item'     => true,
                           'table'         => true,
                           'table-row'     => true,
                           'textbox'       => true,
                           'section'       => true,
                           'chart'         => true,
                           'image'         => true,
                           'bookmark'      => true,
                           );
        
        //preset values
        if (empty($options['element'])) {
            $options['element'] = 'paragraph';
        }
        if (empty($options['match'])) {
            $options['match'] = 1;
        }
        if (!isset($options['repeat'])) {
            $options['repeat'] = 1;
        } else if ($options['repeat'] == 0){
            return;
        }
        if (empty($options['position'])) {
            $options['position'] = 'self';
        }
        if (empty($options['target'])) {
            $options['target'] = 'document';
        }
        //initiate the cloning
        if (!isset($clonable[$options['element']])) {
            $message = 'You can not clone that type of element.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
        } else if (!isset($options['needle'])){
            $message = 'The needle can not be empty.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
        } else {
            if ($options['target'] == 'document'){
                $options['target'] = $this->_dom['content.xml'];
            } else {
                $options['target'] = $this->_dom['styles.xml'];
            }
            $options['manifest'] = $this->_dom['META-INF/manifest.xml'];
            $options['template'] = '';
            $options['template'] =& $this->template;
            $clone = new templates\Cloner($options);
        }
        return $this;
    }
    
    /**
     * removes the chosen content from the document
     * 
     * @param array $options an array with the following keys and values:
     *  'needle' => (string) the text to be searched to select the content
     *   to be removed or the bookmark name if the element option is set
     *   to be equal to bookmark.
     *  'element' => (string) the type of element to be removed. possible values 
     *   are: 
     *      text: (default)the text given in the needle is removed from the 
     *      document, 
     *      bookmark: the contents bookmarked with the needle
     *      paragraph: the paragraphs that contain the needle text are removed,
     *      list: the lists that contain the needle text are removed,
     *      list-item: the list items that contain the needle text are removed,
     *      table: the table that contains the needle text is removed unless the
     *      text fall within a subtable,
     *      table-row: the same that for table but just for a table row
     *      image: the images which title include the needle text are removed, 
     *      chart: the charts which title description include the given text 
     *      are removed,
     *      section: all section content of a section that contains the needle
     *      text is removed,
     *      textbox: all textbox content of a textbox that contains the needle
     *      text is removed,
     *      heading: all the contents that fall under a given heading are
     *      removed whenever the heading contains the needle text
     *  'heading-level' => (int) only applies if 'heading' is the chosen element
     *   value. Default value is 1.
     *  'match' => (mixed) if an integer (defining its order of appareance) only
     *   that match will be removed if 'all'(default value)all the matches will
     *   be removed.
     *  'container' => (bool) if true (default is false) the container paragraph
     *  (if any) will be removed in the case of charts, images and textboxes.
     *  'target' => (string) possible values are: document (default), header or
     *   footer
     * @return CreateDocument
     * @access public
     */
    public function removeContent($options) 
    {
        $removable = array ('text'          => true,
                            'heading'       => true,
                            'paragraph'     => true,
                            'list'          => true,
                            'list-item'     => true,
                            'table'         => true,
                            'table-row'     => true,
                            'textbox'       => true,
                            'section'       => true,
                            'chart'         => true,
                            'image'         => true,
                            'bookmark'      => true,
                            );
        
        if (empty($options['element'])) {
            $options['element'] = 'text';
        }
        if (!isset($removable[$options['element']])) {
            $message = 'You can not remove that type of element.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
        } else if (!isset($options['needle'])){
            $message = 'The needle can not be empty.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
        } else {
            if (isset($options['target'])
                && ($options['target'] == 'header'
                    || $options['target'] == 'footer')){
                $target = $this->_dom['styles.xml'];
            }else {
                $target = $this->_dom['content.xml'];
            }
            $remove = new templates\Remove($target, $options);
        }
        return $this;
    }
    
    /**
     * This method removes the customCursor element
     * 
     * @param DOMNode $node
     * @return void
     * @access public
     */
    public function removeCursor($node = NULL)
    {
        if ($node == NULL) {
            $node = $this->_dom['content.xml']->documentElement;
        }
        $cursors = $node->getElementsBytagName('customCursor');
        if ($cursors->length > 0) {
            //there can only be, by construction a cursor element
            $cursor = $cursors->item(0);
            $cursor->parentNode->removeChild($cursor);
        }
    }
    
    /**
     * replaces the chosen template content with the given content
     * 
     * @param array $vars an array with keys the name of the variables and
     *  values:
     *      'value' => (mixed) it can be:
     *          1. A string or DocumentFragment object
     *          2. An array with entries that are themselves 
     *             strings or DocumentFragment objects
     *      'html' => (bool) if true (default) detects if the content is plain
     *       text or a HTML code. It overwrites the global html option if any.
     *      'parse-line-breaks' => (bool) if true parses line breaks. If the
     *       method detects that the string is HTML the default is false,
     *       otherwise defaults to true. It overwrites the global  
     *       parse-line-breaks option if any.
     *      'block-type' => (bool) if true removes and replaces the containing
     *       paragraph. Default is false. It overwrites the global block-type 
     *       option if any.
     *      'image' => (bool) set only to true if the variable name is
     *       associated with the title of a placeholder image. Default is false.
     *       It overwrites the element option if any.
     *      'match' => (int) if given and if the 'value' option is not an array
     *       only the selected match will be replaced, i.e. if match equals 1
     *       the first appearance of the variable will be replaced and the 
     *       others will be ignored. It overwrites the global match option 
     *       if any.
     *      'width' => (string) an optional new width for image replacement. The
     *       format must be like in CSS, i.e. 100px or 2cm, 3in, etcetera.
     *      'height' => (string) an optional new height for image replacement.
     *       The format must be like in CSS, i.e. 100px or 2cm, 3in, etcetera.
     * @param array $options an array with the following keys and values:
     *  'format' => (array) an array with two entries giving the chosen
     *   openning and closing symbol for template variables. The default value
     *   is array('{{', '}}') but any other combination of symbols is possible, 
     *   even array('', ''), although this is not recommended unless strictly
     *   necessary.
     *  'element' => (string) the element level at which we want to
     *   carry out the replacement. possible values are: text (default), 
     *   paragraph, list, table or image.
     *  'target' => (string) it can be document (default), header or footer.
     *  'html' => (bool) if true (default) detects if the content is plain
     *   text or a HTML code. It may be overwritten for a single value in the
     *   $vars array.
     *  'parse-line-breaks' => (bool) if true parses line breaks. If the
     *   method detects that the string is HTML the default is false,
     *   otherwise defaults to true. It may be overwritten for a single value 
     *   in the $vars array.
     *  'block-type' => (bool) if true removes and replaces the containing
     *   paragraph. Default is false.  It may be overwritten for a single value 
     *   in the $vars array.
     *  'image' => (bool) set only to true if the variable name is
     *   associated with the title of a placeholder image. Default is false.
     *   It overwrites the element option if any.
     *  'match' => (int) if given and if the 'value' option is not an array
     *   only the selected match will be replaced, i.e. if match equals 1
     *   the first appearance of the variable will be replaced and the 
     *   others will be ignored. It may be overwritten for a single value in the
     *   $vars array.
     *  'docType' => (string) it can be default or spreadsheet. Only use this
     *   option to replace variables by numeric values in spreadsheets.
     * 
     * @return void
     * @access public
     */
    public function replace($vars, $options = array()) 
    {
        $elements = array ('paragraph'     => true,
                           'text'          => true,
                           'list'          => true,
                           'table'         => true,
                           'chart'         => true,
                           'image'         => true
                           );
        
        //preset values
        if (empty($options['element'])) {
            $options['element'] = 'text';
        }
        if (empty($options['target'])) {
            $options['target'] = 'document';
        }
        if (isset($options['format']) && count($options['format']) == 2) {
            //correct format
        } else {
            $options['format'] = array('{{', '}}');
        }
        //implement global options at the single variable level
        foreach ($vars as $k => $var) {
            if (!isset($var['html']) && isset($options['html'])){
                $vars[$k]['html'] = $options['html'];
            }
            if (!isset($var['parse-line-breaks']) 
                && isset($options['parse-line-breaks'])){
                $vars[$k]['parse-line-breaks'] = $options['parse-line-breaks'];
            }
            if (!isset($var['block-type']) && isset($options['block-type'])){
                $vars[$k]['block-type'] = $options['block-type'];
            }
            if (!isset($var['image']) 
                && $options['element'] == 'image' ){
                $vars[$k]['image'] = true;
            }
            if (!isset($var['match']) && isset($options['match'])){
                $vars[$k]['match'] = $options['match'];
            }
        }
        //initiate the replacement
        if (!isset($elements[$options['element']])) {
            $message = 'That element type is not available.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
        }  else {
            if ($options['target'] == 'document'){
                $options['DOMDoc'] = $this->_dom['content.xml'];
            } else {
                $options['DOMDoc'] = $this->_dom['styles.xml'];
            }
            $options['manifest'] = $this->_dom['META-INF/manifest.xml'];
            $replace = new templates\Replace($this, $vars, $options);
        }
        return $this;
    }
    
    /**
     * replaces the chosen template chart data with the given content
     * 
     * @param string $var the name used for the chart title. If empty the chatr 
     * will be chosen via the match option.
     * @param array $options with the following keys and values
     *      'data' => (array) it can be an array with different formats:
     *          pie and donuts charts:
     *              array( 'category_1' => 3,
     *                     'category_2' => 5,
     *                     'category_3' => 4.3,
     *                    )
     *          bar, column, area , line, scatter, (filled-)radar 
     *          and column-line charts:
     *              array( 'series' => array('ser_1', 'ser_2'), 
     *                     'category_1' => array(20,40), 
     *                     'category_2' => array(30,10),
     *                     'category_3' => array(12.5, 54),
     *                    )
     *          buble charts: not supported yet
     *  'format' => (array) an array with two entries giving the chosen
     *   openning and closing symbol for the variable. The default value
     *   is array('{{', '}}') but any other combination of symbols is possible, 
     *   even array('', ''), although this is not recommended unless strictly
     *   necessary.
     *  'match' => (int) if given, only the selected match will be replaced, 
     *   i.e. if match equals 1 the first chart tagged with the variable 
     *   will be replaced and the others will be ignored. 
     *      
     * @return void
     * @access public
     */
    public function replaceChartData($var, $options) 
    {
        if (isset($options['format']) && count($options['format']) == 2) {
            //correct format
        } else {
            $options['format'] = array('{{', '}}');
        }
        
        $options['DOMDoc'] = $this->_dom['content.xml'];
        $options['manifest'] = $this->_dom['META-INF/manifest.xml'];
        $options['template'] = '';
        $options['template'] =& $this->template;
        $vars = array();
        $vars[$var] = $options['data'];
        $options['element'] = 'chart';
        $options['target'] = 'document';
        $replace = new templates\Replace($this, $vars, $options);
    }
    
    /**
     * sets the cursor at the chosen location
     * 
     * @param array $options an array with the following keys and values:
     *  'needle' => (string) if given the (first, if match is not set) node 
     *   containing it will be chosen as the reference node.
     *  'element' => (string) it sets the type of the reference node. It can be 
     *   paragraph (default) or table.
     *  'match' => (int) if there is more than one resulting node its order 
     *   of ocurrence. The default is 1 (first match).
     *  'position' => (string) it can be before (default, the cursor is located
     *   before the reference node), after (the cursor is located just
     *   after the reference node) or end (end of the document). In the last 
     *   case the options needle and match will be ignored.
     * @return void
     * @access public
     */
    public function cursor($options = array()) 
    {
        $pos = array('before'   => true,
                     'after'    => true,
                     'end'      => true,     
                    );
        $elm = array('paragraph' => true,
                     'table'     => true,   
                    );
        //preset values
        if (empty($options['needle'])) {
            $options['needle'] = '';
        }
        if (empty($options['element'])) {
            $options['element'] = 'paragraph';
        }
        if (empty($options['match'])) {
            $options['match'] = 1;
        }
        if (empty($options['position'])) {
            $options['position'] = 'before';
        }
        if (!isset($pos[$options['position']])) {
            $message = 'Illeggal position requested.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            return $this;
        }
        if (!isset($elm[$options['element']])) {
            $message = 'Illeggal element requested.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            return $this;
        }
        //start searching the cursor position
        if ($options['position'] == 'end') {
            $this->setCursor($this->content);
        } else {
            $queryObj = new templates\ODFQuery();
            $query = $queryObj->elementQuery($options);
            $xpath = new \DOMXPath($this->_dom['content.xml']);
            $refNodes = $xpath->query($query);
            if ($refNodes->length > 0) {
                $refNode = $refNodes->item(0); 
                $this->setCursor($refNode->parentNode, 
                                 $refNode, 
                                 $options['position']); 
            } else {
                $message = 'The reference node for cursor positioning has ';
                $message .= 'not been found.';
                commons\DOCXPRESSOLogger::logger($message, 'info');
            }
        }
        return $this;
    }
    
    /**
     * creates a document fragment that can be later inserted directly into
     * the document or within another document element
     * 
     * @return DocumentFragment
     * @access public
     */
    public function documentFragment() 
    {
        $fragment = $this->contentODF->documentFragment();
        return $fragment;
    }
    
    /**
     * adds a field that generates some "automatic content" out of the document
     * structure (page numbers and count), the metadata or the like
     * 
     * @param string $type the available types are:
     *  author-name: the full author name
     *  bookmark-ref: reference to a bookmark page
     *  date: current date
     *  page-count: total number of pages 
     *  page-number: current page number (with an optional offset)
     *  title: document title
     * @param array $options the possible keys and values are:
     *      'default-value' => (string) the field default value
     *      'number-format' => (string) used for page-count and page-number 
     *          1: Hindu-Arabic number sequence starts with 1. 
     *          a: number sequence of lowercase Modern Latin basic alphabet 
     *          characters starts with “a”. 
     *          A: number sequence of uppercase Modern Latin basic alphabet 
     *          characters starts with “A”. 
     *          i: number sequence of lowercase Roman numerals starts with “i”. 
     *          I: number sequence of uppercase Roman numerals start with “I”.
     *      'number-offset' => (int) allows to start the numbering with a given
     *      offset. For example, if we would like to ignore the front page for
     *      page numbering we should choose a value of -1.
     *      'reference-format' => (string) used for bookmark-ref
     *          page: displays the number of the page on which the 
     *          referenced item appears. 
     *          text: displays the text of the referenced item.
     *      'reference-name' => (string) the name of the linked reference 
     *      'date-format' => (array) the date is built by running consecutively
     *      through the array entries. possible values are:
     *          day: day of the month with two digits 
     *          day-short: day of the month with one/two digits (as required)
     *          day-of-week: day of the week in textual format
     *          day-of-week-short: abbreviated day of the week in textual format
     *          month: month of the year with two digits
     *          month-short: month of the year with one/two digits (as required) 
     *          month-of-year: month of year in textual form
     *          month-of-year-short: month of year in abbreviated textual form
     *          year: year number with four digits
     *          year-short: year number with 2 digits
     *          text (string): an arbitrary string of text
     *          Examples: 
     *          1. array('day', '/', 'month', '/', 'year') yields 07/03/2014
     *          2. array('day-of-week', ' ', 'day-short', ', 'month-of-year', '-', 'year')
     *          yields Thursday 7, November-2014
     *      'style' => (string) a list of properties in CSS format
     * @param bool $reference if true returns a reference to the form element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function field($type, $options = array(), $reference = true) 
    {
        $element = $this->contentODF->field($type, $options, $reference);
        if ($reference) {
            return $element;
        }
    }
    
    /**
     * adds a footer to a document section
     * 
     * @param array $options with the following keys and values
     *      'section' => (int) the section number. If not given it is assumed 
     *      that the footer will be inserted in the current section
     *      'dynamic-spacing' => (bool) select true if you wish that the
     *       package accomodates the header properties to the general document
     *       layout. Default value is false.
     *      'left-page' => (bool) if true the footer will be specific of left
     *      pages only
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     * 
     * @return ODFElement
     * @access public
     */
    public function footer($options = array()) 
    {
        $footer = $this->_header_footer('footer', $options); 
        return $footer;
    }
    
    /**
     * Inserts an endnote into a paragraph therefore can not be directly used
     * in the document root
     * 
     * @param array $options
     * @param bool $reference
     * @return ODFElement
     * @access public
     */
    public function endnote($options = array(), $reference = true) 
    {
        //one can only include an endnote within a paragraph
        $message = 'You can only include an endnote within a paragraph.';
        commons\DOCXPRESSOLogger::logger($message, 'info');
        return $this;
    }
    
    /**
     * inserts a footnote into a paragraph therefore can not be directly used
     * in the document root
     * 
     * @param array $options
     * @param bool $reference
     * @return ODFElement
     * @access public
     */
    public function footnote($options = array(), $reference = true) 
    {
        //one can only include a footnote within a paragraph
        $message = 'You can only include a footnote within a paragraph.';
        commons\DOCXPRESSOLogger::logger($message, 'info');
        return $this;
    }
    
    /**
     * inserts a form container into the document. The form itself does not 
     * accept styling options so if you want to include them you should wrap
     * the form within a table or frame.
     *
     * @param array $options the avalaible key and values are:
     *  'name' => (string) a unique name that identifies the form. If not given
     *   will be automatically generated.
     *  'target' => (string) that can be '_blank (default), _self or _top 
     *   or a frame name
     *  'action' => (string) points to the URL (if any) where the data of the
     *   form should be sent and processed
     *  'method' => (string) may be get (default) or post
     * @param bool $reference if true returns a reference to the form element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function form($options, $reference = true) 
    {
        $form = $this->contentODF->form($options, $reference);
        if (isset($options['name'])) {
            self::$currentForm = $options['name'];
        }
        if ($reference) {
            //the form element is outside the document flow
            return $this;
        }
    }
    
    /**
     * adds a frame to the document
     * The frame can be floating or enjoy absolute or relative positioning
     * 
     * @param array $options with the following keys and values
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     * @param bool $reference if true returns a reference to the frame element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function frame($options = array(), $reference = true) 
    {
        $element = $this->contentODF->frame($options, $reference);
        if ($reference) {
            return $element;
        } else {
            return $this;
        }
    }
    
    /**
     * gets the thumbnail representation, if it exists, of the odt file
     * 
     * @return string
     * @access public
     */
    public function getThumbnail() 
    {
        if (!empty($this->template['Thumbnails/thumbnail.png'])) {
           return $this->template['Thumbnails/thumbnail.png'];
        } else {
            return '';
        }
    }
    
    /**
     * adds a checkbox to a form within the document
     * 
     * @param array $options with the following keys and values
     *      'form-name' => (string) the name of the form where we want to insert 
     *       this control
     *      'name' => (string) the control name
     *      'value' => (string) the associated value
     *      'checked' => (int) 1 or 0
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     *      'pageNumber' => (int) for absolutely positioned controls (optional).
     * @param bool $reference if true returns a reference to the frame element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function checkbox($options, $reference = true) 
    {
        if (!isset($options['form-name'])) {
            $options['form-name'] = self::$currentForm;
        }
        $element = $this->contentODF->checkbox($options, $reference);
        if ($reference) {
            return $element;
        }
    }
    
    /**
     * Inserts a column break into the document
     * 
     * @return void
     * @access public
     */
    public function columnBreak() 
    {
        $this->contentODF->columnBreak();
    }
    
    /**
     * adds an input field to a form within the document
     * 
     * @param array $options with the following keys and values
     *      'form-name' => (string) the name of the form where we want to insert 
     *      this control
     *      'name' => (string) the control name.
     *      'value' => (string) the associated default value, if any.
     *      'style' => (string) a list of properties in CSS format.
     *      'styleId' => (string) if given the styleId is not generated randomly
     *      'pageNumber' => (int) for absolutely positioned controls (optional).
     * @param bool $reference if true returns a reference to the frame element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function inputField($options, $reference = true) 
    {
        if (!isset($options['form-name'])) {
            $options['form-name'] = self::$currentForm;
        }
        $element = $this->contentODF->inputField($options, $reference);
        if ($reference) {
            return $element;
        }
    }
    
    /**
     * inserts a document fragment previously created with the documentFragment
     * method
     * 
     * @param DocumentFragment $fragment
     * @return CreateDocument
     * @access public
     */
    public function insertDocumentFragment($fragment) 
    {
        $this->contentODF->insertDocumentFragment($fragment);
        //return a reference to the parent to allow further chaining
        return $this;
    }
    
    /**
     * adds a dropdown select to a form within the document
     * 
     * @param array $options with the following keys and values
     *      'form-name' => (string) the name of the form where we want to insert 
     *      this control
     *      'name' => (string) the control name.
     *      'items' => (array) an associative array with keys as options and 
     *      values the corresponding form values
     *      'selected' => (string) the name of the selected item
     *      selected by group otherwise use a checkbox control component
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     *      'pageNumber' => (int) for absolutely positioned controls (optional).
     * @param bool $reference if true returns a reference to the frame element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function select($options, $reference = true) 
    {
        if (!isset($options['form-name'])) {
            $options['form-name'] = self::$currentForm;
        }
        $element = $this->contentODF->select($options, $reference);
        if ($reference) {
            return $element;
        }
    }
    
    /**
     * adds a radio button to a form within the document
     * 
     * @param array $options with the following keys and values
     *      'form-name' => (string) the name of the form where we want to insert 
     *      this control
     *      'name' => (string) the control name. Radio buttons belonging to the
     *      same group should share the same name
     *      'value' => (string) the associated value
     *      'selected' => (bool) true or false. only one readio button should be
     *      selected by group otherwise use a checkbox control component
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     *      'pageNumber' => (int) for absolutely positioned controls (optional).
     * @param bool $reference if true returns a reference to the frame element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function radioButton($options, $reference = true) 
    {
        if (!isset($options['form-name'])) {
            $options['form-name'] = self::$currentForm;
        }
        $element = $this->contentODF->radioButton($options, $reference);
        if ($reference) {
            return $element;
        }
    }
    
    /**
     * adds a text area to a form within the document
     * 
     * @param array $options with the following keys and values
     *      'form-name' => (string) the name of the form where we want to insert 
     *      this control
     *      'name' => (string) the control name
     *      'value' => (string) the associated value
     *      'scroll' => (boolean) if true (default) the text area shows vertical
     *      scrollbars
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     *      'pageNumber' => (int) for absolutely positioned controls (optional).
     * @param bool $reference if true returns a reference to the frame element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function textArea($options, $reference = true) 
    {
        if (!isset($options['form-name'])) {
            $options['form-name'] = self::$currentForm;
        }
        $element = $this->contentODF->textArea($options, $reference);
        if ($reference) {
            return $element;
        }
    }
    
    /**
     * adds a header to a document section
     * 
     * @param array $options with the following keys and values
     *      'section' => (int) the section number. If not given it is assumed 
     *       that the header will be inserted in the current section
     *      'dynamic-spacing' => (bool) select true if you wish that the
     *       package accomodates the header properties to the general document
     *       layout. Default value is false.
     *      'left-page' => (bool) if true the header will be specific of left
     *       pages only
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     * 
     * @return ODFElement
     * @access public
     */
    public function header($options = array()) 
    {
        $header = $this->_header_footer('header', $options); 
        return $header;
    }
    
    /**
     * adds a heading to the document
     * 
     * @param array $options with the following keys and values
     *      'level' => (int) the heading level: 1 (h1), 2 (h2),... up to 6
     *      'text' => (string)  an optional text (it can also be added later 
     *       with the text method)
     *      'style' => (string) a list of properties in CSS format
     *      'enforce' => (bool) if set to true and the current element does not
     *      accept paragraphs the method searches for the first ancestor that
     *      allows for the isnsertion. Beware that the return element is a 
     *      reference to the inserted paragraph and the chaining may result 
     *      affected consequently!!
     * @param bool $reference if true returns a reference to the paragraph
     * element. The default value is true
     * @return ODFElement
     * @access public
     */
    public function heading($options, $reference = true) 
    {
        $element = $this->contentODF->heading($options, $reference);
        if ($reference) {
            return $element;
        }
    }
    
    /**
     * This method allows to "insert" HTML content into the document
     *
     * @param array $options the available keys and values are:
     *  'baseURL' => (string) if set enforces the base URL used for 
     *   relative paths, otherwise it will be autodetected (if possible).
     *  'encoding' => (string) if set enforces the encoding to be used 
     *   otherwise it will be autodetected.
     *  'isFile' => (boolean) true if the HTML code has to be retrieved from 
     *   a file. Default value is false, i.e. the HTML is a string.
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
     *  'html' => (string) it can be a string of HTML code or the path to the 
     *   the path to the HTML file if the 'isFile' option is set to true.
     * @return CreateDocument
     * @access public
     */
    public function html($options) 
    {
        $this->contentODF->html($options);
        //we return a reference to this to allow further chaining
        return $this;
    }
    
    /**
     * Converts a document into HTML5 + CSS + JS
     * 
     * @param string $path the path to the resulting HTML5 file. The path
     * should include the extension (html or htm)
     * @param array $options an array with the following keys and values:
     *  'charts' => (boolean) if true renders the chart via JavaScript.
     *   The default value is true.
     *  'css' => (string) path to an external CSS stylesheet.
     *  'download' => (bolean) if true saves the file and flush its contents to
     *   the browser. Default is false.
     *  'format' => (string) the values can be:
     *      standard: (default) the styles and images are stored in a folder
     *      with the same name of the HTML5 file
     *      single-file: the styles are integrated within the <head> tag 
     *      element and the images are base64 encoded an integrated within the
     *      HTML5 file.
     *  'meta' => (mixed) It can be boolean value: if true the metadata will
     *   be extracted automatically from the document and if false (default) no 
     *   metadata will be included, or an array with key-value pairs the  
     *   property name and its corresponding value.
     *  'SVG' => (boolean) if true renders the drawings as SVG. The default
     *   value is false.
     * 
     * @return string
     * @access public
     * @ignore
     */
    public function ODF2HTML5($path, $options = array()) 
    {
        if (empty($path)) {
            $message = 'Insert a path for the resulting HTML5 file.';
            commons\DOCXPRESSOLogger::logger($message, 'critical');
        }
        //preset values
        if (empty($options['format'])) {
            $options['format'] = 'standard';
        }
        $html =  new Odf2html5\ODF2HTML5($this, $path, $options);
        $data = array();
        $data =  $html->renderHTML();
        return $data;
    }
    
    /**
     * adds an "horizontal rule" to the document
     * 
     * @param array $options with the following keys and values
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     * 
     * @return ODFElement
     * @access public
     */
    public function hr($options = array()) 
    {
        $this->contentODF->hr($options);
        return $this;
    }
    
    /**
     * adds an image to the document
     * The images can be inserted inline (default) or floating to the left 
     * or right
     * 
     * @param array $options with the following keys and values
     *      'src' => (string) path to the image we want to insert
     *      'title' => (string) the image title should be included for
     *       accesibility reasons
     *      'description' => (string) the image description should be included
     *       for accesibility reasons
     *      'style' => (string) a list of properties in CSS format
     * @param bool $reference if true returns a reference to the image element
     * so further elements can be nested. The default value is false.
     * @return ODFElement
     * @access public
     */
    public function image($options, $reference = true) 
    {
        $element = $this->contentODF->paragraph()->image($options, $reference);
        if ($reference) {
            return $element;
        }
    }
    
    /**
     * adds a link to the document
     * 
     * @param array $options with the following keys and values
     *      'text' => (string)  an optional text (it can also be added later 
     *       with the text method)
     *      'target' => (string) the link target (the default is _blank)
     *      'title' => (string) an optional title text for the link
     *      'style' => (string) a list of properties in CSS format
     *      'url' => (string) the link href attribute. It can be a http path or
     *      an anchor to a previously defined bookmark (#name)
     * @param bool $reference if true returns a reference to the paragraph
     * element. The default value is true
     * @return ODFElement
     * @access public
     */
    public function link($options, $reference = true) 
    {
        $element = $this->contentODF->link($options, $reference);
        if ($reference) {
            return $element;
        }
    }
    
    /**
     * Inserts a line break into the document
     * 
     * @return void
     * @access public
     */
    public function lineBreak() 
    {
        $this->contentODF->lineBreak();
    }
    
    
    /**
     * inserts an item into a list. It can not be directly inserted into the
     * document
     * 
     * @return CreateDocument
     * @access public
     */
    public function listItem() 
    {
        //one can only include a list item within a list
        $message = 'You can only add a list item within a list.';
        commons\DOCXPRESSOLogger::logger($message, 'info');
        return $this;
    }
    
    /**
     * loads a custom theme
     * 
     * @param string $path the path to the correponding CSS file. The
     * @return CreateDocument
     * @access public
     */
    public function loadTheme($path = '') 
    {
        //create a ThemeManager instance
        $theme = new themes\ThemeManager($this, $path);
        return $this;
    }
    
    /**
     * adds a math equation to the document
     * The equations can be inserted inline (default) or floating to the left, 
     * right or center.
     * 
     * @param string $equation the equation that we want to insert in MathML 1.0
     * or StarMath 5.0 format
     * @param array $options with the following keys and values
     *      'math-settings' => (array) with the following keys and values:
     *          'base-font-size' => (int) base font size used in the equation.
     *           The default size is 12 (pt).
     *          'rel-text-size' => (int) relative text size (default: 100)
     *          'rel-indexes-size' => (int) relative index size (default: 60)
     *          'rel-functions-size' => (int) relative function size 
     *           (default: 100)
     *          'rel-operators-size' => (int) relative operator size 
     *           (default: 100)
     *          'rel-limits-size' => (int) relative limit size (default: 60)
     *          'variables-font' => (string) default is Times New Roman
     *          'functions-font' => (string) default is Times New Roman
     *          'numbers-font' => (string) default is Times New Roman
     *          'text-font' => (string) default is Times New Roman
     *          'custom-serif-font' => (string) default is Times New Roman
     *          'custom-sans-serif-font' => (string) default is Arial
     *          'custom-fixed-font' => (string) default is Courier New         
     *      'style' => (string) a list of properties in CSS format
     *  NOTE: Only MathML 1.0 is supported. You can check the documentation in
     *        http://www.w3.org/TR/1998/REC-MathML-19980407/.
     *        For StarMath 5.0 syntax you may refer to the official docs in:
     *        https://wiki.openoffice.org/wiki/Documentation/OOo3_User_Guides/Math_Guide/Math_commands_reference
     *        http://www.openoffice.org/documentation/manuals/oooauthors/MathObjects.pdf
     * @return ODFElement
     * @access public
     */
    public function math($equation, $options = array()) 
    {
        $element = $this->contentODF->paragraph()->math($equation, $options);
        return $element;
    }
    
    /**
     * sets the document metadata
     * 
     * @param array $options with the following keys and values
     *      'author' => (string) document author name
     *      'date' => (string) date in the format: yyyy-mm-ddTHH:mm:ssZ, for
     *       example, 2014-06-20T09:37:12Z
     *      'keywords' => (string) a list of document keywords (with no specific
     *       format)
     *      'subject' => (string) document subject
     *      'title' => (string) document title
     * @return CreateDocument
     * @access public
     */
    public function metadata($options) 
    {
        $field = array(
            'author' => 'meta:initial-creator',
            'date' => 'dc:date',
            'keywords' => 'meta:keyword',
            'subject' => 'dc:subject',
            'title' => 'dc:title',
        );
        
        //load metadata.xml in the DOM
        $metadata = new \DOMDocument();
        $metadata->loadXML($this->template['meta.xml']);
        $meta = $metadata->getElementsByTagName('meta')->item(0);
        foreach ($options as $key => $value) {
            if (isset($field[$key])) {
                commons\Utilities::updateNodeValue($field[$key], $value, $meta);
            }
        }
        //special cases
        if (isset($options['date'])) {
            commons\Utilities::updateNodeValue('meta:creation-date', 
                                               $options['date'], 
                                               $meta);
        }
        if (isset($options['author'])) {
            commons\Utilities::updateNodeValue('dc:creator', 
                                               $options['author'], 
                                               $meta);
        }
        //
        //save the meta file
        $this->template['meta.xml'] = $metadata->saveXML();
        
        //return the parent ODF node so we can use chaining
        return $this;
    }
    
    
    /**
     * Inserts a page break into the document
     * 
     * @return void
     * @access public
     */
    public function pageBreak() 
    {
        $this->contentODF->pageBreak();
    }
    
    /**
     * Stablish the page layout properties.
     * 
     * @param array $options the availables keys and values are:
     *      'orientation' => (string) can be portrait (default) or landscape
     *      'paperSize' => (string) the predefined available options are:
     *          A3
     *          A4 (default)
     *          letter
     *          legal
     *      If the width and height properties are set in the style option this
     *      value will be ignored
     *      'section' => (int)  the section number to which we want to apply
     *      the custom layout. If not set or empty the style is applied to all
     *      document sections.
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     * @return CreateDocument
     * @access public
     */
    public function pageLayout($options = array()) 
    {
        //run over the master styles section of the styles.xml file to select
        //the affected sections
        $masterStyles = $this->_dom['styles.xml']
                             ->documentElement
                             ->getElementsByTagName('master-page');

        $sections = array();
        $counter = 1;
        foreach ($masterStyles as $master){
            $sectionStyle = $master->getAttribute('style:page-layout-name');
            $sectionId = $master->getAttribute('style:name');
            if (!empty($options['section'])) {
                if ($options['section'] == $counter) {
                    $sections[$sectionId] = $sectionStyle;
                }
            } else {
                $sections[$sectionId] = $sectionStyle;
            }
            $counter++;
        }
        //extract the styles associated with the orientation and paperSize 
        //options
        $params = array();
        if (isset($options['orientation'])) {
            $params['style:print-orientation'] = $options['orientation'];
        } else {
            $params['style:print-orientation'] = 'portrait';
        }
        if (!isset($options['paperSize'])
            || !isset(elements\Resources::$paperSize[$options['paperSize']])) {
            $options['paperSize'] = 'A4';
        }
        foreach(elements\Resources::$paperSize[$options['paperSize']] 
                as $key => $value) {
            $params[$key] = $value;
        }
        //exchange width and height if we are in landscape mode
        if (isset($options['orientation']) 
            && $options['orientation'] == 'landscape') {
           $width = $params['fo:page-width'];
           $height = $params['fo:page-height'];
           $params['fo:page-height'] = $width;
           $params['fo:page-width'] = $height;
        }
        //parse the styles
        $pageProps = new parser\ParseCSSProperties();
        if (isset($options['style'])) {
            $CSSStyle = $pageProps->parseCSS($options['style']);
            $CSSStyle = parser\StyleProcessor::ODFStyles($CSSStyle[0]);
        } else {
            $CSSStyle = array();
        }
        //normalize now the names to the standard ODF style attributes
        foreach($CSSStyle as $key => $value){
            if (isset(parser\StyleProcessor::$CSSODF[$key])
                && \is_string(parser\StyleProcessor::$CSSODF[$key])) {
                $CSSStyle[parser\StyleProcessor::$CSSODF[$key]] =
                    $CSSStyle[$key];
                unset ($CSSStyle[$key]);
            } else if ( $key == 'width' || $key == 'height') {
                $CSSStyle['fo:page-' . $key] = $value;
            } 
        }
        
        //merge the style arrays
        $style = array_merge($params, $CSSStyle);
        
        //get the page layout nodes
        $pageProps = $this->_dom['styles.xml']
                          ->getElementsByTagName('page-layout');
        foreach ($pageProps as $pageNode) {
            $name = $pageNode->getAttribute('style:name');
            if (\in_array($name, $sections)) {
                $layoutNode = $pageNode
                        ->getElementsByTagName('page-layout-properties')
                        ->item(0);
                //parse the background properties
                if (isset($style['xlink:href'])) {
                    //check if there is already a background-image element and 
                    //if so remove it 
                    $bgNodes = $layoutNode
                                ->getElementsbyTagName('background-image');
                    if ($bgNodes->length > 0) {
                        //remove element
                        $bgNodes->item(0)->parentNode
                                          ->removeChild($bgNodes->item(0));
                    }
                    //create the new background-image element
                    $name = 
                    commons\Utilities::getBackgroundImage($style['xlink:href']);
                    if (!empty($name)) {
                        $bg = $layoutNode
                                ->ownerDocument
                                ->createElement('style:background-image');
                        $bg->setAttribute('xlink:href', $name);
                        if (isset($style['style:position'])) {
                            $bg->setAttribute('style:position', 
                                              $style['style:position']);
                        }
                        if (isset($style['style:repeat'])) {
                            $bg->setAttribute('style:repeat', 
                                              $style['style:repeat']);
                        }
                        $layoutNode->appendChild($bg);
                    }
                    //unset all the background properties not to include them as
                    //attributes of the layout node
                    unset($style['xlink:href']);
                    unset($style['style:position']);
                    unset($style['style:repeat']);
                }
                //parse the column properties
                if (isset($style['fo:column-count'])) {
                    //check if there is a columns element and if so
                    //remove it
                    $colNodes = $layoutNode->getElementsbyTagName('columns');
                    if ($colNodes->length > 0) {
                        //remove element
                        $colNodes->item(0)->parentNode
                                          ->removeChild($colNodes->item(0));
                    }
                    $columns = $layoutNode->ownerDocument
                                          ->createElement('style:columns');
                    
                    $columns->setAttribute('fo:column-count',
                                           $style['fo:column-count']);
                    if (isset($style['fo:column-gap'])) {
                        $columnGap = \implode($style['fo:column-gap']);
                        $columns->setAttribute('fo:column-gap', $columnGap);
                    }
                    //check if there is a column separator
                    if (isset($style['style:style'])
                        || isset($style['style:width'])
                        || isset($style['style:color'])    
                            ) {
                        $colSep = $layoutNode->ownerDocument
                                          ->createElement('style:column-sep');
                        $colSep->setAttribute('style:height', '100%');
                        if (isset($style['style:style'])) {
                            $colSep->setAttribute('style:style', 
                                $style['style:style']);
                        }
                        if (isset($style['style:color'])) {
                            $colSep->setAttribute('style:color', 
                                $style['style:color']);
                        }
                        if (isset($style['style:width'])) {
                            $colSep->setAttribute('style:width', 
                                $style['style:width']);
                        }
                        $columns->appendChild($colSep);
                    }
                    $layoutNode->appendChild($columns);
                    //unset all the column properties not to include them as
                    //attributes of the layout node
                    unset($style['fo:column-count']);
                    unset($style['fo:column-gap']);
                    unset($style['fo:rel-width']);
                    unset($style['style:style']);
                    unset($style['style:color']);
                    unset($style['style:width']);
                }
                //include all the remaining props as attributes of the 
                //layout node
                foreach ($style as $key => $value) {

                    $layoutNode->setAttribute($key, $value);
                }
            }
        }
        //return the parent document so we can use chaining
        return $this;
    }
    
    /**
     * adds a paragraph to the document
     * 
     * @param array $options with the following keys and values
     *      'text' => (string)  an optional text (it can also be added later 
     *       with the text method)
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     * @param bool $reference if true returns a reference to the paragraph
     * element. The default value is true
     * @return ODFElement
     * @access public
     */
    public function paragraph($options = array(), $reference = true) 
    {
        $element = $this->contentODF->paragraph($options, $reference);
        if ($reference) {
            return $element;
        }
    }
    
    /**
     * parses the cell childs of a row to set inherited table properties
     * 
     * @param array $options with the following keys and values
     *      'dom' => (DOMDocument) the dom we need to search
     *      'id' => (string) unique identifier
     *      'idType' => (string) it can be styleId or xml:id
     *      'tag' => (string)  the tag we want to parse
     *      'style' => (array) the preparsed CSS styles
     *      
     * @return void
     * @access public
     * @static
     */
    public static function parseRowCellChilds($options) 
    {
        $xpath = new \DOMXPath($options['dom']);
        if ($options['idType'] == 'xml:id') {
            $query = '//table:table-row[@xml:id="' . $options['id'] . '"]';
        } else {
            $query = '//table:table-row';
            $query .= '[@table:style-name="' . $options['id'] . '"]';
        }
        $tableNode = $xpath->query($query)->item(0);
        if ($tableNode === NULL) {
            $message = 'There was a problem finding the requested row.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            return;
        }
        //parse all required border properties
        $sides = array('top', 'right', 'bottom', 'left');
        $border = array();
        foreach ($sides as $side) {
            if (isset($options['style']['border-' . $side])) {
                $border['border-' . $side] = 'border-' . $side . ':' .
                        $options['style']['border-'. $side] . ';';
            } else {
                $border['border-' . $side] = '';
            }
        }
        
        $cols = $tableNode->getElementsByTagName('table-cell');
        $colCounter = 1;
        $numCols = $cols->length;
        foreach ($cols as $col) {
            //first get the style name
            $style = $col->getAttribute('table:style-name'); 
            if ($colCounter == 1) {
                self::addBorder(self::$automaticStyles[$style],
                                  $border['border-left']);
            } 
            if ($colCounter == $numCols) {
                self::addBorder(self::$automaticStyles[$style],
                                  $border['border-right']);
            }
            self::addBorder(self::$automaticStyles[$style],
                              $border['border-top'] . $border['border-bottom']);
            $colCounter++;
        }
    }
    
    /**
     * parses the cell childs of a table to set inherited table properties
     * 
     * @param array $options with the following keys and values
     *      'dom' => (DOMDocument) the dom we need to search
     *      'id' => (string) unique identifier
     *      'idType' => (string) it can be styleId or xml:id
     *      'tag' => (string)  the tag we want to parse
     *      'style' => (array) the preparsed CSS styles
     *      
     * @return void
     * @access public
     * @static
     */
    public static function parseTableCellChilds($options) 
    {
        $xpath = new \DOMXPath($options['dom']);
        if ($options['idType'] == 'xml:id') {
            $query = '//table:table[@xml:id="' . $options['id'] . '"]';
        } else {
            $query = '//table:table';
            $query .= '[@table:style-name="' . $options['id'] . '"]';
        }
        $tableNode = $xpath->query($query)->item(0);
        if ($tableNode === NULL) {
            $message = 'There was a problem finding the requested table.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            return;
        }
        //parse all required border properties
        $sides = array('top', 'right', 'bottom', 'left');
        $border = array();
        foreach ($sides as $side) {
            if (isset($options['style']['border-' . $side])) {
                $border['border-' . $side] = 'border-' . $side . ':' .
                        $options['style']['border-'. $side] . ';';
            } else {
                $border['border-' . $side] = '';
            }
        }
        //get all rows
        $rows = $tableNode->getElementsByTagName('table-row');
        $rowCounter = 1;
        $numRows = $rows->length;
        foreach ($rows as $row) {
            $cols = $row->getElementsByTagName('table-cell');
            $colCounter = 1;
            $numCols = $cols->length;
            foreach ($cols as $col) {
                //first get the style name
                $style = $col->getAttribute('table:style-name');
                if ($rowCounter == 1) {
                    self::addBorder(self::$automaticStyles[$style],
                                      $border['border-top']);
                }
                if ($rowCounter == $numRows) {
                    self::addBorder(self::$automaticStyles[$style],
                                      $border['border-bottom']);
                } 
                if ($colCounter == 1) {
                    self::addBorder(self::$automaticStyles[$style],
                                      $border['border-left']);
                } 
                if ($colCounter == $numCols) {
                    self::addBorder(self::$automaticStyles[$style],
                                      $border['border-right']);
                } 
                $colCounter++;
            }
            $rowCounter++;
        }
    }
    
    
    /**
     * parses the current template for placeholder variables and stores the 
     * result in the placeholderVariables static variable
     * 
     * @param array $options an array with the following keys and values:
     *  'format' => (array) an array with two entries giving the chosen
     *   openning and closing symbol for template variables. The default value
     *   is array('{{', '}}') but any other combination of symbols is possible.
     *   Althoug allowed, we do not recommend using the same symbol for both 
     *   arrays entries or symbols that may be used somewhere else within the 
     *   document because that may lead to "false positives"
     * @return CreateDocument
     * @access public
     */
    public function parseVariables($options = array()) 
    {
        self::$placeholderVariables = array();
        $this->_parseVars('document', $options);
        $this->_parseVars('header', $options);
        $this->_parseVars('footer', $options);

        return $this;
    }
    
    
    /**
     * Adds a new section to the document with its own layout.
     * 
     * @param array $options the availables keys and values are:
     *      'orientation' => (string) can be portrait (default) or landscape
     *      'paperSize' => (string) the predwefined available options are:
     *          A3
     *          A4 (default)
     *          letter
     *          legal
     *      If the width and height properties are set in the style option this
     *      value will be ignored
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     * @return CreateDocument
     * @access public
     */
    public function section($options = array()) 
    {
        //create an empty and hidden paragraph with the required master style
        $node = $this->contentODF->getNode();
        $ns = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
        $sectNode = $node->ownerDocument->createElementNS($ns, 'text:p');
        $id = uniqid();
        $sectNodeStyleId = 'section_' . $id;
        self::$sectionIds[] = $sectNodeStyleId;
        $masterStyleId = 'master_' . $id;
        $layoutStyleId = 'layout_' . $id;
        $sectNode->setAttributeNS($ns, 'text:style-name', $sectNodeStyleId);
        //check if there is a cursor within the node
        $cursors = $node->getElementsByTagName('customCursor');
        if ($cursors->length > 0) {
            $cursor = $cursors->item(0);
            $cursor->parentNode->insertBefore($sectNode, $cursor);
        } else {
            $node->appendChild($sectNode);
        }
        //hide this element and link it to a master section style
        $styleNode = $node->ownerDocument
                          ->createElement('style:style');
        $styleNode->setAttribute('style:family', 'paragraph');
        $styleNode->setAttribute('style:name', $sectNodeStyleId);
        $styleNode->setAttribute('style:master-page-name', $masterStyleId);
        $pProps = $node->ownerDocument
                       ->createElement('style:paragraph-properties');
        //to make sure that ir renders nicely in Word just in case 
        //the _repairSections method  fails we set some additional props
        $pProps->setAttribute('fo:line-height', '0.5pt');
        $pProps->setAttribute('fo:margin-top', '0pt');
        $pProps->setAttribute('fo:margin-bottom', '0pt');
        $pProps->setAttribute('fo:padding-top', '0pt');
        $pProps->setAttribute('fo:padding-bottom', '0pt');
        $styleNode->appendChild($pProps);
        $tProps = $node->ownerDocument
                       ->createElement('style:text-properties');
        $tProps->setAttribute('text:display', 'none');
        $tProps->setAttribute('fo:font-size', '0.5pt');
        $styleNode->appendChild($tProps);
        //save this style in the automaticStyles array
        self::$automaticStyles[$sectNodeStyleId] = $styleNode;
        self::$styleTypes[$sectNodeStyleId] = 'DOMNode';
        self::$styleTarget[$sectNodeStyleId] = 'document';
        //now we have to create the master style info and save it in the
        //styles.xml file
        //first the master style
        $masterStyles = $this->_dom['styles.xml']
                             ->documentElement
                             ->getElementsByTagName('master-styles')
                             ->item(0);
        $ns = 'urn:oasis:names:tc:opendocument:xmlns:style:1.0';
        $masterPageNode = $masterStyles
                          ->ownerDocument
                          ->createElementNS($ns, 'style:master-page');
        $masterPageNode->setAttribute('style:name', $masterStyleId);
        $masterPageNode->setAttribute('style:page-layout-name', $layoutStyleId);
        $masterStyles->appendChild($masterPageNode);
        //the section layout properties go in the $layoutStyleId style included
        //in the automatic-styles node of styles.xml
        $autoStyles = $this->_dom['styles.xml']
                             ->documentElement
                             ->getElementsByTagName('automatic-styles')
                             ->item(0);
        $pageLayoutNode = $autoStyles
                          ->ownerDocument
                          ->createElementNS($ns, 'style:page-layout');
        $pageLayoutNode->setAttribute('style:name', $layoutStyleId);
        $layoutNode = $autoStyles
                      ->ownerDocument
                      ->createElement('style:page-layout-properties');
        //extract the styles associated with the orientation and paperSize 
        //options
        $params = array();
        if (isset($options['orientation'])) {
            $params['style:print-orientation'] = $options['orientation'];
        } else {
            $params['style:print-orientation'] = 'portrait';
        }
        if (!isset($options['paperSize'])
            || !isset(elements\Resources::$paperSize[$options['paperSize']])) {
            $options['paperSize'] = 'A4';
        }
        foreach(elements\Resources::$paperSize[$options['paperSize']] 
                as $key => $value) {
            $params[$key] = $value;
        }
        //exchange width and height if we are in landscape mode
        if (isset($options['orientation']) 
            && $options['orientation'] == 'landscape') {
           $width = $params['fo:page-width'];
           $height = $params['fo:page-height'];
           $params['fo:page-height'] = $width;
           $params['fo:page-width'] = $height;
        }
        //parse the styles
        $pageProps = new parser\ParseCSSProperties();
        if(isset($options['style'])){
            $CSSStyle = $pageProps->parseCSS($options['style']);
        } else {
            $CSSStyle[0] = array();
        }
        $CSSStyle = parser\StyleProcessor::ODFStyles($CSSStyle[0]);
        //normalize now the names to the standard ODF style attributes
        foreach($CSSStyle as $key => $value){
            if (isset(parser\StyleProcessor::$CSSODF[$key])
                && \is_string(parser\StyleProcessor::$CSSODF[$key])) {
                $CSSStyle[parser\StyleProcessor::$CSSODF[$key]] =
                    $CSSStyle[$key];
                unset ($CSSStyle[$key]);
            } else if ( $key == 'width' || $key == 'height') {
                $CSSStyle['fo:page-' . $key] = $value;
            } 
        }
        
        //merge the style arrays
        $style = array_merge($params, $CSSStyle);
        
        //parse the background properties
        if (isset($style['xlink:href'])) {
            //create the new background-image element
            $name = commons\Utilities::getBackgroundImage($style['xlink:href']);
            if (!empty($name)) {
                $bg = $layoutNode
                        ->ownerDocument
                        ->createElement('style:background-image');
                $bg->setAttribute('xlink:href', $name);
                if (isset($style['style:position'])) {
                    $bg->setAttribute('style:position', 
                                      $style['style:position']);
                }
                if (isset($style['style:repeat'])) {
                    $bg->setAttribute('style:repeat', 
                                      $style['style:repeat']);
                }
                $layoutNode->appendChild($bg);
            }
            //unset all the background properties not to include them as
            //attributes of the layout node
            unset($style['xlink:href']);
            unset($style['style:position']);
            unset($style['style:repeat']);
        }
        //parse the column properties
        if (isset($style['fo:column-count'])) {
            $columns = $layoutNode->ownerDocument
                                  ->createElement('style:columns');

            $columns->setAttribute('fo:column-count',
                                   $style['fo:column-count']);
            if (isset($style['fo:column-gap'])) {
                $columns->setAttribute('fo:column-gap', 
                                       $style['fo:column-gap']);
            }
            //check if there is a column separator
            if (isset($style['style:style'])
                || isset($style['style:width'])
                || isset($style['style:color'])    
                    ) {
                $colSep = $layoutNode->ownerDocument
                                  ->createElement('style:column-sep');
                $colSep->setAttribute('style:height', '100%');
                if (isset($style['style:style'])) {
                    $colSep->setAttribute('style:style', 
                        $style['style:style']);
                }
                if (isset($style['style:color'])) {
                    $colSep->setAttribute('style:color', 
                        $style['style:color']);
                }
                if (isset($style['style:width'])) {
                    $colSep->setAttribute('style:width', 
                        $style['style:width']);
                }
                $columns->appendChild($colSep);
            }
            $layoutNode->appendChild($columns);
            //unset all the column properties not to include them as
            //attributes of the layout node
            unset($style['fo:column-count']);
            unset($style['fo:column-gap']);
            unset($style['style:style']);
            unset($style['style:color']);
            unset($style['style:width']);
        }
        //include all the remaining props as attributes of the layout node
        foreach ($style as $key => $value) {
            $layoutNode->setAttribute($key, $value);
        }
        //by the time being we do not elt customize the footnote separator so we
        //include the default one
        $fnSep = $layoutNode->ownerDocument
                            ->createElement('footnote-sep');
        $fnSep->setAttribute('style:width', '0.007in');
        $fnSep->setAttribute('style:rel-width', '33%');
        $fnSep->setAttribute('style:color', '#000000');
        $fnSep->setAttribute('style:line-style', 'solid');
        $fnSep->setAttribute('style:adjustment', 'left');
        $layoutNode->appendChild($fnSep);
        //append the style to the automatic styles node
        $pageLayoutNode->appendChild($layoutNode);
        $autoStyles->appendChild($pageLayoutNode);
        
        //return the parent document node so we can use chaining
        return $this;
    }
    
    /**
     * A tab can only be added to a run of text
     * 
     * @param array $options
     * @return ODFElement
     * @access public
     */
    public function tab($options = array()) 
    {
        //one can only include a list item within a list
        $message = 'You can only add a tab within a paragraph.';
        commons\DOCXPRESSOLogger::logger($message, 'info');
        return $this;
    }
    
    /**
     * adds a table to the document
     * 
     * @param array $options with the following keys and values
     *      'data' => (array)  an optional array of arrays with the
     *       values to be included in the different table rows and cells. 
     *       Beware of teh limited formatting capabilities of this option.
     *       For more advanced formatting one should use
     *       the row and cell methods.
     *       Beware of teh limited formatting capabilities of this option.
     *      'grid' => (mixed) mandatory if tableData is not given. 
     *       It may be:
     *          The number of columns (integer): all columns are of equal size
     *          An array where each item represents the respective column width
     *      'style' => (string) a list of properties in CSS format
     *      'mask' => (array) this array stablishes the table theme options that
     *       should be applied to the current table. The default values are:
     *       array( 'NE'          => true,
     *              'NW'          => true,
     *              'SE'          => true,
     *              'SW'          => false,
     *              'firstRow'    => true,
     *              'lastRow'     => false,
     *              'bandedRow'   => true,
     *              'firstCol'    => true,
     *              'lastCol'     => false,
     *              'bandedCol'   => false)
     *      'styleId' => (string) if given the styleId is not generated randomly
     * @param bool $reference if true returns a reference to the text element
     * so they can be nested.
     * @return ODFElement
     * @access public
     */
    public function table($options = array(), $reference = true) 
    {
        $element = $this->contentODF->table($options, $reference);
        if ($reference) {
            return $element;
        }
    }
    
    /**
     * inserts a cell into a row. It can not be directly used in the document
     * 
     * @return CreateDocument
     * @access public
     */
    public function cell() 
    {
        //one can only include a cell within a table row
        $message = 'You can only add a cell to a table row.';
        commons\DOCXPRESSOLogger::logger($message, 'info');
        //return the parent ODF node so we can use chaining
        return $this;
    }
    
    /**
     * inserts a row into a table. It can not be directly used in the document
     * 
     * @return CreateDocument
     * @access public
     */
    public function row() 
    {
        //one can only include a row within a table
        $message = 'You can only add a row to a table.';
        commons\DOCXPRESSOLogger::logger($message, 'info');
        return $this;
    }
    
    /**
     * adds a table of contents to the document
     * 
     * @param array $options with the following keys and values
     *      'auto-update' => (boolean) if true (default value) the  TOC will
     *       automatically update on openning and the user will not be prompted 
     *       to do so. This option is ignored unless the target document 
             is in Open Document Format (.odt)
     *      'leader-char' => (string) the leader char the joins the name of the  
     *       heading with its page number. The default value is '.'.
     *      'linked' => (boolean) if true (default value) the toc entry is
     *       linked to the corresponding heading so on can navegate through the 
     *       contents from the toc.
     *      'outline-level' => (int) an integer number (smaller than 10) that
     *       sets the the highest heading level that wil show up in the toc. The
     *       default value is 3.
     *      'title' => (string) The title, if any, of the table of contents. The
     *       format is given in the first array entry of the style option
     *      'style' => (array) an array with the styles in CSS format 
     *       for the title (style[0]) and the toc levels (the j-th level style
     *       being given by style[j]).
     * 
     * @return CreateDocument
     * @access public
     */
    public function toc($options = array()) 
    {
        $toc = $this->contentODF->toc($options);
        //return the root element to allow for chaining
        return $this;
    }
    
    /**
     * adds text into a paragraph
     * If we try to insert directly text into the document a wrapping paragraph
     * with default styles will be automatically created
     * 
     * @param array $options with the following keys and values
     *      'text' => (string)  an optional text (it can also be added later 
     *       with the text method)
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     * @param bool $reference if true returns a reference to the text element
     * so they can be nested.
     * @return ODFElement
     * @access public
     */
    public function text($options, $reference = true) 
    {
        $element = $this->contentODF->paragraph($options, $reference);
        if ($reference) {
            return $element;
        }   
    }
    
    /**
     * adds a textbox to the document
     * The textbox can be floating or enjoy absolute or relative positioning
     * 
     * @param array $options with the following keys and values
     *      'pageNumber' => (int) for absolutely positioned textboxes (optional)
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     * @param bool $reference if true returns a reference to the frame element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function textBox($options = array(), $reference = true) 
    {
        $element = $this->contentODF->textBox($options, $reference);
        if ($reference) {
            return $element;
        } else {
            return $this;
        }
    }  
    
    /**
     * adds an ordered list to the document
     * 
     * @param array $options with the following keys and values
     *      'items' => (array) a (nested) array with the items to be included
     *       in the list (plain text or document fragments). This argument is
     *       optional because the items can also be added via the itemList
     *       method.
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     * @param bool $reference if true returns a reference to the element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function orderedList($options = array(), $reference = true) 
    {
        $element = $this->contentODF->orderedList($options, $reference);
        if ($reference) {
            return $element;
        }
    }
    
    /**
     * adds an unordered list to the document
     * 
     * @param array $options with the following keys and values
     *      'items' => (array) a (nested) array with the items to be included
     *       in the list (plain text or document fragments). This argument is
     *       optional because the items can also be added via the itemList
     *       method.
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     * @param bool $reference if true returns a reference to the element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function unorderedList($options = array(), $reference = true) 
    {
        $element = $this->contentODF->unorderedList($options, $reference);
        if ($reference) {
            return $element;
        }
    }
    
    /**
     * inserts a chart into a document with all data and styling options
     * encoded in a XML string.
     * 
     * @param string $xml with the following structure
     * <chart style="CSS styles"
     *        type="column|bar|pie|donut|area|line|scatter|bubble|radar|filled-radar|column-line|3Dcolumn|3Dbar|3Dpie|3Ddonut|3Darea|3Dline|3Dscatter" 
     *        data-label-number="none|value|percentage"
     *        label-position="avoid-overlap|center|top|top-right|right|bottom-right|bottom|bottom-left|left|top-left|inside|outside|near-origin"
     *        label-position-negative="top-left|inside|outside|near-origin"
     *        hole-size="integer"
     *        pie-offset="integer"
     *        angle-offset="integer"
     *        stacked="boolean"
     *        chart-interpolation="none|b-spline|cubic-spline"
     *        spline-resolution="integer"
     *        deep="boolean" >
     *     <title color="hexadecimal color"
     *            font-family="string"
     *            font-size="float(pt|cm|in|mm)"
     *            font-weight="normal|bold"
     *            font-style="normal|italic"
     *            stroke="solid|dash|none"
     *            fill-color="hexadecimal color"
     *            opacity="integer%"
     *            stroke-width="float(pt|cm|in|mm)"
     *            stroke-color="hexadecimal color"
     *            stroke-opacity="integer%"
     *            stroke-linejoin="round|bevel|middle|miter|none"
     *            stroke-linecap="butt|round|square" >Title</title>
     *     <legend legend-position="left|right|top|bottom" 
     *             color="hexadecimal color"
     *             font-family="string"
     *             font-size="float(pt|cm|in|mm)"
     *             font-weight="normal|bold"
     *             font-style="normal|italic"
     *             stroke="solid|dash|none"
     *             fill-color="hexadecimal color"
     *             opacity="integer%"
     *             stroke-width="float(pt|cm|in|mm)"
     *             stroke-color="hexadecimal color"
     *             stroke-opacity="integer%"
     *             stroke-linejoin="round|bevel|middle|miter|none"
     *             stroke-linecap="butt|round|square"/>
     *     <grid dimension="x|y|z"
     *           type="major|minor"
     *           stroke="solid|dash|none"
     *           stroke-width="float(pt|cm|in|mm)"
     *           stroke-color="hexadecimal color"
     *           stroke-opacity="integer%"
     *           stroke-linejoin="round|bevel|middle|miter|none"
     *           stroke-linecap="butt|round|square" />
     *     <axis dimension="x|y|z" 
     *           visible="boolean"
     *           logarithmic="boolean"
     *           font-color="hexadecimal color"
     *           font-size="float(pt|cm|in|mm)"
     *           axis-position="start|end"
     *           origin="float"
     *           maximum="float"
     *           minimum ="float"
     *           label-arrangement="side-by-side|stagger-even|stagger-odd"
     *           display-level="boolean"
     *           axis-label-position="near-axis|near-axis-other-side|outside-end|outside-start"
     *           reverse-direction="boolean"
     *           text-overlap="boolean"
     *           line-break="boolean"
     *           stroke="solid|dash|none"
     *           stroke-width="float(pt|cm|in|mm)"
     *           stroke-color="hexadecimal color"
     *           stroke-opacity="integer%"
     *           stroke-linejoin="round|bevel|middle|miter|none"
     *           stroke-linecap="butt|round|square"
     *           interval-major="float"
     *           interval-minor-divisor="integer"
     *           tick-marks-major-inner="boolean"
     *           tick-marks-minor-inner="boolean"
     *           tick-marks-major-outer="boolean"
     *           tick-marks-minor-outer="boolean" />
     *     <component type="wall|floor" 
     *                fill-color="hexadecimal color"
     *                opacity="integer%"
     *                stroke="solid|dash|none"
     *                stroke-width="float(pt|cm|in|mm)"
     *                stroke-color="hexadecimal color"
     *                stroke-opacity="integer%"
     *                stroke-linejoin="round|bevel|middle|miter|none"
     *                stroke-linecap="butt|round|square" />
     *     <transform3D rotate-x="integer" 
     *                  rotate-y="integer" 
     *                  rotate-z="integer" 
     *                  right-angled-axes="true|false" 
     *                  perspective="integer" />
     *     <!-- for pie and donut charts -->
     *     <categories>
     *         <category name="" 
     *                   value=""
     *                   fill-color="hexadecimal color"
     *                   opacity="integer%"
     *                   stroke="solid|dash|none"
     *                   stroke-width="float(pt|cm|in|mm)"
     *                   stroke-color="hexadecimal color"
     *                   stroke-opacity="integer%" />
     *         <category name="" 
     *                   value=""
     *                   fill-color="hexadecimal color"
     *                   opacity="integer%"
     *                   stroke="solid|dash|none"
     *                   stroke-width="float(pt|cm|in|mm)"
     *                   stroke-color="hexadecimal color"
     *                   stroke-opacity="integer%" />
     *         <category name="" 
     *                   value=""
     *                   fill-color="hexadecimal color"
     *                   opacity="integer%"
     *                   stroke="solid|dash|none"
     *                   stroke-width="float(pt|cm|in|mm)"
     *                   stroke-color="hexadecimal color"
     *                   stroke-opacity="integer%" />
     *     </categories>
     *     <!-- for all other charts -->
     *     <series>
     *         <ser name="" 
     *              fill-color="hexadecimal color"
     *              opacity="integer%"
     *              stroke="solid|dash|none"
     *              stroke-width="float(pt|cm|in|mm)"
     *              stroke-color="hexadecimal color"
     *              stroke-opacity="integer%"
     *              solid-type="cuboid|cylinder|cone|pyramid" />
     *         <ser name="" 
     *              fill-color="hexadecimal color"
     *              opacity="integer%"
     *              stroke="solid|dash|none"
     *              stroke-width="float(pt|cm|in|mm)"
     *              stroke-color="hexadecimal color"
     *              stroke-opacity="integer%"
     *              solid-type="cuboid|cylinder|cone|pyramid" />
     *     </series>
     *     <categories>
     *         <category name="">
     *             <data value="" />
     *             <data value="" />
     *         </category>
     *         <category name="">
     *             <data value="" />
     *             <data value="" />
     *         </category>
     *         <category name="">
     *             <data value="" />
     *             <data value="" />
     *         </category>
     *     </categories>
     * </chart>
     * @param string $styleId
     * 
     * @return ODFElement
     * @access public
     */
    public function XMLChart($xml, $styleId = '') 
    {
        $element = $this->contentODF->paragraph()->XMLchart($xml, $styleId);
        return $element;
    }
    
    /**
     * Extracts all files from a ZipArchive object 
     * 
     * @param ZipArchive $zip
     * @return array
     * @access public
     * @static
     * 
     */
    public static function extractTemplateFiles($zip)
    {
        $zipContent = array();

        if ($zip instanceof \ZipArchive) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                $zipContent[$stat['name']] = $zip->getFromName($stat['name']);
            }
        } else {
            $message = 'Error trying to open the template to extract files';
            commons\DOCXPRESSOLogger::logger($message, 'critical');
        }
        return $zipContent;
    }
    
    /**
     * This method inserts a customCursor node within the provided DOMNode just
     * before the provided reference node (if empty the cursor is appended)
     * 
     * @param DOMNode $node
     * @param DOMNode $refNode
     * @param string $position
     * @return void
     * @access public
     */
    public function setCursor($node, $refNode = NULL, $position = 'before')
    {
        //first remove any pre-existing cursor
        $this->removeCursor();
        //create the element
        $cursor = $node->ownerDocument->createElement('customCursor');
        if ($position == 'before') {
            if ($refNode != NULL){
               $node->insertBefore($cursor, $refNode);
            } else {
               $node->appendChild($cursor); 
            }
        } else if ($position == 'after') {
            if ($refNode != NULL && !empty($refNode->nextSibling)){
                $this->setCursor($node, $refNode->nextSibling);
            } else {
                $this->setCursor($node);
            }
        }
    }
    
    /**
     * This method generates the final document
     * 
     * @param string $path the path where we want to save the generated file
     * @param array $options rendering options with the following keys
     * and values
     * The avalaible keys and values are:
     *  'soffice' => (string) path to soffice.exe . If not explicitely given
     *   the path for soffice stored in config.ini is used.
     *  'legacy' => (bool) if true converts the ouput to Word 97 compatible
     *   .doc file. Only applies to .doc format.
     *  'PageRange' => (string) if empty (default) includes all pages. If a 
     *   single number it renders only the corresponding page and if a range,
     *   i.e. 1-3 renders from the page 1 to three both included.
     *  'UseLosslessCompression' => (bool) if true images are not compressed. 
     *   The default is false.
     *  'Quality' => (int) the quality of the compressed images (JPG). 
     *   Deafault value is 90.
     *  'ReduceImageResolution' => (bool) if true the image resolution is 
     *   changed. Default is false.
     *  'MaxImageResolution' => (int) maximum image resolution. 
     *   Default value is 300.
     *  'SelectPdfVersion => (int) PDF version:
     *      0: PDF 1.4 (default).
     *      1: PDF/A-1 (ISO 19005-1:2005).
     *  'UseTaggedPDF => (bool) if true the generated PDF will be tagged for 
     *   accesibility. Default value is false.
     *  'ExportFormFields => (bool) exports form fields. Default value is true.
     *  'FormsType' => (int) form protocol:
     *      0: FDF.
     *      1: PDF.
     *      2: HTML(default value).
     *      3: XML.
     *  'ExportBookmarks' => (bool) exports bookmarks. Default value is true.
     *  'EmbedStandardFonts' => (bool) embeds the standard PDF fonts. Default 
     *   value is false.
     *  'Watermark' => (string) watermark text. Default is an empty string.
     *  'InitialView' => (int) initial view:
     *      0: (default) default view mode, neither outlines or thumbnails.
     *      1: outline pane opened.
     *      2: thumbnail pane opened.
     *  'Magnification' => (int) PDF magnification on openning:
     *      0: (default) default magnification.
     *      1: fit entire page within the viewer window.
     *      2: fit entire page width within the viewer window.
     *      3: fit entire page width (cutting margins) within the viewer window.
     *      4: opens with the zoom specified in the Zoom option.
     *  'Zoom' => (int) zoom level on openning. Default is 100 (it only 
     *   applies if Maginification equals 4). The available range is 50-1600.
     *  'PageLayout' => (int) PDF layout on openning:
     *      0: (default) default viewer configuration.
     *      1: one page at a time.
     *      2: display pages in one column.
     *      3: display pages in two columns.
     *  'FirstPageOnLeft' => (bool) if true the first page should be on the 
     *   left. Default is false (only used if PageLayout equals 3).
     *  'CenterWindow' => (bool) if true the PDF window is centered in the 
     *   screen. Default is false.
     *  'OpenInFullScreenMode' => (bool) if true the PDF opens in full screen 
     *   mode. Default is false.
     *  'DisplayPDFDocumentTitle' => (bool) if true the PDF window shows the 
     *   document title. Default is true.
     *  'HideViewerMenubar' => (bool) if true hides the viewer menu. Default 
     *   is false.
     *  'HideViewerToolbar' => (bool) if true hides the viewer toolbar. Default 
     *   is false.
     *  'HideViewerWindowControls' => (bool) if true hides the viewer window 
     *   controls. Default is false.
     *  'EncryptFile' => (bool) if true encrypts the file. Default is false.
     *  'DocumentOpenPassword' => (string) password to open file if the option 
     *   EncryptFile is set to true. Default is an empty string.
     *  'RestrictPermissions' => (bool) if true restricts you may restrict 
     *   printing, copying and modifying. Default is false.
     *  'PermissionPassword' => (string) password to to access the restricted 
     *   functionalities if the option DocumentOpenPassword is set to true. 
     *   Default is an empty string.
     *  'Printing' => (int) printing configuration:
     *      0: the document can not be printed.
     *      1: the document can only be printed in low resolution.
     *      2: (default) the document can be printed with no restrictions.
     *  'Changes' => (int) specify which changes are allowed:
     *      0: protected against any change.
     *      1: pages can be added, deleted or rotated.
     *      2: filling forms.
     *      3: filling forms and adding comments.
     *      4: (default) all changes are allowed but page extraction.
     *  'EnableCopyingOfContent' => (bool) if true the content can be copied 
     *   and pasted elsewhere. Default is true.
     * 
     * @return void
     * @access public
     */
    public function render($path = '', $options = array())
    {      
        //check licenses
        if (self::$config['license']['key'] == ''
            || core\License::check(self::$config['license']['key']) == false) {
            $text = 'This document has been generated with ';
            $textLink = 'DOCXPRESSO';
            $url = 'http://www.docxpresso.com';
            //$this->contentODF->paragraph();
            $legend = 'font-size: 7.5pt; color: #777777; font-family: Arial';
            /*$this->contentODF
                    ->paragraph()->style($legend)
                        ->text(array('text' => $text))
                        ->link(array('text'=> $textLink, 'url' => $url))
                            ->style('font-weight: bold; color: #b74444')
                            ->end('link')
                        ->text(array('text' => '.'));*/
                            
        }
        //set legacy to false by default
        if (!isset($options['legacy'])) {
            $options['legacy'] = false;
        }
        // get the format of the document and return if not supported
        $allowedFormats = array('odt', 'pdf', 'doc', 'docx', 'rtf');
        // get file info
        $fileInfo = \pathinfo($path);
        self::$format = \strtolower($fileInfo['extension']);
        $extLength = strlen(self::$format);
        $fileName = \substr($path, 0, -$extLength -1); 
        // if the extension is not supported throw an exception
        if (!in_array(self::$format, $allowedFormats)) {
            $message = 'The extension ' . self::$format . ' is not supported';
            commons\DOCXPRESSOLogger::logger($message, 'critical');
        }
        //check if further processing is needed
        //take into account fields to be forced to be updated
        if ( self::$format == 'docx'
             || self::$format == 'rtf'
             || self::$format == 'pdf'
             || ($options['legacy'] && self::$format == 'doc')
             || self::$toc
             || self::$fields
             || count(self::$updateMath) > 0
            ){
            $sofficeProc = '_h5p_' . \uniqid();
            $fileName .= $sofficeProc;
        }
        //check the format and redefine if needed
        if (!isset($sofficeProc)) {
            //the path is correct
        } else {
            if (self::$format == 'doc') {
                //this is a hack to let the macro do the conversion properly
                $path = $fileName . '.odt';
            } else {
                $path = $fileName . '.odt';
            }
        }

        // check if we can write on the path
        if (!fopen($path, 'w')) {
            $message = 'Unable to write ' . $path . '. Check write access.';
            commons\DOCXPRESSOLogger::logger($message, 'critical');
        }
        
        //create the new zip file
        $zip = new \ZipArchive();
        $zip->open($path);
        //check for page 'open' breaks
        if (self::$break == 'page' || self::$break == 'column') {
            $options = array();
            $options['style'] = 'break-before: ' . self::$break;
            $this->contentODF->paragraph($options, false);
            self::$break == 'none';
        }
        //save the modified DOMDocuments into the template
        //content.xml
        //remove cursor if any
        $this->removeCursor($this->content);
        //repair the grid data in tables that have been generated from HTML code
        // and/or add styles comming from a theme or table and row borders
        foreach (self::$tables as $key => $value) {
            $this->_repairTable($key);
        }
        //at this point we have to process all styles comming from the static
        //arrays: self::$automaticStyles & self::$styleTypes
        //that are included in the content.xml office:automatic-styles element
        $this->_styleProccesor->processStyles();
        //we have to do some extra work if there are some "pending" header and
        //footer styles to parse
        $numStyle = \count(self::$styleHeader);
        $numStyle += \count(self::$styleHeader);
        if ($numStyle > 0) {
            //get the relevant nodes using xpath
            $xStyle = new \DOMXPath($this->_dom['styles.xml']);
            foreach (self::$styleHeader as $key => $value){
                $query = '//style:page-layout[@style:name="' . $key . '"]';
                $layout = $xStyle->query($query)->item(0);
                $st = $layout->getElementsByTagName('header-style')
                                      ->item(0);
                $prop = $st->getElementsByTagName('header-footer-properties')
                                      ->item(0);
                $this->_insertHeaderFooterStyle($prop, $value);
            } 
            
        }
        //take care of the tabs
        if (\count(self::$tabStops) > 0) {
            $this->_parseTabs();
        }
        //repair sections for visualization in Word
        foreach (self::$sectionIds as $key => $value) {
            $this->_repairSections($value);
        }
        if (self::$format == 'rtf') {
            $this->_repairFloats();
        }
        //remove fo:background-color="transparent" for docx rendering otherwise
        //one get an undesirable white background
        if (self::$format == 'docx') {
            $this->_removeEmptyBackground($this->_dom['content.xml']);
            $this->_removeEmptyBackground($this->_dom['styles.xml']);
        }
        //if the target format is doc we have to remove the image luminance
        //because it is not properly handled by Word
        if (self::$format == 'doc') {
            $this->_removeImageLuminance($this->_dom['content.xml']);
            $this->_removeImageLuminance($this->_dom['styles.xml']);
        }
        $this->template['content.xml'] = $this->_dom['content.xml']->saveXML();
        //style.xml  
        $this->template['styles.xml'] = $this->_dom['styles.xml']->saveXML();
        //styles for lists
        if (count(self::$list)) {
            $bullet = '<style:style style:name="docxpresso_bullet" ';
            $bullet .= 'style:family="text" >';
            $bullet .= '<style:text-properties ';
            $bullet .= 'style:font-name="OpenSymbol" ';
            $bullet .= 'style:font-name-asian="OpenSymbol" ';
            $bullet .= 'style:font-name-complex="OpenSymbol" /></style:style>';
            $bullet .= '</office:styles>';
            $this->template['styles.xml'] = str_replace(
                                            '</office:styles>',
                                            $bullet,
                                            $this->template['styles.xml']
                                            );
            $font = '<style:font-face style:name="OpenSymbol" ';
            $font .= 'svg:font-family="OpenSymbol" />';
            $font .= '</office:font-face-decls>';
            $this->template['styles.xml'] = str_replace(
                                            '</office:font-face-decls>',
                                            $font,
                                            $this->template['styles.xml']
                                            );
        }
        //footnotes and endnotes styles
        if (self::$currentFootnote > 0 || self::$currentEndnote > 0){
            $note = '<style:style style:name="_note" style:family="text" >';
            $note .= '<style:text-properties ';
            $note .= 'style:text-position="super 58%" /></style:style>';
            $note .= '</office:styles>';
            $this->template['styles.xml'] = str_replace(
                                            '</office:styles>',
                                            $note,
                                            $this->template['styles.xml']
                                            );
        }
        if (self::$currentFootnote > 0){
            $footnoteStyle = '<text:notes-configuration ';
            $footnoteStyle .= 'text:note-class="footnote" ';
            $footnoteStyle .= 'text:citation-style-name="_note" ';
            $footnoteStyle .= 'text:citation-body-style-name="_note" ';
            $footnoteStyle .= 'text:start-value="0" style:num-format="1" ';
            $footnoteStyle .= 'text:start-numbering-at="document" ';
            $footnoteStyle .= 'text:footnotes-position="page" />';
            $footnoteStyle .= '</office:styles>';
            $this->template['styles.xml'] = str_replace(
                                            '</office:styles>',
                                            $footnoteStyle,
                                            $this->template['styles.xml']
                                            );
        }
        if (self::$currentEndnote > 0){
            $endnoteStyle = '<text:notes-configuration ';
            $endnoteStyle .= 'text:note-class="endnote" ';
            $endnoteStyle .= 'text:citation-style-name="_note" ';
            $endnoteStyle .= 'text:citation-body-style-name="_note" ';
            $endnoteStyle .= 'text:start-value="0" style:num-format="i" ';
            $endnoteStyle .= 'text:start-numbering-at="document" ';
            $endnoteStyle .= '/>';
            $endnoteStyle .= '</office:styles>';
            $this->template['styles.xml'] = str_replace(
                                            '</office:styles>',
                                            $endnoteStyle,
                                            $this->template['styles.xml']
                                            );
        }
        //insert all the required media and modify the manifest file
        foreach (self::$media as $key => $value) {
            $zip->addFromString($key, $value);
            $manifestEntry = $this->_dom['META-INF/manifest.xml']
                                  ->createElement('manifest:file-entry');
            $extension = \pathinfo($key, PATHINFO_EXTENSION );
            $extension = \str_replace('jpg', 'jpeg', $extension);
            $manifestEntry->setAttribute('manifest:media-type', 
                                         'image/' . $extension);
            $manifestEntry->setAttribute('manifest:full-path', $key);
            $this->manifest->appendChild($manifestEntry);
        }
        //insert all the required charts and modify the manifest file
        foreach (self::$charts as $key => $value) {
            //in principle every chart has associated a content.xml file and
            //a styles.xml file that is required for Word rendering
            //content.xml
            if (self::$format == 'doc'
                || self::$format == 'docx'
                || self::$format == 'rtf') {
                $chartStyle = $value->getStyleId();
                $nodeChart =$this->_styleProccesor
                                 ->processSingleStyle('chart:chart', 
                                          $chartStyle, 
                                          self::$automaticStyles[$chartStyle], 
                                          $target = 'chart');
                $value->repairChart4Word($nodeChart);
            }
            $zip->addFromString($key . '/content.xml', $value);
            $manifestEntry = $this->_dom['META-INF/manifest.xml']
                                  ->createElement('manifest:file-entry');
            $manifestEntry->setAttribute('manifest:full-path', 
                                         $key . '/content.xml');
            $manifestEntry->setAttribute('manifest:media-type', 'text/xml');
            $this->manifest->appendChild($manifestEntry);
            //styles.xml
            $zip->addFromString($key . '/styles.xml', chart\Chart::$styleXML);
            $manifestEntry = $this->_dom['META-INF/manifest.xml']
                                  ->createElement('manifest:file-entry');
            $manifestEntry->setAttribute('manifest:full-path', 
                                         $key . '/styles.xml');
            $manifestEntry->setAttribute('manifest:media-type', 'text/xml');
            
            //chart media type manifest entry
            $this->manifest->appendChild($manifestEntry);
            $manifestEntry2 = $this->_dom['META-INF/manifest.xml']
                                  ->createElement('manifest:file-entry');
            $manifestEntry2->setAttribute('manifest:full-path', $key . '/');
            $manifestEntry2->setAttribute('manifest:media-type', 
                                         'application/vnd.oasis.opendocument.chart'
                                        );
            $this->manifest->appendChild($manifestEntry2);
        }
        //insert all the required math equations and modify the manifest file
        foreach (self::$math as $key => $value) {
            //in principle every math equation has associated a content.xml 
            //file and an optional settings.xml file
            //content.xml
            $zip->addFromString($key . '/content.xml', $value);
            $manifestEntry = $this->_dom['META-INF/manifest.xml']
                                  ->createElement('manifest:file-entry');
            $manifestEntry->setAttribute('manifest:media-type', 'text/xml');
            $manifestEntry->setAttribute('manifest:full-path', 
                                         $key . '/content.xml');
            $this->manifest->appendChild($manifestEntry);
            $manifestEntry2 = $this->_dom['META-INF/manifest.xml']
                                  ->createElement('manifest:file-entry');
            $manifestEntry2->setAttribute('manifest:media-type', 
                                         'application/vnd.oasis.opendocument.formula'
                                        );
            $manifestEntry2->setAttribute('manifest:full-path', $key . '/');
            $this->manifest->appendChild($manifestEntry2);
            //settings.xml
            $zip->addFromString($key . '/settings.xml', 
                                self::$mathSettings[$key]);
            $manifestEntry = $this->_dom['META-INF/manifest.xml']
                                  ->createElement('manifest:file-entry');
            $manifestEntry->setAttribute('manifest:media-type', 'text/xml');
            $manifestEntry->setAttribute('manifest:full-path', 
                                         $key . '/settings.xml');
            $this->manifest->appendChild($manifestEntry);
        }
        //META-INF/manifest.xml
        $this->template['META-INF/manifest.xml'] = 
            $this->_dom['META-INF/manifest.xml']->saveXML();
        
        //insert the contents
        foreach ($this->template as $key => $value) {
            $zip->addFromString($key, $value);  
        }
        $success = $zip->close();

        //check if further processing is needed
        //take into account fields to be forced to be updated
        if ( isset($sofficeProc) && $success){
            
            $baseURI = \getcwd();
            
            //fill the lacking $options
            $options['outputFormat'] = \strtoupper(self::$format);
            if ($this->_isAbsolutePath($fileName)) {
                $options['fileName'] = $fileName;
            } else {
                $options['fileName'] = $baseURI . '/' . $fileName;
            }
            if (self::$toc) {
                $options['updateTOC'] = true;
            }
            if (self::$fields) {
                $options['updateFields'] = true;
            }
            if (count(self::$updateMath) > 0) {
                $options['updateMath'] = true;
            }          
            
            //create an instance of the Render class
            $render = new core\Render();
            $opt = $render->options($options);
            $opt = \str_replace('_nil_', '', $opt);
            $tmpName = '/csv/' . microtime(true) . '-' . mt_rand(9, 99999999);
            $tmpFile = fopen( __DIR__ . $tmpName . '.tmp' , 'w');
            \fwrite($tmpFile, $opt);
            \fclose($tmpFile);
            \rename(__DIR__ . $tmpName . '.tmp', __DIR__ . $tmpName . '.csv');
        }
        
    }
    
    /**
     * selects the following  node in the document tree
     * 
     * @param DOMNode $node
     * @return mixed (DOMnode or NULL)
     * @access private
     */
    private function _followingNode($node) 
    {        
        $next = $node->nextSibling;
        if ($next !== NULL) {
            $nextType = $next->nodeType;
            if ($nextType == 1) {
                return $next;
            } else {
                return $this->_followingNode($next);           
            }
        } else {
            return NULL;
        } 
    }
    
    /**
     * generates the header or footer wrapper
     * 
     * @param string $type
     * @param array $options
     * @return ODFElement
     * @access private
     */
    private function _header_footer($type, $options) 
    {
        if (isset($options['dynamic-spacing']) 
            && $options['dynamic-spacing'] == true) {
            $dynamicSpacing = 'true';
        } else {
            $dynamicSpacing = 'false';
        }
        if (isset($options['section']) 
                   && \is_numeric($options['section'])) {
            $section = $options['section'];
        } else {
            $section = 'current';
        }
        //select the required master style node and grab its name
        $masterStyles = $this->_dom['styles.xml']
                             ->documentElement
                             ->getElementsByTagName('master-page');
        $numSections = $masterStyles->length;
        if ($section == 'current') {
            $section = $numSections;
        }
        $counter = 1;
        foreach ($masterStyles as $master) {
            if ($section == $counter){
               $pageName = $master->getAttribute('style:page-layout-name');
               $pageNode = $master;
            }
            $counter++;
        }
        //determine the node name from the $type parameter and the
        //$options['left-page'] value
        if (isset($options['left-page']) && $options['left-page'] === true) {
            $nodeName = $type . '-left';
        } else {
            $nodeName = $type;
        }
        //check if there is already a node with that tag name and if so 
        //remove it
        $affectedNodes = $pageNode->getElementsByTagName($nodeName);
        if ($affectedNodes->length > 0) {
           $affectedNodes->item(0)->parentNode
                         ->removeChild($affectedNodes->item(0));
        }
        //create the corresponding new node
        $ns = 'urn:oasis:names:tc:opendocument:xmlns:style:1.0';
        $newNode = $pageNode->ownerDocument
                            ->createElementNS($ns, 'style:' . $nodeName);
        $pageNode->appendChild($newNode);
        //build a ODFElement object associated with the selected master node so
        //we can add content to it via chaining
        $odfElement = new elements\ODFElement($newNode, NULL, 'header-footer');
        //get the corresponding page layout node so we can handle the specific
        //options for the header/footer
        $layoutNodes = $this->_dom['styles.xml']
                            ->documentElement
                            ->getElementsByTagName('page-layout');
        foreach($layoutNodes as $page) {
            $name = $page->getAttribute('style:name');
            if ($name == $pageName) {
              $layoutNode = $page;  
            }
        }
        //check again if there is already a node with the required styles
        $affectedNodes = $layoutNode->getElementsByTagName($type . '-style');
        if ($affectedNodes->length > 0) {
           $affectedNodes->item(0)->parentNode
                         ->removeChild($affectedNodes->item(0));
        }
        //create the corresponding new node
        $ns = 'urn:oasis:names:tc:opendocument:xmlns:style:1.0';
        $styleNode = $pageNode
                     ->ownerDocument
                     ->createElementNS($ns, 'style:' . $type . '-style');
        $styleProps  = $pageNode
                       ->ownerDocument
                       ->createElementNS($ns, 'style:header-footer-properties');
        $styleProps->setAttribute('style:dynamic-spacing', $dynamicSpacing);
        //parse the specific styles that may include: border, margin and padding
        //as well as min-height (see parser\StyleProcessor::$headerFooterStyles
        //for a complete list)
        //parse the styles        
        if (isset($options['style'])) {
            $this->_insertHeaderFooterStyle($styleProps, $options['style']);
        }
        //append the style nodes
        $styleNode->appendChild($styleProps);
        $layoutNode->appendChild($styleNode);
        //return the element required for chaining
        return $odfElement;   
    }
    
    /**
     * detects if a path is absolute or relative
     * 
     * @param string $path
     * @return ODFElement
     * @access private
     */
    private function _isAbsolutePath($path) 
    {
        $initial = \substr($path, 0, 1);
        if ($initial == '/' | $initial == '\\') {
            return true;
        }
        $regex = '/^[a-zA-Z0-9]?:/';
        $volume = \preg_match($regex, $path);
        if ($volume == 1) {
            return true;
        }
        return false;
    }
    
    /**
     * inserts the styles of each header and footer
     * 
     * @param DOMNode $node
     * @param string $style
     * @return ODFElement
     * @access private
     */
    private function _insertHeaderFooterStyle($node, $style) 
    {
        $ns = array(
            'fo' => 'urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0',
            'style' => 'urn:oasis:names:tc:opendocument:xmlns:style:1.0',
            'svg' => 'urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0',
        );
        if(\is_array($style)){
                $CSSStyle = $style;
        } else {
            $props = new parser\ParseCSSProperties();
            $CSSSArray = $props->parseCSS($style);
            $CSSStyle = \array_merge($CSSSArray[0], $CSSSArray[1]);
        }
        $CSSStyle = parser\StyleProcessor::ODFStyles($CSSStyle);
        foreach ($CSSStyle as $key => $value) {
            if (isset(parser\StyleProcessor::$CSSODF[$key])
                && isset(parser\StyleProcessor::$headerFooterStyles[$key])) {
                if ($key != 'height') {
                    $node->setAttributeNS($ns[parser\StyleProcessor::$headerFooterStyles[$key]],
                                          parser\StyleProcessor::$CSSODF[$key],
                                          $value);            
                } else {
                    $node->setAttributeNS($ns['svg'], 'svg:height', $value);
                }
            }
        }
    }
    
    /**
     * This method takes care of the document tab positions and styles
     * 
     * @return void
     * @access private
     */
    private function _parseTabs()
    {
        //if not given we will use a default tab separation of 36pt
        $defaultTabSpacing = 36;
        $xpath_content = new \DOMXPath($this->_dom['content.xml']);
        $xpath_style = new \DOMXPath($this->_dom['styles.xml']);
        foreach (self::$tabStops as $styleId => $tab) {
            if ($tab[0]['target'] == 'header-footer' 
                || $tab[0]['target'] == 'style') {
                $domDoc = $this->_dom['styles.xml'];
                $xpath = $xpath_style;
            } else {
                $domDoc = $this->_dom['content.xml'];
                $xpath = $xpath_content;
            }
            $tabStops = $domDoc->createElement('style:tab-stops');
            $tabCounter = 0;
            $tabPosition = 0;
            foreach ($tab as $key => $op) {
                $tabStop = $domDoc->createElement('style:tab-stop');
                if (isset($op['type'])) {
                    $tabStop->setAttribute('style:type', $op['type']);
                }
                if (isset($op['character'])) {
                    $tabStop->setAttribute('style:char', $op['character']);
                } else {
                    $tabStop->setAttribute('style:char', '.');
                }
                if (isset($op['leader'])) {
                    $tabStop->setAttribute('style:leader-style', $op['leader']);
                }
                if (isset($op['position'])) {
                    $tabPosition += $op['position'];
                } else {
                    $tabPosition += $defaultTabSpacing;
                }
                $tabStop->setAttribute('style:position', $tabPosition . 'pt');
                $tabStops->appendChild($tabStop);
                $tabCounter++;
            }
            //append the style:tab-stops element in the corresponding style file
            $query = '//style:style[@style:name="' . $styleId . '"]';
            $query .= '/style:paragraph-properties';
            $styleNode = $xpath->query($query)->item(0);
            $styleNode->appendChild($tabStops);
        }
    }
    
    /**
     * parses the current template for placeholder variables and stores the 
     * result in the placeholderVariables static variable
     * 
     * @param string $type it can be document header or footer
     * @param array $options an array with the following keys and values:
     *  'format' => (array) an array with two entries giving the chosen
     *   openning and closing symbol for template variables. The default value
     *   is array('{{', '}}') but any other combination of symbols is possible.
     *   we do not recommend using the same symbol for both arrays entries
     *   or symbols that may be used somewhere else within the document because
     *   that may lead to "false positives"
     * @return CreateDocument
     * @access public
     */
    private function _parseVars($type = 'document', $options = array()) 
    {
        if ($type == 'document') {
            $dom = $this->_dom['content.xml'];
        } else {
            $dom = $this->_dom['styles.xml'];
        }
        if (!isset($options['format'])) {
            $start = '{{';
            $end = '}}';
        } else {
            $start = $options['format'][0];
            $end = $options['format'][1];
        }
        //select text nodes that contain both start and end
        $xpath = new \DOMXPath($dom);
        if ($type == 'document') {
            $root = '*';
        } else if ($type == 'header') {
            $root = 'style:header';
        } else if ($type == 'footer') {
            $root = 'style:footer';
        }
        $query = '//' . $root . '//text()[contains(., "' . $start . '") and '; 
        $query .= 'contains(., "' . $end . '")]';
        $nodes = $xpath->query($query);
        foreach ($nodes as $node) {
            if ($node->parentNode->nodeName == 'svg:title') {
                if ($node->parentNode->parentNode
                         ->firstChild->nodeName == 'draw:image') {
                    $nType = 'image';
                } else {
                    $nType = 'object';
                }
            } else {
                $nType = 'text';
            }
            $text = $node->nodeValue;
            //extract the variable names
            $base = \explode($start, $text);
            if ($start != $end) {
                for ($j = 0; $j < count($base); $j++) {
                    $data = \explode($end, $base[$j]);
                    if (!empty($data[0]) && count($data) > 1){
                        self::$placeholderVariables[$type][$data[0]] = $nType;
                    }
                }
            } else {
               for ($j = 0; $j < count($base); $j++) {
                    if (!empty($base[$j]) && $j%2 === 1){
                        self::$placeholderVariables[$type][$base[$j]] = $nType;
                    }
                } 
            }

        }
        //we should finally look for variables within bookmark names
        $xpath->registerNamespace('php', 'http://php.net/xpath');
        $xpath->registerPhpFunctions('preg_match');
        $query = '//text:bookmark-start';
        $reg = '/' . $start . '.*' . $end .'/';
        $query .= "[php:functionString('preg_match', '$reg', @text:name) > 0]";
        $bnodes = $xpath->query($query);
        foreach ($bnodes as $node) {
            $attr = $node->getAttribute('text:name');
            $attr = \str_replace(array($start, $end), '', $attr);
            if (!isset(self::$placeholderVariables[$type][$attr])) {
                //we check that there is no other variable with the same name
                //already
                self::$placeholderVariables[$type][$attr] = 'bookmark';
            }
        }
    }
    /**
     * This method makes sure that the custom template that is going to be
     * used fulfills certain conditions
     * 
     * @return void
     * @access private
     */
    private function _processTemplate()
    {
        
    }
    
    /**
     * This method repairs the transparent backgrounds and empty
     * background-image nodes for docx rendering
     * 
     * @param string $dom
     * @return void
     * @access private
     */
    private function _removeEmptyBackground($dom)
    {  
        $xpath = new \DOMXPath($dom);
        $query = '//style:paragraph-properties';
        $query .= '[@fo:background-color="transparent"]';
        $nodes = $xpath->query($query);
        foreach ($nodes as $node) {
            $node->removeAttribute('fo:background-color');
        }
        //we need also to remove empty background-image nodes
        $query = '//style:background-image[not(@xlink:href)]';
        $nodes = $xpath->query($query);
        foreach ($nodes as $node) {
            $node->parentNode->removeChild($node);
        }
    }
    
    /**
     * This method repairs the luminance attribute that it is not correctly
     * rendered by Word
     * 
     * @param string $dom
     * @return void
     * @access private
     */
    private function _removeImageLuminance($dom)
    {  
        $xpath = new \DOMXPath($dom);
        $query = '//style:graphic-properties';
        $query .= '[@draw:luminance="0%"]';
        $nodes = $xpath->query($query);
        foreach ($nodes as $node) {
            $node->removeAttribute('draw:luminance');
        }
    }
    
    /**
     * This method repairs the floating frames that are anchored to 'char' 
     * for RTF rendering (this method is not used otherwise)
     * 
     * @param string $key
     * @return void
     * @access private
     */
    private function _repairFloats()
    {  
        $xpath = new \DOMXPath($this->_dom['content.xml']);
        $query = '//draw:frame[@text:anchor-type="char"]';
        $frameNodes = $xpath->query($query);
        foreach ($frameNodes as $frame) {
            $frame->setAttribute('text:anchor-type', 'paragraph');
        }
    }
    
    /**
     * This method repairs the grid of tables generated via the html method
     * 
     * @param string $key
     * @return void
     * @access private
     */
    private function _repairTable($key)
    {
        $xpath = new \DOMXPath($this->_dom['content.xml']);
        $query = '//table:table[@xml:id="' . $key . '"]';
        $tableNode = $xpath->query($query)->item(0);
        $docDOM = $this->_dom['content.xml'];
        if ($tableNode === NULL) {
            //The table may be in the header or footer
            $xpath = new \DOMXPath($this->_dom['styles.xml']);
            $tableNode = $xpath->query($query)->item(0); 
            $docDOM = $this->_dom['styles.xml'];
        }
        if ($tableNode === NULL) {
            $message = 'There was a problem grabbing a themed table.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            return;
        }
        //if created by the html method there only can be a single column node
        //so we have to repair the grid
        if (isset(self::$tables[$key]['html']) && self::$tables[$key]['html']) {
            $stylesNode = $docDOM->getElementsByTagName('automatic-styles')
                                 ->item(0);
            $column = $tableNode->getElementsByTagName('table-column')->item(0);
            $ns = 'urn:oasis:names:tc:opendocument:xmlns:style:1.0';
            foreach (self::$tables[$key]['grid'] as $size) {
                $newCol = $docDOM->createElement('table:table-column');
                $colStyleId = commons\Utilities::generateId('style_');
                $newCol->setAttribute('table:style-name', $colStyleId);
                $column->parentNode->insertBefore($newCol, $column);
                //create the corresponding style element
                $colStyle = $docDOM->createElementNS($ns,'style:style');
                $colStyle->setAttributeNS($ns, 'style:name', $colStyleId);
                $colStyle->setAttributeNS($ns, 'style:family', 'table-column');
                $stCol = $docDOM->createElementNS($ns, 
                                              'style:table-column-properties');
                //Define the style of the column based on the grid values
                if (strpos($size, '%') !== false) {
                    $size = str_replace('%', '*', $size);
                    $stCol->setAttributeNS($ns, 
                                           'style:rel-column-width',
                                           \trim($size));
                } else {
                    if (!empty($size)) {
                        $stCol->setAttributeNS($ns, 
                                               'style:column-width',
                                               \trim($size));
                    }
                }
                $colStyle->appendChild($stCol);
                $stylesNode->appendChild($colStyle);
            }
            //remove the original column element
            $column->parentNode->removeChild($column);
            //Now compute the total width in points
            $width = elements\Table::tableWidth(self::$tables[$key]['grid']);
            if (!empty($width)) {
                $identifier = $tableNode->getAttribute('table:style-name');
                $query = '//style:style[@style:name="' . $identifier . '"]';
                $query .= '/style:table-properties';
                $tableStyle = $xpath->query($query)->item(0);
            }
        }
        
        //for Word we need to insert the table:covered-table-cell
        //elements that may be lacking
        $table =& self::$tables[$key]['structure'];
        $gridCount = \count($table[0]);
        $rowNodes = $tableNode->getElementsByTagName('table-row');                      
        foreach ($rowNodes as $rowNode) {
            $cols = $rowNode->getElementsByTagName('table-cell')
                            ->length;
            $cols += $rowNode->getElementsByTagName('covered-table-cell')
                             ->length;
            if ($cols < $gridCount) {
                //there are lacking entries in the row
                $lackingCells = $gridCount - $cols;
                for ($j = 0; $j < $lackingCells; $j++) {
                    $tableNS = 'urn:oasis:names:tc:opendocument:xmlns:table:1.0';
                    $coveredCell = $rowNode->ownerDocument
                        ->createElement($tableNS, 'table:covered-table-cell');
                    $rowNode->appendChild($coveredCell);
                }
           }
        }
        if (isset(self::$tables[$key]['tableBorders'])) {
            //borders can not be defined at the table or row level so
            //we need to parse the whole table
            $this->_tableBorders($tableNode, $key);
        }
        if (!empty(self::$theme)) {
           if (isset(self::$tables[$key]['theme'])) {
                $class = self::$tables[$key]['theme'];
                $mask = self::$tables[$key]['mask'];
                $this->_themeTableStyles($tableNode, $key, $class, $mask);
            }
        }
        
        
    }
    
    /**
     * This method repairs the hidden section nodes inserted by DOCXPRESSO for
     * their correct visualization in Word (by default Word shows hidden text
     * in editing mode).
     * 
     * @param string $key
     * @return void
     * @access private
     */
    private function _repairSections($key)
    {  
        $xpath = new \DOMXPath($this->_dom['content.xml']);
        $query = '//text:p[@text:style-name="' . $key . '"]';
        $sectNode = $xpath->query($query)->item(0);
        $nextParagraph = $this->_followingNode($sectNode);
        if (!empty($nextParagraph)) {
            $refArray = \explode('_', $key);
            $ref = \array_pop($refArray);
            $nodeName = $nextParagraph->nodeName;
            if ($nodeName == 'table:table'){
                $styleId = $nextParagraph->getAttribute('table:style-name');
            } else if ($nodeName == 'draw:frame'){
                $styleId = $nextParagraph->getAttribute('draw:style-name');
            } else if ($nodeName == 'text:list'){
                $p = $nextParagraph->getElementsByTagName('text:p')->item(0);
                if (!empty($p)) {
                    $styleId = $p->getAttribute('text:style-name');  
                }   
            } else {
                $styleId = $nextParagraph->getAttribute('text:style-name');
            }
            $xpath = new \DOMXPath($this->_dom['content.xml']);
            //if the retrieval of the style id failed we leave
            if (empty($styleId)) {
                $message = 'The section master page could not be repaired.';
                commons\DOCXPRESSOLogger::logger($message, 'info');
                return;
            }
            $query = '//style:style[@style:name="' . $styleId . '"] ';
            $styleNode = $xpath->query($query)->item(0);
            $masterPage = $styleNode->getAttribute('style:master-page-name');
            if (empty($masterPage)) {
                $styleNode->setAttribute('style:master-page-name', 
                                         'master_' . $ref);
                $sectNode->parentNode->removeChild($sectNode);
                if (self::$format == 'rtf') {
                    //If the output format is RTF we have to take care of certain
                    //styling properties that spoil the conversion like the
                    //paddings!!!
                    $parProps = $styleNode
                                ->getElementsByTagName('paragraph-properties')
                                ->item(0);
                    $attr2Remove = array('fo:padding-top' => true,
                                         'fo:padding-right' => true,
                                         'fo:padding-bottom' => true,
                                         'fo:padding-left' => true,    
                                        );
                    $attr= $parProps->attributes;
                    $length = $attr->length;
                    for ($j = $length-1; $j >= 0; $j--) {
                        if (\in_array($attr->item($j)->name, $attr2Remove)) {
                            $parProps->removeAttributeNode($attr->item($j));
                        }
                    } 
                }
            } else {
                return;
            }
        }
    }
    
    /**
     * includes the table theme styles
     * 
     * @param DOMNode $tableNode
     * @param string $key
     * @param string $class
     * @param array $mask
     * @return void
     * @access private
     */
    private function _themeTableStyles($tableNode, $key, $class, $mask)
    {
        $defaultMask = array('NE'          => true,
                             'NW'          => true,
                             'SE'          => true,
                             'SW'          => false,
                             'firstRow'    => true,
                             'lastRow'     => false,
                             'bandedRow'   => true,
                             'firstCol'    => true,
                             'lastCol'     => false,
                             'bandedCol'   => false);
        $mask = \array_merge($defaultMask, $mask);
        //get all rows
        $rows = $tableNode->getElementsByTagName('table-row');
        $rowCounter = 1;
        $numRows = $rows->length;
        foreach ($rows as $row) {
            $cols = $row->getElementsByTagName('table-cell');
            $colCounter = 1;
            $numCols = $cols->length;
            foreach ($cols as $col) {
                //first get the style name
                $style = $col->getAttribute('table:style-name');
                //now we have to run over all the possibilities that are plenty
                //we will do it in order preserving precedence
                //set a mark where the theme styles are going to start to be 
                //added
                self::$automaticStyles[$style] = ';theme:default;' .
                            self::$automaticStyles[$style];
                //specific class
                if (!empty($class)) {
                    if ($rowCounter == 1 && $colCounter == 1 && $mask['NE']) {
                        $selector = 'table.' . $class . ' td.NE';
                        if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                            self::$automaticStyles[$style] = 
                                    themes\ThemeManager::$CSSProps[$selector] .
                                    self::$automaticStyles[$style];
                        }
                    }
                    if ($rowCounter == 1 
                        && $colCounter == $numCols 
                        && $mask['NW']) {
                        $selector = 'table.' . $class . ' td.NW';
                        if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                            self::$automaticStyles[$style] = 
                                    themes\ThemeManager::$CSSProps[$selector] .
                                    self::$automaticStyles[$style];
                        }
                    }
                    if ($rowCounter == $numRows 
                        && $colCounter == 1 
                        && $mask['SE']) {
                        $selector = 'table.' . $class . ' td.SE';
                        if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                            self::$automaticStyles[$style] = 
                                    themes\ThemeManager::$CSSProps[$selector] .
                                    self::$automaticStyles[$style];
                        }
                    }
                    if ($rowCounter == $numRows 
                        && $colCounter == $numCols 
                        && $mask['SW']) {
                        $selector = 'table.' . $class . ' td.SW';
                        if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                            self::$automaticStyles[$style] = 
                                    themes\ThemeManager::$CSSProps[$selector] .
                                    self::$automaticStyles[$style];
                        }
                    }
                    if ($rowCounter == 1 && $mask['firstRow']) {
                        $selector = 'table.' . $class . ' td.firstRow';
                        if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                            self::$automaticStyles[$style] = 
                                    themes\ThemeManager::$CSSProps[$selector] .
                                    self::$automaticStyles[$style];
                        }
                    }
                    if ($colCounter == 1 && $mask['firstCol']) {
                        $selector = 'table.' . $class . ' td.firstCol';
                        if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                            self::$automaticStyles[$style] = 
                                    themes\ThemeManager::$CSSProps[$selector] .
                                    self::$automaticStyles[$style];
                        }
                    }
                    if ($rowCounter == $numRows
                        && $rowCounter != 1
                        && $mask['lastRow']) {
                        $selector = 'table.' . $class . ' td.lastRow';
                        if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                            self::$automaticStyles[$style] = 
                                    themes\ThemeManager::$CSSProps[$selector] .
                                    self::$automaticStyles[$style];
                        }
                    } 
                    if ($colCounter == $numCols
                        && $colCounter != 1
                        && $mask['lastCol']) {
                        $selector = 'table.' . $class . ' td.lastCol';
                        if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                            self::$automaticStyles[$style] = 
                                    themes\ThemeManager::$CSSProps[$selector] .
                                    self::$automaticStyles[$style];
                        }
                    } 
                    if ($rowCounter%2 == 0 && $mask['bandedRow']) {
                        $selector = 'table.' . $class . ' td.bandedRow';
                        if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                            self::$automaticStyles[$style] = 
                                    themes\ThemeManager::$CSSProps[$selector] .
                                    self::$automaticStyles[$style];
                        }
                    }
                    if ($colCounter%2 == 0 && $mask['bandedCol']) {
                        $selector = 'table.' . $class . ' td.bandedCol';
                        if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                            self::$automaticStyles[$style] = 
                                    themes\ThemeManager::$CSSProps[$selector] .
                                    self::$automaticStyles[$style];
                        }
                    }
                    $selector = 'table.' . $class . ' td';
                    if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                        self::$automaticStyles[$style] = 
                                themes\ThemeManager::$CSSProps[$selector] .
                                self::$automaticStyles[$style];
                    }
                }
                //general styles
                if ($rowCounter == 1 && $colCounter == 1 && $mask['NE']) {
                    $selector = 'table td.NE';
                    if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                        self::$automaticStyles[$style] = 
                                themes\ThemeManager::$CSSProps[$selector] .
                                self::$automaticStyles[$style];
                    }
                }
                if ($rowCounter == 1 
                    && $colCounter == $numCols 
                    && $mask['NW']) {
                    $selector = 'table td.NW';
                    if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                        self::$automaticStyles[$style] = 
                                themes\ThemeManager::$CSSProps[$selector] .
                                self::$automaticStyles[$style];
                    }
                }
                if ($rowCounter == $numRows 
                    && $colCounter == 1 
                    && $mask['SE']) {
                    $selector = 'table td.SE';
                    if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                        self::$automaticStyles[$style] = 
                                themes\ThemeManager::$CSSProps[$selector] .
                                self::$automaticStyles[$style];
                    }
                }
                if ($rowCounter == $numRows 
                    && $colCounter == $numCols 
                    && $mask['SW']) {
                    $selector = 'table td.SW';
                    if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                        self::$automaticStyles[$style] = 
                                themes\ThemeManager::$CSSProps[$selector] .
                                self::$automaticStyles[$style];
                    }
                }
                if ($rowCounter == 1 && $mask['firstRow']) {
                    $selector = 'table td.firstRow';
                    if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                        self::$automaticStyles[$style] = 
                                themes\ThemeManager::$CSSProps[$selector] .
                                self::$automaticStyles[$style];
                    }
                }
                if ($colCounter == 1 && $mask['firstCol']) {
                    $selector = 'table td.firstCol';
                    if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                        self::$automaticStyles[$style] = 
                                themes\ThemeManager::$CSSProps[$selector] .
                                self::$automaticStyles[$style];
                    }
                }
                if ($rowCounter == $numRows
                    && $rowCounter != 1
                    && $mask['lastRow']) {
                    $selector = 'table td.lastRow';
                    if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                        self::$automaticStyles[$style] = 
                                themes\ThemeManager::$CSSProps[$selector] .
                                self::$automaticStyles[$style];
                    }
                } 
                if ($colCounter == $numCols
                    && $colCounter != 1
                    && $mask['lastCol']) {
                    $selector = 'table td.lastCol';
                    if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                        self::$automaticStyles[$style] = 
                                themes\ThemeManager::$CSSProps[$selector] .
                                self::$automaticStyles[$style];
                    }
                } 
                if ($rowCounter%2 == 0 && $mask['bandedRow']) {
                    $selector = 'table td.bandedRow';
                    if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                        self::$automaticStyles[$style] = 
                                themes\ThemeManager::$CSSProps[$selector] .
                                self::$automaticStyles[$style];
                    }
                }
                if ($colCounter%2 == 0 && $mask['bandedCol']) {
                    $selector = 'table td.bandedCol';
                    if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                        self::$automaticStyles[$style] = 
                                themes\ThemeManager::$CSSProps[$selector] .
                                self::$automaticStyles[$style];
                    }
                } 
                $selector = 'table td';
                if (!empty(themes\ThemeManager::$CSSProps[$selector])) {
                    self::$automaticStyles[$style] = 
                            themes\ThemeManager::$CSSProps[$selector] .
                            self::$automaticStyles[$style];
                }
                $colCounter++;
            }
            $rowCounter++;
        }  
    }
     
}