<?php

/**
 * ODF Elements
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
use Docxpresso\Core\Forms as forms;
use Docxpresso\Themes as themes;
use Docxpresso;

/**
 * Allows for the creation and addition of an element into the document
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class ODFElement
{
    /**
     * the style id of the current element
     * 
     * @var string
     * @access private
     */
    private $_currentStyleId;
    
    /**
     * the document DOMDocument element
     * 
     * @var DOMDocument
     * @access private
     */
    private $_dom;
    
    /**
     * the DOMNode element of the current ODFElement
     * 
     * @var DOMNode
     * @access private
     */
    private $_node;
    
    /**
     * true if the ODF node has childs
     * 
     * @var mixed (a string or false)
     * @access private
     */
    private $_hasODFChild;
    
    /**
     * a reference to the parent ODFElement
     * @var ODFElement
     * @access private
     */
    private $_parentODFElement;
    
    /**
     * the style id
     * 
     * @var string
     * @access private
     */
    private $_styleId;
    
    /**
     * the ODFElement target (document, header, ...)
     * 
     * @var string
     * @access private
     */
    private $_target;
    
    /**
     * Construct
     *
     * @param DOMElement $node
     * @param ODFElement $parentODFElement
     * @param string $target
     * @access public
     */
    public function __construct($node, 
                                $parentODFElement = NULL, 
                                $target = 'document')
    {
        $this->_node = $node;
        //we save in $nodeMap the ODFElement to which this DOMNode belongs
        Docxpresso\createDocument::$nodeMap[$node] = $this;
        $this->_dom = $this->_node->ownerDocument;
        $this->_nodeType = $this->_node->nodeName;
        $this->_hasODFChild = false;
        $this->_parentODFElement = $parentODFElement;
        if ($this->_parentODFElement !== NULL) {
            $this->_currentStyleId = $this->_parentODFElement->getStyleId();
        } else {
            $this->_currentStyleId = '';
        }
        $this->_target = $target;
    }
    
    /**
     * gets the first ancestor that accepts the ODFElement that we are
     * trying to insert
     * 
     * @param ODFElement $odfNode
     * @param array $allowedNodes where the keys must be the allowed tags and  
     * the value should be set to true. The method in_array is not used 
     * to improve performance
     * @return ODFNode
     * @access public
     */
    public function getAncestor($odfNode, $allowedNodes) 
    {
        $ancestor = $odfNode->getParentODFElement();
        if ($ancestor != NULL) {
            if (isset($allowedNodes[$ancestor->getNode()->nodeName])) {
                return $ancestor;
            } else {
                $ancestor = $this->getAncestor($ancestor, $allowedNodes);
            }
        } 
        return $ancestor;
    }
    
    /* Some standard getters and setters */
    
    /**
     * get the current style
     * 
     * @return string
     * @access public
     */
    public function getCurrentStyleId() 
    {
        return $this->_currentStyleId;
    }
    
    /**
     * set the current style
     * 
     * @param string $styleId
     * @return void
     * @access public
     */
    public function setCurrentStyleId($styleId) 
    {
        $this->_currentStyleId = $styleId;
    }
    
    /**
     * detects if and ODF node has childs
     * 
     * @return bool
     * @access public
     */
    public function getHasODFChild() 
    {
        return $this->_hasODFChild;
    }
    
    /**
     * sets the the _hasODFChild property
     * 
     * @param bool $odfChild
     * @return void
     * @access public
     */
    public function setHasODFChild($odfChild) 
    {
        $this->_hasODFChild = $odfChild;
    }
    
    /**
     * gets the DOMNode associated with an ODF node
     * @return DOMNode
     * @access public
     */
    public function getNode() 
    {
        return $this->_node;
    }
    
    /**
     * sets the _node property
     * 
     * @param DOMNode $node
     * @return void
     * @access public
     */
    public function setNode($node) 
    {
        $this->_node = $node;
    }
    
    /**
     * gets the node type
     * 
     * @return string
     * @access public
     */
    public function getNodeType() 
    {
        return $this->_nodeType;
    }
    
    /**
     * sets the _nodeType property
     * 
     * @param string $type
     * @return void
     * @access public
     */
    public function setNodeType($type) 
    {
        $this->_nodeType = $type;
    }
    
    /**
     * gets the parent of an ODF element
     * 
     * @return ODFElement
     * @access public
     */
    public function getParentODFElement() 
    {
        return $this->_parentODFElement;
    }
    
    /**
     * sets the _parentODFElement property
     * 
     * @param ODFElement $odfElement
     * @return void
     * @access public
     */
    public function setParentODFElement($odfElement) 
    {
        $this->_parentODFElement = $odfElement;
    } 
    
    /**
     * gets the style id
     * 
     * @return string
     * @access public
     */
    public function getStyleId() 
    {
        return $this->_styleId;
    }
    
    /**
     * sets the styleId property
     * 
     * @param string $styleId
     * @return void
     * @access public
     */
    public function setStyleId($styleId) 
    {
        $this->_styleId = $styleId;
    }
    
    /**
     * gets the target
     * 
     * @return string
     * @access public
     */
    public function getTarget() 
    {
        return $this->_target;
    }
    
    /**
     * sets the target
     * 
     * @param string $target
     * @return void
     * @access public
     */
    public function setTarget($target) 
    {
        $this->_target = $target;
    }
    
    /**
     * adds a bookmark
     * If we try to insert directly a bookmark into the document a wrapping 
     * paragraph with default styles will be automatically created
     * 
     * @param array $options with the following keys and values
     *      'name' => (string) the bookmark name needed for croos references
     *      'text' => (string)  the text to insert (can be empty)
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     * @param bool $reference if true returns a reference to the text element
     * so they can be nested.
     * @return ODFElement
     * @access public
     */
    public function bookmark($options, $reference = false) 
    {
        if (!isset($options['text'])) {
            $options['text'] = '';
        }
        if (!isset($options['name'])) {
            $options['name'] = 'name_' . mt_rand(11111, 999999999);
        }
        $text = $this->text($options, $reference);
        $start = $this->_dom->createElement('text:bookmark-start');
        $start->setAttribute('text:name', $options['name']);
        $end = $this->_dom->createElement('text:bookmark-end');
        $end->setAttribute('text:name', $options['name']);
        $this->_insertODFNode($start, $text->getNode(), 'first');
        $this->_insertODFNode($end, $text->getNode(), 'append');
        if ($reference) {
            return $text;
        } else {
            return $this;
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
     *           the radius of the circle. In case of ring charts it specifies 
     *           additional distance of a segment from the center of the circle. 
     *           The distance is given as percentage of the thickness of the 
     *           ring.
     *          'angle-offset' => (int) it specifies in degrees a counter 
     *           clockwise rotation of a polar coordinate in a circle, ring or
     *           polar chart.
     *          'stacked' => (boolean) default value is false. it specifies the 
     *           accumulation of the series values per category. Each value is 
     *           in addition to the other values in the same category.
     *          'gap-width' => (int) specifies a gap between neighboring groups 
     *           of bars in a bar/column chart. It is specified as an integer 
     *           percentage relative to the width of a single bar
     *          'overlap' => (int) specifies how much bars within the same 
     *           category in a bar/column chart overlap. The attribute value is 
     *           an integer that is interpreted as a percentage relative to the 
     *           width of a bar. Negative values specify gaps between bars
     *          'percentage' => (boolean) if true specifies a percentage 
     *           accumulation of values per category
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
     * @param bool $reference if true returns a reference to the frame element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function chart($type, $options=array(), $reference = true) 
    {
        if (isset($options['styleId'])) {
            $this->_styleId = $options['styleId'];
        } else {
            $this->_styleId = commons\Utilities::generateId();
            $options['styleId'] = $this->_styleId;
        }
        $target = $this->getTarget();
        $this->_styleProcessing('draw:frame', 
            Image::getDefaultStyles(0),
            $options,
            $this->_styleId,
            $target);
        $options['type'] = $type;
        $chart = new DocumentObject($this, 'chart', $options);
        if (isset($options['data'])) {
           $chart->chartData($options['data']); 
        }
        return $chart;
    }
    
    /**
     * Sets the 3D rotation angles and perspective
     * 
     * @param $options with the following keys and values
     *      'rotate-x' => (int) rotation angle respect the x-axis (default
     *       value is 11)
     *      'rotate-y' => (int) rotation angle respect the y-axis (default
     *       value is 25)
     *      'rotate-z' => (int) rotation angle respect the z-axis (default
     *       value is 5)
     *      'right-angled-axes' => (boolean) default value is true (if true
     *       the rotate-z option is ignored)
     *      'perspective' => (int) given as a percentage (default value 
     *       is 20)
     *          
     * @return ODFElement
     * @access public
     */
    public function chart3DTransform($options = array()) 
    {
        $chart = $this->_getChart();
        if ($chart !== false) {
            $chart->Transform3D($options);
        } 
        return $this;
    }
    
    /**
     * sets the styles for the x, y and z chart axis
     * 
     * @param (string) $axis it may be x, y or z 
     * @param $options with the following keys and values
     *      'visible' => (boolean) default value is true
     *      'logarithmic' => (boolean) default value is false  
     *      'font-color' => (string) hexadecimal color: #ff0000, ...
     *      'font-family' => (string) Arial, Calibri, ...
     *      'font-size' => (int) given in points
     *      'axis-position' => (mixed) it can be start (default), end or a
     *       numeric value dictating where the perpendicular axis shiould cross.
     *       In the case of the y-axis it refers to the category, i.e. 1 before
     *       the first category, 2 behind the second and so long so forth
     *      'minimum' => (float) specifies the minimun value of an axes
     *      'maximum' => (float) specifies the maximun value of an axes
     *      'label-arrangement' => (string) side-by-side, stagger-even or 
     *       stagger-odd.
     *      'display-label' => (boolean) default value is true
     *      'axis-label-position' => (string) near-axis (default),
     *       near-axis-other-side, outside-end or outside-start
     *      'reverse-direction' => (boolean) default is false 
     *      'text-overlap' => (boolean) default is false. It specifies 
     *       whether axis labels may overlap each other
     *      'line-break' => (boolean) default is true. specifies whether 
     *       word wrapping is allowed for axis labels
     *      'stroke' => (string) solid (default), dash or none.
     *      'stroke-width' => (string) given in points, cm or in. The defaults 
     *       are: 0.75pt for major grids and 0.5pt for minor grids
     *      'stroke-color' => (string) hexadecimal color. 
     *      'stroke-opacity' => (string) percentage: 100% (default), 80%, ....
     *      'stroke-linejoin' => (string) round (default), bevel, middle, 
     *       miter or none
     *      'stroke-linecap' => (string) butt (default), round or square.
     *      'interval-major' => (float) specifies the separation between
     *       major intervals within an axis
     *      'interval-minor-divisor' => (integer) specifies the number of
     *       divisions between to major interval lines
     *      'tick-marks-major-inner' => (boolean) default value is false
     *      'tick-marks-minor-inner' => (boolean) default value is false
     *      'tick-marks-major-outer' => (boolean) default value is false
     *      'tick-marks-minor-outer' => (boolean) default value is false      
     *              
     * @return ODFElement
     * @access public
     */
    public function chartAxis($axis, $options = array()) 
    {
        //check if this object has chart within it
        $chart = $this->_getChart();
        if ($chart !== false) {
            $chart->axis($axis, $options);
        } 
        return $this;
    }
    
    /**
     * sets the styles of the different chart components: wall and floor
     * 
     * @param string $component it can be wall or floor
     * @param $options with the following keys and values
     *      'fill-color' => (string) hexadecimal color: #ffffff (default)
     *      'opacity' => (string) percentage: 100% (default), 80%, ....
     *      'stroke' => (string) solid (default), dash or none.
     *      'stroke-width' => (string) given in points.
     *      'stroke-color' => (string) hexadecimal color. 
     *      'stroke-opacity' => (string) percentage: 100% (default), 80%, ....
     *      'stroke-linejoin' => (string) round (default), bevel, middle, 
     *       miter or none
     *      'stroke-linecap' => (string) butt (default), round or square.
     *                      
     * @return ODFElement
     * @access public
     */
    public function chartComponent($component = 'wall', $options = array()) 
    {
        //check if this object has chart within it
        $chart = $this->_getChart();
        if ($chart !== false) {
            $chart->component($component, $options);
        } 
        return $this;
    }
    
    /**
     * inserts the data into the chart
     * 
     * @param array $data
     * @param $options (array) an array of arrays 
     * each of them with the following keys and values:
     *      'fill-color' => (string) hexadecimal color.
     *      'opacity' => (string) percentage: 100% (default), 80%, ....
     *      'stroke' => (string) solid (default), dash or none.
     *      'stroke-width' => (string) given in points, cm or in.
     *      'stroke-color' => (string) hexadecimal color. 
     *      'stroke-opacity' => (string) percentage: 100% (default), 80%, ....
     *      'stroke-linejoin' => (string) round (default), bevel, middle, 
     *       miter or none
     *      'stroke-linecap' => (string) butt (default), round or square.
     *      'solid-type' => (string) cuboid (default), cylinder, cone or 
     *       pyramid. It only applies to 3D bar and column charts.
     * 
     * @return ODFElement
     * @access public
     */
    public function chartData($data, $options = array()) 
    {
        //check if this object has chart within it
        $chart = $this->_getChart();
        if ($chart !== false) {
            $chart->data($data, $options);
        } 
        return $this;
    }
    
    /**
     * sets the styles for the x, y and z chart grids
     * 
     * @param (string) $axis it may be x, y or z 
     * @param (string) $type it may be major or minor
     * @param $options with the following keys and values
     *      'stroke' => (string) solid (default), dash or none.
     *      'stroke-width' => (string) given in points. The defaults 
     *       are: 0.75pt for major grids and 0.5pt for minor grids
     *      'stroke-color' => (string) hexadecimal color. For major grids the
     *       default is #d9d9d9 and #f0f0f0 for minor grids
     *      'stroke-opacity' => (string) percentage: 100% (default), 80%, ....
     *      'stroke-linejoin' => (string) round (default), bevel, middle, 
     *       miter or none
     *      'stroke-linecap' => (string) butt (default), round or square.     
     *              
     * @return ODFElement
     * @access public
     */
    public function chartGrid($axis = 'x', $type = 'major', $options = array()) 
    {
        //check if this object has chart within it
        $chart = $this->_getChart();
        if ($chart !== false) {
            $chart->grid($axis, $type, $options);
        } 
        return $this;
    }
    
    /**
     * inserts a legend into the chart
     * 
     * @param $options with the following keys and values
     *      'legend-position' => (string) left, right, top or bottom
     *      'color' => (string) hexadecimal value: #333333, ...
     *      'font-family' => (string) Arial, Calibri, ...
     *      'font-size => (string) (string) given in points, cm or in.
     *      'font-weight' => (string) bold or normal.
     *      'font-style' => (string) italic or normal.
     *      'fill-color' => (string) hexadecimal color: #ffffff (default)
     *      'fill-opacity' => (string) percentage: 100% (default), 80%, ....
     *      'stroke' => (string) solid (default), dash or none.
     *      'stroke-width' => (string) given in points, cm or in.
     *      'stroke-color' => (string) hexadecimal color. 
     *      'stroke-opacity' => (string) percentage: 100% (default), 80%, ....
     *      'stroke-linejoin' => (string) round (default), bevel, middle, 
     *       miter or none
     *      'stroke-linecap' => (string) butt (default), round or square.
     * @return ODFElement
     * @access public
     */
    public function chartLegend($options = array()) 
    {
        $chart = $this->_getChart();
        if ($chart !== false) {
            $chart->legend($options);
        } 
        return $this;
    }
    
    /**
     * inserts a title in the chart
     * 
     * @param $options with the following keys and values
     *      'text' => (string) the title text
     *      'style' => (string) a list of properties in CSS format
     * @return ODFElement
     * @access public
     * @ignore
     */
    public function chartTitle($options = array()) 
    {
        $chart = $this->_getChart();
        if ($chart !== false) {
            $title = $chart->title($options);
        } 
        return $this;
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
     *          1: Hindu-Arabic number sequence, starts with 1 (default). 
     *          a: number sequence of lowercase Modern Latin basic alphabet 
     *          characters, starts with 'a'. 
     *          A: number sequence of uppercase Modern Latin basic alphabet 
     *          characters, starts with 'A'. 
     *          i: number sequence of lowercase Roman numerals, starts with 'i'. 
     *          I: number sequence of uppercase Roman numerals, start with 'I'.
     *      'number-offset' => (int) allows to start the numbering with a given
     *       offset. For example, if we would like to ignore the front page for
     *       page numbering we should choose a value of -1.
     *      'reference-format' => (string) used for bookmark-ref
     *          page: displays the number of the page on which the 
     *          referenced item appears. 
     *          text: displays the text of the referenced item.
     *      'reference-name' => (string) the name of the linked reference 
     *      'date-format' => (array) the date is built by running consecutively
     *       through the array entries. possible values are:
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
     *          2. array('day-of-week', ' ', 'day-short', ', ' , 'month-of-year', ' ', 'year')
     *          yields Thursday 7, November-2014
     *      'style' => (string) a list of properties in CSS format
     * @param bool $reference if true returns a reference to the form element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function field($type, $options = array(), $reference = true) 
    {
        $fieldTypes = array(
            'author-name'        => true,
            'bookmark-ref'       => true,
            'date'               => true,
            'page-count'         => true,
            'page-number'        => true,
            'title'              => true,
        );
        if (!isset($type)) {
            $message = 'You have to set the field type.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            return $this;
        }
        if (!isset($fieldTypes[$type])) {
            $message = 'The chosen field type is not available';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            return $this;
        }
        if ($type == 'author-name') {
            $type = 'initial-creator';
        }
        $nodeName = $this->getNodeType();
        if($nodeName == 'text:a'
           || $nodeName = 'text:p'
           || $nodeName == 'text:span') {
            //this is a hack to avoid poor inheritance of link styles in Word
            $autoStyles =& Docxpresso\CreateDocument::$automaticStyles;
            if (isset($options['style']) 
                && \is_string($options['style'])
                && !empty($this->_currentStyleId)) {
                $options['style'] = $autoStyles[$this->_currentStyleId] . ';'
                                    . $options['style'];
            } else if (!isset($options['style']) 
                       && !empty($this->_currentStyleId)
                       && \is_string($autoStyles[$this->_currentStyleId])) {
                $options['style'] = $autoStyles[$this->_currentStyleId] . ';';
            } else if (!isset($options['style']) 
                       && !empty($this->_currentStyleId)
                       && \is_array($autoStyles[$this->_currentStyleId])) {
                $options['style'] = $autoStyles[$this->_currentStyleId];
            }
        }
        if (isset($options['styleId'])) {
            $this->_styleId = $options['styleId'];
        } else {
            $this->_styleId = commons\Utilities::generateId();
        }
        $target = $this->getTarget();
        $this->_styleProcessing('text:span', 
                                Text::getDefaultStyles(0),
                                $options,
                                $this->_styleId,
                                $target);
        if (!isset(Field::$parentNodes[$this->_nodeType])) {
            $field = $this->paragraph()
                          ->field($type, $options, $reference);
        } else {
            $field = new Field($this, $type, $options);
        }
        if ($reference) {
            return $field;
        } else {
            return $this;
        }
    }
    
    /**
     * inserts a form container into the document. The form it self does not 
     * accept styling options so if you want to include them you should wrap
     * the form within a table, frame or section element.
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
     * @return void
     * @access public
     */
    public function form($options, $reference = true) 
    {
        if (isset($options['name'])) {
            Docxpresso\CreateDocument::$currentForm = $options['name'];
        } else {
            
        }       
        //check if the office.forms element exists in the DOM and if it 
        //does not create it
        $formsWrapper = $this->_dom->getElementsByTagName('office:forms');
        if ($formsWrapper->length == 0) {
            //create the office:forms element and insert it in the dom
            $officeForms = $this->_dom->createElement('office:forms');
            $officeForms->setAttribute('form:automatic-focus', 'false');
            $officeForms->setAttribute('form:apply-design-mode', 'false');
            //this node has to be inserted as the first child of
            //the office:text element
            $textNode = $this->_dom->getElementsByTagName('text')->item(0);
            $this->_insertODFNode($officeForms, $textNode, 'first');
        }
        $form = new forms\Form($this->_dom, $options);
        $formWrapper = $this->_dom->getElementsByTagName('office:forms')->item(0);
        $formWrapper->appendChild($form->getFormNode());
        Docxpresso\CreateDocument::$forms[$form->getName()] = $form;
        return $this;
    }
    
    /**
     * Inserts an footnote into a paragraph
     * 
     * @param array $options the posible keys and values are: 
     *      'label' => (string) the footnote label. You should not declare this 
     *       option if you prefer automatic labelling.  
     *      'note' => (mixed)  the note content. It can be a plain string of
     *       text or a Document Fragment object.
     *      'style' => (string) a list of properties in CSS format for the note.
     *      'styleId' => (string) if given the styleId is not generated randomly
     * @param bool $reference if true returns a reference to the form element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function footnote($options = array(), $reference = true) 
    {
        Docxpresso\CreateDocument::$currentFootnote++;
        $note = $this->_note('footnote', $options);
        if ($reference) {
            return $note;
        } else {
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
     *      'pageNumber' => (int) for absolutely positioned frames (optional)
     * @param bool $reference if true returns a reference to the frame element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function frame($options = array(), $reference = true) 
    {
        //check if there is a parent paragraph or an allowed parent tag
        //otherwise create a paragraph element where the frame will be inserted
        if (!isset(Frame::$parentNodes[$this->_nodeType])) {
            $pStyle = 'margin: 0; padding: 0;';            
            $parentName = $this->parent()->getNodeType();
            if ($parentName != 'table:table-cell'){
                //this is a hack because charts within cells create problems
                //with the line-height property
                $pStyle .= 'font-size: 0.5pt; line-height: 0.5pt';
            }
            $frame = $this->paragraph(array('style' => $pStyle))
                          ->frame($options, $reference);
        } else {
            if (isset($options['styleId'])) {
                $this->_styleId = $options['styleId'];
            } else {
                $this->_styleId = commons\Utilities::generateId();
            }
            $target = $this->getTarget();
            $this->_styleProcessing('draw:frame', 
                                    Frame::getDefaultStyles(0),
                                    $options,
                                    $this->_styleId,
                                    $target);
            $this->_styleProcessing('text:p', 
                                    Frame::getDefaultStyles(0),
                                    $options,
                                    'text-' . $this->_styleId,
                                    $target);
            $frame = new Frame($this, $options);   
        }
        if ($reference) {
                return $frame;
            } else {  
                return $this;
        }
    }
    
    
    
    /**
     * adds a submit button to a form within the document
     * 
     * @param array $options with the following keys and values
     *      'form-name' => (string) the name of the form where we want to insert 
     *      this control
     *      'name' => (string) the control name
     *      'value' => (string) the associated value
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     *      'pageNumber' => (int) fro absolutely positioned controls (optional)
     * @param bool $reference if true returns a reference to the frame element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function button($options, $reference = true) 
    {
        if (!isset($options['form-name'])) {
            $options['form-name'] = Docxpresso\CreateDocument::$currentForm;
        }
        
        if (isset(Docxpresso\CreateDocument::$forms[$options['form-name']])) {
            $form = Docxpresso\CreateDocument::$forms[$options['form-name']];
            $controlName = $form->addFormButton($options);
        } else {
            $message = 'The form name was not recognized';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            $controlName = uniqid();
        }
        if (!isset(Control::$parentNodes[$this->_nodeType])) {
            $element = $this
                        ->paragraph()
                        ->_drawControl($controlName, 'button', $options);
        } else {
            $element = $this
                        ->_drawControl($controlName, 'button', $options);
        }
        if ($reference) {
            return $element;
        } else {
            return $this;
        }
        
    }
    
    /**
     * adds a checkbox to a form within the document
     * 
     * @param array $options with the following keys and values
     *      'form-name' => (string) the name of the form where we want to insert 
     *      this control
     *      'name' => (string) the control name
     *      'value' => (string) the associated value
     *      'checked' => (bool) true or false
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     *      'pageNumber' => (int) fro absolutely positioned controls (optional)
     * @param bool $reference if true returns a reference to the frame element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function checkbox($options = array(), $reference = true) 
    {
        if (!isset($options['form-name'])) {
            $options['form-name'] = Docxpresso\CreateDocument::$currentForm;
        }        
        if (isset(Docxpresso\CreateDocument::$forms[$options['form-name']])) {
            $form = Docxpresso\CreateDocument::$forms[$options['form-name']];
            $controlName = $form->addFormCheckbox($options);
        } else {
            $message = 'The form name was not recognized';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            $controlName = uniqid();
        }
        if (!isset(Control::$parentNodes[$this->_nodeType])) {
            $element = $this->paragraph()
                            ->_drawControl($controlName, 'checkbox', $options);
        } else {
            $element = $this->_drawControl($controlName, 'checkbox', $options);
        }
        if ($reference) {
            return $element;
        } else {
            return $this;
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
        Docxpresso\CreateDocument::$break = 'column';
    }
    
    /**
     * creates a document fragment that can be later inserted directly into
     * the document or within another element
     * 
     * @param ODFElement $odfNode
     * @return DocumentFragment
     * @access public
     */
    public function documentFragment($odfNode = NULL) 
    {
        if (empty($odfNode)) {
            $fragment = new DocumentFragment($this);
        } else {
            $fragment = new DocumentFragment($odfNode);
        }
        return $fragment;
    }
    
    /**
     * finish the current chaining and returns the "cursor" to the containing 
     * element. This method is particularly useful to stop chaining in deeply 
     * nested elements. The "back chaining" automatically stops at the root
     * level if not containing element has been found.
     * 
     * @param $element it can take the following values:
     *  'cell'
     *  'chart',
     *  'frame',
     *  'endnote',
     *  'footnote',
     *  'link',
     *  'list',
     *  'math',
     *  'paragraph',
     *  'root',
     *  'row',
     *  'table',
     * if element is left empty the method returns the root document element.
     * @return ODFElement
     * @access public
     */
    public function end($element = '') 
    {
        $refNodes = array( 
            'cell'      => 'table:cell',
            'chart'     => 'draw:object',
            'endnote'   => 'text:note-body',
            'footnote'  => 'text:note-body',
            'frame'     => 'draw:frame',
            'heading'   => 'text:h',
            'image'     => 'draw:frame',
            'link'      => 'text:a',
            'list'      => 'text:list',
            'math'      => 'draw:object',
            'paragraph' => 'text:p',
            'row'       => 'table:row',
            'table'     => 'table:table', 
            'textbox'   => 'draw:frame',
            'control'   => 'draw:control'
        );
        if (isset($refNodes[$element])){
            $elementNode = $refNodes[$element]; 
        } else if ($element = '') {
            $elementNode = 'root'; 
        } else {
            $message = 'The end method is not defined for that element';
            commons\DOCXPRESSOLogger::logger($message, 'info');
        }
        $node = $this->_gotoODFnode($this, $elementNode);
        $name = $node->getNode()->nodeName;
        if ($name == 'office:text' || $name == 'office:fragment') {
            return $node;
        } else if ($name == 'draw:frame' || $name == 'draw:control') {
            return $node->getParentODFElement()->getParentODFElement();
        } else if ($name == 'text:note-body') {
            return $node->_gotoODFnode($node, 'text:p');
        }else {
            return $node->getParentODFElement();
        }
    }
    
    /**
     * Inserts an endnote into a paragraph
     *
     * @param array $options the posible keys and values are: 
     *      'label' => (string) the endnote label. You should not declare this 
     *       option if you prefer automatic labelling.  
     *      'note' => (mixed)  the note content. It can be a plain string of
     *       text or a Document Fragment object.
     *      'style' => (string) a list of properties in CSS format for the note.
     *      'styleId' => (string) if given the styleId is not generated randomly
     * @param bool $reference if true returns a reference to the form element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function endnote($options = array(), $reference = true) 
    {
        Docxpresso\CreateDocument::$currentEndnote++;
        $note = $this->_note('endnote', $options);
        if ($reference) {
            return $note;
        } else {
            return $this;
        }
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
    public function inputField($options = array(), $reference = true) 
    {
        if (!isset($options['form-name'])) {
            $options['form-name'] = Docxpresso\CreateDocument::$currentForm;
        }
        
        if (isset(Docxpresso\CreateDocument::$forms[$options['form-name']])) {
            $form = Docxpresso\CreateDocument::$forms[$options['form-name']];
            $controlName = $form->addFormInputField($options);
        } else {
            $message = 'The form name was not recognized';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            $controlName = uniqid();
        }
        if (!isset(Control::$parentNodes[$this->_nodeType])) {
            $element = $this
                        ->paragraph()
                        ->_drawControl($controlName, 'inputField', $options);
        } else {
            $element = $this
                        ->_drawControl($controlName, 'inputField', $options);
        }
        if ($reference) {
            return $element;
        } else {
            return $this;
        }
    }
    
    /**
     * inserts a document fragment previously created with the documentFragment
     * method
     * 
     * @param DocumentFragment $fragment
     * @return ODFElement
     * @access public
     */
    public function insertDocumentFragment($fragment) 
    {
        $name = $this->_node->nodeName;
        //run over all the document fragment childs
        $childs = $fragment->getNode()->childNodes;
        foreach ($childs as $child) {
           if (isset(Resources::$ODFChilds[$name][$child->nodeName])) {
                $new = $child->cloneNode(true);
                //check if there is a cursor within the node
                $cursors = $this->_node->getElementsByTagName('customCursor');
                if ($cursors->length > 0) {
                    $cursor = $cursors->item(0);
                    $node = $cursor->parentNode->insertBefore($new, $cursor);
                } else {
                    $node = $this->_node->appendChild($new);
                }
                //we have to modify the parent ODF that was saved in the
                //CreateDocument::$automaticStyles array to control the
                //"style" relative size and width inheritance.
                //Beware that if the same fragment is inserted more than once
                //it will inherit those properties from the last parent.
                $styleId = $this->_styleAttribute($node);
                $parentId = $this->_styleAttribute($this->getNode());
                if(isset(Docxpresso\CreateDocument::$automaticStyles[$styleId])
                   && is_string(Docxpresso\CreateDocument::$automaticStyles[$styleId])){
                    Docxpresso\CreateDocument::$automaticStyles[$styleId] .=
                            ';parentStyleId: ' . $parentId . ';';
                }
            } else if ($child->nodeName != '#text') {
                $element = new ODFElement($child, $this);
                $this->insertDocumentFragment($element);
            }
        }
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
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     *      'pageNumber' => (int) for absolutely positioned controls (optional)
     * @param bool $reference if true returns a reference to the frame element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function select($options = array(), $reference = true) 
    {
        if (!isset($options['form-name'])) {
            $options['form-name'] = Docxpresso\CreateDocument::$currentForm;
        }        
        if (isset(Docxpresso\CreateDocument::$forms[$options['form-name']])) {
            $form = Docxpresso\CreateDocument::$forms[$options['form-name']];
            $controlName = $form->addFormSelect($options);
        } else {
            $message = 'The form name was not recognized';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            $controlName = uniqid();
        }
        if (!isset(Control::$parentNodes[$this->_nodeType])) {
            $element = $this->paragraph()
                            ->_drawControl($controlName, 'select', $options);
        } else {
            $element = $this->_drawControl($controlName, 'select', $options);
        }
        if ($reference) {
            return $element;
        } else {
            return $this;
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
     *      'pageNumber' => (int) fro absolutely positioned controls (optional)
     * @param bool $reference if true returns a reference to the frame element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function radioButton($options = array(), $reference = true) 
    {
        if (!isset($options['form-name'])) {
            $options['form-name'] = Docxpresso\CreateDocument::$currentForm;
        }        
        if (isset(Docxpresso\CreateDocument::$forms[$options['form-name']])) {
            $form = Docxpresso\CreateDocument::$forms[$options['form-name']];
            $controlName = $form->addFormRadioButton($options);
        } else {
            $message = 'The form name was not recognized';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            $controlName = uniqid();
        }
        if (!isset(Control::$parentNodes[$this->_nodeType])) {
            $element = $this
                        ->paragraph()
                        ->_drawControl($controlName, 'radioButton', $options);
        } else {
            $element = $this
                        ->_drawControl($controlName, 'radioButton', $options);
        }
        if ($reference) {
            return $element;
        } else {
            return $this;
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
     *      'pageNumber' => (int) for absolutely positioned controls (optional)
     * @param bool $reference if true returns a reference to the frame element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function textArea($options = array(), $reference = true) 
    {
        if (!isset($options['form-name'])) {
            $options['form-name'] = Docxpresso\CreateDocument::$currentForm;
        }
        if (empty($options['name'])) {
            $options['name'] = commons\Utilities::generateId('textarea');
        }
        if (isset(Docxpresso\CreateDocument::$forms[$options['form-name']])) {
            $form = Docxpresso\CreateDocument::$forms[$options['form-name']];
            $controlName = $form->addFormTextArea($options);
        } else {
            $message = 'The form name was not recognized';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            $controlName = uniqid();
        }
        if (!isset(Control::$parentNodes[$this->_nodeType])) {
            $element = $this
                        ->paragraph()
                        ->_drawControl($controlName, 'textArea', $options);
        } else {
            $element = $this
                        ->_drawControl($controlName, 'textArea', $options);
        }
        if ($reference) {
            return $element;
        } else {
            return $this;
        }
    }
    
    /**
     * adds a footer to a document section
     * 
     * @param array $options with the following keys and values
     *      'section' => (int) the section number. If not given it is assumed 
     *      that the footer will be inserted in the current section
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
        $footer = Docxpresso\CreateDocument::$currentDocument->footer($options); 
        return $footer;
    }
    
    /**
     * adds a header to a document section
     * 
     * @param array $options with the following keys and values
     *      'section' => (int) the section number. If not given it is assumed 
     *      that the header will be inserted in the current section
     *      'left-page' => (bool) if true the header will be specific of left
     *      pages only
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     * 
     * @return ODFElement
     * @access public
     */
    public function header($options = array()) 
    {
        $header = Docxpresso\CreateDocument::$currentDocument->header($options); 
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
     *      'styleId' => (string) if given the styleId is not generated randomly
     *      'enforce' => (bool) if set to true and the current element does not
     *      accept paragraphs the method searches for the first ancestor that
     *      allows for the insertion. Beware that the return element is a 
     *      reference to the inserted paragraph and the chaining may result 
     *      affected consequently!!
     * @param bool $reference if true returns a reference to the paragraph
     * element. The default value is true
     * @return ODFElement
     * @access public
     */
    public function heading($options = array(), $reference = true) 
    {
        if (!\is_int($options['level'])
            || $options['level'] < 1
            || $options['level'] > 6) {
            $message = 'The heading level: ' . $options['level'] ;
            $message.= ' is not supported';
            commons\DOCXPRESSOLogger::logger($message, 'info');  
        }
        $options['type'] = 'h';
        $paragraph = $this->_paragraph($options);
        if ($reference) {
            return $paragraph;
        } else {
            return $this;
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
     * @return ODFElement
     * @access public
     */
    public function html($options) 
    {
        //create a Document Fragment that will hold the parsed HTML content
        $fragment = $this->documentFragment();
        $htmlFragment = new parser\HTML2ODF($fragment);
        if (isset($options['isFile']) && $options['isFile']) {
            $htmlFragment->loadHTMLFromFile($options);
        } else {
            $htmlFragment->loadHTML($options);
        }
        $this->insertDocumentFragment($fragment);
        //we return a reference to this to allow further chaining
        return $this;
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
        //define the default styles for the hr
        $defSt ='margin-top: 0; margin-bottom: 0.5cm;';
        $defSt .= 'border-bottom: 1.11pt double #808080;';
        $defSt .= 'font-size: 6pt;';
        if (isset($options['style']) && \is_string($options['style'])) {
            $height = parser\CSSPropsLexer::extractSingleProperty('height', 
                                                           $options['style']);
            $options['style'] = $defSt . $options['style'];
            //parse the height as font-size
            if (!empty ($height)){
                $options['style'] .= ';font-size: ' . $height . ';';
            }
        } else if (!isset($options['style'])) {
            $options['style'] = $defSt;
        }
        $this->paragraph($options);
        return $this;
    }
    
    /**
     * adds an image into the document
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
     *      'styleId' => (string) if given the styleId is not generated randomly
     * @param bool $reference if true returns a reference to the image element
     * so further elements can be nested. The default value is false.
     * @return ODFElement
     * @access public
     */
    public function image($options = array(), $reference = true) 
    {
        if (isset($options['styleId'])) {
            $this->_styleId = $options['styleId'];
        } else {
            $this->_styleId = commons\Utilities::generateId();
        }
        //if the image acn not directly inserted wrap it in a paragraph
        if (!isset(Frame::$parentNodes[$this->_nodeType])) {
            return $this->paragraph()->image($options, $reference);
        }
        $target = $this->getTarget();
        $this->_styleProcessing('draw:frame', 
            Image::getDefaultStyles(0),
            $options,
            $this->_styleId,
            $target);
        $image = new Image($this, $options);
        if ($reference) {
            return $image;
        } else {  
            return $this;
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
        //check if there is a parent paragraph or an allowed parent tag
        //otherwise create a paragraph element where the text will be inserted
        //the allowed parents coincide with those of plain text
        if (!isset(Text::$parentNodes[$this->_nodeType])) {
            $paragraph = $this->paragraph(array(), false);
            //now call this very same method
            $paragraph->lineBreak();
        } else {
            $node = $this->getNode();
            $lineBreak = $node->ownerDocument->createElement('text:line-break');
            $node->appendChild($lineBreak);
        }
        return $this;
    }
    
    /**
     * adds a link to the document
     * 
     * @param array $options with the following keys and values
     *      'text' => (string)  the linked text
     *      'target' => (string) the link target (the default is _blank)
     *      'title' => (string) an optional title text for the link
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     *      'url' => (string) the link href attribute. It can be a http path or
     *      an anchor to a previously defined bookmark (#name)
     * @param bool $reference if true returns a reference to the paragraph
     * element. The default value is true
     * @return ODFElement
     * @access public
     */
    public function link($options, $reference = true) 
    {
        //check if there is a parent paragraph or an allowed parent tag
        //otherwise create a paragraph element where the text will be inserted
        if (!isset(Link::$parentNodes[$this->_nodeType])) {
            $paragraph = $this->paragraph(array(), true);
            //now call this very same method
            $a = $paragraph->link($options, $reference);
            //we have to take into account when one has chained the text method 
            //to ODF nodes that are not "paragraph-like"
            if ($this->getNodeType() == 'text:list-item') {
                return $paragraph;
            }
        } else {
            if (isset($options['styleId'])) {
                $this->_styleId = $options['styleId'];
            } else {
                $this->_styleId = commons\Utilities::generateId();
            }
            $target = $this->getTarget();
            $this->_styleProcessing('text:a', 
                                    Link::getDefaultStyles(0),
                                    $options,
                                    $this->_styleId,
                                    $target);
            $a = new Link($this, $options);
        }
        if ($reference) {
            return $a;
        } else {
            return $this;
        }
    }
    
    /**
     * adds a list item within a list
     * 
     * @param array $options with the following keys and values
     *      'text' => (mixed)  a string with an optional text (it can also be 
     *       added later with the text or paragraph methods) or a 
     *       documentFragment object
     *      'enforce' => (bool) if set to true (default) and the current element 
     *       does not accept paragraphs the method searches for the first 
     *       ancestor that allows for the insertion. Beware that the return
     *       element is a reference to the inserted listItem and the chaining
     *       may result affected consequently!!
     * @param bool $reference if true returns a reference to the paragraph
     * element. The default value is true
     * @return ODFElement
     * @access public
     */
    public function listItem($options = array(), $reference = true) 
    {
        //if enforce is not set we assume that we should
        //get the first element ancestor that accepts the paragraph element
        if (!isset($options['enforce'])) {
            $options['enforce'] = true;
        }
        if (isset($options['styleId'])) {
            $this->_styleId = $options['styleId'];
        } else {
            $this->_styleId = commons\Utilities::generateId();
        }
        $target = $this->getTarget();
        //TODO: check how to implement list-style-override to be able to
        //modify the list-style-type of a single item. The naive way to
        //insert it as a paragraph style does not work.
        /*$this->_styleProcessing('text:list-item', 
                                Paragraph::getDefaultStyles(0),
                                $options,
                                $this->_styleId,
                                $target);*/
        if ($options['enforce']
            && !isset(ListItem::$parentNodes[$this->_nodeType]) ) {
            $ancestor = $this->getAncestor($this,
                                           ListItem::$parentNodes);
            //update the _styleId private property so we keep the right
            //inheritance
            $ancestor->setStyleId($this->_styleId);
            $item = new listItem($ancestor);
        } else {
            $item = new listItem($this);
        }
        if (isset($options['text']) && \is_string($options['text'])) {
            $options['auto'] = true;
            $paragraph = $item->paragraph($options, true);
            if ($reference) {
                return $paragraph;
            }
        } else if (isset($options['text']) 
                   && ($options['text'] instanceof DocumentFragment)) {
            $fragment = $item->insertDocumentFragment($options['text']);
            if ($reference) {
                return $fragment;
            }
        }
        if ($reference) {
            return $item;
        } else {
            return $this;
        }
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
     *      'type' => (string) it can be MathML (default) or StarMath
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
        $options['math'] = true;
        if (isset($options['styleId'])) {
            $this->_styleId = $options['styleId'];
        } else {
            $this->_styleId = commons\Utilities::generateId();
        }
        $target = $this->getTarget();
        $this->_styleProcessing('draw:frame', 
            Image::getDefaultStyles(0),
            $options,
            $this->_styleId,
            $target);
        if (!isset($options['type'])) {
            $options['type'] = 'mathml';
        }
        $options['equation'] = $equation;
        $math = new DocumentObject($this, 'math', $options);

        return $math;
    }
    
    /**
     * Inserts a page break into the document
     * 
     * @return void
     * @access public
     */
    public function pageBreak() 
    {
        Docxpresso\CreateDocument::$break = 'page';
    }
    
    /**
     * adds a paragraph to the document
     * 
     * @param array $options with the following keys and values
     *      'text' => (mixed)  a string with an optional text (it can also be 
     *      added later with the text method) or a documentFragment object
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     *      'enforce' => (bool) if set to true (default) and the current element 
     *      does not accept paragraphs the method searches for the first 
     *      ancestor that allows for the insertion. Beware that the return
     *      element is a reference to the inserted paragraph and the chaining
     *      may result affected consequently!!
     * @param bool $reference if true returns a reference to the paragraph
     * element. The default value is true
     * @return ODFElement
     * @access public
     */
    public function paragraph($options = array(), $reference = true) 
    {
        $options['type'] = 'p';
        $paragraph = $this->_paragraph($options);
        if ($reference) {
            return $paragraph;
        } else {
            return $this;
        }
    }
    
    /**
     * Adds an style to an element
     * 
     * @param mixed $style it can be a CSS like properties string or an array
     * @return void
     * @access public
     */
    public function style($style = '') 
    {
        $prefix = array('text:p'           => 'text',
                        'text:h'           => 'text',
                        'text:span'        => 'text',
                        'text:a'           => 'text',
                        'text:list'        => 'text',
                        //'text:list-item'   => 'text',
                        'table:table'      => 'table',
                        'table:table-row'  => 'table',
                        'table:table-cell' => 'table',
                        'draw:frame'       => 'draw',
                        'draw:text-box'    => 'draw',
                        'draw:control'     => 'draw',
                        'style:header'     => 'header',
                        'style:footer'     => 'footer',
                       );
        $ns = array('text:p'           => 'urn:oasis:names:tc:opendocument:xmlns:text:1.0',
                    'text:h'           => 'urn:oasis:names:tc:opendocument:xmlns:text:1.0',
                    'text:span'        => 'urn:oasis:names:tc:opendocument:xmlns:text:1.0',
                    'text:a'           => 'urn:oasis:names:tc:opendocument:xmlns:text:1.0',
                    'text:list'        => 'urn:oasis:names:tc:opendocument:xmlns:text:1.0',
                    'text:list-item'   => 'urn:oasis:names:tc:opendocument:xmlns:text:1.0',
                    'table:table'      => 'urn:oasis:names:tc:opendocument:xmlns:table:1.0',
                    'table:table-row'  => 'urn:oasis:names:tc:opendocument:xmlns:table:1.0',
                    'table:table-cell' => 'urn:oasis:names:tc:opendocument:xmlns:table:1.0',
                    'draw:frame'       => 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0',
                    'draw:text-box'    => 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0',
                    'draw:control'     => 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0',
                   );
        
        $name = $this->_node->nodeName;
        if (!isset($prefix[$name])) {
            $message = 'The element: ' . $name . ' can not be styled this way.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            return $this;
        }
        if ($name == 'style:header' || $name == 'style:footer') {
            //This is a little more complex because the header styles are
            //stored in the page properties within the styles.xml file
            $pageLayout = $this->_node
                               ->parentNode
                               ->getAttribute('style:page-layout-name');
            if (!empty($pageLayout)) {
                if($name == 'style:header'){
                    Docxpresso\CreateDocument::$styleHeader[$pageLayout] = $style;
                } else {
                    Docxpresso\CreateDocument::$styleFooter[$pageLayout] = $style;
                }                   
            }
            return $this;
        }
        if($name == 'text:list-item') {
            /*$styleId = $this->_node->getAttribute($prefix[$name] 
                                                  . ':style-override');*/
        } else {
            $styleId = $this->_node->getAttribute($prefix[$name] 
                                                  . ':style-name');
        }
        if (empty($styleId)) {
            $styleId = commons\Utilities::generateId();
            if($name == 'text:list-item') {
                /*$this->_node->setAttributeNS($ns[$name],
                                             $prefix[$name] . ':style-override',
                                             $styleId);*/
            } else {
                $this->_node->setAttributeNS($ns[$name],
                                             $prefix[$name] . ':style-name',
                                             $styleId);
            }
        }
        $target = $this->getTarget();
        
        //for frames, form controls and textboxes we have to do some extra work 
        //because there are some attributes directly set in the draw:frame 
        //and draw:control elements
        if ($this->getNodeType() == 'draw:frame' 
            || $this->getNodeType() == 'draw:control') {
           $frame = $this->_node; 
           $this->_addStyle($name, $style, $styleId, $target);
           $this->_addStyle('text:p', $style, 'text-' . $styleId, $target);
        } else if ( $this->getNodeType() == 'draw:text-box') {
           $frame = $this->_node->parentNode;
           //we have to force by hand certain values to style thenparent frame
           $name = 'draw:frame';
           $styleId = $frame->getAttribute($prefix[$name] . ':style-name');
           $this->_addStyle($name, $style, $styleId, $target);
        } else if ( $this->getNodeType() == 'text:list') {
            $xmlId = $this->getNode()->getAttribute('xml:id');
            if (!empty($xmlId)) {
                $listData = explode('_', $xmlId);
                $listId = $listData[0] . '_' . $listData[1];
                $level = (integer) $listData[2];
                $st =& Docxpresso\CreateDocument::$list;
                if (isset($st[$listId][$level]['style'])) {
                    $st[$listId][$level]['style'] .= $style;
                } else {
                    $st[$listId][$level]['style'] = $style;  
                }
            }  
        } else {
           $this->_addStyle($name, $style, $styleId, $target);
        }
        if (isset($frame)) {
            $data = Frame::parsePositioning(array('style' => $style), 
                                            false,
                                            true);
            if (!empty($data['width'])) {
                $frame->setAttribute('svg:width', $data['width']);
            }
            if (!empty($data['height'])) {
                $frame->setAttribute('svg:height', $data['height']);
            } 
            //if is set $x
            if (isset($data['x'])) {
               $frame->setAttribute('svg:x', $data['x']); 
            }
            //if is set $y
            if (isset($data['y'])) {
               $frame->setAttribute('svg:y', $data['y']); 
            }
            if (!empty($data['anchor'])){
                $textNS = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
                $frame->setAttributeNS($textNS, 
                                       'text:anchor-type',
                                       $data['anchor']);
            }
        }
        return $this;
    }
    
    /**
     * adds a tab to a run of text
     * 
     * @param array $options with the following keys and values
     *      'type' => (string)  the accepted values are left, center, right or
     *       char (in this case the text will be aligned in reference to a 
     *       specified character)
     *      'character' => (string) the character to be used in if the type is 
     *       char (the default value is '.')
     *      'leader' => (string) the available style are:
     *          none: tab stop has no leader line. 
     *          dash: tab stop has a dashed leader line. 
     *          dot-dash: tab stop has a dot followed by a dash. 
     *          dot-dot-dash: tab stop has two dots followed by a dash.  
     *          dotted: tab stop has a dotted leader line. 
     *          long-dash: tab stop has a long dash line. 
     *          solid: tab stop has a solid leader line. 
     *          wave: tab stop has a wavy leader line. 
     *      'position' => (int) distance from the left margin or the left indent
     *       given in points.
     * 
     * @return ODFElement
     * @access public
     */
    public function tab($options = array()) 
    {
        //we should get the parent paragraph
        $nodeType = $this->getNodeType();
        if ($nodeType == 'text:p' || $nodeType == 'text:h') {
            $tab = new Tab($this, $options);
        } else {
            //get the containing paragraph
            $ancestor = $this->getAncestor($this,
                                           Tab::$parentNodes);
            $tab = new Tab($ancestor, $options);
        }
        return $this;
    }
    /**
     * adds a table to the document
     * 
     * @param array $options with the following keys and values
     *      'table-content' => (array)  an optional array of arrays with the
     *       values to be included in the different table rows and cells. 
     *       Each final entry may be an string of text or a document fragment. 
     *       Beware that you can get higher control
     *       by including the table contents via the row and cell methods.
     *      'grid' => (mixed) mandatory if the data option is not set. 
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
     * @param bool $reference if true returns a reference to the paragraph
     * element. The default value is true 
     * 
     * @return ODFElement
     * @access public
     */
    public function table($options = array(), $reference = true) 
    {
        //First check for page  breaks
        if (Docxpresso\CreateDocument::$break == 'page'
            || Docxpresso\CreateDocument::$break == 'column') {
            if(isset($options['style'])){
                $options['style'] = $this->_break($options['style']);
            } else {
                $options['style'] = $this->_break();
            }
            //reset the static variable to none
            Docxpresso\CreateDocument::$break = 'none';
        }
        if (!isset($options['grid'])) {
            if (isset($options['data'])) {
                $options['grid'] = \count($options['data']);
            } else {
                //we set just one column although we will try to fix that as
                //new cells are added to the table
                $options['grid'] = 1;
                $message = 'We set the table to have just a single column.' ;
                commons\DOCXPRESSOLogger::logger($message, 'info'); 
            }
        }
        //if enforce is not set we assume that we should
        //get the first element ancestor that accepts the table element
        if (!isset($options['enforce'])) {
            $options['enforce'] = true;
        }
        
        if (isset($options['styleId'])) {
            $this->_styleId = $options['styleId'];
        } else {
            $this->_styleId = commons\Utilities::generateId();
        }
        
        $target = $this->getTarget();
        $this->_styleProcessing('table:table', 
                                Table::getDefaultStyles($options['grid']),
                                $options,
                                $this->_styleId,
                                $target);
        if ($options['enforce']
            && !isset(Table::$parentNodes[$this->_nodeType]) ) {
            $ancestor = $this->getAncestor($this,
                                           Table::$parentNodes);
            //update the _styleId private property so we keep the right
            //inheritance
            $ancestor->setStyleId($this->_styleId);
            $table = new Table($ancestor, $options);
        } else {
            $table = new Table($this, $options);
        }
        if (isset($options['table-content'])) {
            foreach ($options['table-content'] as $value) {
                $table->row(array('row-content' => $value));
            }
        }
        if ($reference) {
            return $table;
        } else {
            return $this;
        }
    }
    
    /**
     * adds a table cell to a table
     * 
     * @param array $options with the following keys and values
     *      'cell-content' => (mixed)  the cell content. It may be a string of
     *       text or a document fragment.
     *      'rowspan' => (int) the number of rows that the table cell should
     *       cover.
     *      'colspan' => (int) the number of columns that the table cell should
     *       cover.
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     * @param bool $reference if true returns a reference to the paragraph
     * element. The default value is true 
     * 
     * @return ODFElement
     * @access public
     */
    public function cell($options = array(), $reference = true) 
    {
        //if enforce is not set we assume that we should
        //get the first element ancestor that accepts the paragraph element
        if (!isset($options['enforce'])) {
            $options['enforce'] = true;
        }
        if (isset($options['styleId'])) {
            $this->_styleId = $options['styleId'];
        } else {
            $this->_styleId = commons\Utilities::generateId();
        }
        $target = $this->getTarget();
        $this->_styleProcessing('table:table-cell', 
                                Cell::getDefaultStyles(0),
                                $options,
                                $this->_styleId,
                                $target);
        if ($options['enforce']
            && !isset(Cell::$parentNodes[$this->_nodeType])) {
            $ancestor = $this->getAncestor($this,
                                           Cell::$parentNodes);
            //update the _styleId private property so we keep the right
            //inheritance
            $ancestor->setStyleId($this->_styleId);
            $cell = new Cell($ancestor, $options);
        } else {
            $cell = new Cell($this, $options);
        }
        if (isset($options['cell-content']) 
            && \is_string($options['cell-content'])) {
            $paragraph = $cell->paragraph($options, true);
            if ($reference) {
                return $paragraph;
            }
        } else if (isset($options['cell-content']) 
                   && ($options['cell-content'] instanceof DocumentFragment)) {
            $fragment = $cell->insertDocumentFragment($options['cell-content']);
            if ($reference) {
                return $fragment;
            }
        }
        if ($reference) {
            return $cell;
        } else {
            return $this;
        }
    }
    
    /**
     * adds a table row to a table
     * 
     * @param array $options with the following keys and values
     *      'header' => (boolean) if true it is a header row. If a table spans
     *       trough more than one page the header rows are repeated at the
     *       beginning of each new page.
     *      'row-content' => (array)  the row content. Each array item may be a
     *       string of text or a document fragment. Beware that you may have
     *       higher control by using the cell method to insert content 
     *       into a row.
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     * @param bool $reference if true returns a reference to the paragraph
     * element. The default value is true 
     * 
     * @return ODFElement
     * @access public
     */
    public function row($options = array(), $reference = true) 
    {
        //First check for page breaks
        if (Docxpresso\CreateDocument::$break == 'page'
            || Docxpresso\CreateDocument::$break == 'column') {
            if(isset($options['style'])){
                $options['style'] = $this->_break($options['style']);
            } else {
                $options['style'] = $this->_break();
            }
            //reset the static variable to none
            Docxpresso\CreateDocument::$break = 'none';
        }
        //if enforce is not set we assume that we should
        //get the first element ancestor that accepts the row element
        if (!isset($options['enforce'])) {
            $options['enforce'] = true;
        }

        if (isset($options['styleId'])) {
            $this->_styleId = $options['styleId'];
        } else {
            $this->_styleId = commons\Utilities::generateId();
        }
        $target = $this->getTarget();
        $this->_styleProcessing('table:table-row', 
                                Row::getDefaultStyles(0),
                                $options,
                                $this->_styleId,
                                $target);
        if ($options['enforce']
            && !isset(Row::$parentNodes[$this->_nodeType]) ) {
            $ancestor = $this->getAncestor($this,
                                           Row::$parentNodes);
            //update the _styleId private property so we keep the right
            //inheritance
            $ancestor->setStyleId($this->_styleId);
            $row = new Row($ancestor, $options);
        } else {
            $row = new Row($this, $options);
        }
        if (isset($options['row-content'])) {
            foreach ($options['row-content'] as $value) {
                $table->row(array('cell-content' => $value));
            }
        }
        if ($reference) {
            return $row;
        } else {
            return $this;
        }
    }
    
    /**
     * adds text into a paragraph
     * If we try to insert directly text into the document a wrapping paragraph
     * with default styles will be automatically created
     * 
     * @param array $options with the following keys and values
     *      'nestSpans' => (boolean) if set to true the text spans will be 
     *       nested. Default value is false
     *      'text' => (string)  the text to insert
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     * @param bool $reference if true returns a reference to the text element
     * so they can be nested.
     * @return ODFElement
     * @access public
     */
    public function text($options, $reference = true) 
    {
        //if we allow nesting of spans 'text:span' can be an allowable parent
        //otherwise (default behaviour) we should remove it from the list
        if (isset($options['nestSpans']) && $options['nestSpans'] === true) {
            $parentNodes = Text::$parentNodes;
        } else {
            $parentNodes = Text::$parentNodes;
            unset($parentNodes['text:span']);
        }
        if($this->getNodeType() == 'text:a') {
            //this is a hack to avoid poor inheritance of link styles in Word
            $autoStyles =& Docxpresso\CreateDocument::$automaticStyles;
            if (isset($options['style']) 
                && \is_string($options['style'])
                && !empty($this->_currentStyleId)) {
                $options['style'] = $autoStyles[$this->_currentStyleId] . ';'
                                    . $options['style'];
            } else if (!isset($options['style']) 
                       && \is_string($autoStyles[$this->_currentStyleId])
                       && !empty($this->_currentStyleId)) {
                $options['style'] = $autoStyles[$this->_currentStyleId] . ';';
            }
        }
        if (isset($options['styleId'])) {
            $this->_styleId = $options['styleId'];
        } else {
            $this->_styleId = commons\Utilities::generateId();
        }
        $target = $this->getTarget();
        $this->_styleProcessing('text:span', 
                                Text::getDefaultStyles(0),
                                $options,
                                $this->_styleId,
                                $target);
        if (!isset($options['text'])) {
            $options['text'] = '';
        }
        //check if there is a parent paragraph or an allowed parent tag
        //otherwise search for a suitable ancestor node or create a paragraph 
        //element where the text will be inserted
        if (!isset($parentNodes[$this->_nodeType])) {
            //run over the spacial cases:
            // 1. table cell
            // 2. list item
            // 3. text box
            if($this->_nodeType == 'table:table-cell'
               || $this->_nodeType == 'text:list-item'
               || $this->_nodeType == 'draw:text-box'
               || $this->_nodeType == 'style:header'
               || $this->_nodeType == 'style:footer'
               || $this->_nodeType == 'text:note-body') {
                $span = $this->paragraph()->text($options, $reference);
                if ($reference) {
                    return $span;
                } else {
                    return $this;
                }
            } else {
                $ancestor = $this->getAncestor($this, $parentNodes);
                //update the _styleId private property so we keep the right
                //inheritance
                $ancestor->setStyleId($this->_styleId);
                $span = new Text($ancestor, $options['text']);
            }
        } else {
            $span = new Text($this, $options['text']);
        }
        if ($reference) {
            return $span;
        } else {
            return $this;
        }    
    }
    
    /**
     * adds text box into the document
     * The text box can be inserted inline (default) or floating to the left 
     * or right
     * 
     * @param array $options with the following keys and values
     *      'pageNumber' => (int) for absolutely positioned textboxes (optional)
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     * @param bool $reference if true returns a reference to the image element
     * so further elements can be nested. The default value is false.
     * @return ODFElement
     * @access public
     */
    public function textBox($options = array(), $reference = true) 
    {
        if (isset($options['styleId'])) {
            $this->_styleId = $options['styleId'];
        } else {
            $this->_styleId = commons\Utilities::generateId();
        }
        $target = $this->getTarget();
        $this->_styleProcessing('draw:frame', 
            TextBox::getDefaultStyles(0),
            $options,
            $this->_styleId,
            $target);
        $textBox = new TextBox($this, $options);
        if ($reference) {
            return $textBox;
        } else {  
            return $this;
        }

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
        $this->_styleId = commons\Utilities::generateId();
        $this->_styleProcessing('text:p', 
                                '',
                                '',
                                $this->_styleId,
                                'document');
        
        if (isset($options['auto-update'])) {
            $update = $options['auto-update'];
        } else {
            $update = true;
        }
        if ($update) {
            //set the toc static variable to true
            Docxpresso\CreateDocument::$toc = true;
        }
        //set the title
        if (isset($options['title'])) {
            $title = $options['title'];
        } else {
            $title = '';
        }
        //set the maximum number of levels
        if (isset($options['outline-level'])
            && is_int($options['outline-level'])
            && $options['outline-level'] <= 10) {
            $maxLevel = $options['outline-level'];
        } else {
            $maxLevel = 3;
        }
        //set the leader char
        if (isset($options['leader-char'])) {
            $leader = $options['leader-char'];
        } else {
            $leader = '.';
        }
        $node = $this->getNode();
        $ns = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
        $tocNode = $node->ownerDocument
                        ->createElement('text:table-of-content');
        $ns = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
        $tocNode->setAttributeNS($ns, 'text:style-name', $this->_styleId);
        $name = 'Table of Contents' . uniqid();
        $tocNode->setAttribute('text:name', $name);
        $tocSource = $node->ownerDocument
                          ->createElement('text:table-of-content-source');
        $tocSource->setAttribute('text:outline-level', $maxLevel);
        $tocSource->setAttribute('text:use-index-marks', 'false');
        $tocTitle = $node->ownerDocument
                          ->createElement('text:index-title-template', $title);
        $tocTitle->setAttribute('text:style-name', 'styleTOC_0');
        $tocSource->appendChild($tocTitle);
        //now loop over the template
        for ($j = 1; $j <= $maxLevel; $j++) {
            $template = $node->ownerDocument
                ->createElement('text:table-of-content-entry-template');
            $template->setAttribute('text:outline-level', $j);
            $template->setAttribute('text:style-name', 'styleTOC_' . $j);
            //insert the start of the TOC link
            $linkStart = $node->ownerDocument
                ->createElement('text:index-entry-link-start');
            $linkStart->setAttribute('text:style-name', 'linkTOC_' . $j);
            $template->appendChild($linkStart);
            //insert the chapter
            $chapter = $node->ownerDocument
                ->createElement('text:index-entry-chapter');
            $template->appendChild($chapter);
            //insert the heading text
            $text = $node->ownerDocument
                ->createElement('text:index-entry-text');
            $template->appendChild($text);
            //insert the tab info
            $tab = $node->ownerDocument
                ->createElement('text:index-entry-tab-stop');
            $tab->setAttribute('style:type', 'right');
            $tab->setAttribute('style:leader-char', $leader);
            $template->appendChild($tab);
            //insert the page number
            $page = $node->ownerDocument
                ->createElement('text:index-entry-page-number');
            $template->appendChild($page);
            //close the link
            $linkEnd = $node->ownerDocument
                ->createElement('text:index-entry-link-end');
            $template->appendChild($linkEnd);
            //insert the template node in the template source
            $tocSource->appendChild($template);
        }
        //insert the source into the toc
        $tocNode->appendChild($tocSource);
        
        //generate and append the table of contents body
        $body = $node->ownerDocument
                ->createElement('text:index-body');
        //take care of the title
        $titleNode = $node->ownerDocument
                          ->createElement('text:index-title');
        $titleNode->setAttribute('text:name', $name . '_Head');
        $p = $node->ownerDocument->createElement('text:p', $title);
        $p->setAttribute('text:style-name', 'styleTOC_0');
        $titleNode->appendChild($p);
        $body->appendChild($titleNode);
        $tocNode->appendChild($body);
        
        //check if there is a cursor within the node
        $cursors = $node->getElementsByTagName('customCursor');
        if ($cursors->length > 0) {
            $cursor = $cursors->item(0);
            $cursor->parentNode->insertBefore($tocNode, $cursor);
        } else {
            $node->appendChild($tocNode);
        }
        //now take care of the styles
        for ( $j = 0; $j < $maxLevel; $j++) {
            if (isset($options['style'][$j])) {
                $style = $options['style'][$j];
            } else {
                $style = '';
            }
            if ($j > 0) {
                $margin = 'margin-left:' . ($j-1) * 0.5 . 'cm;';
            } else {
                $margin = 'margin-left: 0cm;';
            }
            $margin .= 'margin-top: 0cm; margin-bottom: 0cm; margin-right: 0cm;';
            //p styles
            $sP = $margin . $style;
            Docxpresso\CreateDocument::$automaticStyles['styleTOC_' . $j] = $sP;
            Docxpresso\CreateDocument::$styleTypes['styleTOC_' . $j] = 'text:p';
            Docxpresso\CreateDocument::$styleTarget['styleTOC_' . $j] = 'style';
            //link styles
            $sL = 'padding: 0cm;';
            $sL .= 'text-decoration: none; color: black;' . $style;
            Docxpresso\CreateDocument::$automaticStyles['linkTOC_' . $j] = $sL;
            Docxpresso\CreateDocument::$styleTypes['linkTOC_'. $j] = 'text:span';
            Docxpresso\CreateDocument::$styleTarget['linkTOC_' . $j] = 'style';
     
        }
        //return the root element to allow for further chaining
        return $this;
    }
    
    /**
     * adds an unordered list to the document
     * 
     * @param array $options with the following keys and values
     *      'items' => (array) a (nested) array with the items to be included
     *      in the list (plain text or document fragments). This argument is
     *      optional because the items can also be added via the itemList
     *      method.
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     *      'enforce' => (bool) if set to true (default) and the current element 
     *      does not accept paragraphs the method searches for the first 
     *      ancestor that allows for the insertion. Beware that the return
     *      element is a reference to the inserted paragraph and the chaining
     *      may result affected consequently!!
     * @param bool $reference if true returns a reference to the list element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function orderedList($options = array(), $reference = true) 
    {
        $options['type'] = 'ordered';
        $listId = commons\Utilities::generateId('list_');
        $oList = $this->_list($listId, $options, $reference);
        if (isset($options['items'])) {
            $oList = $oList->_insertListItems($this,
                                              $options['items'], 
                                              'orderedList');
        }
        if ($oList->getNodeType() != 'text:list') {
            $list = $oList->parentList();
        } else {
            $list = $oList;
        }
        if ($reference) {
            return $list;
        } else {
            return $this;
        }
    }
    
    /**
     * adds an unordered list to the document
     * 
     * @param array $options with the following keys and values
     *      'items' => (array) a (nested) array with the items to be included
     *      in the list (plain text or document fragments). This argument is
     *      optional because the items can also be added via the itemList
     *      method.
     *      'style' => (string) a list of properties in CSS format
     *      'styleId' => (string) if given the styleId is not generated randomly
     *      'enforce' => (bool) if set to true (default) and the current element 
     *      does not accept paragraphs the method searches for the first 
     *      ancestor that allows for the insertion. Beware that the return
     *      element is a reference to the inserted paragraph and the chaining
     *      may result affected consequently!!
     * @param bool $reference if true returns a reference to the list element
     * so further elements can be nested. The default value is true.
     * @return ODFElement
     * @access public
     */
    public function unorderedList($options = array(), $reference = true) 
    {
        $options['type'] = 'unordered';
        $listId = commons\Utilities::generateId('list_');
        $uList = $this->_list($listId, $options, $reference);
        if (isset($options['items'])) {
            $uList = $uList->_insertListItems($this,
                                              $options['items'], 
                                              'unorderedList');
        }
        if ($uList->getNodeType() != 'text:list') {
            $list = $uList->parentList();
        } else {
            $list = $uList;
        }
        if ($reference) {
            return $list;
        } else {
            return $this;
        }
    }
    
    /**
     * Moves one step up in the ODF 'block' element tree.
     * 
     * @param $element it can take the following values:
     *  'list',
     *  'paragraph',
     *  'section',
     *  'table',
     *  'row',
     *  'cell',
     *  'chart',
     *  'frame',
     *  'root'
     * if element is left empty the method returns the first parent that
     * fits one of the previous values.
     * @return ODFElement
     * @access public
     */
    public function parent($element = '') 
    {
        $parent = $this->getParentODFElement();
        if(empty($parent)){
            return $this;
        }
        $name = $parent->getNode()->nodeName;
        if (!empty($element)) {
            if (isset(Resources::$ODFBlockElements[$element])
                && $name == Resources::$ODFBlockElements[$element]) {
                return $parent;
            } else if ($name != 'office:text' && $name != 'office:fragment') {
                $parent = $parent->parent($element);
            } else {
                $message = 'The parent ' . $element . 'could not be found' ;
                commons\DOCXPRESSOLogger::logger($message, 'info'); 
                return $this;
            }
        } else {
            if (\in_array($name, Resources::$ODFBlockElements)) {
                return $parent;
            } else if ($name != 'office:text' && $name != 'office:fragment') {
                $parent = $parent->parent($element);
            } else {
                $message = 'A parent element could not be found' ;
                commons\DOCXPRESSOLogger::logger($message, 'info'); 
                return $this;
            }
        } 
        return $parent;
    }
    
    /**
     * adds extra whitespace within a paragraph or run of text
     * If we try to insert directly text into the document a wrapping paragraph
     * with default styles will be automatically created
     * 
     * @param int $number the number of whitespaces we want to insert
     * @return ODFElement
     * @access public
     */
    public function whitespace($number = 1) 
    {
        //check if there is a parent paragraph or an allowed parent tag
        //otherwise search for a suitable ancestor node or create a paragraph 
        //element where the text will be inserted
        if (isset(WhiteSpace::$parentNodes[$this->_nodeType])) {
            $ws = new WhiteSpace($this, $number);
        } else {
            //one can only include a row within a table
            $message = 'You can only add whitespace to a run of text.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
        }
            return $this;   
    }
    
    /**
     * Moves one list up in the ODF element tree
     * 
     * @return ODFElement
     * @access public
     */
    public function parentList() 
    {
        $list= $this->parent('list')->parent('list');
        return $list;
    }
    
    /**
     * adds plain text into a node that accepts it directly.
     * 
     * @param string $text
     * @return ODFElement
     * @access public
     */
    public function plainText($text) 
    {
        $nodeName = $this->getNodeType();
        $node = $this->getNode();
        if ($nodeName == 'text:a') {
            //this case is a little more complex because of poor inheritance in
            //the current implementations of ODF
            $styleId = $node->getAttribute('text:style-name');
            $span = $node->ownerDocument->createElement('text:span', $text);
            if (!empty($styleId)) {
                $textNS = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
                $span->setAttributeNS($textNS, 
                                      'text:style-name', 
                                       $styleId);
            }
            $node->appendChild($span);
        } else {
            $nodeText = $node->ownerDocument->createTextNode($text);
            $node->appendChild($nodeText);
        }
        return $this;    
    }
    
    /**
     * inserts a chart into a document with all data and styling options
     * encoded in a XML string.
     * 
     * @param string $xml with the following structure
     * <chart style="CSS styles"
     *       type="column|bar|pie|donut|area|line|scatter|bubble|radar|filled-radar|column-line|3Dcolumn|3Dbar|3Dpie|3Ddonut|3Darea|3Dline|3Dscatter"  
     *        data-label-number="none|value|percentage"
     *        label-position="avoid-overlap|center|top|top-right|right|bottom-right|bottom|bottom-left|left|top-left|inside|outside|near-origin"
     *        label-position-negative="top-left|inside|outside|near-origin"
     *        hole-size="integer"
     *        pie-offset="integer"
     *        angle-offset="integer"
     *        stacked="boolean"
     *        gap-width="integer"
     *        overlap="integer"
     *        percentage="boolean"
     *        chart-interpolation="none|b-spline|cubic-spline"
     *        spline-resolution="integer"
     *        deep="boolean"
     *        solid-type="cuboid|cylinder|cone|pyramid" >
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
     *     <legend name="" //only applies to bubble charts
     *             legend-position="left|right|top|bottom" 
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
     *     <!-- for bubble charts -->
     *     <series>
     *         <ser name="" 
     *              fill-color="hexadecimal color"
     *              opacity="integer%"
     *              stroke="solid|dash|none"
     *              stroke-width="float(pt|cm|in|mm)"
     *              stroke-color="hexadecimal color"
     *              stroke-opacity="integer%" />
     *     </series>
     *     <categories>
     *         <category>
     *             <data value="" />
     *             <data value="" />
     *             <data value="" />
     *         </category>
     *         <category>
     *             <data value="" />
     *             <data value="" />
     *             <data value="" />
     *         </category>
     *         <category>
     *             <data value="" />
     *             <data value="" />
     *             <data value="" />
     *         </category>
     *     </categories>
     *     <!-- for all other charts -->
     *     <series>
     *         <ser name="" 
     *              fill-color="hexadecimal color"
     *              opacity="integer%"
     *              stroke="solid|dash|none"
     *              stroke-width="float(pt|cm|in|mm)"
     *              stroke-color="hexadecimal color"
     *              stroke-opacity="integer%" />
     *         <ser name="" 
     *              fill-color="hexadecimal color"
     *              opacity="integer%"
     *              stroke="solid|dash|none"
     *              stroke-width="float(pt|cm|in|mm)"
     *              stroke-color="hexadecimal color"
     *              stroke-opacity="integer%" />
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
        if (!empty($styleId)) {
            $this->_styleId = $styleId;
        } else {
            $this->_styleId = commons\Utilities::generateId();
        }
        //load the xml string into the DOM
        $chartXML = new \DOMDocument();
        $chartXML->loadXML($xml);
        //set some general required variables
        $options = array();
        $options['styleId'] = $this->_styleId;
        $options['xml'] = $chartXML;
        $chartNode = $chartXML->getElementsByTagName('chart')->item(0);
        $style = $chartNode->getAttribute('style');
        if (!empty($style)) {
           $options['style'] = $style;
        } else {
           $options['style'] = '';
        }
        $target = $this->getTarget();
        $this->_styleProcessing('draw:frame', 
            Image::getDefaultStyles(0),
            $options,
            $this->_styleId,
            $target);
        $chartOptions = array();
        $chartOptions = 
            commons\Utilities::attributes2Array($chartNode);
        if (!isset($chartOptions['type'])) {
            $message = 'No chart type was not defined';
            commons\DOCXPRESSOLogger::logger($message, 'critical'); 
            return $this;
        }
        $options['chart-properties'] = $chartOptions;
        $options['type'] = $chartOptions['type'];
        unset($options['chart-properties']['style']);
        unset($options['chart-properties']['type']);
        $chart = new DocumentObject($this, 'chart', $options);
        //run now over the data nodes and other chart properties
        //detect the type
        if ($chartOptions['type'][0] == '3') {
            $type = \strtolower(\substr($chartOptions['type'], 2));
        } else {
            $type = \strtolower($chartOptions['type']);
        }
        //data
        $data = array();
        $dataOptions = array();
        if ($type == 'pie' || $type == 'donut') {
            $categories = $chartXML->getElementsByTagName('category');
            $counter = 0;
            foreach ($categories as $category) {
                $dataOptions[$counter] = 
                    commons\Utilities::attributes2Array($category);
                $data[$dataOptions[$counter]['name']] = 
                    $dataOptions[$counter]['value'];
                unset($dataOptions[$counter]['name']);
                unset($dataOptions[$counter]['value']);
                $counter++;
            }
        } else if ($type == 'bubble') {
            $series = $chartXML->getElementsByTagName('ser');
            foreach ($series as $ser) {
                $dataOptions[0] = 
                    commons\Utilities::attributes2Array($ser);
                unset($dataOptions[0]['name']);
            }
            $categories = $chartXML->getElementsByTagName('category');
            $counter = 0;
            foreach ($categories as $category) {
                $data[$counter] = array();
                $dataNodes = $category->getElementsByTagName('data');
                foreach ($dataNodes as $dataNode) {
                    $data[$counter][] = $dataNode->getAttribute('value');
                }
                $counter++;
            }
        } else {
            $series = $chartXML->getElementsByTagName('ser');
            $counter = 0;
            $data['series'] = array();
            foreach ($series as $ser) {
                $dataOptions[$counter] = 
                    commons\Utilities::attributes2Array($ser);
                $data['series'][] = $dataOptions[$counter]['name'];
                unset($dataOptions[$counter]['name']);
                $counter++;
            }
            $categories = $chartXML->getElementsByTagName('category');
            foreach ($categories as $category) {
                $name = $category->getAttribute('name');
                $data[$name] = array();
                $dataPoints = $category->getElementsByTagName('data');
                foreach($dataPoints as $point) {
                    $data[$name][] = $point->getAttribute('value');
                }
            } 
        }
        $chart->chartData($data, $dataOptions);
        //legend
        $legends = $chartXML->getElementsByTagName('legend');
        if ($legends->length > 0) {
            $legend = $legends->item(0);
            $legendOptions = commons\Utilities::attributes2Array($legend);
            $chart->chartLegend($legendOptions);
        }
        //title
        $titles = $chartXML->getElementsByTagName('title');
        if ($titles->length > 0) {
            $title = $titles->item(0);
            $titleOptions = commons\Utilities::attributes2Array($title);
            $titleOptions['text'] = $title->nodeValue;
            $chart->chartTitle($titleOptions);
        }
        //axis
        $axis = $chartXML->getElementsByTagName('axis');
        if ($axis->length > 0) {
            foreach ($axis as $ax) {
                $axisOptions = 
                    commons\Utilities::attributes2Array($ax);
                $dimension = $axisOptions['dimension'];
                unset($axisOptions['dimension']);
                //import some general properties
                if (isset($chartOptions['gap-width'])
                    && !isset($axisOptions['gap-width'])) {
                    $axisOptions['gap-width'] = $chartOptions['gap-width']; 
                }
                if (isset($chartOptions['overlap'])
                    && !isset($axisOptions['overlap'])) {
                    $axisOptions['overlap'] = $chartOptions['overlap']; 
                }
                $chart->chartAxis($dimension, $axisOptions);
            }
        }
        //grid
        $grids = $chartXML->getElementsByTagName('grid');
        if ($axis->length > 0) {
            foreach ($grids as $grid) {
                $gridOptions = 
                    commons\Utilities::attributes2Array($grid);
                $dimension = $gridOptions['dimension'];
                if (isset($gridOptions['type'])) {
                    $type = $gridOptions['type'];
                    unset($gridOptions['type']);
                } else {
                    $type = 'major';
                }
                unset($gridOptions['dimension']);
                $chart->chartGrid($dimension, $type, $gridOptions);
            }
        }
        //components
        $components = $chartXML->getElementsByTagName('component');
        if ($components->length > 0) {
            foreach ($components as $component) {
                $componentOptions = 
                    commons\Utilities::attributes2Array($component);
                if (isset($componentOptions['type'])) {
                    $type = $componentOptions['type'];
                    unset($componentOptions['type']);
                } else {
                    $type = 'floor';
                }
                if (isset($axisOptions['type'])) {
                    unset($axisOptions['type']);
                }
                $chart->chartComponent($type, $componentOptions);
            }
        }
        //3Dtransform
        $transform = $chartXML->getElementsByTagName('transform3D');
        if ($transform->length > 0) {
            $trans = $transform->item(0);
            $transOptions = commons\Utilities::attributes2Array($trans);
            $chart->chart3DTransform($transOptions);
        }
        
        return $chart;
    }
    
    /**
     * adds plain text to a given paragraph or span of text
     * This method is mainly thought for HTML parsing
     * 
     * @param string $text
     * @return ODFElement
     * @access protected
     */
    protected function _plainText($text) 
    {
        $span = new plainText($this, $text);
        return $this;
    }
    
    /**
     * returns the XML node code
     * 
     * @return string
     * @access public
     */
    public function printXML() 
    {
        return $this->getNode()->ownerDocument->saveXML($this->getNode());
    }
    
    /**
     * Adds an style to list of styles
     * 
     * @param string $type
     * @param mixed $style
     * @param string $styleId
     * @param string $target
     * @return array
     * @access public
     */
    public function _addStyle($type, $style, $styleId, $target) 
    {
        if (isset(Docxpresso\CreateDocument::$automaticStyles[$styleId])) {
            if(is_string(Docxpresso\CreateDocument::$automaticStyles[$styleId])){
                Docxpresso\CreateDocument::$automaticStyles[$styleId] =
                Docxpresso\CreateDocument::$automaticStyles[$styleId] . 
                ';' . $style;
            }else {
                Docxpresso\CreateDocument::$automaticStyles[$styleId] = $style;
            }
        } else {
            Docxpresso\CreateDocument::$automaticStyles[$styleId] = $style;
            //If the style comes from parsing HTML is already an array with
            //all the required default data
            if (is_string($style)){
                Docxpresso\CreateDocument::$automaticStyles[$styleId] .=
                            ';parentStyleId: ' . $this->_currentStyleId . ';';
            }        
        }
        Docxpresso\CreateDocument::$styleTypes[$styleId] = $type;
        Docxpresso\CreateDocument::$styleTarget[$styleId] = $target;
    }
    
    /**
     * manages the page breaks inserted into the document
     * 
     * @param mixed $style
     * @return mixed
     * @access private
     */
    private function _break($style = '') 
    {   
        if(!empty($style) && \is_string($style)){
            $style = 'break-before: '
                    . Docxpresso\CreateDocument::$break . '; ' 
                    . $style;
        } else if (\is_array($style)){
            $style['break-before'] = Docxpresso\CreateDocument::$break;
        } else {
            $style = 'break-before: ' . Docxpresso\CreateDocument::$break . ';';
        }    
        return $style;
    }
    
    /**
     * generates an internal control element
     * 
     * @param string $name
     * @param string $type
     * @param array $options
     * @return ODFElement
     * @access protected
     */
    protected function _drawControl($name, $type, $options) 
    {
        if (isset($options['styleId'])) {
            $this->_styleId = $options['styleId'];
        } else {
            $this->_styleId = commons\Utilities::generateId();
        }
        $target = $this->getTarget();
        $this->_styleProcessing('draw:control', 
                                Control::getDefaultStyles(0, $type),
                                $options,
                                $this->_styleId,
                                $target);
        $this->_styleProcessing('text:p', 
                                Control::getDefaultStyles(0, $type),
                                $options,
                                'text-' . $this->_styleId,
                                $target);
        $control = new Control($this, $name, $type, $options);
        return $control;
    }
    
    /**
     * gets the current chart reference when chaining is not used
     * 
     * @return mixed
     * @access private
     */
    private function _getChart() 
    {
        $charts = $this->getNode()->getElementsByTagName('draw:object');
        $number = $charts->length;
        if ($number > 0) {
            $key = $charts->item($number-1)->getAttribute('xml:id');
            if (isset(Docxpresso\CreateDocument::$charts[$key])) {
                return Docxpresso\CreateDocument::$charts[$key];
            } else {
                $message = 'No chart was found';
                commons\DOCXPRESSOLogger::logger($message, 'info'); 
            }
        } else {
            $message = 'No chart was found';
            commons\DOCXPRESSOLogger::logger($message, 'info'); 
        }
        return false;
    }
    
    /**
     * Returns the first ancestor ODF node with the given node name or, if not
     * found, returns the root document element
     * 
     * @param ODFElement $odfNode
     * @param string $name ODF node name
     * @return ODFNode
     * @access public
     */
    public function _gotoODFnode($odfNode, $name = '') 
    {
        if ($name == '') {
           $name = 'office:text'; 
        }
        $curName = $odfNode->getNode()->nodeName;
        if ($curName == 'office:text' 
            || $curName == 'office:fragment'
            || $curName == $name) {
            return $odfNode;
        }
        $node = $odfNode->getParentODFElement();
        $parent = $node->getNode()->nodeName;
        if ($parent == $name) {
            return $node;
        } else {
            $node = $this->_gotoODFNode($node, $name);
        }
        return $node;
    }
    
    /**
     * Recursively inserts items into a list
     * 
     * @param ODFElement $element
     * @param array $items
     * @param string $type
     * @return ODFElement
     * @access private
     */
    private function _insertListItems($element, $items, $type) 
    {
        foreach ($items as $value) {
            if (!\is_array($value)) {
                $element = $this->listItem(array('text' => $value));
            } else {
                $element->$type(array('items' => $value));
            }
        }
        return $element;
    }
    
    /**
     * Inserts a ODF node
     * 
     * @param DOMNode $newNode the node to insert
     * @param DOMElement $refNode the node where to insert the new node
     * @param string $mode append, after, before, first
     * @return void
     * @access private
     */
    private function _insertODFNode($newNode, 
                                    $refNode = NULL, 
                                    $mode = 'append')
    {
        $node = $this->_dom->importNode($newNode, true);
        if ($refNode !== NULL) {
            $_node = $refNode;
        } else {
            $_node = $this->_node;
        }
        if ($mode == 'append') {
            $_node->appendChild($node);
        } else if ($mode == 'before') {
            $_node->parentNode->insertBefore($node, $_node);
        } else if ($mode == 'after') {
            if ($refNode->nextSibling) {
                $_node->parentNode->insertBefore($node, $_node->nextSibling);
            } else {
                $_node->appendChild($node);
            }
        } else if ($mode == 'first') {
            if($_node->hasChildNodes() === true) {
                $firstNode = $_node->firstChild;
                $_node->insertBefore($node, $firstNode);
            } else {
                $_node->appendChild($node);
            }
        }
    }
    
    /**
     * adds a list to the document
     * 
     * @param string $listId
     * @param array $options
     * @param bool $reference
     * @return ODFElement
     * @access private
     */
    private function _list($listId, $options, $reference = true) 
    {
        //if enforce is not set we assume that we should
        //get the first element ancestor that accepts the list element
        if (!isset($options['enforce'])) {
            $options['enforce'] = true;
        }
        //we organize all the required info regarding depth and styling
        $parentList = $this->parent('list');
        $xmlId = $parentList->getNode()->getAttribute('xml:id');
        if (!empty($xmlId) && \strpos($xmlId, 'list_') !== false) {
           $listData = explode('_', $xmlId);
           $listId = $listData[0] . '_' . $listData[1];
           $level = (integer) $listData[2] + 1;
        } else {
            $level = 0;
        }
        Docxpresso\CreateDocument::$list[$listId][$level] = $options;

        if ($options['enforce']
            && !isset(ListElement::$parentNodes[$this->_nodeType]) ) {
            $ancestor = $this->getAncestor($this,
                                           ListElement::$parentNodes);
            $list = new ListElement($ancestor, $listId, $level);
        } else {
            $list = new ListElement($this, $listId, $level);
        }

        if ($reference) {
            return $list;
        } else {
            return $this;
        }
    }
    
    /**
     * Inserts a note (footnot or endnote) into a paragraph
     * 
     * @param string $type it can be endnote or footnote
     * @param array $options 
     * @param bool $reference
     * @return ODFElement
     * @access private
     */
    private function _note($type, $options = array(), $reference = true) 
    {
        //force some stylyng for proper RTF rendering
        $defaultStyles = 'margin-left: 0.3cm; text-indent: -0.3cm;';
        if (isset($options['style'])) {
            if (\is_string($options['style'])) {
                $options['style'] = $defaultStyles . $options['style'];
            }
        } else {
            $options['style'] = $defaultStyles;
        }
        //if enforce is not set we assume that we should
        //get the first element ancestor that accepts the note element
        if (!isset($options['enforce'])) {
            $options['enforce'] = true;
        }
        if (isset($options['styleId'])) {
            $this->_styleId = $options['styleId'];
        } else {
            $this->_styleId = commons\Utilities::generateId();
        }
        $target = $this->getTarget();
        $this->_styleProcessing('text:p', 
                                Note::getDefaultStyles(0),
                                $options,
                                $this->_styleId,
                                $target);
        if ($options['enforce']
            && !isset(Note::$parentNodes[$this->_nodeType]) ) {
            $ancestor = $this->getAncestor($this,
                                           Note::$parentNodes);
            $note = new Note($ancestor, $type, $options);
        } else {
            $note = new Note($this, $type, $options);
        }
        if (isset($options['note']) && \is_string($options['note'])) {
            $options['text'] = $options['note'];
            $note->text($options, false);
        } else if (isset($options['note']) 
                   && ($options['note'] instanceof DocumentFragment)) {
            $note->insertDocumentFragment($options['note']);
        }
        return $note;
    }
    
    /**
     * adds a paragraph/heading to the document
     * 
     * @param array $options
     * @param bool $reference
     * @return ODFElement
     * @access private
     */
    private function _paragraph($options = array()) 
    {
        //First check for page  breaks
        if (Docxpresso\CreateDocument::$break == 'page'
            || Docxpresso\CreateDocument::$break == 'column') {
            if(isset($options['style'])){
                $options['style'] = $this->_break($options['style']);
            } else {
                $options['style'] = $this->_break();
            }
            //reset the static variable to none
            Docxpresso\CreateDocument::$break = 'none';
        }
        //if enforce is not set we assume that we should
        //get the first element ancestor that accepts the paragraph element
        if (!isset($options['enforce'])) {
            $options['enforce'] = true;
        }
        //check if we are dealing with a heading
        if (isset($options['level']) && $options['type'] == 'h') {
            $data = $options['level'];
        } else {
            $data = 0;
        }

        if (isset($options['styleId'])) {
            $this->_styleId = $options['styleId'];
        } else {
            $this->_styleId = commons\Utilities::generateId();
        }
        $target = $this->getTarget();
        if ($options['type'] == 'h') {
            $this->_styleProcessing('text:h', 
                                    Paragraph::getDefaultStyles($data),
                                    $options,
                                    $this->_styleId,
                                    $target);
        } else {
            $this->_styleProcessing('text:p', 
                                    Paragraph::getDefaultStyles($data),
                                    $options,
                                    $this->_styleId,
                                    $target);
        }
        if ($options['enforce']
            && !isset(Paragraph::$parentNodes[$this->_nodeType]) ) {
            $ancestor = $this->getAncestor($this,
                                           Paragraph::$parentNodes);
            //update the _styleId private property so we keep the right
            //inheritance
            $ancestor->setStyleId($this->_styleId);
            $paragraph = new Paragraph($ancestor, $options);
        } else {
            $paragraph = new Paragraph($this, $options);
        }
        if (isset($options['text']) 
            && (\is_string($options['text']) || \is_numeric($options['text']))){
            //the text properties are already inherited via the paragraph style
            //so we do not need to explicitely set them in the generated span
            unset($options['style']);
            //indicate that the text method has been automatically called
            $options['auto'] = true;
            $paragraph->text($options, false);
        } else if (isset($options['text']) 
                   && ($options['text'] instanceof DocumentFragment)) {
            $paragraph->insertDocumentFragment($options['text']);
        }
        return $paragraph;
    }
    
    /**
     * Gets the style attribute for different types of nodes
     * 
     * @param DOMNode $node
     * @return string
     * @access private
     */
    private function _styleAttribute($node) 
    { 
        $name = $node->nodeName;
        $ns = explode(':', $name);
        if ($node->nodeName != '#text') {
            $style = $node->getAttribute($ns[0] . ':style-name');
        }
        if (!empty($style)) {
            return $style;
        }
    }
    
    /**
     * process a style to be included in the styles array
     * 
     * @param string $type
     * @param string $defaultStyle
     * @param array $options
     * @param string $styleId
     * @param string $target 
     * @return void
     * @access protected
     */
    protected function _styleProcessing($type, 
                                        $defaultStyle, 
                                        $options,  
                                        $styleId,
                                        $target = 'document') 
    { 
        if (isset($options['style']) && is_string($options['style'])) {
            if (empty(Docxpresso\CreateDocument::$theme)) {
                $options['style'] = $defaultStyle . $options['style'];
            } else {
                $options['style'] = $this->_themeStyles($type,
                                                        $defaultStyle,
                                                        $options);
            }
        } else if (isset($options['style']) && is_array($options['style'])) {
            $props = $options['style'];
        } else {
            if (!empty(Docxpresso\CreateDocument::$theme)) {
                $options['style'] = '';
                $options['style'] = $this->_themeStyles($type,
                                                        $defaultStyle,
                                                        $options);
            } else {
                $options['style'] = $defaultStyle;
            }
        }
        $props = $this->_addStyle($type, $options['style'], $styleId, $target);
        return $props;
    }
    
    /**
     * process a style to be includeed in the styles array
     * 
     * @param string $type
     * @param string $defaultStyle
     * @param array $options
     * @return string
     * @access protected
     */
    protected function _themeStyles($type, $defaultStyle, $options) 
    { 
        $themeStyle = $defaultStyle;
        $theme =& themes\ThemeManager::$CSSProps;
        if ($type == 'text:p') {
            if (isset($theme['p'])) {
                $themeStyle .= $theme['p'];
            }
            if (isset(themes\ThemeManager::$CSSClasses[$options['style']])){
                $themeStyle .= $theme['p.' . $options['style']];
            } else {
                $themeStyle .= $options['style'];
            }
        } else if ($type == 'text:span') {
            if (isset($theme['span'])) {
                $themeStyle .= $theme['span'];
            }
            if (isset(themes\ThemeManager::$CSSClasses[$options['style']])){
                $themeStyle .= $theme['span.' . $options['style']];
            } else {
                $themeStyle .= $options['style'];
            }
        } else if ($type == 'text:a') {
            if (isset($theme['a'])) {
                $themeStyle .= $theme['a'];
            }
            if (isset(themes\ThemeManager::$CSSClasses[$options['style']])){
                $themeStyle .= $theme['a.' . $options['style']];
            } else {
                $themeStyle .= $options['style'];
            }
        } else if ($type == 'text:h') {
            if (isset($options['level']) && \is_numeric($options['level'])) {
                $level = $options['level'];
            } else {
                $level = 1;
            }
            if (isset($theme['h' . $level])) {
                $themeStyle .= $theme['h' . $level];
            }
            if (isset(themes\ThemeManager::$CSSClasses[$options['style']])){
                $themeStyle .= $theme['h' . $level . '.' . $options['style']];
            } else {
                $themeStyle .= $options['style'];
            }
        } else if ($type == 'table:table') {
            if (isset($theme['table'])) {
                $themeStyle .= $theme['table'];
            }
            if (isset(themes\ThemeManager::$CSSClasses[$options['style']])){
                $themeStyle .= $theme['table.' . $options['style']];
            } else {
                $themeStyle .= $options['style'];
            }
        } 
        //return the style
        return $themeStyle;
    }   
}