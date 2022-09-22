<?php
/**
 * BackendMenus model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct             	_afterDelete               	_addMenuItem
 * model (static)		   	_relations
 * getError                
 * addMenu
 * deleteMenu 
 *
 */

class BackendMenus extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'backend_menus';
    /** @var string */
    protected $_tableTranslation = 'backend_menu_translations';
    /** @var bool */
    private $_isError = false;
	
    /**
	 * Class default constructor
     */
	public function __construct()
    {
        parent::__construct();
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
		return array(
			'id' => array(
				self::HAS_MANY,
				$this->_tableTranslation,
				'menu_id',
				'condition'=>'language_code = \''.A::app()->getLanguage().'\'',
				'joinType'=>self::LEFT_OUTER_JOIN,
				'fields'=>array('name'=>'menu_name')
			),
		);
	}	
		
	/**
	 * This method is invoked after deleting a record successfully
	 * @param string $id
	 */
	protected function _afterDelete($id = 0)
	{
		$this->_isError = false;
		// Delete menu names from translation table
		if(false === $this->_db->delete($this->_tableTranslation, 'menu_id = :menu_id', array(':menu_id'=>$id))){
			$this->_isError = true;
		}
	}
	
	/** 
	 * Returns boolean that indicates if the last operation was successfull
	 * @return boolean
	 */
	public function getError()
	{
		return $this->_isError;
	}    
	
    /** 
	 * Adds records to backend menus table
	 * @param string $code
	 * @param string $name
	 * @return boolean
	 */
	public function addMenu($code = '', $name = '')
	{
        // Add main item
        $lastInsertId = $this->_addMenuItem($code, $name, $code.'.png');
        
        // Add settings item
        $this->_addMenuItem($code, A::t('app', 'Settings'), '', 'modules/settings/code/'.$code, $lastInsertId);
        
        // Add sub items
		$configModule = CLoader::file('main.php', 'protected/modules/'.$code.'/config/', true);
		$managementLinks = (isset($configModule['managementLinks']) && is_array($configModule['managementLinks'])) ? $configModule['managementLinks'] : array();
        foreach($managementLinks as $key => $val){
            $this->_addMenuItem($code, $key, '', $val, $lastInsertId);
        }
        
        CFile::copyFile(
            APPHP_PATH.'/assets/modules/'.$code.'/images/icon.png',
            APPHP_PATH.'/templates/backend/images/icons/'.$code.'.png'
        );
	}
    
    /**
     * Adds new item to backend menu
	 * @param string $code
	 * @param string $name
	 * @param int $parentId
	 * @return boolean
	 */
    private function _addMenuItem($code = '', $name = '', $icon = '', $url = '', $parentId = 0)
    {
        $total = ($count = $this->count()) ? $count + 1 : 0;

        // Add new menu
        $sql = "INSERT INTO ".CConfig::get('db.prefix').$this->_table." (id, parent_id, url, module_code, icon, is_system, is_visible, sort_order) VALUES (NULL, :parent_id, :url, :code, :icon, 0, 1, :total)";
        $this->_db->customExec($sql, array(':parent_id'=>(int)$parentId, ':url'=>$url, ':code'=>$code, ':icon'=>$icon, ':total'=>(int)($total + 1)));
        $backendMenusLastId = $this->_db->lastInsertId();

        // Add new menu translations
        $sql = "INSERT INTO ".CConfig::get('db.prefix').$this->_tableTranslation." (id, menu_id, language_code, name) SELECT NULL, :menu_id, code, :name FROM ".CConfig::get('db.prefix')."languages";
        $this->_db->customExec($sql, array(':menu_id'=>(int)$backendMenusLastId, ':name'=>$name));
        
        return $backendMenusLastId;
    }
    
    /** 
	 * Deletes all related records from translations table
	 * @param string $code
	 * @param string $mode
	 * @return boolean
	 */
	public function deleteMenu($code = '', $mode = 'all')
	{
        if($mode == 'icons' || $mode == 'all'){
            // Delete all icons for the given module
            $menus = $this->_db->select('SELECT icon FROM '.CConfig::get('db.prefix').$this->_table.' WHERE module_code = :module_code', array(':module_code'=>$code));
            $totalMenus = count($menus);
            for($i = 0 ; $i < $totalMenus; $i++){
                if($menus[$i]['icon'] != '') CFile::deleteFile(APPHP_PATH.'/templates/backend/images/icons/'.$menus[$i]['icon']);
            }            
        }

        if($mode == 'all'){
            // Delete all records from menu translations table
            $sql = "DELETE
                    FROM ".CConfig::get('db.prefix').$this->_tableTranslation."
                    WHERE menu_id IN (
                            SELECT id
                            FROM ".CConfig::get('db.prefix').$this->_table."
                            WHERE module_code = :code
                        )
                    ";
            $this->_db->customExec($sql, array(':code'=>$code));
    
            // Delete all records from menus table
            $sql = "DELETE FROM ".CConfig::get('db.prefix').$this->_table." WHERE module_code = :code";
            $this->_db->customExec($sql, array(':code'=>$code));
        }
	}

}
