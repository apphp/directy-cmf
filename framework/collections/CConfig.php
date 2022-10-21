<?php
/**
 * CConfig core class file
 *
 * @project ApPHP Framework
 * @author ApPHP <info@apphp.com>
 * @link http://www.apphpframework.com/
 * @copyright Copyright (c) 2012 - 2019 ApPHP Framework
 * @license http://www.apphpframework.com/license/
 *
 * PUBLIC (static):			PROTECTED:					PRIVATE:		
 * ----------               ----------                  ----------
 * load
 * set
 * get
 * exists
 *
 */	  

class CConfig
{   
	/** @var array */	
	private static $_conf;


 	/**
 	 * Loads config parameters
 	 * @param array $config
 	 * @return void
 	 */
	public static function load($config)
 	{
		self::$_conf = $config;
		//json_decode(json_encode($arr));
 	}

 	/**
 	 * Sets config parameters
 	 * @param string $param
 	 * @param mixed $value
 	 * @return void
 	 */
  	public static function set($param = '', $value = '')
 	{
		if(!empty($param)){
			$paramParts = explode('.', $param);
			$parts = count($paramParts);
			$ind1 = isset($paramParts[0]) ? $paramParts[0] : null;
			$ind2 = isset($paramParts[1]) ? $paramParts[1] : null;
			$ind3 = isset($paramParts[2]) ? $paramParts[2] : null;
			$ind4 = isset($paramParts[3]) ? $paramParts[3] : null;
			if($parts == 1){
				if(isset(self::$_conf[$ind1])){
					self::$_conf[$ind1] = $value;
				}
			}elseif($parts == 2){
				if(isset(self::$_conf[$ind1][$ind2])){
					self::$_conf[$ind1][$ind2] = $value;
				}
			}elseif($parts == 3){
				if(isset(self::$_conf[$ind1][$ind2][$ind3])){
					self::$_conf[$ind1][$ind2][$ind3] = $value;
				}
			}elseif($parts == 4){
				if(isset(self::$_conf[$ind1][$ind2][$ind3][$ind4])){
					self::$_conf[$ind1][$ind2][$ind3][$ind4] = $value;
				}
			}
		}			
    }

 	/**
 	 * Get config parameters
 	 * @param string $param
 	 * @param mixed $default
 	 * @return mixed
 	 */
  	public static function get($param = '', $default = '')
 	{
		$result = '';
        
		if(!empty($param)){
			$paramParts = explode('.', $param);
			$parts = count($paramParts);
			$ind1 = isset($paramParts[0]) ? $paramParts[0] : null;
			$ind2 = isset($paramParts[1]) ? $paramParts[1] : null;
			$ind3 = isset($paramParts[2]) ? $paramParts[2] : null;
			$ind4 = isset($paramParts[3]) ? $paramParts[3] : null;
			if($parts == 1){
				if(isset(self::$_conf[$ind1])){
					$result = self::$_conf[$ind1];
				}
			}elseif($parts == 2){
				if(isset(self::$_conf[$ind1][$ind2])){
					$result = self::$_conf[$ind1][$ind2];
				}
			}elseif($parts == 3){
				if(isset(self::$_conf[$ind1][$ind2][$ind3])){
					$result = self::$_conf[$ind1][$ind2][$ind3];
				}
			}elseif($parts == 4){
				if(isset(self::$_conf[$ind1][$ind2][$ind3][$ind4])){
					$result = self::$_conf[$ind1][$ind2][$ind3][$ind4];
				}
			}
		}			
		
		return (empty($result) && !empty($default)) ? $default : $result;
 	}

 	/**
 	 * Check if config parameter exists
 	 * @param string $param
 	 * @return mixed
 	 */
  	public static function exists($param = '')
 	{
		$result = false;
        
		if(!empty($param)){
			$paramParts = explode('.', $param);
			$parts = count($paramParts);
			$ind1 = isset($paramParts[0]) ? $paramParts[0] : null;
			$ind2 = isset($paramParts[1]) ? $paramParts[1] : null;
			$ind3 = isset($paramParts[2]) ? $paramParts[2] : null;
			$ind4 = isset($paramParts[3]) ? $paramParts[3] : null;
			if($parts == 1){
				if(isset(self::$_conf[$ind1])){
					$result = true;
				}
			}elseif($parts == 2){
				if(isset(self::$_conf[$ind1][$ind2])){
					$result = true;
				}
			}elseif($parts == 3){
				if(isset(self::$_conf[$ind1][$ind2][$ind3])){
					$result = true;
				}
			}elseif($parts == 4){
				if(isset(self::$_conf[$ind1][$ind2][$ind3][$ind4])){
					$result = true;
				}
			}
		}			
		
		return $result;
 	}
	
}