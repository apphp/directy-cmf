<?php
/**
 * Languages model
 *
 * PUBLIC:                PROTECTED               PRIVATE
 * ---------------        ---------------         ---------------
 * __construct
 * beforeSave
 * afterSave
 * afterDelete
 * getError
 * getDefaultLanguage
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
    
    private $isError = false;
    
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
	public function beforeSave($id = 0)
	{
		// if language is default - it must be active
		if($this->is_default) $this->is_active = 1;
		return true;
	}

	/**
	 * This method is invoked after saving a record successfully
	 * @param string $id
	 */
	public function afterSave($id = 0)
	{
		$this->isError = false;
		
		// if this language is default - remove default flag in all other languages
		if($this->is_default){
        	if(!$this->db->update($this->_table, array('is_default'=>0), 'id != '.$id)){
        		$this->isError = true;
        	}
		}
		
	    // create folder for new language messages
	    CFile::copyDirectory('protected/messages/'.A::app()->getLanguage(), 'protected/messages/'.$this->code);
	    
		// after insert new language - clone site descriptions and translation data for all needed tables
		if($this->isNewRecord()){
			// get list of all tables
			$allTables = $this->db->showTables();
			$pattern = '/'.CConfig::get('db.prefix').'(\w*(_translations|site_info|_description))/'; 
			if(is_array($allTables)){
				foreach($allTables as $table){
					// check if it is a translation or description table
					if(preg_match($pattern, $table[0], $matches)){	
						// get list of all columns, except id
						$columns = $this->db->showColumns($matches[1]);				
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
						$sql = 'INSERT INTO '.$table[0].' ('.$colsList.')
							(SELECT '.$colsListForSelect.'
							FROM '.$table[0].'
							WHERE language_code = "'.A::app()->getLanguage().'")';
						if(!$this->db->customExec($sql)){
							$this->isError = true;
						}		
					}
				}
			}
		}		
	}
	
	/**
	 * This method is invoked after deleting a record successfully
	 * @param string $id
	 */
	public function afterDelete($id = 0)
	{
		$this->isError = false;
		// delete site description and translation data for the deleted language from all needed tables
		
		// get list of all tables
		$allTables = $this->db->showTables();
		$pattern = '/'.CConfig::get('db.prefix').'(\w*(_translations|site_info|_description))/'; 
		if(is_array($allTables)){
			foreach($allTables as $table){
				// check if it is a translation or description table
				if(preg_match($pattern, $table[0], $matches)){
					if(false === $this->db->delete($matches[1], 'language_code="'.$this->code.'"')){
						$this->isError = true;
					}
				}
			}
		}
	}
	
	/**
	 * Returns default language
	 */
	public function getDefaultLanguage()
	{
		$lang = $this->find('is_default = 1');
		return isset($lang[0]['code']) ? $lang[0]['code'] : '';
	}
	
	
	/** 
	 * Returns boolean that indicates if the last operation was successfull
	 * @return boolean
	 */
	public function getError()
	{
		return $this->isError;
	}
	
}
