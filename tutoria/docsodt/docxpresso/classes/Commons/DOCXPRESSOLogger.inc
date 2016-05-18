<?php

/**
 * Logger
 *
 * @copyright  Copyright (c) 2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Commons;

/**
 * Logger
 *
 * @package    Docxpresso
 * @subpackage Commons
 */

class DOCXPRESSOLogger
{
    /**
     * log
     * @access private
     * @static
     * @var string
     */
    private static $_log = NULL;

    /**
     * Singleton, return instance of class
     *
     * @access public
     * @param $message
     * @param $level Values: trace, debug, info, warn, error, fatal
     * @static
     */
    public static function logger($message, $level)
    {
        $levels = array(
            'debug', 
            'info', 
            'notice', 
            'warning', 
            'error', 
            'critical',
        );

        if (!self::$_log) {
            self::$_log = new \Monolog\Logger('doxcpersso');
            // file handler
            $destinationLog = dirname(__FILE__) . '/../../log/docxpreso.log';
            self::$_log->pushHandler(
                new \Monolog\Handler\StreamHandler(
                    $destinationLog, 
                    \Monolog\Logger::WARNING
                )
            );
        }

        // only some leves are valids
        if (in_array($level, $levels)) {
            $stringLevel = 'add' . ucfirst($level);
            self::$_log->$stringLevel($message);
        }
    }
}