<?php
/**
 * ModulesSettings model
 *
 * PUBLIC:                PROTECTED               PRIVATE
 * ---------------        ---------------         ---------------
 * __construct                                    _loadSettings 
 * update
 * getSettings
 * getShortcodes
 *
 * STATIC:
 * ------------------------------------------
 * model
 * param
 * get
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
   	public static function model($className = __CLASS__)
   	{
		return parent::model($className);
   	}
    
	/**
	 * Updates all settings for given module
	 */
	public function update($valuesArray)
	{
		$result = true;
		if(is_array($valuesArray)){
			foreach($valuesArray as $id => $value){
				if(!$this->_db->update($this->_table, array('property_value'=>$value), 'id='.$id)){
					$result = false;
				}
			}
		}
		return $result;
	}
	
	/**
	 *	Returns module settings parameter
	 *	@param string $module
	 *	@param string $param
	 *	@param string $type
	 */
	public static function param($module = '', $param = '', $type = 'value')
	{
		return isset(self::$_arrModuleSettings[$module][$param][$type]) ? self::$_arrModuleSettings[$module][$param][$type] : '';
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
		$modulesSettings = $this->_db->select('
            SELECT ms.*
			FROM '.CConfig::get('db.prefix').$this->_table.' ms
			INNER JOIN '.CConfig::get('db.prefix').'modules m ON ms.module_code = m.code  
            WHERE ms.property_key = \'shortcode\' AND  m.is_active = 1 AND m.is_installed = 1'
		);
		
		foreach($modulesSettings as $key => $val){			
			$_arrModuleSettings[$val['module_code']] = array('value'=>$val['property_value'], 'description'=>$val['description']);
		}
		return $_arrModuleSettings;
	}

	/**
	 * Loads all modules settings 1 time
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
