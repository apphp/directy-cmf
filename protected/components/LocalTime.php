<?php
/**
 * LocalTime - component for working with local date and time
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 *
 * STATIC
 * -------------------------------------------
 * currentTime
 * currentDate
 * currentDateTime
 * 							
 *
 */

class LocalTime extends CComponent
{
    
    /**
     * Returns current time in a given format
     * @param $format
     * @return string
     */
	public static function currentTime($format = 'H:i:s')
	{
        // check if there is a DST (daylight saving time)
        $offset = (Bootstrap::init()->getSettings('daylight_saving') && date('I', time())) ? 3600 : 0;    
        return date($format, time() + $offset);
    }

    /**
     * Returns current date in a given format
     * @param $format
     * @return string
     */
	public static function currentDate($format = 'Y-m-d')
	{
        // check if there is a DST (daylight saving time)
        $offset = (Bootstrap::init()->getSettings('daylight_saving') && date('I', time())) ? 3600 : 0;    
        return date($format, time() + $offset);
    }

    /**
     * Returns current datetime in a given format
     * @param $format
     * @return string
     */
	public static function currentDateTime($format = 'Y-m-d H:i:s')
	{
        // check if there is a DST (daylight saving time)
        $offset = (Bootstrap::init()->getSettings('daylight_saving') && date('I', time())) ? 3600 : 0;    
        return date($format, time() + $offset);
    }
    
}