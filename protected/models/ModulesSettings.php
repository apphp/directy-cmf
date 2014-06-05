<?php
/**
 * ModulesSettings
 *
 * PUBLIC:                PROTECTED               PRIVATE
 * ---------------        ---------------         ---------------
 * __construct             loadSettings 
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
	private static $arrModuleSettings = array();

    /**
	 * Class default constructor
     */
    public function __construct()
    {		
        parent::__construct();
		$this->loadSettings();
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
				if(!$this->db->update($this->_table, array('property_value'=>$value), 'id='.$id)){
					$result = false;
				}
			}
		}
		return $result;
	}
	
	/**
	 *	Returns module settings parameter
	 *	@param $module
	 *	@param $param
	 */
	public static function param($module = '', $param = '')
	{
		return isset(self::$arrModuleSettings[$module][$param]) ? self::$arrModuleSettings[$module][$param] : '';
	}
	
	/**
	 * Get all modules settings
	 */
	public function getSettings()
	{
		return self::$arrModuleSettings;
	}
	
	/**
	 * Get all modules shortcodes
	 */
	public function getShortcodes()
	{
		$arrModuleSettings = array();
		$modulesSettings = $this->db->select('
            SELECT ms.*
			FROM '.CConfig::get('db.prefix').$this->_table.' ms
			INNER JOIN '.CConfig::get('db.prefix').'modules m ON ms.module_code = m.code  
            WHERE ms.property_key = \'shortcode\' AND  m.is_active = 1 AND m.is_installed = 1'
		);
		
		foreach($modulesSettings as $key => $val){			
			$arrModuleSettings[$val['module_code']] = $val['property_value'];
		}
		return $arrModuleSettings;
	}

	/**
	 * Loads all modules settings 1 time
	 */
	private function loadSettings()
	{
		$modulesSettings = $this->findAll();
		foreach($modulesSettings as $key => $val){			
			self::$arrModuleSettings[$val['module_code']][$val['property_key']] = $val['property_value'];
		}
	}
	
}
