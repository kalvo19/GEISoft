<?php

/**
 *  CSS Resources
 *
 * @copyright  Copyright (c) 2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Parser\CSS;

/**
 * This class stores certain resources needed for the parsing of CSS data
 *
 * @package    Docxpresso
 * @subpackage Parser
 */

class Resources
{
    /**
     * CSS colors as string
     * 
     * @access public
     * @static
     * @var array
     */
    public static $CSScolors = array(
        'aliceblue' => '#F0F8FF',
        'antiquewhite' => '#FAEBD7',
        'aqua' => '#00FFFF',
        'aquamarine' => '#7FFFD4',
        'azure' => '#F0FFFF',
        'beige' => '#F5F5DC',
        'bisque' => '#FFE4C4',
        'black' => '#000000',
        'blanchedalmond' => '#FFEBCD',
        'blue' => '#0000FF',
        'blueviolet' => '#8A2BE2',
        'brown' => '#A52A2A',
        'burlywood' => '#DEB887',
        'cadetblue' => '#5F9EA0',
        'chartreuse' => '#7FFF00',
        'chocolate' => '#D2691E',
        'coral' => '#FF7F50',
        'cornflowerblue' => '#6495ED',
        'cornsilk' => '#FFF8DC',
        'crimson' => '#DC143C',
        'cyan' => '#00FFFF',
        'darkblue' => '#00008B',
        'darkcyan' => '#008B8B',
        'darkgoldenrod' => '#B8860B',
        'darkgray' => '#A9A9A9',
        'darkgrey' => '#A9A9A9',
        'darkgreen' => '#006400',
        'darkkhaki' => '#BDB76B',
        'darkmagenta' => '#8B008B',
        'darkolivegreen' => '#556B2F',
        'darkorange' => '#FF8C00',
        'darkorchid' => '#9932CC',
        'darkred' => '#8B0000',
        'darksalmon' => '#E9967A',
        'darkseagreen' => '#8FBC8F',
        'darkslateblue' => '#483D8B',
        'darkslategray' => '#2F4F4F',
        'darkslategrey' => '#2F4F4F',
        'darkturquoise' => '#00CED1',
        'darkviolet' => '#9400D3',
        'deeppink' => '#FF1493',
        'deepskyblue' => '#00BFFF',
        'dimgray' => '#696969',
        'dimgrey' => '#696969',
        'dodgerblue' => '#1E90FF',
        'firebrick' => '#B22222',
        'floralwhite' => '#FFFAF0',
        'forestgreen' => '#228B22',
        'fuchsia' => '#FF00FF',
        'gainsboro' => '#DCDCDC',
        'ghostwhite' => '#F8F8FF',
        'gold' => '#FFD700',
        'goldenrod' => '#DAA520',
        'gray' => '#808080',
        'grey' => '#808080',
        'green' => '#008000',
        'greenyellow' => '#ADFF2F',
        'honeydew' => '#F0FFF0',
        'hotpink' => '#FF69B4',
        'indianred' => '#CD5C5C',
        'indigo' => '#4B0082',
        'ivory' => '#FFFFF0',
        'khaki' => '#F0E68C',
        'lavender' => '#E6E6FA',
        'lavenderblush' => '#FFF0F5',
        'lawngreen' => '#7CFC00',
        'lemonchiffon' => '#FFFACD',
        'lightblue' => '#ADD8E6',
        'lightcoral' => '#F08080',
        'lightcyan' => '#E0FFFF',
        'lightgoldenrodyellow' => '#FAFAD2',
        'lightgray' => '#D3D3D3',
        'lightgrey' => '#D3D3D3',
        'lightgreen' => '#90EE90',
        'lightpink' => '#FFB6C1',
        'lightsalmon' => '#FFA07A',
        'lightseagreen' => '#20B2AA',
        'lightskyblue' => '#87CEFA',
        'lightslategray' => '#778899',
        'lightslategrey' => '#778899',
        'lightsteelblue' => '#B0C4DE',
        'lightyellow' => '#FFFFE0',
        'lime' => '#00FF00',
        'limegreen' => '#32CD32',
        'linen' => '#FAF0E6',
        'magenta' => '#FF00FF',
        'maroon' => '#800000',
        'mediumaquamarine' => '#66CDAA',
        'mediumblue' => '#0000CD',
        'mediumorchid' => '#BA55D3',
        'mediumpurple' => '#9370D8',
        'mediumseagreen' => '#3CB371',
        'mediumslateblue' => '#7B68EE',
        'mediumspringgreen' => '#00FA9A',
        'mediumturquoise' => '#48D1CC',
        'mediumvioletred' => '#C71585',
        'midnightblue' => '#191970',
        'mintcream' => '#F5FFFA',
        'mistyrose' => '#FFE4E1',
        'moccasin' => '#FFE4B5',
        'navajowhite' => '#FFDEAD',
        'navy' => '#000080',
        'oldlace' => '#FDF5E6',
        'olive' => '#808000',
        'olivedrab' => '#6B8E23',
        'orange' => '#FFA500',
        'orangered' => '#FF4500',
        'orchid' => '#DA70D6',
        'palegoldenrod' => '#EEE8AA',
        'palegreen' => '#98FB98',
        'paleturquoise' => '#AFEEEE',
        'palevioletRed' => '#D87093',
        'papayawhip' => '#FFEFD5',
        'peachpuff' => '#FFDAB9',
        'peru' => '#CD853F',
        'pink' => '#FFC0CB',
        'plum' => '#DDA0DD',
        'powderblue' => '#B0E0E6',
        'purple' => '#800080',
        'red' => '#FF0000',
        'rosybrown' => '#BC8F8F',
        'royalblue' => '#4169E1',
        'raddlebrown' => '#8B4513',
        'salmon' => '#FA8072',
        'sandybrown' => '#F4A460',
        'seagreen' => '#2E8B57',
        'seashell' => '#FFF5EE',
        'sienna' => '#A0522D',
        'silver' => '#C0C0C0',
        'skyblue' => '#87CEEB',
        'slateblue' => '#6A5ACD',
        'slategray' => '#708090',
        'slategrey' => '#708090',
        'snow' => '#FFFAFA',
        'springgreen' => '#00FF7F',
        'steelblue' => '#4682B4',
        'tan' => '#D2B48C',
        'teal' => '#008080',
        'thistle' => '#D8BFD8',
        'tomato' => '#FF6347',
        'turquoise' => '#40E0D0',
        'violet' => '#EE82EE',
        'wheat' => '#F5DEB3',
        'white' => '#FFFFFF',
        'whitesmoke' => '#F5F5F5',
        'yellow' => '#FFFF00',
        'yellowgreen' => '#9ACD32'
    );
    /**
     * The parsed CSS properties are set to true
     * 
     * @access public
     * @static
     * @var array
     */
    public static $CSSProperties = array(
        //Color properties
        'color' => true, 
        'opacity' => true,
        //Background and Border Properties
        'background' => true,
        //'background-attachment' => false,
        'background-color' => true,
        'background-image' => true,
        'background-position' => true,
        'background-repeat' => true,
        //'background-clip' => false,
        //'background-origin' => false,
        //'background-size' => false,
        'border' => true,
        'border-bottom' => true,
        'border-bottom-color' => true,
        //'border-bottom-left-radius' => false, 
        //'border-bottom-right-radius' => false,
        'border-bottom-style' => true,
        'border-bottom-width' => true,
        'border-color' => true,
        //'border-image' => false,
        //'border-image-outset' => false,
        //'border-image-repeat' => false,
        //'border-image-slice' => false,
        //'border-image-source' => false,
        //'border-image-width' => false,
        'border-left' => true, 
        'border-left-color' => true,
        'border-left-style' => true,
        'border-left-width' => true,
        'border-radius' => true,
        'border-right' => true,
        'border-right-color' => true,
        'border-right-style' => true,
        'border-right-width' => true,
        'border-style' => true,
        'border-top' => true,
        'border-top-color' => true,
        //'border-top-left-radius' => false,
        //'border-top-right-radius' => false,
        'border-top-style' => true,
        'border-top-width' => true,
        'border-width' => true,
        //'box-decoration-break' => false, 
        'box-shadow' => true,
        //Basic Box Properties
        'bottom' => true,
        'clear' => true,
        'clip' => true,
        'display' => true,
        'float' => true,
        'height' => true,
        'left' => true,
        //'overflow' => false,
        //'overflow-x' => false, 
        //'overflow-y' => false,
        'padding' => true,
        'padding-bottom' => true,
        'padding-left' => true,
        'padding-right' => true,
        'padding-top' => true, 
        'position' => true,
        'right' => true,
        'top' => true,
        'visibility' => true,
        'width' => true,
        'vertical-align' => true,
        'z-index' => true,
        //Flexible Box Layout
        //'align-content' => false,
        //'align-items' => false,
        //'align-self' => false,
        //'flex' => false,
        //'flex-basis' => false,
        //'flex-direction' => false,
        //'flex-flow' => false,
        //'flex-grow' => false,
        //'flex-shrink' => false,
        //'flex-wrap' => false,
        //'justify-content' => false, 
        'margin' => true,
        'margin-bottom' => true,
        'margin-left' => true,
        'margin-right' => true,
        'margin-top' => true,
        'max-height' => true,
        'max-width' => true,
        'min-height' => true,
        'min-width' => true,
        //'order' => false,
        //Text Properties
        //'hanging-punctuation' => false,
        'hyphens' => true,
        'letter-spacing' => true,
        'line-break' => true,
        'line-height' => true,
        //'overflow-wrap' => false,
        //'tab-size' => false,
        'text-align' => true,
        'text-align-last' => true, 
        'text-indent' => true,
        //'text-justify' => false,
        'text-transform' => true,
        //'white-space' => false,
        //'word-break' => false,
        //'word-spacing' => false,
        //'word-wrap' => false,
        ///Text Decoration Properties
        'text-decoration' => true,
        'text-decoration-color' => true,
        'text-decoration-line' => true,
        'text-decoration-style' => true,
        'text-shadow' => true,
        //'text-underline-position' => false,
        //Font Properties
        'font' => true,
        'font-family' => true,
        //'font-feature-setting' => false,
        //'@font-feature-values' => false,
        'font-kerning' => true, //Since CSS3
        //'font-language-override' => false,
        //'font-synthesis' => false,
        //'font-variant-alternates' => false,
        //'font-variant-caps' => false,
        //'font-variant-east-asian' => false,
        //'font-variant-ligatures' => false,
        //'font-variant-numeric' => false,
        //'font-variant-position' => false,
        'font-size' => true,
        'font-style' => true,
        'font-variant' => true,
        'font-weight' => true,
        //'@font-face' => false,
        //'font-size-adjust' => false,
        //'font-stretch' => false,
        //Writing Modes Properties
        'direction' => true,
        //'text-orientation' => false,
        //'text-combine-horizontal' => false,
        //'unicode-bidi' => false, 
        'writing-mode' => true,
        //Table Properties
        'border-collapse' => true,
        'border-spacing' => true,
        //'caption-side' => false,
        'empty-cells' => true,
        'table-layout' => true,
        //Lists and Counters Properties
        'counter-increment' => true,
        'counter-reset' => true,
        'list-style' => true,
        'list-style-image' => true,
        //'list-style-position' => false,
        'list-style-type' => true,
        //Animation Properties
        //'@keyframes' => false,
        //'animation' => false,
        //'animation-delay' => false,
        //'animation-direction' => false,
        //'animation-duration' => false,
        //'animation-fill-mode' => false,
        //'animation-iteration-count' => false,
        //'animation-name' => false,
        //'animation-timing-function' => false,
        //'animation-play-state' => false,
        //Transform Properties
        //'backface-visibility' => false,
        //'perspective' => false,
        //'perspective-origin' => false,
        'transform' => true,
        //'transform-origin' => false,
        //'transform-style' => false,
        //Transitions Properties
        //'transition' => false,
        //'transition-property' => false,
        //'transition-duration' => false,
        //'transition-timing-function' => false,
        //'transition-delay' => false,
        //Basic User Interface Properties
        //'box-sizing' => false,
        //'content' => false,
        //'cursor' => false,
        //'icon' => false,
        //'ime-mode' => false,
        //'nav-down' => false,
        //'nav-index' => false,
        //'nav-left' => false,
        //'nav-right' => false,
        //'nav-up' => false,
        'outline' => true,
        'outline-color' => true,
        'outline-offset' => true,
        'outline-style' => true,
        'outline-width' => true,
        //'resize' => false,
        //'text-overflow' => false,
        //Multi-column Layout Properties
        'break-after' => true,
        'break-before' => true,
        'break-inside' => true,
        'column-count' => true,
        //'column-fill' => false,
        'column-gap' => true,
        'column-rule' => true,
        'column-rule-color' => true,
        'column-rule-style' => true,
        'column-rule-width' => true,
        //'column-span' => false,
        'column-width' => true,
        'columns' => true,
        'widows' => true, 
        //Paged Media
        'orphans' => true,
        'page-break-after' => true,
        'page-break-before' => true,
        'page-break-inside' => true,
        //Generated Content for Paged Media
        //'marks' => false,
        //'quotes' => false,
        //Filter Effects Properties
        //'filter' => false,
        //Image Values and Replaced Content
        //'image-orientation' => false,
        //'image-rendering' => false,
        //'image-resolution' => false,
        //'object-fit' => false,
        //'object-position' => false,
        //Masking Properties
        //'mask' => false,
        //'mask-type' => false,
        //Speech Properties
        //'mark' => false,
        //'mark-after' => false,
        //'mark-before' => false,
        //'phonemes' => false,
        //'rest' => false,
        //'rest-after' => false,
        //'rest-before' => false,
        //'voice-balance' => false,
        //'voice-duration' => false,
        //'voice-pitch' => false, 
        //'voice-pitch-range' => false, 
        //'voice-rate' => false,
        //'voice-stress' => false, 
        //'voice-volume' => false, 
        //Marquee Properties
        //'marquee-direction' => false,
        //'marquee-play-count' => false,
        //'marquee-speed' => false,
        //'marquee-style' => false, 
        //'parentstyleid' => true,//DOCXPRESSO internal property
     );
    /**
     * CSS rules or directives
     * If set to false is not parsed by the package
     * 
     * @access public
     * @static
     * @var array
     */
    public static $CSSrules = array(
        '@bottom-center'        => false,					
        '@bottom-left'          => false,				
        '@bottom-left-corner'   => false,					
        '@bottom-right'         => false,				
        '@bottom-right-corner'  => false,					
        '@charset'              => true,
        '@counter'              => true,				
        '@font-face'            => true,
        '@import'               => true,
        '@keyframes'            => true,	
        '@left-bottom'          => false,					
        '@left-middle'          => false,					
        '@left-top'             => false,					
        '@media'                => true,
        '@namespace'            => true,
        '@page'                 => true,
        '@right-bottom'         => false,				
        '@right-middle'         => false,					
        '@right-top'            => false,					
        '@string'               => false,
        '@supports'             => true,
        '@top-center'           => false,					
        '@top-left'             => false,					
        '@top-left-corner'      => false,					
        '@top-right'            => false,					
        '@top-right-corner'     => false,			
    );
    /**
     * inherited CSS properties
     * 
     * @access public
     * @static
     * @var array
     */
    public static $inheritedCSSProperties = array(
        //Color properties
        'color' => true, //Sets the color of text. Since CSS1
        'opacity' => true, //Sets the opacity level for an element. Since CSS3
        //Text Properties
        'letter-spacing' => true, //Increases or decreases the space between characters in a text. Since CSS1
        'line-height' => true, //Sets the line height. Since CSS1
        'text-align' => true, //Specifies the horizontal alignment of text. Since CSS1
        'text-align-last' => true, //Describes how the last line of a block or a line right before a forced line break is aligned when text-align is "justify". Since CSS3
        'text-indent' => true, //Specifies the indentation of the first line in a text-block. Since CSS1
        'text-justify' => true, //Specifies the justification method used when text-align is "justify". Since CSS3
        'text-transform' => true, //Controls the capitalization of text. Since CSS1
        'hyphens' => true,
        'letter-spacing' => true,
        'line-break' => true,
        'line-height' => true,
        ///Text Decoration Properties
        'text-decoration' => true, //Specifies the decoration added to text. Since CSS1
        'text-decoration-color' => true, //Specifies the color of the text-decoration. Since CSS3
        'text-decoration-line' => true, //Specifies the type of line in a text-decoration. Since CSS3
        'text-decoration-style' => true, //Specifies the style of the line in a text decoration. Since CSS3
        'text-shadow' => true, //Adds shadow to text. Since CSS3
        //Font Properties
        'font' => true, //Sets all the font properties in one declaration. Since CSS1
        'font-family' => true, //Specifies the font family for text. Since CSS1
        'font-kerning' => true, //Since CSS3
        'font-size' => true, //Specifies the font size of text. Since CSS1
        'font-style' => true, //Specifies the font style for text. Since CSS1
        'font-variant' => true, //Specifies whether or not a text should be displayed in a small-caps font. Since CSS1
        'font-weight' => true, //Specifies the weight of a font. Since CSS1
        //Writing Modes Properties
        'direction' => true, //Specifies the text direction/writing direction. Since CSS2
        'writing-mode' => true, //Since CSS3
        //Transform properties
        'transform' => true,
        //Layout Properties
        'widows' => true, //Sets the minimum number of lines that must be left at the top of a page when a page break occurs inside an element. Since CSS2
        //Paged Media
        'orphans' => true, //Sets the minimum number of lines that must be left at the bottom of a page when a page break occurs inside an element. Since CSS2
     );
    /**
     * available line styles
     * @access public
     * @static
     * @var array
     */
    public static $lineStyles = array(
        'none' => 'none',
        'solid' => 'solid',
        'dotted' => 'dotted',
        'dashed' => 'dash',
        'wavy' => 'wave',
    );
    /**
     * special chars
     * @access public
     * @static
     * @var array
     */
    public static $metaChars = array(
        ' ' => true,
        '.' => true,
        '#' => true,
        '>' => true,
        '[' => true,
        ']' => true,
        '(' => true,
        ')' => true,
        ':' => true,
        '~' => true,
        '!' => true,
        '+' => true,
        '=' => true,
        '"' => true,
        '\'' => true,
        '*' => true,
        '^' => true,
        '$' => true,
        '|' => true,
    );
    /**
     * pseudoclasses
     * @access public
     * @static
     * @var array
     */
    public static $pseudoclasses = array(
        'link' => false,
        'visited' => false,
        'active' => false,
        'hover' => false,
        'focus' => false,
        'first-child' => true,
        'lang' => true,
        'contains' => true, //this is not really a pseudoclass but it fits here
        //CSS3
        'nth-child' => true,
        'nth-last-child' => true,
        'nth-of-type' => true,
        'nth-last-of-type' => true,
        'last-child' => true,
        'first-of-type' => true,
        'last-of-type' => true,
        'only-child' => true,
        'only-of-type' => true,
        'root' => true,
        'empty' => true,
        'target' => false,
        'enabled' => true,
        'disabled' => true,
        'checked' => true,
        'not' => true,
    );
            
    /**
     * pseudoelements
     * @access public
     * @static
     * @var array
     */
    public static $pseudoelements = array(
        'first-letter' => false,
        'first-line' => false,
        'before' => false,
        'after' => false,
        'selection' => false,
    );

}