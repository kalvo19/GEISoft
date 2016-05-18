<?php

/**
 * ODFQuery
 *
 * @copyright  Copyright (c) 2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    2.0
 * @since      2.0
 */

namespace Docxpresso\Templates;

/**
 * This class centralizes the generation of XPath queries for templates
 *
 * @package    Docxpresso
 * @subpackage Templates
 */

class ODFQuery
{
    
    /**
     * Construct
     *
     * @access public
     */
    public function __construct()
    {
        
    }
    
    /**
     * creates the query to select all the content that fall within a 
     * given bookmark.
     * Warning: only elements at the same document depth are selected
     *
     * @param array $options
     * @return string
     * @access public
     */
    public function bookmarkQuery($options)
    {
        $bmk = $options['needle'];
        //this chunk of the query selects the paragraph|heading that contains
        //the bookmartStart node for a given bookmark name
        $query_1 = '//*[./text:bookmark-start[@text:name="' . $bmk . '"]]';
        if (isset($options['match']) && \is_int($options['match'])) {
            $query_1 = $query_1  . '[' . $options['match'] .']';
        } 
        //the xpath condition to avoid siblings coming after the bookmark-end
        if (isset($options['match']) && \is_int($options['match'])) {
            $condition =  ($options['match'] - 1); 
            $condition .= ' = count(preceding-sibling::*';
        } else {
            $condition = '0 = count(preceding-sibling::*';
        }
        $condition .= '[.//text:bookmark-end[@text:name="' . $bmk . '"]])';
        $query_2 = '/following-sibling::*[' . $condition . ']';
        $query = $query_1 . ' | ' . $query_1 . $query_2;
        return $query;
    }
    
    /**
     * creates the query to find a generic element
     *
     * @param array $options
     * @return string
     * @access public
     */
    public function elementQuery($options)
    {
        $rm = array ('paragraph'     => 'text:p',
                     'list'          => 'text:list',
                     'list-item'     => 'text:list-item',
                     'table'         => 'table:table',
                     'table-row'     => 'table:table-row',
                     'section'       => 'text:section',
                     );
        //we include a no descendant clause into the query in order to avoid
        //the case where we remove, for example, a table that contains itself
        //a table with the required content (only the inner table will be 
        //removed)
        $subClause = 'descendant::' . $rm[$options['element']] . '[';
        $subClause .= './/text()[contains(., "' . $options['needle'] . '")]]';
        //the required query reads
        $baseQuery = '//' . $rm[$options['element']] . '[';
        $baseQuery .= './/text()[contains(., "' . $options['needle'] . '")]';
        $baseQuery .= ' and not(' . $subClause . ')';
        $baseQuery .= ']';
        
        if (isset($options['match']) && \is_int($options['match'])) {
            $query = '(' . $baseQuery . ')' . '[' . $options['match'] .']';
        } else {
            $query = $baseQuery;
        }
        return $query;
    }
    
    /**
     * creates the query to select the first parent with the requested tag
     *
     * @param array $tags
     * @return string
     * @access public
     */
    public function getParent($tags)
    {
        $query = '(';
        foreach ($tags as $tag){
            $query .= './ancestor::' . $tag . ' | ';
        }
        $query .= ' ./ancestor::noneTag)[1]';
        return $query;
    }
    
    /**
     * creates the query to select all the content that fall within a 
     * heading that contains the needle text at a specific heading level
     *
     * @param array $options
     * @return string
     * @access public
     */
    public function headingQuery($options)
    {
        if (isset($options['heading-level'])) {
            $level = $options['heading-level'];
        } else {
            $level = 1;
        }
        $text = $options['needle'];
        //this chunk of the query selects the chosen heading
        $baseQuery = '//text:h[';
        $baseQuery .= '@text:outline-level=' . $level . ' and ';
        $baseQuery .= 'contains(., "' . $text . '")';
        $baseQuery .= ']';
        if (isset($options['match']) && \is_int($options['match'])) {
            $baseQuery = '(' . $baseQuery . ')' . '[' . $options['match'] .']';
        } 
        //now we have to take into account all the siblings of that heading
        //until we hit another heading of the same or higher level
        $query = $baseQuery . ' | ';
        $q1 = 'preceding-sibling::text:h[@text:outline-level <=' . $level . ']';
        $q2 = 'preceding-sibling::text:h[@text:outline-level=' . $level;
        $q2 .= 'and contains(., "' . $text . '")]';
        $a = 'count(' . $q1 . ')';
        $b = 'count(' . $q2 . '/' . $q1 . ')';
        $conditionQuery = '(' . $a . ' - ' . $b . ') <= 1 ';
        $conditionQuery.= ' and not(@text:outline-level <=' . $level . ')';
        $query .= $baseQuery . '/following-sibling::*[' . $conditionQuery . ']';
        return $query;
    }
    
    /**
     * creates a query to find an object/image node
     *
     * @param array $options
     * @param string $type it can be remove (default) or clone
     * @return string
     * @access public
     */
    public function objQuery($options, $type = 'remove')
    {
        $rm = array('chart' => 'object',
                    'image' => 'image',            
                    );
        
        $baseQuery = '//draw:frame[';
        $baseQuery .= './draw:'. $rm[$options['element']];
        if (!empty($options['needle'])) {
            $baseQuery .= ' and ./svg:title/';
            $baseQuery .= 'text()[contains(.,"' . $options['needle'] . '")]';
        }
        $baseQuery .= ']';
        if (isset($options['container']) && $options['container']){
            $baseQuery .='/ancestor::text:p[1]';
        } else if ($type == 'remove') {
            $baseQuery .='/ancestor::text:span[1]';
        }
        
        if (isset($options['match']) && \is_int($options['match'])) {
            $query = '(' . $baseQuery . ')' . '[' . $options['match'] .']';
        } else {
            $query = $baseQuery;
        }
        return $query;
    }
    
    /**
     * creates the query to find a paragraph that includes a set of variables
     *
     * @param array $options
     * @param string $tag
     * @return string
     * @access public
     */
    public function paragraphQuery($options, $tag)
    {
        $baseQuery = '//' . $tag . '[';
        foreach ($options['search'] as $needle) {
            $baseQuery .= 'contains(., "' . $needle . '") and ';
        }
        $baseQuery .= '1=1]';
        
        if (isset($options['match']) && \is_int($options['match'])) {
            $query = '(' . $baseQuery . ')' . '[' . $options['match'] .']';
        } else {
            $query = $baseQuery;
        }
        
        return $query;
    }
    
    /**
     * creates the query to find all rows within a table that contain a set
     * of variables
     *
     * @param array $options
     * @return string
     * @access public
     */
    public function rowQuery($options)
    {
        //build the contains condition
        $condition = '';
        foreach ($options['search'] as $needle) {
            $condition .= 'contains(., "' . $needle . '") or ';
        }
        $condition .= '1=2';
        //the required query reads
        $baseQuery_1 = './table:table-row[' . $condition .']';
        $baseQuery_2 = './*/table:table-row[' . $condition .']';
        $query = $baseQuery_1 . ' | ' . $baseQuery_2;
        return $query;
    }
    
    /**
     * creates the query to find a cell in a spreadsheet
     *
     * @param array $options
     * @return string
     * @access public
     */
    public function spreadsheetQuery($options)
    {
        $baseQuery = '//table:table-cell';
        $baseQuery .= '[not(@table:formula)]';
        $baseQuery .= '[.//text()[contains(., "' . $options['needle'] . '")]]';
        if (isset($options['match']) && \is_int($options['match'])) {
            $query = '(' . $baseQuery . ')' . '[' . $options['match'] .']';
        } else {
            $query = $baseQuery;
        }
        return $query;
    }
    
    /**
     * creates the query to find a table containing a set of variables
     *
     * @param array $options
     * @return string
     * @access public
     */
    public function tableQuery($options)
    {
        //build the contains condition
        $condition = '';
        foreach ($options['search'] as $needle) {
            $condition .= 'contains(., "' . $needle . '") and ';
        }
        $condition .= '1=1';
        //no descendant clause
        $subClause = 'descendant::table:table[' . $condition . ']';
        //the required query reads
        $baseQuery = '//table:table[' . $condition;
        $baseQuery .= ' and not(' . $subClause . ')';
        $baseQuery .= ']';
        
        if (isset($options['match']) && \is_int($options['match'])) {
            $query = '(' . $baseQuery . ')' . '[' . $options['match'] .']';
        } else {
            $query = $baseQuery;
        }
        return $query;
    }
    
    /**
     * creates the query to find a text node
     *
     * @param array $options
     * @return string
     * @access public
     */
    public function textQuery($options)
    {
        $baseQuery = '//text()[contains(., "' . $options['needle'] . '")]';
        if (isset($options['match']) && \is_int($options['match'])) {
            $query = '(' . $baseQuery . ')' . '[' . $options['match'] .']';
        } else {
            $query = $baseQuery;
        }
        return $query;
    }
    
    /**
     * creates the query to find a textbox node
     *
     * @param array $options
     * @param string $type it can be remove (default) or clone
     * @return string
     * @access public
     */
    public function tboxQuery($options, $type = 'remove')
    {
        $baseQuery = '//draw:frame[';
        $baseQuery .= './draw:text-box ';
        $baseQuery .= '//text()[contains(.,"' . $options['needle'] . '")]';
        $baseQuery .= ']';
        if (isset($options['container']) && $options['container']){
            $baseQuery .='/ancestor::text:p[1]';
        } else if ($type == 'remove') {
            $baseQuery .='/ancestor::text:span[1]';
        }
        
        if (isset($options['match']) && \is_int($options['match'])) {
            $query = '(' . $baseQuery . ')' . '[' . $options['match'] .']';
        } else {
            $query = $baseQuery;
        }
        return $query;
    }

}