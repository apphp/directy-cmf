<?php
/**
 * CWidgs - widgets base class
 * This file contains widgets classes implementing feature
 *
 * @project ApPHP Framework
 * @author ApPHP <info@apphp.com>
 * @link http://www.apphpframework.com/
 * @copyright Copyright (c) 2012 - 2015 ApPHP Framework
 * @license http://www.apphpframework.com/license/
 *
 * PUBLIC (static):			PROTECTED:					PRIVATE:		
 * ----------               ----------                  ----------
 * changeKeyCaseRecursive
 * params
 * keyAt
 * issetKey
 * unsetKey
 * 
 */	  

class CWidgs
{

	/**
	 * @var boolean whether the keys are case-sensitive. Defaults to false.
	 */
	protected static $_caseSensitive = true;
	/**
	 * @var int 
	 */
	protected static $_case = CASE_LOWER;
	/**
	 * @var array
	 */
	protected static $_params = array();

	/**
	 * Initialize
	 * @param mixed @params
	 * @return void
	 */
    public static function init($params = array())
    {
		// Store params in class property 
		self::$_params = $params;
		
		// Change param keys if defined in config
		if(CConfig::get('widgets.paramKeysSensitive') === true){
			self::$_caseSensitive = true;
		}
		
		if(!self::$_caseSensitive){
			$params = array_change_key_case($params, self::$_case);
			$params = self::changeKeyCaseRecursive($params);
			self::$_params = $params;
		}
	}

	/**
	 * Change key case recursively
	 * @param mixed @params
	 * @return mixed
	 */
	private static function changeKeyCaseRecursive($params)
	{
		foreach($params as $key => $val){ 
			if(is_array($params[$key])){ 
				$params[$key] = array_change_key_case($params[$key], self::$_case);
				$params[$key] = self::changeKeyCaseRecursive($params[$key]); 
			}
		}
		
		return $params;
	}

	/**
	 * Returns the item with the specified key
	 * This implement by converting the key to lower case first if {@link caseSensitive} is false
	 * @param mixed $key the key
	 * @param mixed $default
	 * @param string $validation
	 * @param array $validationData
	 * @return mixed the element at the offset, null if no element is found at the offset
	 */	
	protected static function params($key, $default = null, $validation = '', $validationData = array())
	{
		if(!self::$_caseSensitive){
			$key = (self::$_case === CASE_UPPER) ? strtoupper($key) : strtolower($key);
		}
		
		$paramParts = explode('.', $key);
		$parts = count($paramParts);
		$result = null;
		
		if($parts == 1){
			if(isset(self::$_params[$paramParts[0]])){
				if($validation == 'is_array'){
					if(is_array(self::$_params[$paramParts[0]])){
						$result = self::$_params[$paramParts[0]];
					}
				}else if($validation == 'in_array'){
					if(in_array(self::$_params[$paramParts[0]], $validationData)){
						$result = self::$_params[$paramParts[0]];
					}				
				}else{
					$result = self::$_params[$paramParts[0]];
				}				
			}
		}else if($parts == 2){
			if(isset(self::$_params[$paramParts[0]][$paramParts[1]])){
				$result = self::$_params[$paramParts[0]][$paramParts[1]];
			}			
		}else if($parts == 3){
			if(isset(self::$_params[$paramParts[0]][$paramParts[1]][$paramParts[2]])){
				$result = self::$_params[$paramParts[0]][$paramParts[1]][$paramParts[2]];
			}			
		}
		
		return ($result === null && $default !== null) ? $default : $result;
	}	

	/**
	 * Returns the item with the specified key from the given array
	 * This implement by converting the key to lower case first if {@link caseSensitive} is false
	 * @param mixed $key the key
	 * @param array $array
	 * @param mixed $default
	 * @param string $validation
	 * @return mixed the element at the offset, null if no element is found at the offset
	 */	
	protected static function keyAt($key, $array = array(), $default = null, $validation = '')
	{
		if(!self::$_caseSensitive){
			$key = (self::$_case === CASE_UPPER) ? strtoupper($key) : strtolower($key);
		}

		$paramParts = explode('.', $key);
		$parts = count($paramParts);
		$result = null;
		
		if($parts == 1){
			if(isset($array[$paramParts[0]])){
				if($validation == 'is_array'){
					if(is_array($result = $array[$paramParts[0]])){
						$result = $array[$paramParts[0]];
					}
				}else{
					$result = $array[$paramParts[0]];
				}				
			}
		}else if($parts == 2){
			if(isset($array[$paramParts[0]][$paramParts[1]])){
				$result = $array[$paramParts[0]][$paramParts[1]];
			}			
		}
		
		return ($result === null && $default !== null) ? $default : $result;
	}
	
	/**
	 * Cehck if specified key exists in a given array 
	 * This implement by converting the key to lower case first if {@link caseSensitive} is false
	 * @param mixed $key the key
	 * @param array &$array
	 * @return bool
	 */	
	protected static function issetKey($key, &$array = array())
	{
		if(!self::$_caseSensitive){
			$key = (self::$_case === CASE_UPPER) ? strtoupper($key) : strtolower($key);
		}
	
		$paramParts = explode('.', $key);
		$parts = count($paramParts);
		
		if($parts == 1){
			return isset($array[$paramParts[0]]) ? true : false;
		}else if($parts == 2){
			return isset($array[$paramParts[0]][$paramParts[1]]) ? true : false;
        }else{
            return isset($array[$key]) ? true : false;
		}
	}

	/**
	 * Unset item with the specified key from the given array
	 * This implement by converting the key to lower case first if {@link caseSensitive} is false
	 * @param mixed $key the key
	 * @param array &$array
	 * @return void
	 */	
	protected static function unsetKey($key, &$array = array())
	{
		if(!self::$_caseSensitive){
			$key = (self::$_case === CASE_UPPER) ? strtoupper($key) : strtolower($key);
		}
		
		if(isset($array[$key])){
			unset($array[$key]);
		}
	}

}
