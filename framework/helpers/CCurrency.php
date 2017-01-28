<?php
/**
 * CCurrency is a helper class that provides a set of helper methods for common currency operations
 *
 * @project ApPHP Framework
 * @author ApPHP <info@apphp.com>
 * @link http://www.apphpframework.com/
 * @copyright Copyright (c) 2012 - 2013 ApPHP Framework
 * @license http://www.apphpframework.com/license/
 *
 * PUBLIC:					PROTECTED:					PRIVATE:		
 * ----------               ----------                  ----------
 * format
 * 
 */	  

class CCurrency
{
    /**
     * Format price value
     * @param mixed $price
     * @param array $params
     */
    public static function format($price, $params = array())
    {
 		// get currency info
        $rate               = isset($params['rate']) ? $params['rate'] : A::app()->getCurrency('rate');		
        $decimals           = isset($params['decimals']) ? $params['decimals'] : A::app()->getCurrency('decimals');		
        $symbol             = isset($params['symbol']) ? $params['symbol'] : A::app()->getCurrency('symbol');
        $symbolPlace        = isset($params['symbolPlace']) ? $params['symbolPlace'] : A::app()->getCurrency('symbol_place'); 
        $decimalSeparator   = isset($params['decimalSeparator']) ? $params['decimalSeparator'] : '';
        $thousandsSeparator = isset($params['thousandsSeparator']) ? $params['thousandsSeparator'] : '';
		
        $return  = ($symbolPlace == 'before') ? $symbol : '';
        $return .= ($decimals != '' && $rate != '') ? number_format($price * $rate, $decimals, $decimalSeparator, $thousandsSeparator) : $price;
        $return .= ($symbolPlace == 'after') ? $symbol : '';
               
        return $return;
          
    }

}