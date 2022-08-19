<?php
/**
 * LocalTime - component for working with local date and time
 *
 * PUBLIC (static):         PRIVATE:
 * -----------              ------------------
 * init
 * currentTime
 * currentDate
 * currentDateTime
 *
 */

class LocalTime extends CComponent
{
    
	/**
     *	Returns the instance of object
     *	@return current class
     */
	public static function init()
	{
		return parent::init(__CLASS__);
	}

    /**
     * Returns current time in a given format
     * @param mixed $format
     * @return string
     */
	public static function currentTime($format = 'H:i:s')
	{
        // Check if there is a DST (daylight saving time)
        $offset = (Bootstrap::init()->getSettings('daylight_saving') && date('I', time())) ? 3600 : 0;    
        return ($format === null) ? (time() + $offset) : date($format, time() + $offset);
    }

    /**
     * Returns current date in a given format
     * @param mixed $format
     * @return string
     */
	public static function currentDate($format = 'Y-m-d')
	{
        // Check if there is a DST (daylight saving time)
        $offset = (Bootstrap::init()->getSettings('daylight_saving') && date('I', time())) ? 3600 : 0;    
		return ($format === null) ? (time() + $offset) : date($format, time() + $offset);
    }

    /**
     * Returns current datetime in a given format
     * @param mixed $format
     * @return string
     */
	public static function currentDateTime($format = 'Y-m-d H:i:s')
	{
        // Check if there is a DST (daylight saving time)
        $offset = (Bootstrap::init()->getSettings('daylight_saving') && date('I', time())) ? 3600 : 0;		
        return ($format === null) ? (time() + $offset) : date($format, time() + $offset);
    }
    
}