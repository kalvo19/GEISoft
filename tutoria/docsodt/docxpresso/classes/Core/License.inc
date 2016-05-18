<?php

/**
 * License
 *
 * @copyright  Copyright (c) 2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Core;

/**
 * Docxpresso license
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class License
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
     * chekcs the validity of the license key
     *
     * @param string $license
     * @return boolean
     * @access public
     * @static
     */
    public static function check($license)
    {
        $serial = substr($license, 0, -5);
	$seed = 'HTML52PDF-' . $serial;
	$code = $serial . strtoupper(substr(sha1($seed), 5, 5));
	if ($code == $license) {
            return true;
	} else {
            return false;
	}
    }   
}