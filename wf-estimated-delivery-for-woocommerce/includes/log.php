<?php
if (!defined('ABSPATH')) {
    exit;
}

class Estimated_Delivery_Log 
{

    /**
     * Hold the Record log status whether enabled or not
     */
    private static $record_log_status;

    /**
     * It will hold the WC_Logger class object .
     */
    private static $wc_logger_oj;

    public static function init_log()
    {
        $content="<------------------- Estimated Delivery Log File  ------------------->\n";
        return $content;
    }

    /**
     * To write the estimated delivery in WC Log file.
     */
    public static function log_update($msg,$title)
    {
        if( empty(self::$record_log_status) ) {
            self::$record_log_status = get_option('wf_estimated_delivery_record_log');
        }

        if( self::$record_log_status === 'yes' )
        {
            if( empty(self::$wc_logger_oj) ) {
                self::$wc_logger_oj = new WC_Logger();
            }
            
            $head       = "<------------------- ( ".$title." ) ------------------->\n";
            $log_text   = $head.print_r((object)$msg,true);
            self::$wc_logger_oj->add("estimated_delivery_log",$log_text);
        }
    }
}
