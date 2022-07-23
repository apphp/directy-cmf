<?php
/**
 * CArray is a helper class that provides a set of helper methods for common array operations
 *
 * @project ApPHP Framework
 * @author ApPHP <info@apphp.com>
 * @link http://www.apphpframework.com/
 * @copyright Copyright (c) 2012 - 2013 ApPHP Framework
 * @license http://www.apphpframework.com/license/
 *
 * PUBLIC:					PROTECTED:					PRIVATE:		
 * ----------               ----------                  ----------
 * groupByValue
 * 
 */	  

class CArray
{

	/**
	 * Groups array by sub array value
	 * [0] => array {
     *      'value' => $value
     *   }
	 * @param array $array 
	 * @param string $value
	 * @return array
	 */
	public static function groupByValue($array, $value = '')
	{
        $return = array();
        
        if(is_array($array)){
            foreach($array as $k => $v){
                if(isset($v[$value])){
                    $return[$v[$value]][] = $v;    
                }                
            }            
        }
        
        return $return;        
    }

}