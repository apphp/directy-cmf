<?php
/**
 * Modules model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct             	_relations					_loadData
 * model (static)          	_afterSave
 * reLoadData
 * reOrder
 * param (static)
 * isInstalled (static)
 * getModules
 *
 */

class Modules extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'modules';
	/** @var array */    
	private static $_arrModule = array();

    /**
	 * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();
		$this->_loadData();
    }

    /**
     * Returns the static model of the specified AR class
     */
    public static function model()
    {
        return parent::model(__CLASS__);
    }
    	
	/**
     * Defines relations between different tables in database and current $_table
	 */
	protected function _relations()
	{

	}
    
	/**
	 * This method is invoked after saving a record successfully
	 * @param string $pk
	 * You may override this method
	 */
	protected function _afterSave($pk = '')
	{
        // $pk - key used for saving operation
        if(BackendMenus::model()->exists('module_code = :moduleCode', array(':moduleCode'=>$this->_columns['code']))){
            if($this->_columns['show_in_menu'] == 1){
                // Do nothing    
            }else{
                BackendMenus::model()->deleteMenu($this->_columns['code']);
            }                
        }elseif($this->_columns['show_in_menu'] == 1){            
            BackendMenus::model()->addMenu($this->_columns['code'], $this->_columns['name']);
        }
	}
    
	/**
	 * Re-orders modules in table after one of modules was uninstalled
	 * @param string $moduleType
	 * @return bool
	 */
	public function reOrder($moduleType = 'application')
	{
		$records = $this->findAll('is_system = '.($moduleType == 'system' ? 1 :0));
		
		$count = 0;
		foreach($records as $record){
			$this->updateByPk($record['id'], array('sort_order'=>++$count));
		}
	}
	
	/**
	 * Re-loads data of all modules one time
	 */
	public function reLoadData()
	{
		self::$_arrModule = array();
		$this->_loadData();
	}	
	
	/**
	 *	Returns module data parameter
	 *	Ex.: Modules::model()->param('moduleName', 'paramName')
	 *	@param string $module
	 *	@param string $param
	 *	@return mixed
	 */
	public static function param($module = '', $param = '')
	{
		return isset(self::$_arrModule[$module][$param]) ? self::$_arrModule[$module][$param] : null;
	}
	
	/**
	 *	Checks if module is installed
	 *	Ex.: Modules::model()->isInstalled('moduleName')
	 *	@param string $module
	 *	@return bool
	 */
	public static function isInstalled($module = '')
	{
		return isset(self::$_arrModule[$module]['is_installed']) ? (bool)self::$_arrModule[$module]['is_installed'] : false;
	}	

	/**
	 * Get all modules data
	 */
	public function getModules()
	{
		return self::$_arrModule;
	}

	/**
	 * Loads data of all modules one time
	 */
	private function _loadData()
	{
		$modules = $this->findAll();
		foreach($modules as $key => $val){			
			self::$_arrModule[$val['code']] = array(
				'is_installed' 	=> $val['is_installed'],
				'is_active' 	=> $val['is_active'],
				'version' 		=> $val['version'],
				'has_test_data' => $val['has_test_data'],
			);
		}
	}

}
