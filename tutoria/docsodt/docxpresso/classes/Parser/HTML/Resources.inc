<?php

/**
 * Resources
 * 
 * @copyright  Copyright (c) 2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Parser\HTML;

/**
 * This class stores certain resources needed for the parsing of HTML data
 *
 * @package    Docxpresso
 * @subpackage Parser
 */

class Resources
{

    /**
     * HTML block tags
     * @access public
     * @static
     * @var array
     */
    public static $blockTags = array(
        'address' => 'table',
        'article' => 'table',
        'aside' => 'table',
        'blockquote' => 'paragraph',
        'caption' => 'paragraph',
        'center' => 'table',
        'dd' => 'paragraph',
        'div' => 'table',
        'dt' => 'paragraph',
        'fieldset' => 'fieldset',
        'figcaption' => 'paragraph',
        'figure' => 'table',
        'footer' => 'footer',
        'form' => 'form',
        'h1' => 'paragraph',
        'h2' => 'paragraph',
        'h3' => 'paragraph',
        'h4' => 'paragraph',
        'h5' => 'paragraph',
        'h6' => 'paragraph',
        'header' => 'header',
        'hgroup' => 'table',
        'hr' => 'hr',
        'li' => 'list',
        'menu' => 'list',
        'menuitem' => 'list',
        'ol' => 'list',
        'output' => 'table',
        'p' => 'paragraph',
        'pre' => 'paragraph',
        'section' => 'section',
        'table' => 'table',
        'ul' => 'list',
    );
    /**
     * HTML inline tags
     * @access public
     * @static
     * @var array
     */
    public static $inlineTags = array(
        'a' => true,
        'abbr' => true,
        'acronym' => true,
        'applet' => false,
        'audio' => false,
        'b' => true,
        'bdo' => true,
        'big' => true,
        'br' => true,
        'button' => true,
        'canvas' => false,
        'cite' => true,
        'code' => true,
        'command' => true,
        'datalist' => true,
        'del' => true,
        'details' => true,
        'dfn' => true,
        'dialog' => true,
        'em' => true,
        'endnote' => true,
        'font' => true,
        'footnote' => true,
        'i' => true,
        'iframe' => true,
        'img' => true,
        'input' => true,
        'ins' => true,
        'kbd' => false,
        'label' => true,
        'legend' => true,
        'link' => false,
        'mark' => true,
        'math' => true,
        'meter' => false,
        'nav' => true,
        'optgroup' => true,
        'option' => true,
        'q' => true,
        'select' => true,
        'small' => true,
        'source' => true,
        'span' => true,
        'strike' => true,
        'strong' => true,
        'sub' => true,
        'summary' => true,
        'sup' => true,
        'tbody' => true,
        'td' => true,
        'textarea' => true,
        'tfoot' => true,
        'th' => true,
        'thead' => true,
        'time' => true,
        'var' => true,
        'video' => false,
        'wbr' => true,
    );
    /**
     * special block tag elements
     * @access public
     * @static
     * @var array
     */
    public static $newBlockLevelTags = array(
        'article'   => 'html5',
        'header'    => 'html5',
        'footer'    => 'html5',
        'section'   => 'html5',
        'toc'       => 'docxpresso',
        'outline'   => 'docxpresso',
    );
    /**
     * special inline tag elements
     * @access public
     * @static
     * @var array
     */
    public static $newInlineTags = array(
        'audio'     => 'html5',
        'canvas'    => 'html5',
        'nav'       => 'html5',
        'ruby'      => 'html5',
        'rt'        => 'html5',
        'rp'        => 'html5',
        'video'     => 'html5',
        'chart'     => 'docxpresso',
        'endnote'   => 'docxpresso',
        'footnote'  => 'docxpresso',
        'page'      => 'docxpresso',
        'date'      => 'docxpresso',
        'math'      => 'docxpresso',
        'ref'       => 'docxpresso',
        'tab'       => 'docxpresso'
    );
    /**
     * HTML tags (extended)
     * @access public
     * @static
     * @var array
     */
    public static $tags = array(
        '!DOCTYPE' => false, //Defines the document type
        'a' => true, //Defines a hyperlink
        'abbr' => true, //Defines an abbreviation
        'acronym' => true, //Not supported in HTML5. Use 'abbr' instead. Defines an acronym
        'address' => true, //Defines contact information for the author/owner of a document
        'applet' => false, //Not supported in HTML5. Use 'object' instead. Defines an embedded applet
        'area' => false, //Defines an area inside an image-map
        'article' => true, //Defines an article
        'aside' => true, //Defines content aside from the page content
        'audio' => false, //Defines sound content
        'b' => true, //Defines bold text
        'base' => true, //Specifies the base URL/target for all relative URLs in a document
        'basefont' => true, //Not supported in HTML5. Use CSS instead. Specifies a default color, size, and font for all text in a document
        'bdi' => true, //Isolates a part of text that might be formatted in a different direction from other text outside it
        'bdo' => true, //Overrides the current text direction
        'big' => true, //Not supported in HTML5. Use CSS instead. Defines big text
        'blockquote' => true, //Defines a section that is quoted from another source
        'body' => true, //Defines the document's body
        'br' => true, //Defines a single line break
        'button' => true, //Defines a clickable button
        'canvas' => false, //Used to draw graphics, on the fly, via scripting (usually JavaScript)
        'caption' => true, //Defines a table caption
        'center' => true, //Not supported in HTML5. Use CSS instead.Defines centered text
        'chart' => true, //DOCXPRESSO extension
        'cite' => true, //Defines the title of a work
        'code' => true, //Defines a piece of computer code
        'col' => true, //Specifies column properties for each column within a 'colgroup' element 
        'colgroup' => true, //Specifies a group of one or more columns in a table for formatting
        'command' => true, //Defines a command button that a user can invoke
        'datalist' => true, //Specifies a list of pre-defined options for input controls
        'date' => true, //DOCXPRESSO extension
        'dd' => true, //Defines a description/value of a term in a description list
        'del' => true, //Defines text that has been deleted from a document
        'details' => true, //Defines additional details that the user can view or hide
        'dfn' => true, //Defines a definition term
        'dialog' => false, //Defines a dialog box or window
        'dir' => false, //Not supported in HTML5. Use 'ul' instead. Defines a directory list
        'div' => true, //Defines a section in a document
        'dl' => true, //Defines a description list
        'dt' => true, //Defines a term/name in a description list
        'em' => true, //Defines emphasized text 
        'embed' => false, //Defines a container for an external (non-HTML) application
        'endnote' => true, //DOCXPRESSO extension
        'fieldset' => true, //Groups related elements in a form
        'figcaption' => true, //Defines a caption for a 'figure' element
        'figure' => true, //Specifies self-contained content
        'font' => true, //Not supported in HTML5. Use CSS instead. Defines font, color, and size for text
        'footer' => true, //Defines a footer for a document or section
        'footnote' => true, //DOCXPRESSO extension
        'form' => true, //Defines an HTML form for user input
        'frame' => false, //Not supported in HTML5. Defines a window (a frame) in a frameset
        'frameset' => false, //Not supported in HTML5. Defines a set of frames
        'h1' => true, // Defines HTML heading level 1
        'h2' => true, // Defines HTML heading level 2
        'h3' => true, // Defines HTML heading level 3
        'h4' => true, // Defines HTML heading level 4
        'h5' => true, // Defines HTML heading level 5
        'h6' => true, // Defines HTML heading level 6
        'head' => true, //Defines information about the document
        'header' => true, //Defines a header for a document or section
        'hgroup' => true, //Groups a set of headings
        'hr' => true, // Defines a thematic change in the content
        'html' => true, //Defines the root of an HTML document
        'i' => true, //Defines a part of text in an alternate voice or mood
        'iframe' => false, //Defines an inline frame
        'img' => true, //Defines an image
        'input' => true, //Defines an input control
        'ins' => true, //Defines a text that has been inserted into a document
        'kbd' => false, //Defines keyboard input
        'keygen' => false, //Defines a key-pair generator field (for forms)
        'label' => true, //Defines a label for an 'input' element
        'legend' => true, //Defines a caption for a 'fieldset' element
        'li' => true, //Defines a list item
        'link' => false, //Defines the relationship between a document and an external resource (most used to link to style sheets)
        'main' => false, //Specifies the main content of a document
        'map' => false, //Defines a client-side image-map
        'math' => true, //DOCXPRESSO extension
        'mark' => true, //Defines marked/highlighted text
        'menu' => true, //Defines a list/menu of commands
        'menuitem' => true, //an element of a munu list
        'meta' => false, //Defines metadata about an HTML document
        'meter' => false, //Defines a scalar measurement within a known range (a gauge)
        'nav' => true, //Defines navigation links
        'noframes' => false, //Not supported in HTML5. Defines an alternate content for users that do not support frames
        'noscript' => false, //Defines an alternate content for users that do not support client-side scripts
        'object' => false, //Defines an embedded object
        'ol' => true, //Defines an ordered list
        'optgroup' => true, //Defines a group of related options in a drop-down list
        'option' => true, //Defines an option in a drop-down list
        'outline' => true, //DOCXPRESSO extension
        'output' => true, //Defines the result of a calculation
        'p' => true, //Defines a paragraph
        'page' => true, //DOCXPRESSO extension
        'param' => false, //Defines a parameter for an object
        'pre' => true, //Defines preformatted text
        'progress' => false, //Represents the progress of a task
        'q' => true, //Defines a short quotation
        'ref' => true, //DOCXPRESSO extension
        'rp' => false, //Defines what to show in browsers that do not support ruby annotations
        'rt' => false, //Defines an explanation/pronunciation of characters (for East Asian typography)
        'ruby' => false, //Defines a ruby annotation (for East Asian typography)
        's' => false, //Defines text that is no longer correct
        'samp' => true, //Defines sample output from a computer program
        'script' => false, //Defines a client-side script
        'section' => true, //Defines a section in a document
        'select' => true, //Defines a drop-down list
        'small' => true, //Defines smaller text
        'source' => false, //Defines multiple media resources for media elements ('video' and 'audio')
        'span' => true, //Defines a chunk of formatted text
        'strike' => true, //Not supported in HTML5. Use 'del' instead. Defines strikethrough text
        'strong' => true, //Defines important text
        'style' => false, //Defines style information for a document
        'sub' => true, //Defines subscripted text
        'summary' => true, //Defines a visible heading for a 'details' element
        'sup' => true, //Defines superscripted text
        'svg' => true, //Defines sa scalable vector graphic element
        'tab' => true, //DOCXPRESSO extension
        'table' => true, //Defines a table
        'tbody' => true, //Groups the body content in a table
        'td' => true, //Defines a cell in a table
        'textarea' => true, //Defines a multiline input control (text area)
        'tfoot' => true, //Groups the footer content in a table
        'th' => true, //Defines a header cell in a table
        'thead' => true, //Groups the header content in a table
        'time' => true, //Defines a date/time
        'title' => false, //Defines a title for the document
        'toc' => true, //DOCXPRESSO extension
        'tr' => true, //Defines a row in a table
        'track' => false, //Defines text tracks for media elements ('video' and 'audio')
        'tt' => true, //Not supported in HTML5. Use CSS instead. Defines teletype text
        'u' => true, //Defines text that should be stylistically different from normal text
        'ul' => true, //Defines an unordered list
        'var' => true, //Defines a variable
        'video' => false, //Defines a video or movie
        'wbr' => true, //Defines a possible line-break
    );
    
    /**
     * tag default styles
     * @access public
     * @static
     * @var array
     */
    public static $tagStyles = array(
        'a' => array('color' => '#0000ff',
                     'text-decoration' => 'underline'),
        /* the other props are enforced via the default stylesheet
        'abbr' => array(),
        'acronym' => array(),
        'address' => array('font-style' => 'italic'),
        'article' => array(),
        'aside' => array(),
        'b' => array('font-weight' => 'bold'),
        'base' => array(),
        'basefont' => array(),
        'bdi' => array(),
        'bdo' => array('unicode-bidi' => 'bidi-override'),
        'big' => array('font-size' => array(1.15, 'em')),
        'blockquote' => array('padding' => array(0, 'pt'), 
                              'margin-top' => array(1, 'em'),
                              'margin-bottom' => array(1, 'em'),
                              'margin-left' => array(40, 'px'),
                              'margin-right' => array(40, 'px'),
                            ),
        'body' => array(),
        'br' => array(),
        'button' => array(),
        'caption' => array('text-align' => 'center'),
        'center' => array('margin' => 'auto',
                          'text-align' => 'center',
                         ),
        'chart' => array(),
        'cite' => array('font-style' => 'italic'),
        'code' => array('font-family' => 'monospace'),
        'col' => array(),
        'colgroup' => array(),
        'command' => array(),
        'datalist' => array('display' => 'none'),
        'date' => array(),
        'dd' => array('padding' => array(0, 'pt'), 
                      'margin-left' => array(40, 'px'),
                     ),
        'del' => array('text-decoration' => 'line-trough'),
        'details' => array(),
        'dfn' => array('font-style' => 'italic'),
        'div' => array(),
        'dl' => array('padding' => array(0, 'pt'), 
                      'margin-top' => array(1, 'em'),
                      'margin-bottom' => array(1, 'em'),
                      'margin-left' => array(0, 'px'),
                      'margin-right' => array(0, 'px'),
                     ),
        'dt' => array(),
        'em' => array('font-style' => 'italic'),
        'endnote' => array(),
        'fieldset' => array('padding' => array(1, 'em'),
                            'border-top-width' => array(1, 'px'),
                            'border-top-color' => '#000000',
                            'border-top-style' => 'solid',
                            'border-right-width' => array(1, 'px'),
                            'border-right-color' => '#000000',
                            'border-right-style' => 'solid',
                            'border-bottom-width' => array(1, 'px'),
                            'border-bottom-color' => '#000000',
                            'border-bottom-style' => 'solid',
                            'border-left-width' => array(1, 'px'),
                            'border-left-color' => '#000000',
                            'border-left-style' => 'solid',
                            'margin' => array(2, 'px'),
                           ),
        'figcaption' => array('padding' => array(0, 'pt')),
        'figure' => array('padding' => array(0, 'pt'),
                          'margin-top' => array(1, 'em'),
                          'margin-bottom' => array(1, 'em'),
                          'margin-left' => array(40, 'px'),
                          'margin-right' => array(40, 'px'),
                         ),
        'font' => array(),
        'footer' => array(),
        'footnote' => array(),
        'form' => array(),
        'h1' => array('padding' => array(0, 'pt'),
                      'margin-top' => array(0.0277, 'in'),
                      'margin-bottom' => array(0, 'in'),
                      'keep-with-next' => 'always',
                      'keep-together' => 'always',
                      'color' => '#2E74B5',
                      'font-size' => array(16, 'pt'),
                      ),
        'h2' => array('padding' => array(0, 'pt'),
                      'margin-top' => array(0.0277, 'in'),
                      'margin-bottom' => array(0, 'in'),
                      'keep-with-next' => 'always',
                      'keep-together' => 'always',
                      'color' => '#2E74B5',
                      'font-size' => array(13, 'pt'),
                      ),
        'h3' => array('padding' => array(0, 'pt'),
                      'margin-top' => array(0.0277, 'in'),
                      'margin-bottom' => array(0, 'in'),
                      'keep-with-next' => 'always',
                      'keep-together' => 'always',
                      'color' => '#1F4D78',
                      'font-size' => array(12, 'pt'),
                      ),
        'h4' => array('padding' => array(0, 'pt'),
                      'margin-top' => array(0.0277, 'in'),
                      'margin-bottom' => array(0, 'in'),
                      'keep-with-next' => 'always',
                      'keep-together' => 'always',
                      'color' => '#2E74B5',
                      'font-size' => array(11, 'pt'),
                      'font-style' => 'italic',
                      ),
        'h5' => array('padding' => array(0, 'pt'),
                      'margin-top' => array(0.0277, 'in'),
                      'margin-bottom' => array(0, 'in'),
                      'keep-with-next' => 'always',
                      'keep-together' => 'always',
                      'color' => '#2E74B5',
                      'font-size' => array(11, 'pt'),
                      ),
        'h6' => array('padding' => array(0, 'pt'),
                      'margin-top' => array(0.0277, 'in'),
                      'margin-bottom' => array(0, 'in'),
                      'keep-with-next' => 'always',
                      'keep-together' => 'always',
                      'color' => '#1F4D78',
                      'font-size' => array(11, 'pt'),
                      'font-style' => 'italic',
                      ),
        'head' => array(),
        'header' => array(),
        'hgroup' => array(),
        'hr' => array('padding' => array(0, 'pt'),
                      'margin-top' => array(0, 'pt'),
                      'margin-bottom' => array(0.5, 'cm'),
                      'border-bottom-width' => array(1.11, 'pt'),
                      'border-bottom-color' => '#808080',
                      'border-bottom-style' => 'double',
                      'font-size' => array(6, 'pt'),
                      ),
        'html' => array(),
        'i' => array('font-style' => 'italic'),
        'img' => array(),
        'input' => array(),
        'ins' => array('text-decoration' => 'underline'),
        'label' => array(),
        'legend' => array('padding' => array(0, 'pt')),
        'li' => array(),
        'mark' => array('background-color' => 'yellow', 
                        'color' => 'black'
                       ),
        'math' => array(),
        'menu' => array(),
        'menuitem' => array(),
        'nav' => array('padding' => array(0, 'pt')),
        'ol' => array(),
        'optgroup' => array(),
        'option' => array(),
        'outline' => array(),
        'output' => array(),
        'p' => array('padding' => array(0, 'pt')),
        'page' => array(),
        'pre' => array('padding' => array(0, 'pt'),
                       'font-family' => 'monospace',
                       'white-space' => 'pre',
                       'margin-top' => array(1, 'em'),
                       'margin-bottom' => array(1, 'em'),
                       'margin-left' => array(0, 'px'),
                       'margin-right' => array(0, 'px'),
                      ),
        'q' => array(),
        'ref' => array(),
        'samp' => array('padding' => array(0, 'pt'),
                       'font-family' => 'monospace',
                       'white-space' => 'pre',
                       ),
        'section' => array(),
        'select' => array(),
        'small' => array('font-size' => array(0.85, 'em')),
        'span' => array(),
        'strike' => array('text-decoration' => 'line-trough'),
        'strong' => array('font-weight' => 'bold'),
        'sub' => array('vertical-align' => 'sub', 'font-size' => array(0.85, 'em')),
        'summary' => array('padding' => array(0, 'pt')),
        'sup' => array('vertical-align' => 'sub', 'font-size' => array(0.85, 'em')),
        'tab' => array(),
        'table' => array(),
        'tbody' => array(),
        'td' => array(),
        'textarea' => array(),
        'tfoot' => array(),
        'th' => array(),
        'thead' => array(),
        'time' => array(),
        'toc' => array(),
        'tr' => array(),
        'tt' => array('font-family' => 'monospace'),
        'u' => array('text-decoration' => 'underline'),
        'ul' => array(),
        'var' => array('text-decoration' => 'underline'),
        'wbr' => array(),
         */
    );
    
    /**
     * default CSS values for specific tags
     * @access public
     * @static
     * @var string
     */
    public static $defaultCSS = '
        a {text-decoration: underline;}
        address {font-style: italic;}
        b {font-weight: bold;}
        bdo {unicode-bidi: bidi-override;}
        big {font-size: 1.15em;}
        blockquote {padding: 0; margin: 1em 40px;}
        caption {text-align: center;}
        center {margin: auto; text-align: center;}
        cite {font-style: italic;}
        code {font-family: monospace;}
        datalist {display: none;}
        dd {padding: 0; margin-left: 40px;}
        del {text-decoration: line-through;}
        dfn {font-style; italic;}
        dl {padding: 0; margin: 1em 0;}
        em {font-style: italic;}
        endnote {marfin-left: 0.3cm; text-indent: -0.3cm}
        fieldset {padding: 1em; margin: 2px;border: 1px solid #000000;}
        figcaption {padding: 0;}
        figure {padding: 0; margin: 1em 40px;}
        footnote {margin-left: 0.3cm; text-indent: -0.3cm}
        h1 {color: #2E74B5; font-size: 16pt; padding: 0; margin-top: 0.0277in; margin-bottom: 0; keep-with-next: always; keep-together: always;}
        h2 {color: #2E74B5; font-size: 13pt; padding: 0; margin-top: 0.0277in; margin-bottom: 0; keep-with-next: always; keep-together: always;}
        h3 {color: #1F4D78; font-size: 12pt; padding: 0; margin-top: 0.0277in; margin-bottom: 0; keep-with-next: always; keep-together: always;}
        h4 {color: #2E74B5; font-size: 11pt; font-style: italic; padding: 0; margin-top: 0.0277in; margin-bottom: 0; keep-with-next: always; keep-together: always;} 
        h5 {color: #2E74B5; font-size: 11pt; padding: 0; margin-top: 0.0277in; margin-bottom: 0; keep-with-next: always; keep-together: always;}
        h6 {color: #1F4D78; font-size: 13pt; font-style: italic; padding: 0; margin-top: 0.0277in; margin-bottom: 0; keep-with-next: always; keep-together: always;}        
        hr {padding: 0; margin-top: 0; margin-bottom: 0.5cm; border-bottom: 1.11pt double #808080; font-size: 6pt;}
        i {font-style: italic;}
        ins {text-decoration: underline;}
        legend {padding: 0;}
        mark {background-color: yellow; color: black;}
        nav {padding: 0;}
        p {padding: 0;}
        pre {padding: 0; font-family: monospace; white-space: pre; margin: 1em 0;}
        samp {padding: 0; font-family: monospace; white-space: pre;}
        small {font-size: 0.85em;}
        strike {text-decoration: line-through;}
        strong {font-weight: bold;}
        sub {vertical-align: sub; font-size: 0.85em;}
        summary {padding: 0;}
        sup {vertical-align: super; font-size: 0.85em;}
        tt {font-family: monospace;}
        u {text-decoration: underline;}
        var {text-decoration: underline;}
    ';
    
    /**
     * attributes
     * @access public
     * @static
     * @var array
     */
    public static $attributes = array(
        
    );
    /**
     * Construct
     *
     * @access public
     */
    public function __construct()
    {        
        //Do nothing 
    }

}