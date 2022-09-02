<?php
/**
 * Vocabulary controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct             	_getMessagesFileContent
 * indexAction			   	_saveMessagesFileContent
 * manageAction			   	_getPredefinedLanguages 
 * updateAction			   	_validate			   
 * importAction			   	_getFilesList
 * 
 */

class VocabularyController extends CController
{
	const FILE_START = '<?php return array (';
	const FILE_END = ');';
	
    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

        // Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());
		
		// Block access if admin has no active privilege to access vocabulary
		if(!Admins::hasPrivilege('vocabulary', array('view', 'edit'))){
			$this->redirect('backend/index');
		}
		
        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Vocabulary')));
        // Set backend mode
        Website::setBackend();

		$this->_view->errorField = '';
		$this->_view->actionMessage = '';
				
		// Get list of all messages folders
		$languages = $this->_getPredefinedLanguages();
		$langList = array();
		if(is_array($languages)){
			foreach($languages as $lang){
				$langList[$lang['code']] = $lang['name_native'];
			}
		}
		$this->_view->langList = $langList;
	}
        
	/**
	 * Controller default action handler
	 */
	public function indexAction()
	{
        $this->redirect('vocabulary/manage');    
    }

    /**
     * Manage vocabulary action handler
     */
	public function manageAction()
	{
		// Block access if admin has no active privilege to manage vocabulary
        Website::prepareBackendAction('view', 'vocabulary', 'backend/index');

	    $cRequest = A::app()->getRequest();

    	if(in_array($cRequest->getPost('act'), array('changeLang', 'changeFile'))){
    		$this->_view->language = $cRequest->getPost('language');
    	}else{
			// Default is current active language			
			$this->_view->language = A::app()->getLanguage(); 
		}		
		$this->_view->isActiveLanguage = ($this->_view->language == A::app()->getLanguage());

    	// Get list of files in the selected language folder
		$this->_view->filesList = $this->_getFilesList($this->_view->language);

		if($cRequest->getPost('act') == 'changeFile'){
	    	$this->_view->fileName = $cRequest->getPost('fileName');
    	}else{
			// Default is the first file in the folder			
			$this->_view->fileName = isset($this->_view->filesList['app.php']) ? 'app.php' : ''; 
		}
		
		// Read the messages file
		$this->_view->fileContent = $this->_getMessagesFileContent($this->_view->language, $this->_view->fileName);
        if(!$this->_view->fileContent) $this->redirect('vocabulary/manage');
        $this->_view->render('vocabulary/manage');    
    }

    /**
     * Update vocabulary action handler
     */
	public function updateAction()
	{		
		// Block access if admin has no active privilege to edit vocabulary
		Website::prepareBackendAction('edit', 'vocabulary', 'vocabulary/manage');
     	
		$cRequest = A::app()->getRequest();
    	$msg = '';
    	$msgType = '';
		
    	if($cRequest->getPost('act') == 'send'){

			$this->_view->language = $cRequest->getPost('language');
			$this->_view->isActiveLanguage = ($this->_view->language == A::app()->getLanguage());
			$this->_view->fileName = $cRequest->getPost('fileName');
			$this->_view->fileContent = trim($cRequest->getPost('fileContent'));
			
	    	// Get list of files in the selected language folder
			$this->_view->filesList = $this->_getFilesList($this->_view->language);
			
    	 	$result = CWidget::create('CFormValidation', array(
				'fields'=>array(
                	'language'   =>array('title'=>A::t('app', 'Language'), 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($this->_view->langList))),
					'fileName'   =>array('title'=>A::t('app', 'Language File'), 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($this->_view->filesList))),
					'fileContent'=>array('title'=>A::t('app', 'Content'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>50000)),
				),
	   		));
    		if($result['error']){
    			$msg = $result['errorMessage'];
    			$msgType = 'validation';
    			$this->_view->errorField = $result['errorField'];
    		}else{				
   				// Validate the input: 'key' => 'value',
    			$res = $this->_validate($this->_view->fileContent);
     			if($res == ''){ 
					if(APPHP_MODE == 'demo'){
						$msgType = 'warning';
						$msg = A::t('core', 'This operation is blocked in Demo Mode!');
					}else{
						// Save the messages file
						if($this->_saveMessagesFileContent($this->_view->language, $this->_view->fileName, $this->_view->fileContent)){
							$msg = A::t('app', 'Vocabulary Update Success Message');
							$msgType = 'success';
						}else{
							$msg = A::t('app', 'Vocabulary Update Error Message');
							$msgType = 'error';
						}
					}
    			}else{
    				// Validation failed
    				$msg = $res;
    				$msgType = 'error';
       			}
    		}
	    	if(!empty($msg)){
	    		$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
	    	}	    
			$this->_view->render('vocabulary/manage');
		
		}else{
			$this->redirect('vocabulary/manage');
		}
	}
	
	/**
	 * Import vocabulary files from active language to selected language
	 * @param string $lang The language code
	 */
	public function importAction($lang = '')
	{
		// Block access if admin has no active privilege to import vocabulary
		Website::prepareBackendAction('edit', 'vocabulary', 'vocabulary/manage');
     	
		$msg = '';
    	$msgType = '';

    	// check that the language exists and active 
		if(!Languages::model()->exists('code = :lang AND is_active=1', array(':lang'=>$lang))){
			$this->redirect('vocabulary/manage');
		}
		$this->_view->language = $lang;
		$this->_view->isActiveLanguage = ($this->_view->language == A::app()->getLanguage());
		
		$src = '/protected/messages/'.A::app()->getLanguage();
		$dest = '/protected/messages/'.$lang;
		if(APPHP_MODE == 'demo'){
			$msg = A::t('core', 'This operation is blocked in Demo Mode!');
			$msgType = 'warning';			
		}else{
			if(CFile::copyDirectory($src, $dest)){
				$msg .= A::t('app', 'Folder Copy Success Message', array('{source}'=>$src, '{destination}'=>$dest));
				$msgType = 'success';
			}else{
				$msg .= A::t('app', 'Folder Copy Error Message', array('{source}'=>$src, '{destination}'=>$dest));
				$msgType = 'error';
			}
		}
		$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
		
    	// Get list of files in the selected language folder
		$this->_view->filesList = $this->_getFilesList($lang);
		// Default is the first file in the folder			
		$this->_view->fileName = isset($this->_view->filesList['app.php']) ? 'app.php' : ''; 
		// read the messages file
		$this->_view->fileContent = $this->_getMessagesFileContent($this->_view->language, $this->_view->fileName);		
		
		$this->_view->render('vocabulary/manage');
	}
	
	/**
	 * Read messages file content and extract only the messages
	 * @param string $language
	 * @param string $fileName
	 **/
	private function _getMessagesFileContent($language, $fileName)
	{		
		// Read the whole file as a string
        $fileContent = '';
		if(file_exists('protected/messages/'.$language.'/'.$fileName)){
            $fileContent = file_get_contents('protected/messages/'.$language.'/'.$fileName);
            $fileContent = str_replace(array('<?php', 'return array', ');'), '', $fileContent);
            $fileContent = trim($fileContent, " ()\r\n");
        }
		
		return $fileContent;
	}
	
	/**
	 * Save messages to language file
	 * @param string $language
	 * @param string $fileName
	 * @param string $fileContent
	 * @return boolean
	 */
	private function _saveMessagesFileContent($language, $fileName, $fileContent)
	{
		// add array declaration
		$fileContent = self::FILE_START.$fileContent.self::FILE_END;	
		// Write the file
		return file_put_contents('protected/messages/'.$language.'/'.$fileName, $fileContent);
	}
	
	/*
	 * Returns a list of predefined languages in application
	 */
	private function _getPredefinedLanguages()
	{
		$messagesFolders = CFile::findSubDirectories('protected/messages/');
		$codesList = "'".implode("','", $messagesFolders)."'";
	    return Languages::model()->findAll(array('condition'=>'is_active = 1 AND code IN ('.$codesList.')', 'orderBy'=>'sort_order ASC'));
	}
	
	/**
	 * Validate the syntax of vocabulary file
	 * Each line should be in form: 'text' => 'text',
	 * Note: text can include \' 
	 */
	private function _validate($string)
	{
		// Pattern: 'text' => 'text',
		// Note: text can include \'
		$pattern = "/^\s*'(?:\.|(\\\')|[^\''])*'\s*=>\s*'(?:\.|(\\\')|[^\''])*'\s*,\s*/"; 
		$linesArray = explode("\r\n", $string);
		$i = 1;
		foreach($linesArray as $line){
			if(!preg_match($pattern, $line)){
				return A::t('app', 'Parsing Error Message', array('{line}'=>$i));
			}
			$i++;
		}
		// Return empty error message on success
		return '';
	}
	
	/**
	 * Returns list of files in the selected language folder
	 * @param string $language The language code
	 */
    private function _getFilesList($language = 'en')
    {
    	// Get list of files in the selected language folder
		$files = CFile::findFiles('protected/messages/'.$language.'/');
		$filesList = array();
		if(is_array($files)){
			foreach($files as $file){
				$filesList[$file] = $file;
			}
		}
		return $filesList;
    }
}

