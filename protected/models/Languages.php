<?php
/**
 * Languages model
 *
 * PUBLIC:                PROTECTED               PRIVATE
 * ---------------        ---------------         ---------------
 * __construct            _beforeDelete
 * getError               _beforeSave
 * getDefaultLanguage     _afterSave
 *                        _afterDelete
 * 
 *
 * STATIC:
 * ------------------------------------------
 * model
 * drawSelector
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
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

  	/**
	 * Draws language selector
	 * @return string - html code for languages selector
	 */
	public static function drawSelector()
	{
        $arrLanguages = array();
        $templateName = A::app()->view->getTemplate();
        $languages = self::model()->findAll("is_active = 1 AND used_on IN ('front-end', 'global')");
        if(is_array($languages)){
        	foreach($languages as $lang){
	            $arrLanguages[$lang['code']] = array('name'=>$lang['name_native'], 'icon'=>$lang['icon']);
	        }
        }

        $output = CWidget::create('CLanguageSelector', array(
            'languages' => $arrLanguages,
            'display' => 'icons',
            'imagesPath' => 'images/flags/',
            'currentLanguage' => A::app()->getLanguage(),
        ));
		
        return $output;
	}

	/**
	 * This method is invoked before saving a record
	 * @param string $id
	 */
	protected function _beforeSave($id = 0)
	{
		// if language is default - it must be active
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
		
		// if this language is default - remove default flag in all other languages
		if($this->is_default){
        	if(!$this->_db->update($this->_table, array('is_default'=>0), 'id != '.$id)){
        		$this->_isError = true;
        	}
		}
		
		// after insert new language - clone site descriptions and translation data for all needed tables
		if($this->isNewRecord()){
            // create folder for new language messages
            CFile::copyDirectory('protected/messages/'.A::app()->getLanguage(), 'protected/messages/'.$this->code);
            
			// get list of all tables
			$allTables = $this->_db->showTables();
			$pattern = '/'.CConfig::get('db.prefix').'(\w*(_translations|site_info|_description))/'; 
			if(is_array($allTables)){
				foreach($allTables as $table){
					// check if it is a translation or description table
					if(preg_match($pattern, $table[0], $matches)){	
						// get list of all columns, except id
						$columns = $this->_db->showColumns($matches[1]);				
						$colsList = $colsListForSelect = '';
						if(is_array($columns)){
							foreach($columns as $col){
								if($col[0] != 'id'){
									$colsList = $colsList == '' ? $col[0] : $colsList.','.$col[0];		
									// put new language code value in the new rows 					
									if($col[0] == 'language_code'){
										$colsListForSelect = $colsListForSelect == '' ? '"'.$this->code.'"' : $colsListForSelect.',"'.$this->code.'"';
									}else{
										$colsListForSelect = $colsListForSelect == '' ? $col[0] : $colsListForSelect.','.$col[0];
									}	
								}
							}
						}
						// clone data for new language from current active language
						$sql = 'INSERT INTO '.$table[0].' ('.$colsList.') (SELECT '.$colsListForSelect.' FROM '.$table[0].' WHERE language_code = "'.A::app()->getLanguage().'")';
						if(!$this->_db->customExec($sql)){
							$this->_isError = true;
						}		
					}
				}
			}
		}else{
            // set default language if admin disabled his preferred language
            if(!$this->is_active && $this->code == A::app()->getLanguage()){
                if($defaultLang = Languages::model()->find('is_default = 1')){
                    $params = array(
                        'locale' => $defaultLang->lc_time_name,
                        'direction' => $defaultLang->direction
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
		// delete site description and translation data for the deleted language from all needed tables
		
		// get list of all tables
		$allTables = $this->_db->showTables();
		$pattern = '/'.CConfig::get('db.prefix').'(\w*(_translations|site_info|_description))/'; 
		if(is_array($allTables)){
			foreach($allTables as $table){
				// check if it is a translation or description table
				if(preg_match($pattern, $table[0], $matches)){
					if(false === $this->_db->delete($matches[1], 'language_code="'.$this->code.'"')){
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
