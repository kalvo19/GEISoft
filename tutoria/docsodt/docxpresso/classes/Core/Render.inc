<?php

/**
 * Rendering options
 *
 * @copyright  Copyright (c) 2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Core;

/**
 * This class generates all the required rendering options
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class Render
{
    
    
    /**
     * Construct
     *
     * @access public
     */
    public function __construct()
    {
        //DOCXPRESSO general options
        $this->defRenderOptions['fileName'] = array('default', 'is_string');
        $this->defRenderOptions['outputFormat'] = array('PDF', 'is_string');
        $this->defRenderOptions['legacy'] = array('false', 'is_bool');
        $this->defRenderOptions['updateTOC'] = array('false', 'is_bool');
        $this->defRenderOptions['updateFields'] = array('false', 'is_bool');
        $this->defRenderOptions['updateMath'] = array('false', 'is_bool');
        //PDF export options
        $this->defRenderOptions['PageRange'] = array('_nil_', 'is_string');
        $this->defRenderOptions['UseLosslessCompression'] = array('false', 'is_bool');
        $this->defRenderOptions['Quality'] = array(90, 'is_numeric');
        $this->defRenderOptions['ReduceImageResolution'] = array('false', 'is_bool');
        $this->defRenderOptions['MaxImageResolution'] = array(300, 'is_numeric');
        $this->defRenderOptions['SelectPdfVersion'] = array(0, 'is_numeric');
        $this->defRenderOptions['UseTaggedPDF'] = array('false', 'is_bool');
        $this->defRenderOptions['ExportFormFields'] = array('true', 'is_bool');
        $this->defRenderOptions['FormsType'] = array(2, 'is_numeric');
        $this->defRenderOptions['ExportBookmarks'] = array('true', 'is_bool');
        $this->defRenderOptions['EmbedStandardFonts'] = array('false', 'is_bool');
        $this->defRenderOptions['Watermark'] = array('_nil_', 'is_string');
        $this->defRenderOptions['InitialView'] = array(0, 'is_numeric');
        $this->defRenderOptions['Magnification'] = array(0, 'is_numeric');
        $this->defRenderOptions['Zoom'] = array(100, 'is_numeric');
        $this->defRenderOptions['PageLayout'] = array(0, 'is_numeric');
        $this->defRenderOptions['FirstPageOnLeft'] = array('false', 'is_bool');
        $this->defRenderOptions['CenterWindow'] = array('false', 'is_bool');
        $this->defRenderOptions['OpenInFullScreenMode'] = array('false', 'is_bool');
        $this->defRenderOptions['DisplayPDFDocumentTitle'] = array('true', 'is_bool');
        $this->defRenderOptions['HideViewerMenubar'] = array('false', 'is_bool');
        $this->defRenderOptions['HideViewerToolbar'] = array('false', 'is_bool');
        $this->defRenderOptions['HideViewerWindowControls'] = array('false', 'is_bool');
        $this->defRenderOptions['EncryptFile'] = array('false', 'is_bool');
        $this->defRenderOptions['DocumentOpenPassword'] = array('_nil_', 'is_string');
        $this->defRenderOptions['RestrictPermissions'] = array('false', 'is_bool');
        $this->defRenderOptions['PermissionPassword'] = array('_nil_', 'is_string');
        $this->defRenderOptions['Printing'] = array(2, 'is_numeric'); 
        $this->defRenderOptions['Changes'] = array(4, 'is_numeric');
        $this->defRenderOptions['EnableCopyingOfContent'] = array('true', 'is_bool');  
        }
        
    /**
     * generate options for the CLI
     * 
     * @param array $data
     * @return string
     * @access public
     */
    public function cliOpts($data)
    {
        $renderOpt = '';

        foreach ($this->defRenderOptions as $key => $value) {
            if (isset($data[$key])) {
                if (\is_bool($data[$key])){
                    $renderOpt .= ' --' . $key .  ' ' . var_export($data[$key], 
                                                                   true);
                } else {
                    $renderOpt .= ' --' . $key .  ' ' . $data[$key];
                }
            } else {
                $renderOpt .= ' --' . $key .  ' ';
                $renderOpt .=  $this->defRenderOptions[$key][0];
            }
        }
        //remove unwanted single quotes
        $renderOpt = str_replace('\'', '', $renderOpt);
        return $renderOpt;
    }
        
    /**
     * Generate options for the VB script
     * 
     * @param array $data
     * @return string
     * @access public
     */
    public function options($data)
    {
        
        $renderOpt = '';
        
        
        foreach ($this->defRenderOptions as $key => $value) {
            if (isset($data[$key])) {
                if (\is_bool($data[$key])){
                    $renderOpt .= var_export($data[$key], true) . '|';
                } else {
                    $renderOpt .= $data[$key] . '|';
                }
            } else {
                $renderOpt .= $this->defRenderOptions[$key][0] . '|';
            }
        }

        //remove the last '|' from the string
        $renderOpt = \substr($renderOpt, 0, -1);
        
        return $renderOpt;
    }
    
    /**
     * generate the list of long arguments for the command line interface 
     * options
     *
     * @return array
     * @access public
     */
    public function cliArgs()
    {    
        $opt = array();
        
        foreach ($this->defRenderOptions as $key => $value) {
            $opt[] = $key . ':';
        }  
        
        return $opt;
    }    
    
}