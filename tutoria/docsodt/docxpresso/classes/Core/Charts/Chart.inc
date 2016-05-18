<?php

/**
 * ODF Charts
 *
 * @copyright  Copyright(c)2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Core\Charts;

use Docxpresso\Core\Elements as elements;
use Docxpresso\Parser as parser;
use Docxpresso\Commons as commons;

/**
 * This class creates a ODF chart
 *
 * @package    Docxpresso
 * @subpackage Charts
 */

class Chart
{
    /**
     * available chart types
     * 
     * @var array
     * @access public
     * @static
     */
    public static $chartTypes = array(
        'area'          => 'chart:area',
        'bar'           => 'chart:bar',
        'bubble'        => 'chart:bubble',
        'column'        => 'chart:bar',
        'column-line'   => 'chart:bar',
        'donut'         => 'chart:ring',
        'filled-radar'  => 'chart:filled-radar',
        'line'          => 'chart:line',
        'pie'           => 'chart:circle',
        'radar'         => 'chart:radar',
        'scatter'       => 'chart:scatter',
        'stock'         => 'chart:stock',
        'surface'       => 'chart:surface',
    );
    
    /**
     * default chart properties
     * @var array
     * @access public
     * @static
     */
    public static $defaultChartProperties = array(
        'chart:angle-offset' => false, 
        'chart:auto-position' => false, 
        'chart:auto-size' => false, 
        'chart:axis-label-position' => false, 
        'chart:axis-position' => false, 
        'chart:connect-bars' => false, 
        'chart:data-label-number' => false, 
        'chart:data-label-symbol' => false, 
        'chart:data-label-text' => false, 
        'chart:deep' => false, 
        'chart:display-label' => false, 
        'chart:error-category' => false, 
        'chart:error-lower-indicator' => false, 
        'chart:error-lower-limit' => false, 
        'chart:error-lower-range' => false, 
        'chart:error-margin' => false, 
        'chart:error-percentage' => false, 
        'chart:error-upper-indicator' => false, 
        'chart:error-upper-limit' => false, 
        'chart:error-upper-range' => false, 
        'chart:gap-width' => false, 
        'chart:group-bars-per-axis' => false, 
        'chart:hole-size' => false, 
        'chart:include-hidden-cells' => false, 
        'chart:interpolation' => false, 
        'chart:interval-major' => false, 
        'chart:interval-minor-divisor' => false, 
        'chart:japanese-candle-stick' => false, 
        'chart:label-arrangement' => false, 
        'chart:label-position' => false, 
        'chart:label-position-negative' => false, 
        'chart:lines' => false, 
        'chart:link-data-style-to-source' => false, 
        'chart:logarithmic' => false, 
        'chart:maximum' => false, 
        'chart:mean-value' => false, 
        'chart:minimum' => false, 
        'chart:origin' => false, 
        'chart:overlap' => false, 
        'chart:percentage' => false, 
        'chart:pie-offset' => false, 
        'chart:regression-type' => false, 
        'chart:reverse-direction' => false, 
        'chart:right-angled-axes' => false, 
        'chart:scale-text' => false, 
        'chart:series-source' => false, 
        'chart:solid-type' => false, 
        'chart:sort-by-x-values' => false, 
        'chart:spline-order' => false, 
        'chart:spline-resolution' => false, 
        'chart:stacked' => false, 
        'chart:symbol-height' => false, 
        'chart:symbol-name' => false, 
        'chart:symbol-type' => false, 
        'chart:symbol-width' => false, 
        'chart:text-overlap' => false, 
        'chart:three-dimensional' => false, 
        'chart:tick-mark-position' => false, 
        'chart:tick-marks-major-inner' => false, 
        'chart:tick-marks-major-outer' => false, 
        'chart:tick-marks-minor-inner' => false, 
        'chart:tick-marks-minor-outer' => false, 
        'chart:treat-empty-cells' => false, 
        'chart:vertical' => false, 
        'chart:visible' => false, 
        'style:direction' => false, 
        'style:rotation-angle' => false,
        'text:line-break' => false,
    );
    
    /**
     * graphic properties
     * 
     * @access public
     * @static
     * @var array
     */
    public static $graphicPropsNS = array(
        'ambient-color' => 'dr3d',
        'backface-culling' => 'dr3d',
        'back-scale' => 'dr3d',
        'close-back' => 'dr3d',
        'close-front' => 'dr3d',
        'depth' => 'dr3d',
        'diffuse-color' => 'dr3d',
        'edge-rounding' => 'dr3d',
        'edge-rounding-mode' => 'dr3d',
        'emissive-color' => 'dr3d',
        'end-angle' => 'dr3d',
        'horizontal-segments' => 'dr3d',
        'lighting-mode' => 'dr3d',
        'normals-direction' => 'dr3d',
        'normals-kind' => 'dr3d',
        'shadow' => 'dr3d',
        'shininess' => 'dr3d',
        'specular-color' => 'dr3d',
        'texture-filter' => 'dr3d',
        'texture-generation-mode-x' => 'dr3d',
        'texture-generation-mode-y' => 'dr3d',
        'texture-kind' => 'dr3d',
        'texture-mode' => 'dr3d',
        'vertical-segments' => 'dr3d',
        'auto-grow-height' => 'draw',
        'auto-grow-width' => 'draw',
        'blue' => 'draw',
        'caption-angle' => 'draw',
        'caption-angle-type' => 'draw',
        'caption-escape' => 'draw',
        'caption-escape-direction' => 'draw',
        'caption-fit-line-length' => 'draw',
        'caption-gap' => 'draw',
        'caption-line-length' => 'draw',
        'caption-type' => 'draw',
        'color-inversion' => 'draw',
        'color-mode' => 'draw',
        'contrast' => 'draw',
        'decimal-places' => 'draw',
        'draw-aspect' => 'draw',
        'end-guide' => 'draw',
        'end-line-spacing-horizontal' => 'draw',
        'end-line-spacing-vertical' => 'draw',
        'fill' => 'draw',
        'fill-color' => 'draw',
        'fill-gradient-name' => 'draw',
        'fill-hatch-name' => 'draw',
        'fill-hatch-solid' => 'draw',
        'fill-image-height' => 'draw',
        'fill-image-name' => 'draw',
        'fill-image-ref-point' => 'draw',
        'fill-image-ref-point-x' => 'draw',
        'fill-image-ref-point-y' => 'draw',
        'fill-image-width' => 'draw',
        'fit-to-contour' => 'draw',
        'fit-to-size' => 'draw',
        'frame-display-border' => 'draw',
        'frame-display-scrollbar' => 'draw',
        'frame-margin-horizontal' => 'draw',
        'frame-margin-vertical' => 'draw',
        'gamma' => 'draw',
        'gradient-step-count' => 'draw',
        'green' => 'draw',
        'guide-distance' => 'draw',
        'guide-overhang' => 'draw',
        'image-opacity' => 'draw',
        'line-distance' => 'draw',
        'luminance' => 'draw',
        'marker-end' => 'draw',
        'marker-end-center' => 'draw',
        'marker-end-width' => 'draw',
        'marker-start' => 'draw',
        'marker-start-center' => 'draw',
        'marker-start-width' => 'draw',
        'measure-align' => 'draw',
        'measure-vertical-align' => 'draw',
        'ole-draw-aspect' => 'draw',
        'opacity' => 'draw',
        'opacity-name' => 'draw',
        'parallel' => 'draw',
        'placing' => 'draw',
        'red' => 'draw',
        'secondary-fill-color' => 'draw',
        'shadow' => 'draw',
        'shadow-color' => 'draw',
        'shadow-offset-x' => 'draw',
        'shadow-offset-y' => 'draw',
        'shadow-opacity' => 'draw',
        'show-unit' => 'draw',
        'start-guide' => 'draw',
        'start-line-spacing-horizontal' => 'draw',
        'start-line-spacing-vertical' => 'draw',
        'stroke' => 'draw',
        'stroke-dash' => 'draw',
        'stroke-dash-names' => 'draw',
        'stroke-linejoin' => 'draw',
        'symbol-color' => 'draw',
        'textarea-horizontal-align' => 'draw',
        'textarea-vertical-align' => 'draw',
        'tile-repeat-offset' => 'draw',
        'unit' => 'draw',
        'visible-area-height' => 'draw',
        'visible-area-left' => 'draw',
        'visible-area-top' => 'draw',
        'visible-area-width' => 'draw',
        'wrap-influence-on-position' => 'draw',
        'background-color' => 'fo',
        'border' => 'fo',
        'border-bottom' => 'fo',
        'border-left' => 'fo',
        'border-right' => 'fo',
        'border-top' => 'fo',
        'clip' => 'fo',
        'margin' => 'fo',
        'margin-bottom' => 'fo',
        'margin-left' => 'fo',
        'margin-right' => 'fo',
        'margin-top' => 'fo',
        'max-height' => 'fo',
        'max-width' => 'fo',
        'min-height' => 'fo',
        'min-width' => 'fo',
        'padding' => 'fo',
        'padding-bottom' => 'fo',
        'padding-left' => 'fo',
        'padding-right' => 'fo',
        'padding-top' => 'fo',
        'wrap-option' => 'fo',
        'background-transparency' => 'style',
        'border-line-width' => 'style',
        'border-line-width-bottom' => 'style',
        'border-line-width-left' => 'style',
        'border-line-width-right' => 'style',
        'border-line-width-top' => 'style',
        'editable' => 'style',
        'flow-with-text' => 'style',
        'horizontal-pos' => 'style',
        'horizontal-rel' => 'style',
        'mirror' => 'style',
        'number-wrapped-paragraphs' => 'style',
        'overflow-behavior' => 'style',
        'print-content' => 'style',
        'protect' => 'style',
        'rel-height' => 'style',
        'rel-width' => 'style',
        'repeat' => 'style',
        'run-through' => 'style',
        'shadow' => 'style',
        'shrink-to-fit' => 'style',
        'vertical-pos' => 'style',
        'vertical-rel' => 'style',
        'wrap' => 'style',
        'wrap-contour' => 'style',
        'wrap-contour-mode' => 'style',
        'wrap-dynamic-threshold' => 'style',
        'writing-mode' => 'style',
        'fill-rule' => 'svg',
        'height' => 'svg',
        'stroke-color' => 'svg',
        'stroke-linecap' => 'svg',
        'stroke-opacity' => 'svg',
        'stroke-width' => 'svg',
        'width' => 'svg',
        'x' => 'svg',
        'y' => 'svg',
        'anchor-page-number' => 'text',
        'anchor-type' => 'text',
        'animation' => 'text',
        'animation-delay' => 'text',
        'animation-direction' => 'text',
        'animation-repeat' => 'text',
        'animation-start-inside' => 'text',
        'animation-steps' => 'text',
        'animation-stop-inside' => 'text',
    );
    
    /**
     * available symbol types
     * 
     * @var array
     * @access public
     * @static
     */
    public static $symbols = array(
        'square' => true, 
        'diamond' => true, 
        'arrow-down' => true, 
        'arrow-up' => true, 
        'arrow-right' => true, 
        'arrow-left' => true, 
        'bow-tie' => true, 
        'hourglass' => true, 
        'circle' => true, 
        'star' => true, 
        'x' => true, 
        'plus' => true, 
        'asterisk' => true, 
        'horizontal-bar' => true,
        'vertical-bar' => true,
    );
    
    /**
     * text properties
     * 
     * @access public
     * @static
     * @var array
     */
    public static $textPropsNS = array(
        'color'         => 'fo',
        'font-family'   => 'fo',
        'font-size'     => 'fo',
        'font-weight'   => 'fo',
        'font-style'    => 'fo',
        );
    
    /**
     * This is needed for chart rendering in Word
     * 
     * @var string
     * @access public
     * @static
     */
    public static $styleXML = '<office:document-styles 
	xmlns:chart="urn:oasis:names:tc:opendocument:xmlns:chart:1.0" 
	xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" 
	xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" 
	xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0" 
	xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" 
	xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0" 
	xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0" 
	xmlns:number="urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0" 
	xmlns:svg="urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0" 
	xmlns:dr3d="urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0">
        <office:styles /> 
        </office:document-styles>';
    
    /**
     * camera matrix data
     * 
     * @var array
     * @access private
     */
    private $_cameraMatrix;
    
    /**
     * chart
     * @var DOMDocument
     * @access private
     */
    private $_chart;
    
    /**
     * chart
     * @var string
     * @access private
     */
    private $_chartType;
    
    /**
     * class
     * @var string
     * @access private
     */
    private $_class;
    
    /**
     * color scheme
     * @var array
     * @access private
     */
    private $_colorScheme;
    
    /**
     * custom properties
     * @var array
     * @access private
     */
    private $_customChartProperties;
    
    /**
     * distance
     * @var string
     * @access private
     */
    private $_distance;
    
    /**
     * chart DOM document
     * @var DOMDocument
     * @access private
     */
    private $_dom;
    
    /**
     * focal length
     * @var string
     * @access private
     */
    private $_focalLength;
    
    /**
     * root element
     * @var DOMNode
     * @access private
     */
    private $_rootChartElement;
    
    /**
     * style id
     * @var string
     * @access private
     */
    private $_styleId;
    
    /**
     * styles
     * @var DOMNode
     * @access private
     */
    private $_styles;
    
    /**
     * 3D
     * @var bool
     * @access private
     */
    private $_threeD;
    
    /**
     * VPN
     * @var array
     * @access private
     */
    private $_vpn;
    
    /**
     * VRP
     * @var array
     * @access private
     */
    private $_vrp;
    
    /**
     * VUP
     * @var array
     * @access private
     */
    private $_vup;
    
    /**
     * Chart XPath
     * @var DOMXPath
     * @access private
     */
    private $_xpath;
    
    
    /**
     * Construct
     * 
     * @param array $options
     * @access public
     */
    public function __construct($options)
    {
        $this->_styleId = $options['styleId'];
        $this->_customChartProperties = array();   
        $this->_customChartProperties['chart:right-angled-axes'] = 'true';
        $this->_customChartProperties['chart:auto-size'] = 'true';
        $this->_customChartProperties['chart:auto-position'] = 'true';
        //detect the type
        if ($options['type'][0] == '3') {
            $this->_threeD = true;
            $this->_chartType = \strtolower(\substr($options['type'], 2));
            $this->_customChartProperties['chart:three-dimensional'] = 'true';
        } else {
            $this->_threeD = false;
            $this->_chartType = \strtolower($options['type']);
        }
        if (isset(self::$chartTypes[$this->_chartType])) {
            $this->_class = self::$chartTypes[$this->_chartType];
        } else {
            $message = 'The requested chart type: ' .  $options['type'];
            $message.= ' is not supported';
            commons\DOCXPRESSOLogger::logger($message, 'critical');
        }
        if ($this->_chartType == 'bar') {
            $this->_customChartProperties['chart:vertical'] = 'true';
        }
        //set some default styling properties dependent of the chart type
        $this->_setDefaultChartProps($this->_chartType, $options);
        $this->_baseXML = '<office:document-content 
	xmlns:chart="urn:oasis:names:tc:opendocument:xmlns:chart:1.0" 
	xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" 
	xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" 
	xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0" 
	xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" 
	xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0" 
	xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0" 
	xmlns:number="urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0" 
	xmlns:svg="urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0" 
	xmlns:dr3d="urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0">
            <office:automatic-styles>	
                <number:number-style style:name="N0">
                    <number:number number:min-integer-digits="1" /> 
                </number:number-style>
                <style:style style:name="chartWrapper" 
                             style:family="chart">
                    <style:chart-properties /> 
                    <style:graphic-properties draw:fill="none" 
                                              draw:stroke="none" /> 

                </style:style>
                <style:style style:name="plotArea" 
                             style:family="chart">
                    <style:chart-properties /> 
                    <style:graphic-properties draw:fill="none" 
                                              draw:stroke="none" /> 
                </style:style>
                <style:style style:name="axisX" 
                             style:family="chart" 
                             style:data-style-name="N0">
                    <style:chart-properties chart:display-label="true" 
                                            chart:tick-marks-major-inner="false" 
                                            chart:tick-marks-major-outer="false" 
                                            chart:logarithmic="false" 
                                            chart:reverse-direction="false" 
                                            text:line-break="false" 
                                            chart:link-data-style-to-source="false" 
                                            chart:axis-position="0" 
                                            chart:axis-label-position="outside-start" 
                                            chart:tick-mark-position="at-labels"
                                            chart:gap-width="200" 
                                            chart:overlap="-25" /> 
                    <style:graphic-properties /> 
                    <style:text-properties fo:color="#595959" 
                                           style:text-position="0% 100%" 
                                           fo:font-size="9pt" /> 
                </style:style>
                <style:style style:name="axisY" 
                             style:family="chart" 
                             style:data-style-name="N0">
                    <style:chart-properties chart:display-label="true" 
                                            chart:tick-marks-major-inner="false" 
                                            chart:tick-marks-major-outer="false" 
                                            chart:logarithmic="false" 
                                            chart:reverse-direction="false" 
                                            text:line-break="false" 
                                            chart:link-data-style-to-source="false" 
                                            chart:axis-position="0" 
                                            chart:axis-label-position="outside-start" 
                                            chart:tick-mark-position="at-labels"
                                            chart:gap-width="200" 
                                            chart:overlap="-25" /> 
                    <style:graphic-properties /> 
                    <style:text-properties fo:color="#595959" 
                                           style:text-position="0% 100%" 
                                           fo:font-size="9pt" /> 
                </style:style>
                <style:style style:name="gridY" style:family="chart">
                    <style:graphic-properties svg:stroke-width="0.75pt" 
                                              svg:stroke-color="#d9d9d9" /> 
                    </style:style>
                <style:style style:name="axisZ" 
                             style:family="chart" 
                             style:data-style-name="N0">
                    <style:chart-properties chart:display-label="true" 
                                            chart:tick-marks-major-inner="false" 
                                            chart:tick-marks-major-outer="false" 
                                            chart:logarithmic="false" 
                                            chart:reverse-direction="false" 
                                            text:line-break="false" 
                                            chart:link-data-style-to-source="false" 
                                            chart:axis-position="0" 
                                            chart:axis-label-position="outside-start" 
                                            chart:tick-mark-position="at-labels" /> 
                    <style:graphic-properties /> 
                    <style:text-properties fo:color="#595959" 
                                           style:text-position="0% 100%" 
                                           fo:font-size="9pt" /> 
                </style:style>
                <style:style style:name="chartWall" style:family="chart">
                    <style:graphic-properties draw:fill="none" 
                                              draw:stroke="none" />   
                </style:style>
                <style:style style:name="chartFloor" style:family="chart">
                    <style:graphic-properties draw:fill="none" 
                                              draw:stroke="none" />  
                </style:style>
            </office:automatic-styles>
            <office:body>
                <office:chart>
                    <chart:chart chart:style-name= "chartWrapper">
                        <chart:plot-area chart:style-name= "plotArea">
                            <chart:axis chart:dimension="x"
                                        chart:style-name= "axisX">
                                <chart:categories /> 
                            </chart:axis>
                            <chart:axis chart:dimension="y"
                                        chart:style-name= "axisY">
                                <chart:grid chart:style-name="gridY" 
                                            chart:class="major" />
                            </chart:axis>';
        if($this->_threeD) {
            //Word fails if we include a z-axis in 2D charts
            $this->_baseXML .=  '                  
                            <chart:axis chart:dimension="z"
                                        chart:style-name= "axisZ"/>';
        }
        $this->_baseXML .=  '                             
                            <chart:wall chart:style-name="chartWall" /> 
                            <chart:floor chart:style-name="chartFloor" />
                        </chart:plot-area>
                        <table:table table:name="local-table"/>
                    </chart:chart>
                </office:chart>
            </office:body>
        </office:document-content>';
        //general chart custom properties
        if (isset($options['chart-properties'])) {
            foreach ($options['chart-properties'] as $key => $value) {
                if (isset(self::$defaultChartProperties['chart:' . $key])) {
                    if(\is_bool($value)){
                        $value = \var_export($value, true);
                    }
                    $this->_customChartProperties['chart:' . $key] = $value;
                }
            }
        }
        //load the XML into the DOM
        $this->_dom = new \DOMDocument();
        $this->_dom->loadXML($this->_baseXML);
        $this->_dom->formatOutput = true;
        $this->_xpath = new \DOMXPath($this->_dom);
        $this->_xpath->registerNamespace('style', 
                'urn:oasis:names:tc:opendocument:xmlns:style:1.0');
        $this->_xpath->registerNamespace('chart', 
                'urn:oasis:names:tc:opendocument:xmlns:chart:1.0');
        //extract the style node
        $this->_styles = $this->_dom
                             ->getElementsByTagName('automatic-styles')
                             ->item(0);
        
        if ($this->_threeD) {
            //define the VUP (View Up) vector
            $this->_vup = array(-0.0733876362771618,
                                 0.984807599917971,
                                -0.157379306090273);
            $vup = '(' . implode(' ', $this->_vup) . ')';
            //define the VPN (View Plane Normal) vector
            $this->_vpn = array(0.416199821709347,
                                0.173649045905254,
                                0.892537795986984);
            $vpn = '(' . implode(' ', $this->_vpn) . ')';
            //define the base VRP (View Reference Point) vector
            $this->_vrp = array(4138, 2410, 5771);
            $vrp = '(' . implode(' ', $this->_vrp) . ')';
            //the VUP and the VPN vectors are use to build the camera matrix 
            //that is defined as an array(VUP X VPN, VUP, VPN) where the 
            //X stands for vector product
            $this->_cameraMatrix[0] = array();
            $this->_cameraMatrix[0][0] = $this->_vup[1] * $this->_vpn[2] -
                                         $this->_vup[2] * $this->_vpn[1];   
            $this->_cameraMatrix[0][1] = $this->_vup[2] * $this->_vpn[0] -
                                         $this->_vup[0] * $this->_vpn[2];
            $this->_cameraMatrix[0][2] = $this->_vup[0] * $this->_vpn[1] -
                                         $this->_vup[1] * $this->_vpn[0]; 
            //$this->_cameraMatrix[0] = 
            //array(0.90630677104982, 0, -0.42262044052464);
            $this->_cameraMatrix[1] = $this->_vup;
            $this->_cameraMatrix[2] = $this->_vpn;
            //define the focal length
            $this->_focalLength = '8cm';
            //and the distance
            $this->_distance = '4.2cm';
            if ($this->_chartType == 'pie' || $this->_chartType == 'donut') {
                $this->_transform = $this->_generateTransformationMatrix(-60, 0, 0);
            } else {
                $this->_transform = $this->_generateTransformationMatrix(11, 25, 5);
            }
            //search for the plot-area node
            $plotArea = $this->_dom->getElementsByTagName('plot-area')->item(0);
            $ns = 'urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0';
            $plotArea->setAttributeNS($ns, 'dr3d:vup', $vup);
            $plotArea->setAttributeNS($ns, 'dr3d:vrp', $vrp);
            $plotArea->setAttributeNS($ns, 'dr3d:vpn', $vpn);
            $plotArea->setAttributeNS($ns, 'dr3d:transform', $this->_transform);
            $plotArea->setAttributeNS($ns, 'dr3d:focal-length', $this->_focalLength);
            $plotArea->setAttributeNS($ns, 'dr3d:distance', $this->_distance);
            $plotArea->setAttributeNS($ns, 'dr3d:projection', 'parallel');
            $plotArea->setAttributeNS($ns, 'dr3d:shadow-slant', 0);
            $plotArea->setAttributeNS($ns, 'dr3d:shade-mode', 'flat');
            $plotArea->setAttributeNS($ns, 'dr3d:lighting-mode', 'true');
            $plotArea->setAttributeNS($ns, 'dr3d:ambient-color', '#dddddd');
            /*
             * <chart:plot-area chart:style-name="ch3" chart:data-source-has-labels="both" svg:x="0.319cm" 
             * svg:y="0.672cm" svg:width="13.652cm" svg:height="8.148cm" 
             * dr3d:transform="matrix (1 0 0 0 0.5 -0.866025403784439 0 0.866025403784439 0.5 0cm 0cm 0cm)" 
             * dr3d:vrp="(0 0 87591.2408759124)" 
             * dr3d:projection="parallel" dr3d:distance="4.2cm" 
             * dr3d:focal-length="8cm" dr3d:shadow-slant="0" 
             * dr3d:shade-mode="flat" 
             * dr3d:ambient-color="#cccccc" 
             * dr3d:lighting-mode="true">

             */
        }
        //modify the plotArea style element
        $query = '//style:style[@style:name="plotArea"]/style:chart-properties';
        $style_plotArea = $this->_xpath->query($query)->item(0);
        $ns = 'urn:oasis:names:tc:opendocument:xmlns:chart:1.0';
        foreach ($this->_customChartProperties as $key => $value) {
            $style_plotArea->setAttributeNS($ns, $key, $value);
        }
        
        //set default color scheme
        if (($this->_chartType == 'line' 
            || $this->_chartType == 'radar'
            || $this->_chartType == 'scatter')
            && !$this->_threeD) {;
            $this->_colorScheme = ColorSchemes::$defaultLine;
        } else if ($this->_chartType == 'column-line') {
            $this->_colorScheme = ColorSchemes::$defaultColumnLine;
        } else if ($this->_chartType == 'line' && $this->_threeD) {
            $this->_colorScheme = ColorSchemes::$defaultSolidLine;
        } else if ($this->_chartType == 'pie'
                   || $this->_chartType == 'donut') {
            $this->_colorScheme = ColorSchemes::$defaultPie;
        } else if ($this->_chartType == 'bar'
              || $this->_chartType == 'column') {
            $this->_colorScheme = ColorSchemes::$defaultBar;
        } else {
            $this->_colorScheme = ColorSchemes::$defaultBar;
        }
    }
    
    /**
     * magic method to string
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->_dom->saveXML();
    }
      
    /*Getters and Setters*/
    /**
     * gets the chart type
     * 
     * @access public
     * @return string
     */
    public function getChartType()
    {
        return $this->_chartType;
    }
    
    /**
     * sets the chart type
     * 
     * @param string $type
     * @access public
     * @return void
     */
    public function setChartType($type)
    {
       $this->_chartType = $type;
    }
    
    /**
     * gets the style id
     * 
     * @access public
     * @return string
     */
    public function getStyleId()
    {
        return $this->_styleId;
    }
    
    /**
     * sets the style id
     * 
     * @param string $id
     * @access public
     * @return void
     */
    public function setStyleId($id)
    {
       $this->_styleId = $id;
    }
    
    /**
     * Sets the colorScheme for the chart
     *
     * @param mixed $colorScheme it can be the name of one of the predefined
     * color schemes: default, classic, blue, red, green or an array of arrays
     * with the following keys and values:
     *  'fill-color' (string) an hexadecimal color #ff0000, #56a845, ...
     *  'opacity' (string) given as a percentage 100%, 80%, 50%, ...
     *  'stroke' (string) none, dash or solid
     *  'stroke-width' (string) given in points, cm, in, ...
     *  'stroke-color' (string) an hexadecimal color #ff0000, #56a845, ...
     * 
     * @return void
     * @access public
     */
    public function setColorScheme($colorScheme) 
    {
        if (\is_array($colorScheme)) {
           $length = \max(array(20, \count($colorScheme)));
           for ($j = 0; $j < $length; $j++) {
              if (!isset($this->_colorScheme[$j])) {
                  $this->_colorScheme[$j] = array();
              }
              if (!isset($colorScheme[$j])) {
                  $colorScheme[$j] = array();
              }
              $this->_colorScheme[$j] = \array_merge($this->_colorScheme[$j],
                                                     $colorScheme[$j]); 
           }
        } else {
           $this->_colorScheme = ColorSchemes::$$colorScheme; 
        }
    }
    
    /**
     * Sets the 3D rotation angles and perspective
     * 
     * @param $options with the following keys and values
     *      'rotate-x' => (int) rotation angle respect the x-axis. The default
     *       value is 11 for all charts but for pie or doonut charts that is -60
     *      'rotate-y' => (int) rotation angle respect the y-axis. The default
     *       value is 25 for all charts but for pie or doonut charts that is 0
     *      'rotate-z' => (int) rotation angle respect the z-axis. The default
     *       value is 5 for all charts but for pie or doonut charts that is 0
     *      'right-angled-axes' => (boolean) default value is true (if true
     *       the rotate-z option is ignored)
     *      'perspective' => (int) given as a percentage (default value 
     *       is 20)
     *                   
     * @return void
     * @access public
     */
    public function transform3D($options) 
    {
        if (!$this->_threeD) {
            $message = 'You can not run a 3D transformation on a 2D chart.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            return;
        }
        //check for default values
        if (isset($options['rotate-x'])) {
            $angleX = $options['rotate-x'];
        } else {
            if ($this->_chartType == 'pie' || $this->_chartType == 'donut') {
                $angleX = -60;
            } else {
                $angleX = 11; 
            }
        }
        if (isset($options['rotate-y'])) {
            $angleY = $options['rotate-y'];
        } else {
            if ($this->_chartType == 'pie' || $this->_chartType == 'donut') {
                $angleY = 0;
            } else {
                $angleY = 25; 
            }
        }
        if (isset($options['rotate-z'])) {
            $angleZ = $options['rotate-z'];
        } else {
            if ($this->_chartType == 'pie' || $this->_chartType == 'donut') {
                $angleZ = 0;
            } else {
                $angleZ = 5; 
            }
        }
        //search for the plot-area node
        $plotArea = $this->_dom->getElementsByTagName('plot-area')->item(0);
        $ns = 'urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0';
        if (isset($options['rotate-x'])
            || isset($options['rotate-y'])
            || isset($options['rotate-z'])) {
            $transform = $this->_generateTransformationMatrix($angleX, 
                                                              $angleY, 
                                                              $angleZ);
            $plotArea->setAttributeNS($ns, 'dr3d:transform', $transform);
        }
        if ( isset($options['perspective'])){
            $vrp = $this->_generateVRP($options['perspective']);
            $plotArea->setAttributeNS($ns, 'dr3d:vrp', $vrp);
        }
        if ( isset($options['right-angled-axes'])){
            $query = '//style:style[@style:name = "plotArea"]/style:chart-properties';
            $style_plotArea = $this->_xpath->query($query)->item(0);
            $ns = 'urn:oasis:names:tc:opendocument:xmlns:chart:1.0';
            if (\is_bool($options['right-angled-axes'])){
                $right_angled = \var_export($options['right-angled-axes'], true);
            } else {
                $right_angled = $options['right-angled-axes'];
            }
            
            $style_plotArea->setAttributeNS($ns, 
                                            'chart:right-angled-axes', 
                                            $right_angled);
        }
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
     *       numeric value dictating where the perpendicular axis shiould cross 
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
     *      'stroke-width' => (string) given in points (default 0.75pt), cm, in.
     *      'stroke-color' => (string) hexadecimal color: #d9d9d9 (default)
     *      'stroke-opacity' => (string) percentage: 100% (default), 80%, ....
     *      'stroke-linejoin' => (string) round (default), bevel, middle, 
     *       miter or none
     *      'stroke-linecap' => (string) butt (default), round or square.
     *      'tick-marks-major-inner' => (boolean) default value is false
     *      'tick-marks-minor-inner' => (boolean) default value is false
     *      'tick-marks-major-outer' => (boolean) default value is false
     *      'tick-marks-minor-outer' => (boolean) default value is false      
     *              
     * @return void
     * @access public
     */
    public function axis($axis, $options = array()) 
    {
        //normalize some properties
        if (isset($options['font-color'])) {
            $options['color'] = $options['font-color'];
            unset($options['font-color']);
        }
        //set some default styling properties dependent of the chart type
        $this->_setDefaultAxisProps($axis, $this->_chartType, $options);
        $query = '//style:style[@style:name = "axis' . \strtoupper($axis). '"]';
        $styles = $this->_xpath->query($query);
        if ($styles->length > 0) {
            $chartProps = $styles->item(0)
                                 ->getElementsByTagName('chart-properties')
                                 ->item(0);
            foreach($options as $key => $value){
                if(\is_bool($value)){
                    $value = \var_export($value, true);
                }
                if (isset(self::$defaultChartProperties['chart:' . $key])) {
                    $chartProps->setAttribute('chart:' . $key, $value);
                }
            }
            $graphProps = $styles->item(0)
                                 ->getElementsByTagName('graphic-properties')
                                 ->item(0);
            foreach($options as $key => $value){
                if(\is_bool($value)){
                    $value = \var_export($value, true);
                }
                if (isset(self::$graphicPropsNS[$key])) {
                    $attr = self::$graphicPropsNS[$key] . ':' . $key;
                    $graphProps->setAttribute($attr, $value);
                }
            }
            $textProps = $styles->item(0)
                                ->getElementsByTagName('text-properties')
                                 ->item(0);
            foreach($options as $key => $value){
                if(\is_bool($value)){
                    $value = \var_export($value, true);
                }
                if (isset(self::$textPropsNS[$key])) {
                    $attr = self::$textPropsNS[$key] . ':' . $key;
                    $textProps->setAttribute($attr, $value);
                }
            }
        } else {
            $message = 'The requested axis: ' . $axis . 'was not found.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
        }
    }
    
    /**
     * sets the styles of the different chart components: wall and floor
     * 
     * @param string $component it can be wall or floor
     * @param $options with the following keys and values
     *      'fill-color' => (string) hexadecimal color: #ffffff (default)
     *      'fill-opacity' => (string) percentage: 100% (default), 80%, ....
     *      'stroke' => (string) solid (default), dash or none.
     *      'stroke-width' => (string) given in points, cm or in.
     *      'stroke-color' => (string) hexadecimal color.
     *      'stroke-opacity' => (string) percentage: 100% (default), 80%, ....
     *      'stroke-linejoin' => (string) round (default), bevel, middle, 
     *       miter or none
     *      'stroke-linecap' => (string) butt (default), round or square.
     *                      
     * @return void
     * @access public
     */
    public function component($component = 'wall', $options = array()) 
    {
        //if there is fill-color set the fill option to solid
        if (isset($options['fill-color'])) {
            $options['fill'] = 'solid';
        }
        if (\strtolower($component) == 'wall') {
            $q = 'chartWall';
        } else if (\strtolower($component) == 'floor') {
            $q = 'chartFloor';
        } else {
            $message = 'The component: ' . $component . 'is not allowed.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            return;
        }
        $query = '//style:style[@style:name = "' . $q. '"]';
        $query .= '/style:graphic-properties';
        $styles = $this->_xpath->query($query);
        if ($styles->length > 0) {
            $node = $styles->item(0);
            foreach($options as $key => $value){
                if(\is_bool($value)){
                    $value = \var_export($value, true);
                }
                $node->setAttribute(self::$graphicPropsNS[$key] . ':' . $key,
                                $value);
            }
        } else {
            $message = 'The component: ' . $component . 'was not found.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
        }
    }
    
    /**
     * sets the styles for the x, y and z chart grids
     * 
     * @param (string) $axis it may be x, y or z 
     * @param (string) $type it may be major or minor
     * @param $options with the following keys and values
     *      'stroke' => (string) solid (default), dash or none.
     *      'stroke-width' => (string) given in points, cm or in. The defaults 
     *       are: 0.75pt for major grids and 0.5pt for minor grids
     *      'stroke-color' => (string) hexadecimal color. For major grids the
     *       default is #d9d9d9 and #f0f0f0 for minor grids
     *      'stroke-opacity' => (string) percentage: 100% (default), 80%, ....
     *      'stroke-linejoin' => (string) round (default), bevel, middle, 
     *       miter or none
     *      'stroke-linecap' => (string) butt (default), round or square.      
     *              
     * @return void
     * @access public
     */
    public function grid($axis = 'x', $type = 'major', $options = array()) 
    {
        //set some default styling properties dependent of the chart type
        $this->_setDefaultGridProps($axis, $type, $this->_chartType, $options);
        $query = '//chart:axis[@chart:dimension= "' . \strtolower($axis) . '"]';
        $query .= '/chart:grid[@chart:class= "' . \strtolower($type) . '"]';
        $grid = $this->_xpath->query($query);
        if ($grid->length > 0) {
            $style = $grid->item(0)->getAttribute('chart:style-name');
            //grab the corresponding style graphic-properties node
            $query = '//style:style[@style:name="' . $style . '"]';
            $query .= '/style:graphic-properties';
            $node = $this->_xpath->query($query)->item(0);
            $this->_grid($node, $options);
        } else {
            //there was no associated grid node so we should create it
            $nodeGrid = $this->_dom->createElement('chart:grid');
            $nodeGrid->setAttribute('chart:class', \strtolower($type));
            $styleName = 'style_' . uniqid('', true);
            $nodeGrid->setAttribute('chart:style-name', $styleName);
            //get the axis node where it should be appended
            $query = '//chart:axis';
            $query .= '[@chart:dimension= "' . \strtolower($axis) . '"]';
            $axis = $this->_xpath->query($query)->item(0);
            $axis->appendChild($nodeGrid);
            //create now the style node
            $style = $this->_dom->createElement('style:style');
            $style->setAttribute('style:name', $styleName);
            $style->setAttribute('style:family', 'chart');
            $node = $this->_dom->createElement('style:graphic-properties');
            $this->_grid($node, $options);
            $style->appendChild($node);
            //append the style node
            $stylesNode = $this->_dom
                           ->getElementsByTagName('automatic-styles')
                           ->item(0);
            $stylesNode->appendChild($style);
        }
    }
    
    /**
     * inserts the chart data
     * 
     * @param mixed $data a XML string or an array
     * @param array $options
     * @return void
     * @access public
     */
    public function data($data, $options = array()) 
    {
        if ($this->_chartType == 'pie'
            || $this->_chartType == 'donut') {
            $this->_dataSingleSeries($data, $options) ;
        } else if ($this->_chartType == 'bubble'
                   || $this->_chartType == 'scatter') {
            $this->_dataBubbleSeries($data, $options) ;
        } else {
            $this->_dataMultipleSeries($data, $options) ;
        }
    }
    
    /**
     * creates styles for legends and titles
     * 
     * @param string $styleName
     * @param array $options
     * @return void
     * @access private
     */
    private function _createStyle($styleName, $options) 
    {
        $style = $this->_dom->createElement('style:style');
        $style->setAttribute('style:name', $styleName);
        $style->setAttribute('style:family', 'chart');
        $cp = $this->_dom->createElement('style:chart-properties');
        $cp->setAttribute('chart:auto-position', 'true');
        $style->appendChild($cp);
        $gp = $this->_dom->createElement('style:graphic-properties');
        foreach($options as $key => $value){
            if(\is_bool($value)){
                $value = \var_export($value, true);
            }
            if (isset(self::$graphicPropsNS[$key])) {
                $gp->setAttribute(self::$graphicPropsNS[$key] . ':' . $key,
                                  $value);
            } 
        }
        $style->appendChild($gp);
        $tp = $this->_dom->createElement('style:text-properties');
        foreach($options as $key => $value){
            if(\is_bool($value)){
                $value = \var_export($value, true);
            }
            if (isset(self::$textPropsNS[$key])) {
                $tp->setAttribute(self::$textPropsNS[$key] . ':' . $key,
                                  $value);
            }
        }
        $style->appendChild($tp); 
        //append the style node
        $stylesNode = $this->_dom
                       ->getElementsByTagName('automatic-styles')
                       ->item(0);
        $stylesNode->appendChild($style); 
    }
    
    /**
     * inserts the chart data into a bubble chart
     * 
     * @param array $data 
     * @param array $options
     * @return void
     * @access private
     */
    public function _dataBubbleSeries($data, $options = array()) 
    {
        //set the chart type
        $chart = $this->_getChartNode();
        $chart->setAttribute('chart:class', $this->_class);
        //insert the series elements within the chart:plot-area node
        //in this case we only have a single series
        $seriesNode = $this->_dom->createElement('chart:series');
        $seriesNode->setAttribute('chart:label-cell-address', 
                                  'local-table.$D$1');
        $rows = \count($data);
        $seriesNode->setAttribute('chart:values-cell-range-address', 
                                  'local-table.$D$2:.$D$' . (1 + $rows));
        $seriesNode->setAttribute('chart:class', 'chart:bubble');
        $seriesNode->setAttribute('chart:style-name', 'mainSeriesStyle');
        $this->_generateSeriesStyle('mainSeriesStyle', $options);
        //include the domain nodes for bubble charts
        $domainNode = $this->_dom->createElement('chart:domain');
        $domainNode->setAttribute('table:cell-range-address', 
                                  'local-table.$B$2:.$B$' . (1 + $rows));
        $seriesNode->appendChild($domainNode);
        $domainNode2 = $this->_dom->createElement('chart:domain');
        $domainNode2->setAttribute('table:cell-range-address', 
                                   'local-table.$C$2:.$C$' . (1 + $rows));
        $seriesNode->appendChild($domainNode2);
        //include the data-point nodes for bubble charts
        $dataPointNode = $this->_dom->createElement('chart:data-point');
        $dataPointNode->setAttribute('chart:repeated',  $rows);
        $seriesNode->appendChild($dataPointNode);
        
        $plotArea = $this->_dom->getElementsByTagName('plot-area')->item(0);
        $plotArea->appendChild($seriesNode);
        //populate the table data
        $table = $this->_dom->getElementsByTagName('table')->item(0);
        //table-header-columns element
        $tableHeaderColumns = $this->_dom
                             ->createElement('table:table-header-columns');
        $tableColumn = $this->_dom->createElement('table:table-column');
        $tableHeaderColumns->appendChild($tableColumn);
        $table->appendChild($tableHeaderColumns);
        //table-columns element
        $tableColumns = $this->_dom->createElement('table:table-columns');
        $tableColumn = $this->_dom->createElement('table:table-column');
        $tableColumn->setAttribute('table:number-columns-repeated', $rows);
        $tableColumns->appendChild($tableColumn);
        $table->appendChild($tableColumns);
        //table-header-rows element
        $tableHeaderRows = $this->_dom
                             ->createElement('table:table-header-rows');
        $tableRow = $this->_dom->createElement('table:table-row');
        $tableCell = $this->_dom->createElement('table:table-cell');
        $tableRow->appendChild($tableCell);
        $tableCell = $this->_dom->createElement('table:table-cell');
        $tableRow->appendChild($tableCell);
        $tableCell = $this->_dom->createElement('table:table-cell');
        $tableRow->appendChild($tableCell);
        $tableCell = $this->_dom->createElement('table:table-cell');
        $tableCell->setAttribute('office:value-type', 'string');
        $textNode = $this->_dom->createElement('text:p', 'Y-Values');
        $tableCell->appendChild($textNode);
        $tableRow->appendChild($tableCell);
        $tableHeaderRows->appendChild($tableRow);
        $table->appendChild($tableHeaderRows);

        //insert the real data into rows
        foreach ($data as $value) {
            $rowNode = $this->_dom->createElement('table:table-row');
            $firstCellNode = $this->_dom->createElement('table:table-cell');
            $firstCellNode->setAttribute('office:value-type', 'string');
            $textNode = $this->_dom->createElement('text:p', $value[0]);
            $firstCellNode->appendChild($textNode);
            $rowNode->appendChild($firstCellNode);
            $cellNode = $this->_dom->createElement('table:table-cell');
            $cellNode->setAttribute('office:value-type', 'float');
            $cellNode->setAttribute('office:value', $value[0]);
            $rowNode->appendChild($cellNode);
            $cellNode = $this->_dom->createElement('table:table-cell');
            $cellNode->setAttribute('office:value-type', 'float');
            $cellNode->setAttribute('office:value', $value[1]);
            $rowNode->appendChild($cellNode);
            $cellNode = $this->_dom->createElement('table:table-cell');
            $cellNode->setAttribute('office:value-type', 'float');
            $cellNode->setAttribute('office:value', $value[2]);
            $rowNode->appendChild($cellNode);
            $table->appendChild($rowNode);
        }

    }
    
    /**
     * inserts the chart data
     * 
     * @param mixed $data a XML string or an array
     * @param array $options
     * @return void
     * @access private
     */
    public function _dataMultipleSeries($data, $options = array()) 
    {
        //set the chart type
        $chart = $this->_getChartNode();
        $chart->setAttribute('chart:class', $this->_class);
        //insert the series elements within the chart:plot-area node
        $serCount = \count($data['series']);
        $catCount = \count($data) -1;
        //generate the chart:data-point elements that hold the graphic styles
        //for the representation of the values of the different series
        for ($j = 0; $j < $serCount; $j++) {
            $lett = self::rowLetter($j+2);
            $seriesNode = $this->_dom->createElement('chart:series');
            $seriesNode->setAttribute('chart:label-cell-address', 
                                      'local-table.$' . $lett .'$1');
            $seriesNode->setAttribute('chart:values-cell-range-address', 
            'local-table.$' . $lett . '$2:.$' . $lett . '$' . ($catCount + 1));
            $seriesNode->setAttribute('chart:label-cell-address', 
            'local-table.$' . $lett . '$1' );
            //take into account the particular case of column-line chart type
            if ($this->_chartType == 'column-line'
                && $j == ($serCount-1)) {
                $seriesNode->setAttribute('chart:class', 'chart:line');
            } else {
                $seriesNode->setAttribute('chart:class', $this->_class);
            }
            $seriesNode->setAttribute('chart:style-name', 'series_' . $j);
            $seriesNode->setAttribute('chart:style-name', 'DataPoint_' . $j);
            $this->_generateSeriesStyle('series_' . $j, $options);
            $dataPointNode = $this->_dom->createElement('chart:data-point');   
            if (isset($options[$j])) {
                $this->_generateDataPointStyle($j, $options[$j]);
            } else {
                $this->_generateDataPointStyle($j, array()); 
            }
            $dataPointNode->setAttribute('chart:repeated',$catCount);
            $seriesNode->appendChild($dataPointNode);
            $plotArea = $this->_dom->getElementsByTagName('plot-area')->item(0);
            $plotArea->appendChild($seriesNode);
        }
        //Include the categories in the x-axis so they are properly displayed
        //if there is a legend
        $query = '//chart:axis[@chart:dimension = "x"]/chart:categories';
        $chart_categories = $this->_xpath->query($query)->item(0);
        $chart_categories->setAttribute('table:cell-range-address', 
                                  'local-table.$A$2:.$A$' . ($catCount + 1));
        //populate the table data
        $table = $this->_dom->getElementsByTagName('table')->item(0);
        //table-header-columns element
        $tableHeaderColumns = $this->_dom
                             ->createElement('table:table-header-columns');
        $tableColumn = $this->_dom->createElement('table:table-column');
        $tableHeaderColumns->appendChild($tableColumn);
        $table->appendChild($tableHeaderColumns);
        //table-columns element
        $tableColumns = $this->_dom->createElement('table:table-columns');
        $tableColumn = $this->_dom->createElement('table:table-column');
        $tableColumn->setAttribute('table:number-columns-repeated', $serCount);
        $tableColumns->appendChild($tableColumn);
        $table->appendChild($tableColumns);
        //table-header-rows element
        $tableHeaderRows = $this->_dom
                             ->createElement('table:table-header-rows');
        $tableRow = $this->_dom->createElement('table:table-row');
        $tableCell = $this->_dom->createElement('table:table-cell');
        $tableRow->appendChild($tableCell);
        foreach($data['series'] as $value){
            $tableCell = $this->_dom->createElement('table:table-cell');
            $tableCell->setAttribute('office:value-type', 'string');
            $textNode = $this->_dom->createElement('text:p', $value);
            $tableCell->appendChild($textNode);
            $tableRow->appendChild($tableCell);
        }
        $tableHeaderRows->appendChild($tableRow);
        $table->appendChild($tableHeaderRows);
        //insert the real data into rows
        $row = 0;
        foreach ($data as $name => $values) {
            if ($row > 0) {
                $rowNode = $this->_dom->createElement('table:table-row');
                $firstCellNode = $this->_dom->createElement('table:table-cell');
                $firstCellNode->setAttribute('office:value-type', 'string');
                $textNode = $this->_dom->createElement('text:p', $name);
                $firstCellNode->appendChild($textNode);
                $rowNode->appendChild($firstCellNode);
                foreach ($values as $value) {
                    $cellNode = $this->_dom->createElement('table:table-cell');
                    $cellNode->setAttribute('office:value-type', 'float');
                    $cellNode->setAttribute('office:value', $value);
                    $rowNode->appendChild($cellNode);
                }
                $table->appendChild($rowNode);
            }
            $row++;
        }
    }
    
    /**
     * inserts the chart data
     * 
     * @param array $data 
     * @param array $options
     * @return void
     * @access private
     */
    public function _dataSingleSeries($data, $options = array()) 
    {
        //set the chart type
        $chart = $this->_getChartNode();
        $chart->setAttribute('chart:class', $this->_class);
        //insert the series elements within the chart:plot-area node
        //in this case we only have a single series
        $seriesNode = $this->_dom->createElement('chart:series');
        $seriesNode->setAttribute('chart:label-cell-address', 
                                  'local-table.$B$1');
        $rows = \count($data);
        $seriesNode->setAttribute('chart:values-cell-range-address', 
                                  'local-table.$B$2:.$B$' . (1 + $rows));
        //for pie and donut the value of the chart:class attribute in the
        //series node is always 'chatr:circle' otherwise it will not be 
        //rendered correctly but in MS Office
        $seriesNode->setAttribute('chart:class', 'chart:circle');
        $seriesNode->setAttribute('chart:style-name', 'mainSeriesStyle');
        $this->_generateSeriesStyle('mainSeriesStyle', $options);
        //Include the categories in the x-axis so they are properly displayed
        //if there is a legend
        $query = '//chart:axis[@chart:dimension = "x"]/chart:categories';
        $chart_categories = $this->_xpath->query($query)->item(0);
        $chart_categories->setAttribute('table:cell-range-address', 
                                  'local-table.$A$2:.$A$' . (1 + $rows));
        //generate the chart:data-point elements that hold the graphic styles
        //for the representation of the values of the different series
        for ($j = 0; $j < $rows; $j++) {
            $dataPointNode = $this->_dom->createElement('chart:data-point');
            $dataPointNode->setAttribute('chart:style-name', 
                                         'DataPoint_' . $j);
            if (isset($options[$j])) {
                $this->_generateDataPointStyle($j, $options[$j]);
            } else {
                $this->_generateDataPointStyle($j, array()); 
            }
            $seriesNode->appendChild($dataPointNode);
        }
        $plotArea = $this->_dom->getElementsByTagName('plot-area')->item(0);
        $plotArea->appendChild($seriesNode);
        //populate the table data
        $table = $this->_dom->getElementsByTagName('table')->item(0);
        //table-header-columns element
        $tableHeaderColumns = $this->_dom
                             ->createElement('table:table-header-columns');
        $tableColumn = $this->_dom->createElement('table:table-column');
        $tableHeaderColumns->appendChild($tableColumn);
        $table->appendChild($tableHeaderColumns);
        //table-columns element
        $tableColumns = $this->_dom->createElement('table:table-columns');
        $tableColumn = $this->_dom->createElement('table:table-column');
        $tableColumns->appendChild($tableColumn);
        $table->appendChild($tableColumns);
        //table-header-rows element
        $tableHeaderRows = $this->_dom
                             ->createElement('table:table-header-rows');
        $tableRow = $this->_dom->createElement('table:table-row');
        $tableCell = $this->_dom->createElement('table:table-cell');
        $tableRow->appendChild($tableCell);
        $tableCell = $this->_dom->createElement('table:table-cell');
        $tableCell->setAttribute('office:value-type', 'string');
        $textNode = $this->_dom->createElement('text:p', 'chart');
        $tableCell->appendChild($textNode);
        $tableRow->appendChild($tableCell);
        $tableHeaderRows->appendChild($tableRow);
        $table->appendChild($tableHeaderRows);

        //insert the real data into rows
        $rows = \count($data);
        foreach ($data as $name => $value) {
            $rowNode = $this->_dom->createElement('table:table-row');
            $firstCellNode = $this->_dom->createElement('table:table-cell');
            $firstCellNode->setAttribute('office:value-type', 'string');
            $textNode = $this->_dom->createElement('text:p', $name);
            $firstCellNode->appendChild($textNode);
            $rowNode->appendChild($firstCellNode);
            $cellNode = $this->_dom->createElement('table:table-cell');
            $cellNode->setAttribute('office:value-type', 'float');
            $cellNode->setAttribute('office:value', $value);
            $rowNode->appendChild($cellNode);
            $table->appendChild($rowNode);
        }

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
     *      'stroke-color' => (string) hexadecimal color. For major grids the
     *       default is #d9d9d9 and #b3b3b3 for minor grids
     *      'stroke-opacity' => (string) percentage: 100% (default), 80%, ....
     *      'stroke-linejoin' => (string) round (default), bevel, middle, 
     *       miter or none
     *      'stroke-linecap' => (string) butt (default), round or square.
     *      'name' => (string) the name used for the series. It only applies to
     *       bubble charts
     * @return void
     * @access public
     */
    public function legend($options = array()) 
    {
        //set some default values
        if (isset($options['legend-position'])) {
            $position = $options['legend-position'];
        } else {
            $position = 'right';
        }
        if (!isset($options['stroke']) && !isset($options['stroke-color'])) {
            $options['stroke'] = 'none';
        }
        //check if there is a previous legend node and if so remove it
        $legends = $this->_dom->getElementsByTagName('chart:legend');
        if ($legends->length > 0) {
            $legend = $legends->item(0);
            $legend->parentNode->removeChild($legend);
        }
        //the legend is inserted before the chart:plot-area element
        //first normalize the position name to ODF standards
        $plotArea = $this->_dom->getElementsByTagName('plot-area')->item(0);
        if ($position == 'left') {
            $position = 'start';
        } else if ($position == 'right') {
            $position = 'end';
        } else if ($position == 'top' || $position == 'bottom') {
            //do nothing special
        } else {
            return;
        }
        if ($this->_chartType == 'bubble' && isset($options['name'])) {
            //set the series name for bubble charts
            $this->_dom
                 ->getElementsByTagName('table:table-header-rows')
                 ->item(0)
                 ->getElementsByTagName('table:table-cell')
                 ->item(3)
                 ->firstChild
                 ->nodeValue = $options['name'];
        }
        $legendNode = $this->_dom->createElement('chart:legend');
        $legendNode->setAttribute('chart:legend-position', $position);
        $legendNode->setAttribute('chart:style-name', 'Legend');
        $plotArea->parentNode->insertBefore($legendNode, $plotArea);
        //generate the legend style node
        $this->_createStyle('Legend', $options);        
    }
    
    /**
     * inserts a main title into the chart
     * 
     * @param array $options with the following keys and values
     *      'text' => (string) the chart title
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
     * @return void
     * @access public
     * @ignore
     */
    public function title($options = array()) 
    {
        //set some defaults
        if (!isset($options['font-size'])){
            $options['font-size'] = '14pt';
        }
        //check if there is a previous title node and if so remove it
        $titles = $this->_dom->getElementsByTagName('chart:title');
        if ($titles->length > 0) {
            $title = $titles->item(0);
            $title->parentNode->removeChild($title);
        }
        //the title is inserted before the chart:plot-area element
        $plotArea = $this->_dom->getElementsByTagName('plot-area')->item(0);
        
        $titleNode = $this->_dom->createElement('chart:title');
        $titleNode->setAttribute('chart:style-name', 'chartTitle');
        //Notice: we can not use the paragraph element because the title does
        //not allow for spans
        $p = $this->_dom->createElement('text:p', $options['text']);
        $titleNode->appendChild($p);
        $plotArea->parentNode->insertBefore($titleNode, $plotArea);
        //generate the main title style node
        $this->_createStyle('chartTitle', $options);
    }

    
    /**
     * Generates the style for a particular data point
     *
     * @param int $counter
     * @param array $style
     * @return void
     * @access protected
     */
    protected function _generateDataPointStyle($counter, $style) 
    {
        $colorScheme = $this->_colorScheme[$counter];
        $stylesNode = $this->_dom
                           ->getElementsByTagName('automatic-styles')
                           ->item(0);
        if (!empty($style) && \is_array($style)) {        
            $colorScheme = \array_merge($colorScheme, $style);
        } else if (!empty($style) 
                   && \is_array($style)
                   && isset(ColorScheme::$$style)) {
            $colorScheme = ColorScheme::$$style;
        }
        //create the style element:
        //<style:style style:family="chart" style:name="$styleName" />
        $styleNode = $this->_dom->createElement('style:style');
        $styleNode->setAttribute('style:family', 'chart');
        $styleNode->setAttribute('style:name', 'DataPoint_' . $counter);
        //create and append the chart-properties element
        //<style:chart-properties chart:symbol-type="none"/>
        $chartNode = $this->_dom->createElement('style:chart-properties');
        $chartNode->setAttribute('chart:symbol-type', 'none');
        if (isset($style['solid-type'])) {
            $chartNode->setAttribute('chart:solid-type', $style['solid-type']);
        }
        $styleNode->appendChild($chartNode);
        //create and append the graphic-properties element
        //<style:graphic-properties draw:fill="solid" ... /> 
        $graphicNode = $this->_dom->createElement('style:graphic-properties');
        foreach ($colorScheme as $key => $val) {
            if (isset(self::$graphicPropsNS[$key])) {
                $graphicNode->setAttribute(self::$graphicPropsNS[$key] . ':' . $key, 
                                           $val);
            }
        }
        $styleNode->appendChild($graphicNode);
        //set the text properties
        $textNode = $this->_dom->createElement('style:text-properties');
        $textNode->setAttribute('fo:font-size', '10pt');
        $styleNode->appendChild($textNode);
        //and now append the $styleNode to the $stylesNode
        $stylesNode->appendChild($styleNode);
    }
    
    /**
     * Generates the general style for a particular series
     *
     * @param string $seriesName
     * @param array $options
     * @return void
     * @access protected
     */
    protected function _generateSeriesStyle($seriesName, $options = array()) 
    {
        $stylesNode = $this->_dom
                           ->getElementsByTagName('automatic-styles')
                           ->item(0);
        //create the style element:
        //<style:style style:family="chart" style:name="$styleName" />
        $styleNode = $this->_dom->createElement('style:style');
        $styleNode->setAttribute('style:family', 'chart');
        $styleNode->setAttribute('style:name', $seriesName);
        //create and append the chart-properties element
        $chartNode = $this->_dom->createElement('style:chart-properties');
        //set some default attributes
        foreach (self::$defaultChartProperties as $key => $val) {
            if (isset($this->_customChartProperties[$key])) {
                $chartNode->setAttribute($key, 
                                         $this->_customChartProperties[$key]);
            } else {
                if (self::$defaultChartProperties[$key] !== false){
                    $chartNode->setAttribute($key, $val);
                }
            }
        }
        //take into account symbols for line and scatter charts
        if ($this->_chartType == 'line' 
            || $this->_chartType == 'scatter'
            || $this->_chartType == 'radar') {
            //get the index from the $seriesName variable
            $indexArray = \explode('_', $seriesName);
            $index = \array_pop($indexArray);
            if (!empty($options[$index]['symbol']) 
                && isset(self::$symbols[$options[$index]['symbol']])) {
                $chartNode->setAttribute('chart:symbol-type', 'named-symbol');
                $chartNode->setAttribute('chart:symbol-name', 
                                         $options[$index]['symbol']);
                //TODO: let the user fix the size
                $chartNode->setAttribute('chart:symbol-width', '0.2cm');
                $chartNode->setAttribute('chart:symbol-width', '0.2cm');
            }
        }
        $styleNode->appendChild($chartNode);
        //create and append the graphic-properties element
        $graphicNode = $this->_dom->createElement('style:graphic-properties');
        if ($this->_chartType == 'bubble') {
            if (!empty($options[0])) {
                foreach ($options[0] as $key => $val) {
                    if (isset(self::$graphicPropsNS[$key])) {
                        $graphicNode->setAttribute(self::$graphicPropsNS[$key] 
                                                   . ':' . $key, 
                                                   $val);
                    }
                }
            }
        }
        $styleNode->appendChild($graphicNode);
        //and now append the $styleNode to the $stylesNode
        $stylesNode->appendChild($styleNode);
    }
    
    /**
     * gets the chart:chart DOMNode
     * @access private
     * @return string
     */
    private function _getChartNode()
    {
        $ns = 'urn:oasis:names:tc:opendocument:xmlns:chart:1.0';
        $chart = $this->_dom
                      ->getElementsByTagNameNS($ns, 'chart')
                      ->item(0);
        return $chart;
    }
    
    /**
     * Generates the VRP vector depending on the chosen perpective
     *
     * @param integer $perspective given as a percentage
     * @return array
     * @access private
     */
    private function _generateVRP($perspective) 
    {
        //The perspective is inversely proportional to the length of the 
        //VRP vector being 100% for a length of 7500 and 0% for a length of 
        //200000
        //The aproximate relation between both is given by:
        // perspective = 780000/length_VRP - 3.9
        //Using that the VRP vector should be parallel to $this->_vrp we
        //arrive to the following (aproximate) relationship between the 
        //scale factor and the perspective
        $scaleFactor = 103.9 / ($perspective * 3.9);
        //therefore the final VRP vector is
        return '(' . $scaleFactor * $this->_vrp[0] . ' '
                   . $scaleFactor * $this->_vrp[1]. ' '
                   . $scaleFactor * $this->_vrp[2] .')';    
    }
    
    /**
     * Generates the transform matrix associated with a 3D rotation respèct the
     * X,Y and Z axis
     *
     * @param int $rotx an integer angle between -90 and 90. The default is 
     * 11 for all charts but pie and donut that is -60
     * @param int $roty an integer angle between -45 and 45. The default is 
     * 11 for all charts but pie and donut that is 0
     * @param int $rotz an integer angle between -90 and 90. The default is 
     * 5 for all charts but pie and donut that is 0 (this is ignored if 
     * the rightAngle property is set to true)
     * @return array
     * @access private
     */
    private function _generateTransformationMatrix($rotx = 0, 
                                                   $roty = 0, 
                                                   $rotz = 0) 
    {
        //convert the degrees to radians
        $rotx = deg2rad($rotx);
        $roty = deg2rad($roty);
        $rotz = deg2rad($rotz);
        //to get the transformation matrix we have first to compute the standard
        //3D rotation matrix RM generated by consecutive rotations trough the
        //X, Y and Z (in that order).
        $RX = array(array(1,0,0), array(0,1,0), array(0,0,1));
        $RY = array(array(1,0,0), array(0,1,0), array(0,0,1));
        $RZ = array(array(1,0,0), array(0,1,0), array(0,0,1));
        
        if (is_numeric($rotx) && $rotx != 0) {
            $RX[1][1] = \cos($rotx);
            $RX[1][2] = - \sin($rotx);
            $RX[2][1] = \sin($rotx);
            $RX[2][2] = \cos($rotx);
        }
        if (is_numeric($roty) && $roty != 0) {
            $RY[0][0] = \cos($roty);
            $RY[0][2] = - \sin($roty);
            $RY[2][0] = \sin($roty);
            $RY[2][2] = \cos($roty);
        }
        if (is_numeric($rotz) && $rotz != 0) {
            $RZ[0][0] = \cos($rotz);
            $RZ[0][1] = \sin($rotz);
            $RZ[1][0] = - \sin($rotz);
            $RZ[1][1] = \cos($rotz);
        }
        
        $RM = commons\Utilities::matrixMultiplication($RY, $RX);
        $RM = commons\Utilities::matrixMultiplication($RZ, $RM);

        //the required TM (transformation matrix) is obtained as:
        // TM = MC * RM where MC is the inverse of the Camera Matrix (CM), i.e
        // MC * CM = I where I is the identity matrix
        /* The MC matris is given by:
        (0.90630677104983 | -0.073387636277161 | 0.41619982170935
        7.1497382032627×10^-16 | 0.98480759991797 | 0.17364904590525
        -0.42262044052465 | -0.15737930609027 | 0.89253779598699)
         */
        $MC = array();
        $MC[0][0] = 0.90630677104983;
        $MC[0][1] = -0.073387636277161;
        $MC[0][2] = 0.41619982170935;
        $MC[1][0] = 0;
        $MC[1][1] = 0.98480759991797;
        $MC[1][2] = 0.17364904590525;
        $MC[2][0] = -0.42262044052465;
        $MC[2][1] = -0.15737930609027;
        $MC[2][2] = 0.89253779598699;
        $TM = commons\Utilities::matrixMultiplication($MC, $RM);
        
        //last but not least the result is given in the following format:
        $dr3d_transform = 'matrix (';
        $dr3d_transform .= $TM[0][0] . ' ';
        $dr3d_transform .= $TM[1][0] . ' ';
        $dr3d_transform .= $TM[2][0] . ' ';
        $dr3d_transform .= $TM[0][1] . ' ';
        $dr3d_transform .= $TM[1][1] . ' ';
        $dr3d_transform .= $TM[2][1] . ' ';
        $dr3d_transform .= $TM[0][2] . ' ';
        $dr3d_transform .= $TM[1][2] . ' ';
        $dr3d_transform .= $TM[2][2] . ' ';
        $dr3d_transform .= '0cm 0cm 0cm)';

        return $dr3d_transform;
    }
    
    /**
     * repair the chartWrapper style for correct Word rendering
     * 
     * @param DOMNode $node 
     *
     * @return void
     * @access public
     */
    public function repairChart4Word($node) 
    {
        $graphicNode = $this->_dom->getElementsByTagName('style')->item(0);
        $oldNode = $graphicNode->getElementsByTagName('graphic-properties')
                               ->item(0);
        $graphicNode->removeChild($oldNode);
        $newNode = $this->_dom->importNode($node);
        $graphicNode->appendChild($newNode);      
    }
    
    /**
     * sets the styles for the x, y and z chart grids
     * 
     * @param DOMNode $node 
     * @param array $options
     *
     * @return void
     * @access private
     */
    private function _grid($node, $options) 
    {
        foreach ($options as $key => $value) {
            if (isset(self::$graphicPropsNS[$key])) {
                $node->setAttribute(self::$graphicPropsNS[$key] . ':' . $key,
                                    $value);
            }
        }
    }
    
    /**
     * sets some default axis styling properties depending of the chart type
     * 
     * @param string $axis
     * @param string $type
     * @param array $options
     * @return void
     * @access private
     */
    private function _setDefaultAxisProps($axis, $type, &$options) 
    {
        
    }
    
    /**
     * sets some default axis styling properties depending of the chart type
     * 
     * @param string $component
     * @param string $type
     * @param array $options
     * @return void
     * @access private
     */
    private function _setDefaultComponentProps($component, $type, &$options) 
    {
        
    }
    
    /**
     * sets some default grid styling properties depending of the chart type
     * 
     * @param string $axis
     * @param string $gridType
     * @param string $type
     * @param array $options
     * @return void
     * @access private
     */
    private function _setDefaultGridProps($axis, $gridType, $type, &$options) 
    {
        if (!isset($options['stroke-color']) && $gridType == 'major'){
            $options['stroke-color'] = '#d9d9d9';
        } else if (!isset($options['stroke-color']) && $gridType == 'minor'){
            $options['stroke-color'] = '#f0f0f0';
        }
        if (!isset($options['stroke-width']) && $gridType == 'major'){
            $options['stroke-width'] = '0.75pt';
        } else if (!isset($options['stroke-width']) && $gridType == 'minor'){
            $options['stroke-width'] = '0.5pt';
        }
    }
    
    /**
     * sets some default styling properties depending of the chart type
     * 
     * @param string $type
     * @param array $options
     * @return void
     * @access private
     */
    private function _setDefaultChartProps($type, &$options) 
    {
        //bar and column charts
        if (($type == 'bar' || $type == 'column')
             && $this->_threeD === false) {
            if (!isset($options['gap-width'])) {
                $options['gap-width'] = 200;
            }
            if (!isset($options['overlap'])) {
                $options['overlap'] = -25;
            }
        }
    }
    
    /**
     * returns the letter corresponding to a table row (a-z aa-az ba-bz ...)
     *
     * @param int $j
     * @return	string
     * @access	public
     * @static
     */

    public static function rowLetter($j)
    {
        $letter = '';
        $number = $j;
        while ($number > 0)
        {
            $currentLetterNumber = ($number - 1) % 26;
            $currentLetter = \chr($currentLetterNumber + 65);
            $letter = $currentLetter . $letter;
            $number = ($number - ($currentLetterNumber + 1)) / 26;
        }
        return $letter;
    }

}