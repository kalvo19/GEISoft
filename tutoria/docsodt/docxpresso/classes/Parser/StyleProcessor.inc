<?php

/**
 * Style Processor
 *
 * @copyright  Copyright (c) 2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Parser;

use Docxpresso;
use Docxpresso\Commons as commons;
use Docxpresso\Parser\CSS as css;

/**
 * This class process the CSS properties and transform them into their
 * equivalent ODF properties
 *
 * @package    Docxpresso
 * @subpackage Parser
 */

class StyleProcessor
{
        
    /**
     * background style properties
     * 
     * @access public
     * @static
     * @var array
     */
    public static $bgStyles = array(
        'draw:opacity' => true, 
        'style:filter-name' => true, 
        'style:position' => true, 
        'style:repeat' => true, 
        'xlink:actuate' => true, 
        'xlink:href' => true, 
        'xlink:show' => true,
        'xlink:type' => true,
    );
    
    /**
     * chart style properties
     * 
     * @access public
     * @static
     * @var array
     */
    public static $chartStyles = array(
        'chart:angle-offset' => true, 
        'chart:auto-position' => true, 
        'chart:auto-size' => true, 
        'chart:axis-label-position' => true, 
        'chart:axis-position' => true, 
        'chart:connect-bars' => true, 
        'chart:data-label-number' => true, 
        'chart:data-label-symbol' => true, 
        'chart:data-label-text' => true, 
        'chart:deep' => true, 
        'chart:display-label' => true, 
        'chart:error-category' => true, 
        'chart:error-lower-indicator' => true, 
        'chart:error-lower-limit' => true, 
        'chart:error-lower-range' => true, 
        'chart:error-margin' => true, 
        'chart:error-percentage' => true, 
        'chart:error-upper-indicator' => true, 
        'chart:error-upper-limit' => true, 
        'chart:error-upper-range' => true, 
        'chart:gap-width' => true, 
        'chart:group-bars-per-axis' => true, 
        'chart:hole-size' => true, 
        'chart:include-hidden-cells' => true, 
        'chart:interpolation' => true, 
        'chart:interval-major' => true, 
        'chart:interval-minor-divisor' => true, 
        'chart:japanese-candle-stick' => true, 
        'chart:label-arrangement' => true, 
        'chart:label-position' => true, 
        'chart:label-position-negative' => true, 
        'chart:lines' => true, 
        'chart:link-data-style-to-source' => true, 
        'chart:logarithmic' => true, 
        'chart:maximum' => true, 
        'chart:mean-value' => true, 
        'chart:minimum' => true, 
        'chart:origin' => true, 
        'chart:overlap' => true, 
        'chart:percentage' => true, 
        'chart:pie-offset' => true, 
        'chart:regression-type' => true, 
        'chart:reverse-direction' => true, 
        'chart:right-angled-axes' => true, 
        'chart:scale-text' => true, 
        'chart:series-source' => true, 
        'chart:solid-type' => true, 
        'chart:sort-by-x-values' => true, 
        'chart:spline-order' => true, 
        'chart:spline-resolution' => true, 
        'chart:stacked' => true, 
        'chart:symbol-height' => true, 
        'chart:symbol-name' => true, 
        'chart:symbol-type' => true, 
        'chart:symbol-width' => true, 
        'chart:text-overlap' => true, 
        'chart:three-dimensional' => true, 
        'chart:tick-mark-position' => true, 
        'chart:tick-marks-major-inner' => true, 
        'chart:tick-marks-major-outer' => true, 
        'chart:tick-marks-minor-inner' => true, 
        'chart:tick-marks-minor-outer' => true, 
        'chart:treat-empty-cells' => true, 
        'chart:vertical' => true, 
        'chart:visible' => true, 
        'style:direction' => true, 
        'style:rotation-angle' => true,
        'text:line-break' => true,
    );
    
    /**
     * This array holds the relationship between CSS properties and ODF style
     * properties.
     * Sometimes the relationship is one-to-one but in many cases a CSS property
     * may map to different ODF style properties depending on the type of ODF 
     * node or even its value.
     * If the property is mapped into an array it is a reference for further 
     * parsing
     * 
     * @access public
     * @static
     * @var array
     */
    public static $CSSODF = array(
        'color'                 => 'fo:color', 
        'opacity'               => 'style:background-transparency',
        //'background' the shorthand property is not directly parsed
        'background-color'      => 'fo:background-color',
        'background-image'      => 'xlink:href',
        'background-position'   => 'style:position',
        'background-repeat'     => 'style:repeat',
        'border'                => 'fo:border',
        'border-bottom'         => 'fo:border-bottom',
        //'border-bottom-color' parsed only through border-bottom
        //'border-bottom-style' parsed only through border-bottom
        //'border-bottom-width' parsed only through border-bottom
        //'border-color' parsed only through border
        'border-left'           => 'fo:border-left', 
        //'border-left-color' parsed only through border-bottom
        //'border-left-style' parsed only through border-bottom
        //'border-left-width' parsed only through border-bottom
        'border-radius'         => 'draw:corner-radius',
        'border-right'          => 'fo:border-right',
        //'border-right-color' parsed only through border-right
        //'border-right-style' parsed only through border-right
        //'border-right-width' parsed only through border-right
        //'border-style' parsed only through border
        'border-top'            => 'fo:border-top',
        //'border-top-color' parsed only through border-top
        //'border-top-style' parsed only through border-top
        //'border-top-width' parsed only through border-top
        //'border-width'  parsed only through border 
        'box-shadow'            => 'style:shadow',
        //'bottom' => true,
        //'clear' => false,
        'clip'                  => 'fo:clip',
        //'display' => true,
        //'float' => true,
        'height'                => array ('method' => '_parseHeight'),
        //'left' => false, although parse has no direct translation
        //'padding' => false, it is parsed through padding-bottom, ...
        'padding-bottom'        => 'fo:padding-bottom',
        'padding-left'          => 'fo:padding-left',
        'padding-right'         => 'fo:padding-right',
        'padding-top'           => 'fo:padding-top', 
        //'position' => false,
        //'right' => false, although parse has no direct translation
        //'top' => false, although parse has no direct translation
        //'visibility' => false,
        'width'                 => array ('method' => '_parseWidth'),
        'vertical-align'        => array ('method' => '_parseVerticalAlign'),
        'z-index'               => 'draw:z-index',
        //'margin' => false, it is parsed through margin-bottom, ...
        'margin-bottom'         => 'fo:margin-bottom',
        'margin-left'           => 'fo:margin-left',
        'margin-right'          => 'fo:margin-right',
        'margin-top'            => 'fo:margin-top',
        'max-height'            => 'fo:max-height',
        'max-width'             => 'fo:max-width',
        'min-height'            => 'fo:min-height',
        'min-width'             => 'fo:min-width',
        'hyphens'               => 'fo:hyphenate',
        'letter-spacing'        => 'fo:letter-spacing',
        'line-break'            => 'style:line-break',
        'line-height'           => 'fo:line-height',
        'text-align'            => 'fo:text-align',
        'text-align-last'       => 'fo:text-align-last', 
        'text-indent'           => 'fo:text-indent',
        'text-transform'        => 'fo:text-transform',
        'text-decoration'       => array ('method' => '_parseTextDecoration'),
        'text-decoration-color' => array ('method' => '_parseTextDecoration'),
        //'text-decoration-line' => false, it is parse through text-decoration 
        'text-decoration-style' => array ('method' => '_parseTextDecoration'),
        'text-shadow'           => 'fo:text-shadow',
        //'font' => false, it is parsed through font-family, font-size, ...
        'font-family'           => 'fo:font-family',
        'font-kerning'          => 'style:letter-kerning',
        'font-size'             => 'fo:font-size',
        'font-style'            => 'fo:font-style',
        'font-variant'          => 'fo:font-variant',
        'font-weight'           => 'fo:font-weight',
        'direction'             => 'style:writing-mode',
        'writing-mode'          => 'style:writing-mode',
        'border-collapse'       => 'table:border-model',
        'border-spacing'        => true,
        //'empty-cells' => false, it is structural
        'table-layout'          => 'style:use-optimal-column-width',
        'counter-increment'     => true,
        'counter-reset'         => true,
        //'list-style'            => false, parsed via list-style-type and image
        'list-style-image'      => array('method' => '_parseLists'),//not used
        //'list-style-position'   => false, //there is no ODF equivalent
        'list-style-type'       => array('method' => '_parseLists'),//not used
        'transform'             => 'style:text-rotation',
        //'outline'this is parsed as border
        //'outline-color' this is parsed as border
        //'outline-offset' this is parsed as border
        //'outline-style' => this is parsed as border
        //'outline-width' => this is parsed as border
        'break-after'           => array ('method' => '_parseBreaks'),
        'break-before'          => 'fo:break-before',
        'break-inside'          => array ('method' => '_parseBreaks'),
        'column-count'          => 'fo:column-count',
        //column-fill           => false, not supported by any major browser
        'column-gap'            => 'fo:column-gap',
        //'column-rule' =>parsed via the specific colom-rules properties
        'column-rule-color'     => 'style:color',
        'column-rule-style'     => 'style:style',//not parsed by Open Office
        'column-rule-width'     => 'style:width',
        'column-width'          => 'fo:rel-width',
        //'columns' parsed via column-count and column-width
        'widows'                => 'fo:widows', 
        'orphans'               => 'fo:orphans',
        'page-break-after'      => array ('method' => '_parseBreaks'),
        'page-break-before'     => 'fo:break-before',
        'page-break-inside'     => array ('method' => '_parseBreaks'),
        //additional SVG styles required for graphic properties
        'stroke'                => 'draw:stroke',
        'stroke-width'          => 'svg:stroke-width',
        'stroke-color'          => 'svg:stroke-color',
     );
    
    /**
     * graphic style properties
     * 
     * @access public
     * @static
     * @var array
     */
    public static $graphicStyles = array(
        'dr3d:ambient-color' => true,
        'dr3d:backface-culling' => true,
        'dr3d:back-scale' => true,
        'dr3d:close-back' => true,
        'dr3d:close-front' => true,
        'dr3d:depth' => true,
        'dr3d:diffuse-color' => true,
        'dr3d:edge-rounding' => true,
        'dr3d:edge-rounding-mode' => true,
        'dr3d:emissive-color' => true,
        'dr3d:end-angle' => true,
        'dr3d:horizontal-segments' => true,
        'dr3d:lighting-mode' => true,
        'dr3d:normals-direction' => true,
        'dr3d:normals-kind' => true,
        'dr3d:shadow' => true,
        'dr3d:shininess' => true,
        'dr3d:specular-color' => true,
        'dr3d:texture-filter' => true,
        'dr3d:texture-generation-mode-x' => true,
        'dr3d:texture-generation-mode-y' => true,
        'dr3d:texture-kind' => true,
        'dr3d:texture-mode' => true,
        'dr3d:vertical-segments' => true,
        'draw:auto-grow-height' => true,
        'draw:auto-grow-width' => true,
        'draw:blue' => true,
        'draw:caption-angle' => true,
        'draw:caption-angle-type' => true,
        'draw:caption-escape' => true,
        'draw:caption-escape-direction' => true,
        'draw:caption-fit-line-length' => true,
        'draw:caption-gap' => true,
        'draw:caption-line-length' => true,
        'draw:caption-type' => true,
        'draw:color-inversion' => true,
        'draw:color-mode' => true,
        'draw:contrast' => true,
        'draw:decimal-places' => true,
        'draw:draw-aspect' => true,
        'draw:end-guide' => true,
        'draw:end-line-spacing-horizontal' => true,
        'draw:end-line-spacing-vertical' => true,
        'draw:fill' => true,
        'draw:fill-color' => true,
        'draw:fill-gradient-name' => true,
        'draw:fill-hatch-name' => true,
        'draw:fill-hatch-solid' => true,
        'draw:fill-image-height' => true,
        'draw:fill-image-name' => true,
        'draw:fill-image-ref-point' => true,
        'draw:fill-image-ref-point-x' => true,
        'draw:fill-image-ref-point-y' => true,
        'draw:fill-image-width' => true,
        'draw:fit-to-contour' => true,
        'draw:fit-to-size' => true,
        'draw:frame-display-border' => true,
        'draw:frame-display-scrollbar' => true,
        'draw:frame-margin-horizontal' => true,
        'draw:frame-margin-vertical' => true,
        'draw:gamma' => true,
        'draw:gradient-step-count' => true,
        'draw:green' => true,
        'draw:guide-distance' => true,
        'draw:guide-overhang' => true,
        'draw:image-opacity' => true,
        'draw:line-distance' => true,
        'draw:luminance' => true,
        'draw:marker-end' => true,
        'draw:marker-end-center' => true,
        'draw:marker-end-width' => true,
        'draw:marker-start' => true,
        'draw:marker-start-center' => true,
        'draw:marker-start-width' => true,
        'draw:measure-align' => true,
        'draw:measure-vertical-align' => true,
        'draw:ole-draw-aspect' => true,
        'draw:opacity' => true,
        'draw:opacity-name' => true,
        'draw:parallel' => true,
        'draw:placing' => true,
        'draw:red' => true,
        'draw:secondary-fill-color' => true,
        'draw:shadow' => true,
        'draw:shadow-color' => true,
        'draw:shadow-offset-x' => true,
        'draw:shadow-offset-y' => true,
        'draw:shadow-opacity' => true,
        'draw:show-unit' => true,
        'draw:start-guide' => true,
        'draw:start-line-spacing-horizontal' => true,
        'draw:start-line-spacing-vertical' => true,
        'draw:stroke' => true,
        'draw:stroke-dash' => true,
        'draw:stroke-dash-names' => true,
        'draw:stroke-linejoin' => true,
        'draw:symbol-color' => true,
        'draw:textarea-horizontal-align' => true,
        'draw:textarea-vertical-align' => true,
        'draw:tile-repeat-offset' => true,
        'draw:unit' => true,
        'draw:visible-area-height' => true,
        'draw:visible-area-left' => true,
        'draw:visible-area-top' => true,
        'draw:visible-area-width' => true,
        'draw:wrap-influence-on-position' => true,
        'fo:background-color' => true,
        'fo:border' => true,
        'fo:border-bottom' => true,
        'fo:border-left' => true,
        'fo:border-right' => true,
        'fo:border-top' => true,
        'fo:clip' => true,
        'fo:margin' => true,
        'fo:margin-bottom' => true,
        'fo:margin-left' => true,
        'fo:margin-right' => true,
        'fo:margin-top' => true,
        'fo:max-height' => true,
        'fo:max-width' => true,
        'fo:min-height' => true,
        'fo:min-width' => true,
        'fo:padding' => true,
        'fo:padding-bottom' => true,
        'fo:padding-left' => true,
        'fo:padding-right' => true,
        'fo:padding-top' => true,
        'fo:wrap-option' => true,
        'style:background-transparency' => true,
        'style:border-line-width' => true,
        'style:border-line-width-bottom' => true,
        'style:border-line-width-left' => true,
        'style:border-line-width-right' => true,
        'style:border-line-width-top' => true,
        'style:editable' => true,
        'style:flow-with-text' => true,
        'style:horizontal-pos' => true,
        'style:horizontal-rel' => true,
        'style:mirror' => true,
        'style:number-wrapped-paragraphs' => true,
        'style:overflow-behavior' => true,
        'style:print-content' => true,
        'style:protect' => true,
        'style:rel-height' => true,
        'style:rel-width' => true,
        'style:repeat' => true,
        'style:run-through' => true,
        'style:shadow' => true,
        'style:shrink-to-fit' => true,
        'style:vertical-pos' => true,
        'style:vertical-rel' => true,
        'style:wrap' => true,
        'style:wrap-contour' => true,
        'style:wrap-contour-mode' => true,
        'style:wrap-dynamic-threshold' => true,
        'style:writing-mode' => true,
        'svg:fill-rule' => true,
        'svg:height' => true,
        'svg:stroke-color' => true,
        'svg:stroke-linecap' => true,
        'svg:stroke-opacity' => true,
        'svg:stroke-width' => true,
        'svg:width' => true,
        'svg:x' => true,
        'svg:y' => true,
        'text:anchor-page-number' => true,
        'text:anchor-type' => true,
        'text:animation' => true,
        'text:animation-delay' => true,
        'text:animation-direction' => true,
        'text:animation-repeat' => true,
        'text:animation-start-inside' => true,
        'text:animation-steps' => true,
        'text:animation-stop-inside' => true,
    );
    
    /**
     * header and footer style properties
     * 
     * @access public
     * @static
     * @var array
     */
    public static $headerFooterStyles = array(
        'background-color' => 'fo',
        'border' => 'fo',
        'border-bottom' => 'fo',
        'border-left' => 'fo',
        'border-right' => 'fo',
        'border-top' => 'fo',
        'margin' => 'fo',
        'margin-bottom' => 'fo',
        'margin-left' => 'fo',
        'margin-right' => 'fo',
        'margin-top' => 'fo',
        'min-height' => 'fo',
        'padding' => 'fo',
        'padding-bottom' => 'fo',
        'padding-left' => 'fo',
        'padding-right' => 'fo',
        'padding-top' => 'fo',
        'border-line-width' => 'style',
        'border-line-width-bottom' => 'style',
        'border-line-width-left' => 'style',
        'border-line-width-right' => 'style',
        'border-line-width-top' => 'style',
        'dynamic-spacing' => 'style',
        'shadow' => 'style',
        'height' => 'height',
        );
    
    /**
     * page style properties
     * 
     * @access public
     * @static
     * @var array
     */
    public static $pageStyles = array(
        'fo:background-color' => true,
        'fo:border' => true,
        'fo:border-bottom' => true,
        'fo:border-left' => true,
        'fo:border-right' => true,
        'fo:border-top' => true,
        'fo:margin' => true,
        'fo:margin-bottom' => true,
        'fo:margin-left' => true,
        'fo:margin-right' => true,
        'fo:margin-top' => true,
        'fo:padding' => true,
        'fo:padding-bottom' => true,
        'fo:padding-left' => true,
        'fo:padding-right' => true,
        'fo:padding-top' => true,
        'fo:page-height' => true,
        'fo:page-width' => true,
        'style:border-line-width' => true,
        'style:border-line-width-bottom' => true,
        'style:border-line-width-left' => true,
        'style:border-line-width-right' => true,
        'style:border-line-width-top' => true,
        'style:first-page-number' => true,
        'style:footnote-max-height' => true,
        'style:layout-grid-base-height' => true,
        'style:layout-grid-base-width' => true,
        'style:layout-grid-color' => true,
        'style:layout-grid-display' => true,
        'style:layout-grid-lines' => true,
        'style:layout-grid-mode' => true,
        'style:layout-grid-print' => true,
        'style:layout-grid-ruby-below' => true,
        'style:layout-grid-ruby-height' => true,
        'style:layout-grid-snap-to' => true,
        'style:layout-grid-standard-mode' => true,
        'style:num-format' => true,
        'style:num-letter-sync' => true,
        'style:num-prefix' => true,
        'style:num-suffix' => true,
        'style:paper-tray-name' => true,
        'style:print' => true,
        'style:print-orientation' => true,
        'style:print-page-order' => true,
        'style:register-truth-ref-style-name' => true,
        'style:scale-to' => true,
        'style:scale-to-pages' => true,
        'style:shadow' => true,
        'style:table-centering' => true,
        'style:writing-mode' => true,
        );
    
    /**
     * paragraph style properties
     * 
     * @access public
     * @static
     * @var array
     */
    public static $pStyles = array(
        'fo:background-color' => true,
        'fo:border' => true,
        'fo:border-bottom' => true, 
        'fo:border-left' => true, 
        'fo:border-right' => true, 
        'fo:border-top' => true, 
        'fo:break-after' => true, 
        'fo:break-before' => true, 
        'fo:hyphenation-keep' => true, 
        'fo:hyphenation-ladder-count' => true, 
        'fo:keep-together' => true, 
        'fo:line-height' => true, 
        'fo:margin' => true, 
        'fo:margin-bottom' => true, 
        'fo:margin-left' => true, 
        'fo:margin-right' => true, 
        'fo:margin-top' => true, 
        'fo:orphans' => true, 
        'fo:padding' => true, 
        'fo:padding-bottom' => true, 
        'fo:padding-left' => true, 
        'fo:padding-right' => true, 
        'fo:padding-top' => true, 
        'fo:text-align' => true, 
        'fo:text-align-last' => true, 
        'fo:text-indent' => true, 
        'fo:widows' => true, 
        'style:auto-text-indent' => true, 
        'style:background-transparency' => true, 
        'style:border-line-width' => true, 
        'style:border-line-width-bottom' => true, 
        'style:border-line-width-left' => true, 
        'style:border-line-width-right' => true, 
        'style:border-line-width-top' => true, 
        'style:font-independent-line-spacing' => true, 
        'style:join-border' => true, 
        'style:justify-single-word' => true, 
        'style:line-break' => true, 
        'style:line-height-at-least' => true, 
        'style:line-spacing' => true, 
        'style:page-number' => true, 
        'style:punctuation-wrap' => true, 
        'style:register-true' => true, 
        'style:shadow' => true, 
        'style:snap-to-layout-grid' => true, 
        'style:tab-stop-distance' => true, 
        'style:text-autospace' => true, 
        'style:vertical-align' => true, 
        'style:writing-mode' => true, 
        'style:writing-mode-automatic' => true, 
        'text:line-number' => true, 
        'text:number-lines' => true,
    );
    /**
     * ODF style families
     * 
     * @access public
     * @static
     * @var array
     */
    public static $styleFamilies = array(
        'text:list'             => false,
        'text:p'                => 'paragraph',
        'text:p'                => 'paragraph',
        'text:h'                => 'paragraph',
        'text:a'                => 'text',
        'text:span'             => 'text',
        'text:section'          => 'section',
        'table:table'           => 'table',
        'table:table-row'       => 'table-row',
        'table:table-cell'      => 'table-cell',
        'table:table-column'    => 'table-column',
        'chart:chart'           => 'chart',
        'draw:frame'            => 'graphic',
        'draw:control'          => 'graphic',
        'draw:text-box'         => 'graphic',
        'DOMNode'               => true,
    );
    
    /**
     * table style properties
     * 
     * @access public
     * @static
     * @var array
     */
    public static $tableStyles = array(
        'fo:background-color' => true, 
        'fo:break-after' => true, 
        'fo:break-before' => true, 
        'fo:keep-with-next' => true, 
        'fo:margin' => true, 
        'fo:margin-bottom' => true, 
        'fo:margin-left' => true, 
        'fo:margin-right' => true, 
        'fo:margin-top' => true, 
        'style:may-break-between-rows' => true, 
        'style:page-number' => true, 
        'style:rel-width' => true, 
        'style:shadow' => true, 
        'style:width' => true, 
        'style:writing-mode' => true, 
        'table:align' => true, 
        'table:border-model' => true,
        'table:display' => true,    
    );
    
    /**
     * table cell style properties
     * 
     * @access public
     * @static
     * @var array
     */
    public static $tableCellStyles = array(
        'fo:background-color' => true, 
        'fo:border' => true, 
        'fo:border-bottom' => true, 
        'fo:border-left' => true, 
        'fo:border-right' => true, 
        'fo:border-top' => true, 
        'fo:padding' => true, 
        'fo:padding-bottom' => true, 
        'fo:padding-left' => true, 
        'fo:padding-right' => true, 
        'fo:padding-top' => true, 
        'fo:wrap-option' => true, 
        'style:border-line-width' => true, 
        'style:border-line-width-bottom' => true, 
        'style:border-line-width-left' => true, 
        'style:border-line-width-right' => true, 
        'style:border-line-width-top' => true, 
        'style:cell-protect' => true, 
        'style:decimal-places' => true, 
        'style:diagonal-bl-tr' => true, 
        'style:diagonal-bl-tr-widths' => true, 
        'style:diagonal-tl-br' => true, 
        'style:diagonal-tl-br-widths' => true, 
        'style:direction' => true, 
        'style:glyph-orientation-vertical' => true, 
        'style:print-content' => true, 
        'style:repeat-content' => true, 
        'style:rotation-align' => true, 
        'style:rotation-angle' => true, 
        'style:shadow' => true, 
        'style:shrink-to-fit' => true, 
        'style:text-align-source' => true, 
        'style:vertical-align' => true,
        'style:writing-mode' => true,       
    );
    
    /**
     * table column style properties
     * 
     * @access public
     * @static
     * @var array
     */
    public static $tableColumnStyles = array(
        'fo:break-after' => true, 
        'fo:break-before' => true, 
        'style:column-width' => true, 
        'style:rel-column-width' => true,
        'style:use-optimal-column-width' => true,
    );
    
    /**
     * table row style properties
     * 
     * @access public
     * @static
     * @var array
     */
    public static $tableRowStyles = array(
        'fo:background-color' => true, 
        'fo:break-after' => true, 
        'fo:break-before' => true, 
        'fo:keep-together' => true, 
        'style:min-row-height' => true, 
        'style:row-height' => true,
        'style:use-optimal-row-height' => true,       
    );
    
    /**
     * text style properties
     * 
     * @access public
     * @static
     * @var array
     */
    public static $textStyles = array(
        'fo:background-color' => true,
        'fo:color' => true, 
        'fo:country' => true, 
        'fo:font-family' => true, 
        'fo:font-size' => true, 
        'fo:font-style' => true, 
        'fo:font-variant' => true, 
        'fo:font-weight' => true, 
        'fo:hyphenate' => true, 
        'fo:hyphenation-push-char-count' => true, 
        'fo:hyphenation-remain-char-count' => true, 
        'fo:language' => true, 
        'fo:letter-spacing' => true, 
        'fo:script' => true, 
        'fo:text-shadow' => true, 
        'fo:text-transform' => true, 
        'style:country-asian' => true, 
        'style:country-complex' => true, 
        'style:font-charset' => true, 
        'style:font-charset-asian' => true, 
        'style:font-charset-complex' => true, 
        'style:font-family-asian' => true, 
        'style:font-family-complex' => true, 
        'style:font-family-generic' => true, 
        'style:font-family-generic-asian' => true, 
        'style:font-family-generic-complex' => true, 
        'style:font-name' => true, 
        'style:font-name-asian' => true, 
        'style:font-name-complex' => true, 
        'style:font-pitch' => true, 
        'style:font-pitch-asian' => true, 
        'style:font-pitch-complex' => true, 
        'style:font-relief' => true, 
        'style:font-size-asian' => true, 
        'style:font-size-complex' => true, 
        'style:font-size-rel' => true, 
        'style:font-size-rel-asian' => true, 
        'style:font-size-rel-complex' => true, 
        'style:font-style-asian' => true, 
        'style:font-style-complex' => true, 
        'style:font-style-name' => true, 
        'style:font-style-name-asian' => true, 
        'style:font-style-name-complex' => true, 
        'style:font-weight-asian' => true, 
        'style:font-weight-complex' => true, 
        'style:language-asian' => true, 
        'style:language-complex' => true, 
        'style:letter-kerning' => true, 
        'style:rfc-language-tag' => true, 
        'style:rfc-language-tag-asian' => true, 
        'style:rfc-language-tag-complex' => true, 
        'style:script-asian' => true, 
        'style:script-complex' => true, 
        'style:script-type' => true, 
        'style:text-blinking' => true, 
        'style:text-combine' => true, 
        'style:text-combine-end-char' => true, 
        'style:text-combine-start-char' => true, 
        'style:text-emphasize' => true, 
        'style:text-line-through-color' => true, 
        'style:text-line-through-mode' => true, 
        'style:text-line-through-style' => true, 
        'style:text-line-through-text' => true, 
        'style:text-line-through-text-style' => true, 
        'style:text-line-through-type' => true, 
        'style:text-line-through-width' => true, 
        'style:text-outline' => true, 
        'style:text-overline-color' => true, 
        'style:text-overline-mode' => true, 
        'style:text-overline-style' => true, 
        'style:text-overline-type' => true, 
        'style:text-overline-width' => true, 
        'style:text-position' => true, 
        'style:text-rotation-angle' => true, 
        'style:text-rotation-scale' => true, 
        'style:text-scale' => true, 
        'style:text-underline-color' => true, 
        'style:text-underline-mode' => true, 
        'style:text-underline-style' => true, 
        'style:text-underline-type' => true, 
        'style:text-underline-width' => true, 
        'style:use-window-font-color' => true, 
        'text:condition' => true, 
        'text:display' => true,
    );
    
    /**
     * instance of the ParseCSSProperties class
     * @access private
     * @var ParseCSSProperties
     */
    private $_cssParser;
    
    /**
     * sides
     * @access private
     * @var array
     */
    private $_sides = array('top', 'right', 'bottom', 'left');
    
    /**
     * style node reference
     * @access public
     * @static
     * @var DOMNode
     */
    private $_styleNode;
    
    /**
     * Construct
     *
     * @param DOMNode $styleNode
     * @param DOMNode $mainStyleNode
     * @param DOMNode $styleStyleNode
     * @access public
     */
    public function __construct($styleNode, 
                                $mainStyleNode = NULL,
                                $styleStyleNode = NULL)
    {         
        $this->_styleNode = $styleNode;
        $this->_mainStyleNode = $mainStyleNode;
        $this->_styleStyleNode = $styleStyleNode;
        $this->_content = $this->_styleNode->ownerDocument;
        if($mainStyleNode !== NULL){
            $this->_styleDocument = $this->_mainStyleNode->ownerDocument;
        }
        $this->_cssParser = new ParseCSSProperties();
    }
    
    /**
     * Sets certain CSS props in standard ODF format
     *
     * @param array $style
     * @access public
     * @static
     */
    public static function ODFStyles($style)
    {
        $sides = array('top', 'right', 'bottom', 'left');
        foreach ($sides as $value){
            //parse the border properties
            if ( isset($style['border-' . $value . '-width'])
                 || isset($style['border-' . $value . '-style'])
                 || isset($style['border-' . $value . '-color'])) {
                $preStyle = array('1pt', 'solid', '#000000');
                if ( isset($style['border-' . $value . '-width'])){
                    $preStyle[0] = $style['border-' . $value . '-width'][0];
                    $preStyle[0] .= $style['border-' . $value . '-width'][1];
                    unset($style['border-' . $value . '-width']);
                    //set the required graphic style width property
                    $style['stroke-width'] = $preStyle[0];
                } 
                if ( isset($style['border-' . $value . '-style'])){
                    $preStyle[1] = $style['border-' . $value . '-style'];
                    unset($style['border-' . $value . '-style']);
                    //set the required graphic style  property
                    $style['stroke'] = $preStyle[1];
                }
                if ( isset($style['border-' . $value . '-color'])){
                    $preStyle[2] = $style['border-' . $value . '-color'];
                    unset($style['border-' . $value . '-color']);
                    //set the required graphic style color property
                    $style['stroke-color'] = $preStyle[2];
                }
                $style['border-' . $value] = implode(' ', $preStyle);
            }
            //parse the margin properties
            if ( isset($style['margin-' . $value]) ) {
                $style['margin-' . $value] = $style['margin-' . $value][0]
                                            .$style['margin-' . $value][1];
            }
            //parse the padding properties
            if ( isset($style['padding-' . $value]) ) {
                $style['padding-' . $value] = $style['padding-' . $value][0]
                                             .$style['padding-' . $value][1];
            }
            //parse the bottom, left, right and top properties
            if (isset($style[$value])) {
                $style[$value] = $style[$value][0] . $style[$value][1];
            }
        }
        //parse the column separator properties
        if ( isset($style['column-rule-width']) ) {
            $style['column-rule-width'] = $style['column-rule-width'][0]
                                         .$style['column-rule-width'][1];
        }
        if ( isset($style['font-size']) ) {
            $style['font-size'] = $style['font-size'][0]
                                 .$style['font-size'][1];
        }
        if ( isset($style['border-radius']) ) {
            $style['border-radius'] = $style['border-radius'][0]
                                     .$style['border-radius'][1];
        }
        if ( isset($style['height']) ) {
            $style['height'] = $style['height'][0]
                              .$style['height'][1];
        }
        if ( isset($style['min-height']) ) {
            $style['min-height'] = $style['min-height'][0]
                              .$style['min-height'][1];
        }
        if ( isset($style['max-height']) ) {
            $style['max-height'] = $style['max-height'][0]
                              .$style['max-height'][1];
        }
        if ( isset($style['width']) ) {
            $style['width'] = $style['width'][0]
                             .$style['width'][1];
        }
        if ( isset($style['min-width']) ) {
            $style['min-width'] = $style['min-width'][0]
                              .$style['min-width'][1];
        }
        if ( isset($style['max-width']) ) {
            $style['max-width'] = $style['max-width'][0]
                              .$style['max-width'][1];
        }
        if ( isset($style['line-height']) ) {
            $style['line-height'] = $style['line-height'][0]
                                   .$style['line-height'][1];
        }
        if ( isset($style['text-indent']) ) {
            $style['text-indent'] = $style['text-indent'][0]
                                   .$style['text-indent'][1];
        }
        if ( isset($style['letter-spacing']) ) {
            $style['letter-spacing'] = $style['letter-spacing'][0]
                                      .$style['letter-spacing'][1];
        }
        return $style;
    }
    
    /**
     * This method preprocess some CSS properties like border that
     * should be stated in shorthand notation for direct parsing
     * 
     * @param string $props
     * @return array
     * @access public
     */
    private function preprocessCSSStyles($props)
    {
        //parse the style string as CSS
        $CSSProps = $this->_cssParser->parseCSS($props);
        //normal props
        $style_0 = $CSSProps[0]; 
        //important props
        $style_1 = $CSSProps[1];
        $style = \array_merge($style_0, $style_1);
        //preprocess some special properties like borders that have to
        //be parsed in shorthand notation
        $style = self::ODFStyles($style);
        return $style;
    }
    
    /**
     * This method process the CSS type styles for the list
     * 
     * @param string $listId
     * @param string $target
     * @param array $style
     * @return void
     * @access public
     */
    public function processListStyle($listId, 
                                     $style, 
                                     $target = 'document')
    {                           
        //create the wrapping node
        $styleNode = $this->_content->createElement('text:list-style');
        $ns = 'urn:oasis:names:tc:opendocument:xmlns:style:1.0';
        $styleNode->setAttributeNS($ns, 'style:name', $listId);
        //run over the different level styles
        $listType = array();
        foreach($style as $level => $options){
            if (isset($options['style']) && \is_string($options['style'])) {
                $list[$level] = $this->preprocessCSSStyles($options['style']);
            } else if (isset($options['style']) 
                       && \is_array($options['style'])) {
                //the styles come from HTML and have already been parsed
                $list[$level] = $options['style'];
            } else {
                $list[$level] = array();
            }
            $listType[$level] = $options['type'];
        }
        $this->_generateListStyle($styleNode, $listType, $list);
        $this->_styleNode->appendChild($styleNode);
    }
    
    /**
     * This method process the CSS type styles to create the corresponding
     * style nodes in the ODF document
     * 
     * @param string $type
     * @param string $target
     * @param string $styleId
     * @param mixed $style
     * @return void
     * @access public
     */
    public function processSingleStyle($type, 
                                       $styleId, 
                                       $style, 
                                       $target = 'document')
    {                    
        if ($type == 'DOMNode') {
            //this case may apply to styles directly generated by the package
            //like in the case, for example, of date styles
            if ($target == 'header-footer') {
                $this->_mainStyleNode->appendChild($style);
            } else if ($target == 'style')  {
                $this->_styleStyleNode->appendChild($style);
            } else {
                $this->_styleNode->appendChild($style);
            }
            
        } else {
            if (self::$styleFamilies[$type]) {
                if ($target == 'header-footer' || $target == 'style') {
                    $ns = 'urn:oasis:names:tc:opendocument:xmlns:style:1.0';
                    $styleNode = $this->_styleDocument
                                      ->createElementNS($ns, 'style:style');
                } else {
                    $ns = 'urn:oasis:names:tc:opendocument:xmlns:style:1.0';
                    $styleNode = $this->_content
                                      ->createElementNS($ns, 'style:style');
                }
                $ns = 'urn:oasis:names:tc:opendocument:xmlns:style:1.0';
                $styleNode->setAttributeNS($ns, 'style:name', $styleId);
                $styleNode->setAttributeNS($ns, 
                                       'style:family', 
                                       self::$styleFamilies[$type]);
            } else if ($type == 'text:list') {
                if ($target == 'header-footer' || $target == 'style') {
                    $styleNode = $this->_styleDocument
                                      ->createElement('text:list-style');
                } else {
                    $styleNode = $this->_content
                                      ->createElement('text:list-style');
                }
                $ns = 'urn:oasis:names:tc:opendocument:xmlns:style:1.0';
                $styleNode->setAttributeNS($ns, 'style:name', $styleId);
            }
            //Process the styles if they are not yet parsed into an array
            if (\is_array($style)) {
                //preprocess some special properties like borders that have to
                //be parsed in shorthand notation
                $style = self::ODFStyles($style);
            } else {
                $style = $this->preprocessCSSStyles($style);
            }
            //check por parent styles
            if (isset($style['parentStyleId'])) {
                $styleNode->setAttributeNS($ns, 
                                           'style:class', 
                                           $style['parentStyleId']);
            }
            //generate now the styles depending on the data and type
            //Note: we have to generate these styles even if empty 
            //for compatibility with MS Word
            if ($type == 'text:p' 
                || $type == 'text:h'
                || $type == 'text:list-item') {
                $this->_generateParagraphStyle($styleNode, $style);
                $this->_generateTextStyle($styleNode, $style);
            } else if ($type == 'text:span' || $type == 'text:a') {
                $this->_generateTextStyle($styleNode, $style);
            } else if ($type == 'draw:frame' || $type == 'draw:control') {
                //include a generic default empty parent style for RTF rendering
                $styleNode->setAttribute('style:parent-style-name',
                                         'defaultGraphicsStyle');
                $this->_generateGraphicStyle($styleNode, $style);
                unset($style['height']);
                unset($style['min-height']);
                unset($style['width']);
                $this->_generateTextStyle($styleNode, $style);
            } else if ($type == 'text:list') {
                //check if we can safely remove this
                $this->_generateListItemStyle($styleNode, $style);
            } else if ($type == 'chart:chart') {
                //we include the target option to take care of some chart styles
                //for Word output format
                if ($target == 'chart') {
                    $nodeStyleChart = $this->_generateGraphicStyle($styleNode, 
                                                                   $style, 
                                                                   $target);
                    return $nodeStyleChart;
                } else {
                    $this->_generateGraphicStyle($styleNode, $style);
                }
                $this->_generateTextStyle($styleNode, $style);
            } else if ($type == 'table:table') {
                $this->_generateTableStyle($styleNode, $style);
                //The table border styles can not be directly set in the ODF
                //standard so we have to further process them so they are
                //applied to the corresponding table-cells
                if (isset($style['border-top'])
                    ||isset($style['border-right'])
                    ||isset($style['border-bottom'])
                    ||isset($style['border-left'])){
                    //parse the cell childs
                    $options = array();
                    $options['tag'] = 'table:table';
                    $options['idType'] = 'styleId';
                    $options['id'] = $styleId;
                    $options['style'] = $style;
                    $options ['dom'] = $styleNode->ownerDocument;
                    Docxpresso\CreateDocument::parseTableCellChilds($options);                                                           
                }
                unset($style['border-top']);
                unset($style['border-right']);
                unset($style['border-bottom']);
                unset($style['border-left']);
                unset($style['width']);
                $this->_generateTextStyle($styleNode, $style);
            } else if ($type == 'table:table-row') {
                $this->_generateTableRowStyle($styleNode, $style);
                if (isset($style['border-top'])
                    ||isset($style['border-right'])
                    ||isset($style['border-bottom'])
                    ||isset($style['border-left'])){
                    //parse the cell childs
                    $options = array();
                    $options['tag'] = 'table:table-row';
                    $options['idType'] = 'styleId';
                    $options['id'] = $styleId;
                    $options['style'] = $style;
                    $options ['dom'] = $styleNode->ownerDocument;
                    Docxpresso\CreateDocument::parseRowCellChilds($options);                                                           
                }
                unset($style['border-top']);
                unset($style['border-right']);
                unset($style['border-bottom']);
                unset($style['border-left']);
                unset($style['height']);
                unset($style['min-height']);
                $this->_generateParagraphStyle($styleNode, $style);
                $this->_generateTextStyle($styleNode, $style);
            } else if ($type == 'table:table-cell') {
                unset($style['width']);
                $this->_generateTableCellStyle($styleNode, $style);
                //we unset certain styles so they are not also included in
                //the pargraph and text styles. This is only needed for correct
                //rendering in Word
                unset($style['border-top']);
                unset($style['border-right']);
                unset($style['border-bottom']);
                unset($style['border-left']);
                unset($style['vertical-align']);
                if (Docxpresso\createDocument::$format == 'doc') {
                    unset($style['background-color']);
                }
                $this->_generateParagraphStyle($styleNode, $style);
                $this->_generateTextStyle($styleNode, $style);
            } else if ($type == 'table:table-column') {
                $this->_generateTableColumnStyle($styleNode, $style);
                $this->_generateParagraphStyle($styleNode, $style);
                $this->_generateTextStyle($styleNode, $style);
            }
            //Depending on the target (document or header-footer) we choose
            //a different node for the insertions
            if ($target == 'header-footer') {
                $this->_mainStyleNode->appendChild($styleNode);
            } else if ($target == 'style') {
                $this->_styleStyleNode->appendChild($styleNode);
            } else {
                $this->_styleNode->appendChild($styleNode);
            }
        }
    }
      
    /**
     * This method process the CSS type styles to create the corresponding
     * style nodes in the ODF document
     * 
     * @return void
     * @access private
     */
    public function processStyles()
    {
        foreach(Docxpresso\CreateDocument::$automaticStyles as $key => $value){
            //notice that we do not directly use the value because the array may 
            //be updated as we process the styles
            $this->processSingleStyle(
                    Docxpresso\CreateDocument::$styleTypes[$key],
                    $key, 
                    Docxpresso\CreateDocument::$automaticStyles[$key],
                    Docxpresso\CreateDocument::$styleTarget[$key]
                    );
        }
        foreach(Docxpresso\CreateDocument::$list as $key => $value){
            $this->processListStyle($key, $value);
        }
    }
    
    /**
     * This method generates the style node associated with a graphic
     * 
     * @param DOMNode $styleNode
     * @param array $style
     * @param string $target
     * @return void or DOMNode for chart targets
     * @access private
     */
    private function _generateGraphicStyle($styleNode, $style, $target = '')
    {
        $graphicProps = $styleNode->ownerDocument
                               ->createElement('style:graphic-properties');
        //check if the frame is inline, floating or absolutely positioned
        $graphicProps->setAttribute('style:run-through', 'foreground');
        $graphicProps->setAttribute('style:wrap', 'parallel');
        if (isset ($style['float'])
            && ($style['float'] == 'left' 
                || $style['float'] == 'right'
                || $style['float'] == 'center')
            ) {
            $graphicProps->setAttribute('style:vertical-pos', 'from-top');
            $graphicProps->setAttribute('style:vertical-rel', 'line');
            $graphicProps->setAttribute('style:horizontal-pos', 
                                        $style['float']);
            $graphicProps->setAttribute('style:horizontal-rel', 'page-content');
        } else if (isset ($style['position']) 
                   && $style['position'] == 'absolute') {
            $graphicProps->setAttribute('style:vertical-pos', 'from-top');
            $graphicProps->setAttribute('style:vertical-rel', 'page-content');
            $graphicProps->setAttribute('style:horizontal-rel', 'page-content');
        } else if (isset ($style['position']) 
                   && $style['position'] == 'relative') {
            $graphicProps->setAttribute('style:vertical-pos', 'from-top');
            $graphicProps->setAttribute('style:vertical-rel', 'paragraph');
            $graphicProps->setAttribute('style:horizontal-rel', 'page-content');
        } else {
            //There is not a more precise way to position an inline frame
            $verticalPos = array('bottom' => true, 
                                 'middle' => true, 
                                 'top' => true);
            if ( !isset($style['vertical-align'])
                 ||!isset($verticalPos[$style['vertical-align']])) {
                $style['vertical-align'] = 'bottom';
            } else {
                $graphicProps->setAttribute('style:vertical-pos', 
                                            $style['vertical-align']);
                //This spoils the rendering in LibreOffice, we have to use it 
                //only when really needed, i.e. form elements
                $graphicProps->setAttribute('draw:textarea-vertical-align', 
                                            'middle');
            }
            $graphicProps->setAttribute('style:vertical-rel', 'line');
        }
        //For proper rendering of frames in Word we need to set the
        //draw:auto-growth-height to true
        $graphicProps->setAttribute('draw:auto-grow-height', 'true');
        $graphicProps->setAttribute('draw:auto-grow-width', 'true');
        //do some cleaning of properties that have already been parsed
        //in the corresponding methods
        unset($style['height']);
        unset($style['width']);
        unset($style['vertical-align']);
        unset($style['z-index']);
        if (isset($style['border-top'])) {
            $style['border'] = $style['border-top'];
        }
        if (!isset($style['background-color'])) {
            $style['background-color'] = 'transparent';
        }
        //parse the remaining props
        foreach($style as $key => $value) {
            //check if it is a simple property
            if ( isset(self::$CSSODF[$key])
                 && \is_string(self::$CSSODF[$key])) {
                //check if it is a graphic style
                if ( isset(self::$graphicStyles[self::$CSSODF[$key]])) {
                    $graphicProps->setAttribute(self::$CSSODF[$key],
                                             $value);
                }            
            }else if ( isset(self::$CSSODF[$key])
                 && \is_array(self::$CSSODF[$key])) {
                $method = self::$CSSODF[$key]['method'];
                $this->$method($graphicProps, $style, $key, $value);
            }
        }
        if ($target == 'chart') {
            return $graphicProps;
        } else {
            $styleNode->appendChild($graphicProps); 
        }
    }
    
    /**
     * This method generates the style node associated with a list
     * 
     * @param DOMNode $styleNode
     * @param array $listType
     * @param array $listStyle
     * @return void
     * @access private
     */
    private function _generateListStyle($styleNode, $listType, $listStyle)
    {
        $step = 0.635;
        $listStyleTypes = array( 'disc'                         => '•',
                                 'circle'                       => '◦',
                                 'decimal'                      => '1',
                                 'decimal-leading-zero'         => '1',
                                 'decimal-extended'             => '1',
                                 'lower-alpha'                  => 'a',
                                 'lower-greek'                  => 'i',
                                 'lower-latin'                  => 'a',
                                 'lower-roman'                  => 'i',
                                 'none'                         => '',
                                 'square'                       => '▪',
                                 'upper-alpha'                  => 'A',
                                 'upper-latin'                  => 'A',
                                 'upper-roman'                  => 'I',
                                );
        $default = array('unordered' => array('•',
                                              '◦',
                                              '▪',
                                              '•',
                                              '◦',
                                              '▪',
                                              '•',
                                              '◦',
                                              '▪',
                                              ),
                         'ordered'   => array('1',
                                              'a',
                                              'i',
                                              '1',
                                              'a',
                                              'i',
                                              '1',
                                              'a',
                                              'i',
                                              ),
                        );

        foreach($listStyle as $key => $value) {
            if ($listType[$key] == 'unordered') {
                $listLevel = $styleNode->ownerDocument
                             ->createElement('text:list-level-style-bullet');
                $listLevel->setAttribute('text:level', $key + 1);
                $listLevel->setAttribute('text:style-name', 'docxpresso_bullet');
                if (isset($value['list-style-type'])
                    && isset($listStyleTypes[$value['list-style-type']])) {
                    $bullet = $listStyleTypes[$value['list-style-type']];
                } else {
                    $bullet =$default['unordered'][$key];
                }
                $listLevel->setAttribute('text:bullet-char', $bullet);
            } else if ($listType[$key] == 'ordered') {
                $listLevel = $styleNode->ownerDocument
                             ->createElement('text:list-level-style-number');
                $listLevel->setAttribute('text:level', $key + 1);
                $listLevel->setAttribute('text:style-name', 'docxpresso_number');
                if (isset($value['list-style-type'])
                    && isset($listStyleTypes[$value['list-style-type']])) {
                    $numFormat = $listStyleTypes[$value['list-style-type']];
                    if ($value['list-style-type'] == 'decimal-extended') {
                        $listLevel->setAttribute('text:display-levels', 
                                                 $key + 1);
                    }
                } else {
                    $numFormat =$default['ordered'][$key];
                }
                $listLevel->setAttribute('style:num-format', $numFormat);
                $listLevel->setAttribute('style:num-suffix', '.');
            } else if ($listType[$key] == 'image') {
                $listLevel = $styleNode->ownerDocument
                             ->createElement('textlist-level-style-image');
                //TODO: list-level-style-image not supported yet
                //sample XML
                /*<text:list-level-style-image text:level="1" 
                      xlink:href="Pictures/100006A80000018B0000018BA5A6288D.svg" 
                      xlink:type="simple" 
                      xlink:show="embed" 
                      xlink:actuate="onLoad">
                    <style:list-level-properties 
                      text:list-level-position-and-space-mode="label-alignment" 
                      style:vertical-pos="middle" 
                      style:vertical-rel="line" 
                      fo:width="0.395cm" 
                      fo:height="0.395cm">
                        <style:list-level-label-alignment 
                         text:label-followed-by="listtab" 
                         text:list-tab-stop-position="1.27cm" 
                         fo:text-indent="-0.635cm" 
                         fo:margin-left="1.27cm" /> 
                    </style:list-level-properties>
                </text:list-level-style-image>*/
            }
            //list level properties node
            $llp = $styleNode->ownerDocument
                             ->createElement('style:list-level-properties');
            $llp->setAttribute('text:list-level-position-and-space-mode',
                               'label-alignment');
            //list-level-label-alignment node
            $lla = $styleNode->ownerDocument
                             ->createElement('style:list-level-label-alignment');
            $lla->setAttribute('text:label-followed-by', 'listtab');
            $lla->setAttribute('fo:text-indent', '-' . $step . 'cm');
            $tab = 1.27 + $step * $key;
            $tab = $tab . 'cm';
            $lla->setAttribute('text:list-tab-stop-position', $tab);
            $marginLeft = 1.27 + $step * $key;
            if (isset($value['margin-left'])) {
                if (\is_array($value['margin-left'])) {
                    $value['margin-left'] = \implode($value['margin-left']);
                }
                $marginLeft += commons\Utilities::convertUnits('cm',
                                                        $value['margin-left']);
                }
            $marginLeft = $marginLeft . 'cm';
            $lla->setAttribute('fo:margin-left', $marginLeft);
            $llp->appendChild($lla);
            $listLevel->appendChild($llp);
            $styleNode->appendChild($listLevel);
        }
    }
    
    /**
     * This method generates the style node associated with a paragraph
     * 
     * @param DOMNode $styleNode
     * @param array $style
     * @return void
     * @access private
     */
    private function _generateParagraphStyle($styleNode, $style)
    {
        $this->_genericStyle($styleNode, 
                              $style,
                              'style:paragraph-properties',
                              self::$pStyles);
    }
    
    /**
     * This method generates the style node associated with a table
     * 
     * @param DOMNode $styleNode
     * @param array $style
     * @return void
     * @access private
     */
    private function _generateTableStyle($styleNode, $style)
    {
        $this->_genericStyle($styleNode, 
                              $style,
                              'style:table-properties',
                              self::$tableStyles);
    }
    
    /**
     * This method generates the style node associated with a table cell
     * 
     * @param DOMNode $styleNode
     * @param array $style
     * @return void
     * @access private
     */
    private function _generateTableCellStyle($styleNode, $style)
    {
        $this->_genericStyle($styleNode, 
                              $style,
                              'style:table-cell-properties',
                              self::$tableCellStyles);
    }
    
    /**
     * This method generates the style node associated with a table column
     * 
     * @param DOMNode $styleNode
     * @param array $style
     * @return void
     * @access private
     */
    private function _generateTableColumnStyle($styleNode, $style)
    {
        $this->_genericStyle($styleNode, 
                              $style,
                              'style:table-column-properties',
                              self::$tableColumnStyles);
    }
    
    /**
     * This method generates the style node associated with a table row
     * 
     * @param DOMNode $styleNode
     * @param array $style
     * @return void
     * @access private
     */
    private function _generateTableRowStyle($styleNode, $style)
    {
        $this->_genericStyle($styleNode, 
                              $style,
                              'style:table-row-properties',
                              self::$tableRowStyles);
    }
    
    /**
     * This method generates the style node associated with plain text
     * 
     * @param DOMNode $styleNode
     * @param array $style
     * @return void
     * @access private
     */
    private function _generateTextStyle($styleNode, $style)
    {
        $this->_genericStyle($styleNode, 
                              $style,
                              'style:text-properties',
                              self::$textStyles);
    }
    
    /**
     * This method generates the style node associated with a paragraph
     * 
     * @param DOMNode $styleNode
     * @param array $style
     * @param string $styleName
     * @param array $styleArray
     * @return void
     * @access private
     */
    private function _genericStyle($styleNode, $style, $styleName, $styleArray)
    {
        //define an array of 'ODF block type' tags
        $ODFBlock = array('style:table-properties' => true);
        $ns = 'urn:oasis:names:tc:opendocument:xmlns:style:1.0';
        $props = $styleNode->ownerDocument
                           ->createElementNS($ns, $styleName);
        $propCounter = 0;
        foreach($style as $key => $value) {
            //check if it is a simple property
            if ( isset(self::$CSSODF[$key])
                 && \is_string(self::$CSSODF[$key])
                 && isset($styleArray[self::$CSSODF[$key]])) {
                $props->setAttribute(self::$CSSODF[$key], $value); 
                $propCounter++;
            } else if ( isset(self::$CSSODF[$key])
                        && \is_array(self::$CSSODF[$key])) {
                $method = self::$CSSODF[$key]['method'];
                $this->$method($props, $style, $key, $value);
                $propCounter++;
            }
        }
        //Take into account some special cases        
        if ($styleName == 'style:table-row-properties'
            && isset($style['min-height'])){
            if (\is_array($style['min-height'])) {
                $minHeight = \implode($style['min-height']);
            } else {
                $minHeight = $style['min-height'];
            }
            $props->setAttribute('style:min-row-height', $minHeight);
            $propCounter++;
        }
        if (isset($ODFBlock[$styleName])) {
            //if the ODF style attribute align is not explicitely set Open and 
            //Libre Office choose by default 'margins' that set the table to
            //go from margin to margin of the containing page
            if (isset($style['margin-left']) 
                && $style['margin-left'] == 'auto'){
                $props->setAttribute('table:align', 'center');
                $propCounter++;
            } else {
                $props->setAttribute('table:align', 'left');
                $propCounter++;
            }
        }
        //parse background properties
        $bgProps = $styleNode->ownerDocument
                               ->createElement('style:background-image');
        $bgPropsCounter = 0;
        if (isset($style['background-image'])) {
            $style['background-image'] = 
            commons\Utilities::getBackgroundImage($style['background-image']);
        }
        foreach($style as $key => $value) {
            //check if it is a simple property
            if ( isset(self::$CSSODF[$key])
                 && \is_string(self::$CSSODF[$key])
                 && isset(self::$bgStyles[self::$CSSODF[$key]])) {
                $bgProps->setAttribute(self::$CSSODF[$key], $value);
                $bgPropsCounter++;
                $propCounter++;
            } 
        }
        
        if ($bgPropsCounter > 0) {
            $props->appendChild($bgProps);
        }
        if ($propCounter > 0) {
            $styleNode->appendChild($props);
        }
    }
    
    /**
     * Parses the different CSS breaks in ODF style
     * 
     * @param DOMNode $node
     * @param string $style
     * @param string $attribute
     * @param string $value
     * @return void
     * @access private
     */
    private function _parseBreaks($node, $style, $attribute, $value)
    {
        if ($attribute == 'break-inside' || $attribute == 'page-break-inside') {
            $name = $node->nodeName;
            if ($value == 'avoid'
                || $value == 'avoid-page'
                || $value == 'avoid-column') {
                if ($name == 'style:table-properties'){
                    $node->setAttribute('style:may-break-between-rows', 
                                        'false');
                } else {
                    $node->setAttribute('fo:keep-together', 'always');
                }
            }
        } else if ($attribute == 'break-after' 
                   || $attribute == 'page-break-ater') {
            if ($value == 'avoid') {
                $node->setAttribute('fo:keep-with-next', 'always');
            } else if ($value == 'column') {
                $node->setAttribute('fo:break-after', 'column');
            } else {
                $node->setAttribute('fo:break-after', 'page');
            }
        }
    }
    
    /**
     * Parses the different CSS list properties in ODF style
     * 
     * @param DOMNode $node
     * @param string $attribute
     * @param string $value
     * @return void
     * @access private
     */
    private function _parseList($node, $attribute, $value)
    {
        //The list styles are parsed following a different procedure
        //see processListStyle
    }
    /**
     * Parses the different CSS text decoration styles properties in ODF style
     * 
     * @param DOMNode $node
     * @param string $style
     * @param string $attribute
     * @param string $value
     * @return void
     * @access private
     */
    private function _parseTextDecoration($node, $style, $attribute, $value)
    {
        if(isset($style['text-decoration']) 
            && $style['text-decoration'] == 'none') {
            $node->setAttribute('style:text-underline-type', 'none');
            $node->setAttribute('style:text-overline-type', 'none');
            $node->setAttribute('style:text-line-through-type', 'none');
            return;
        } else if (isset($style['text-decoration']) 
            && $style['text-decoration'] == 'overline'){
            $decoration = 'overline';
        } else if (isset($style['text-decoration']) 
            && $style['text-decoration'] == 'line-through'){
            $decoration = 'line-through';
        } else {
            $decoration = 'underline';
        }
        if ($attribute == 'text-decoration' && $decoration != 'none') {
            if (isset($style['text-decoration-style'])
                && $style['text-decoration-style'] == 'double') {
                $node->setAttribute('style:text-' . $decoration . '-type', 
                                    'double');
            } else {
                $node->setAttribute('style:text-' . $decoration . '-type', 
                                    'single');
            }
        }
        if ($attribute == 'text-decoration-color') {
            $node->setAttribute('style:text-' . $decoration . '-color', 
                                 $value);
        }
        if ($attribute == 'text-decoration-style') {
            $node->setAttribute('style:text-' . $decoration . '-width', $value);
        }
        if ($attribute == 'text-decoration-style') {
            if (isset(css\Resources::$lineStyles[$value])) {
            $node->setAttribute('style:text-' . $decoration . '-style', 
                                css\Resources::$lineStyles[$value]);
            }
        }
    }
    
    /**
     * Parses the different CSS vertical align property for text and
     * paragraph properties
     * 
     * @param DOMNode $node
     * @param string $style
     * @param string $attribute
     * @param string $value
     * @return void
     * @access private
     */
    private function _parseVerticalAlign($node, $style, $attribute, $value)
    {
        $name = $node->nodeName;
        if ($name == 'style:paragraph-properties'){
            $node->setAttribute('style:vertical-align', $value);
        } else if ($name == 'style:text-properties') {
            $node->setAttribute('style:text-position', $value);
        } else if ($name == 'style:table-cell-properties'){
            $node->setAttribute('style:vertical-align', $value);
        }
    }
    
    /**
     * Parses the height in ODF style
     * 
     * @param DOMNode $node
     * @param string $style
     * @param string $attribute
     * @param string $value
     * @return void
     * @access private
     */
    private function _parseHeight($node, $style, $attribute, $value)
    {
        $name = $node->nodeName;
        if ($name == 'style:table-properties') {
            $node->setAttribute('style:height', $value);
        } else if ($name == 'style:table-row-properties') {
            $node->setAttribute('style:row-height', $value);
        } else {
            $node->setAttribute('svg:height', $value); 
        }
    }
    
    /**
     * Parses the width in ODF style
     * 
     * @param DOMNode $node
     * @param string $style
     * @param string $attribute
     * @param string $value
     * @return void
     * @access private
     */
    private function _parseWidth($node, $style, $attribute, $value)
    {
        $name = $node->nodeName;
        if ($name == 'style:table-properties') {
            $node->setAttribute('style:width', $value);
        } else {
            $node->setAttribute('svg:width', $value); 
        }
    }
    
}