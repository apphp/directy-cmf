<?php
/**
 * Languages model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct            	_beforeDelete
 * model (static)         	_beforeSave
 * drawSelector (static)  	_afterSave
 * getError               	_afterDelete 
 * getDefaultLanguage     
 *
 */

class Languages extends CActiveRecord
{
    
    /** @var string */    
    protected $_table = 'languages';
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
	 * Draws language selector
	 * @param array $params
	 *      'display' => 'names|keys|icons|dropdown|list',
	 *      'class'	=> '',
	 *      'forceDrawing' => false,
	 * @return string - html code for languages selector
	 */
	public static function drawSelector($params = array())
	{
        // Values: 'display' => 'names|keys|icons|dropdown|list',
        $display = isset($params['display']) ? $params['display'] : 'icons';
		$class = isset($params['class']) ? $params['class'] : '';
		$forceDrawing = isset($params['forceDrawing']) ? (bool)$params['forceDrawing'] : false;
        
        $arrLanguages = array();
        $languages = self::model()->findAll(array('condition'=>"is_active = 1 AND used_on IN ('front-end', 'global')", 'orderBy'=>'sort_order ASC'));
        if(is_array($languages)){
        	foreach($languages as $lang){
	            $arrLanguages[$lang['code']] = array('name'=>$lang['name_native'], 'icon'=>$lang['icon']);
	        }
        }

        $output = CWidget::create('CLanguageSelector', array(
            'languages' 		=> $arrLanguages,
            'display' 			=> $display,
            'imagesPath' 		=> 'images/flags/',
            'currentLanguage' 	=> A::app()->getLanguage(),
			'forceDrawing'		=> $forceDrawing,
			'class'				=> $class
        ));
		
        return $output;
	}

	/**
	 * This method is invoked before saving a record
	 * @param string $id
	 */
	protected function _beforeSave($id = 0)
	{
		// If language is default - it must be active
		if($this->is_default) $this->is_active = 1;
		return true;
	}

	/**
	 * This method is invoked after saving a record successfully
	 * @param string $id
	 */
	protected function _afterSave($id = 0)
	{
		$this->_isError = false;
		
		// If this language is default - remove default flag in all other languages
		if($this->is_default){
            if(!$this->_db->update($this->_table, array('is_default'=>0), 'id != :id', array(':id'=>(int)$id))){    
        		$this->_isError = true;
        	}
		}
		
		// After insert new language - clone site descriptions and translation data for all needed tables
		if($this->isNewRecord()){
            // Create folder for new language messages
            CFile::copyDirectory('protected/messages/'.A::app()->getLanguage(), 'protected/messages/'.$this->code);
            
			// Get list of all tables
			$allTables = $this->_db->showTables();
			$pattern = '/'.CConfig::get('db.prefix').'(\w*(_translations|site_info|_description))/'; 
			if(is_array($allTables)){
				foreach($allTables as $table){
					// Check if it is a translation or description table
					if(preg_match($pattern, $table[0], $matches)){	
                        // Delete rows with current language from table if they exist (to prevent tails)
						$this->_db->delete($matches[1], 'language_code = :language_code', array(':language_code'=>$this->code));
                            
						// Get list of all columns, except id
						$columns = $this->_db->showColumns($matches[1]);				
						$colsList = $colsListForSelect = '';
						if(is_array($columns)){
							foreach($columns as $col){
								if($col[0] != 'id'){
									$colsList = $colsList == '' ? $col[0] : $colsList.','.$col[0];		
									// Put new language code value in the new rows 					
									if($col[0] == 'language_code'){
										$colsListForSelect = $colsListForSelect == '' ? '"'.$this->code.'"' : $colsListForSelect.',"'.$this->code.'"';
									}else{
										$colsListForSelect = $colsListForSelect == '' ? $col[0] : $colsListForSelect.','.$col[0];
									}	
								}
							}
						}
						// Clone data for new language from current active language
						$sql = 'INSERT INTO '.$table[0].' ('.$colsList.') (SELECT '.$colsListForSelect.' FROM '.$table[0].' WHERE language_code = "'.A::app()->getLanguage().'")';
						if(!$this->_db->customExec($sql)){
							$this->_isError = true;
						}		
					}
				}
			}
		}else{
            // Set default language if admin disabled his preferred language
            if(!$this->is_active && $this->code == A::app()->getLanguage()){
                if($defaultLang = Languages::model()->find('is_default = 1')){
                    $params = array(
                        'locale' => $defaultLang->lc_time_name,
                        'direction' => $defaultLang->direction,
						'icon' => $defaultLang->icon,
						'name' => $defaultLang->name,
						'name_native' => $defaultLang->name_native,
                    );
                    A::app()->setLanguage($defaultLang->code, $params);
                }			
            }
        }
	}
	
	/**
	 * This method is invoked before deleting a record (after validation, if any)
	 * @param string $pk
	 * @return boolean
	 */
	protected function _beforeDelete($pk = '')
	{        
        if($this->count() > 1){
            return true;
        }else{
            $this->_isError = true;
            $this->_errorMessage = A::t('core', 'You cannot delete the last remaining record in table {table}!', array('{table}'=>'<b>'.ucfirst($this->_table).'</b>'));
            return false;    
        }
	}

	/**
	 * This method is invoked after deleting a record successfully
	 * @param string $pk
	 */
	protected function _afterDelete($pk = 0)
	{
		$this->_isError = false;
		// Delete site description and translation data for the deleted language from all needed tables
		
		// Get list of all tables
		$allTables = $this->_db->showTables();
		$pattern = '/'.CConfig::get('db.prefix').'(\w*(_translations|site_info|_description))/'; 
		if(is_array($allTables)){
			foreach($allTables as $table){
				// check if it is a translation or description table
				if(preg_match($pattern, $table[0], $matches)){
					if(false === $this->_db->delete($matches[1], 'language_code = :language_code', array(':language_code'=>$this->code))){
						$this->_isError = true;
					}
				}
			}
		}
	}
	
	/**
	 * Returns default language
	 * @return string
	 */
	public function getDefaultLanguage()
	{
		return ($lang = $this->find('is_default = 1')) ? $lang->code : '';
	}	
	
	/** 
	 * Returns boolean that indicates if the last operation was successfull
	 * @return boolean
	 */
	public function getError()
	{
		return $this->_isError;
	}
	
}
