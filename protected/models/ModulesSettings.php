<?php
/**
 * ModulesSettings model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct                                    	  	_loadSettings 
 * model (static)
 * param (static)
 * update
 * getSettings
 * getShortcodes
 *
 */

class ModulesSettings extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'module_settings';
	/** @var array */    
	private static $_arrModuleSettings = array();

    /**
	 * Class default constructor
     */
    public function __construct()
    {		
        parent::__construct();
		$this->_loadSettings();
    }

    /**
     * Returns the static model of the specified AR class
     */
    public static function model()
    {
        return parent::model(__CLASS__);
    }
    
	/**
	 * Updates all settings for given module
	 */
	public function update($valuesArray = array())
	{
		$result = true;
		if(is_array($valuesArray)){
			foreach($valuesArray as $id => $value){
                if(!$this->_db->update($this->_table, array('property_value'=>$value), 'id = :id', array(':id'=>(int)$id))){    
					$result = false;
				}
			}
		}
		return $result;
	}
	
	/**
	 *	Returns module settings parameter
	 *	Ex.: ModulesSettings::model()->param('moduleName', 'paramName')
	 *	@param string $module
	 *	@param string $param
	 *	@param string $type
	 *	@return mixed
	 */
	public static function param($module = '', $param = '', $type = 'value')
	{
		return isset(self::$_arrModuleSettings[$module][$param][$type]) ? self::$_arrModuleSettings[$module][$param][$type] : null;
	}
	
	/**
	 * Get all modules settings
	 */
	public function getSettings()
	{
		return self::$_arrModuleSettings;
	}
	
	/**
	 * Get all modules shortcodes
	 */
	public function getShortcodes()
	{
		$_arrModuleSettings = array();
		$modulesSettings = $this->_db->select("SELECT ms.*, m.class_code
			FROM ".CConfig::get('db.prefix').$this->_table." ms
			INNER JOIN ".CConfig::get('db.prefix')."modules m ON ms.module_code = m.code  
            WHERE ms.property_key = 'shortcode' AND  m.is_active = 1 AND m.is_installed = 1");
		
		if(is_array($modulesSettings)){
			foreach($modulesSettings as $key => $val){			
				$_arrModuleSettings[$val['module_code']] = array('class_code'=>$val['class_code'], 'value'=>$val['property_value'], 'description'=>$val['description']);
			}
		}
		return $_arrModuleSettings;
	}

	/**
	 * Loads settings of all modules one time
	 */
	private function _loadSettings()
	{
		$modulesSettings = $this->findAll();
		foreach($modulesSettings as $key => $val){			
			self::$_arrModuleSettings[$val['module_code']][$val['property_key']] = array(
                'value'=>$val['property_value'],
                'source'=>$val['property_source']
            );
		}
	}
	
}
