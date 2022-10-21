<?php
/**
 * Vocabulary controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct             	_getMessagesFileContent
 * indexAction			   	_saveMessagesFileContent
 * manageAction			   	_getPredefinedLanguages 
 * updateAction			   	_validateRows
 * importAction			   	_getFilesList
 *
 * 
 */

class VocabularyController extends CController
{
	const FILE_START = '<?php return array (';
	const FILE_END = ');';

	private $_backendPath = '';

    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

		// Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());

		// Get BackEnd path
		$this->_backendPath = Website::getBackendPath();

		// Block access if admin has no active privilege to access vocabulary
		if(!Admins::hasPrivilege('vocabulary', array('view', 'edit'))){
			$this->redirect($this->_backendPath.'dashboard/index');
		}

		// Set meta tags according to active language
		Website::setMetaTags(array('title'=>A::t('app', 'Vocabulary')));
		// Set backend mode
		Website::setBackend();

		$this->_view->backendPath = $this->_backendPath;
		$this->_view->errorField = '';
		$this->_view->actionMessage = '';

		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

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
        $this->redirect($this->_backendPath.'vocabulary/manage');
    }

    /**
     * Manage vocabulary action handler
     */
	public function manageAction()
	{
		// Block access if admin has no active privilege to manage vocabulary
        Website::prepareBackendAction('view', 'vocabulary', 'dashboard/index');

		$this->_view->actionMessage = CWidget::create(
			'CMessage', array('info', A::t('app', 'To edit click on any row you want to change and them double click on textarea.'), array('button'=>true))
		);

		if($this->_cSession->hasFlash('alert')){
			$alert = $this->_cSession->getFlash('alert');
			$alertType = $this->_cSession->getFlash('alertType');

			$this->_view->actionMessage = CWidget::create(
				'CMessage', array($alertType, $alert, array('button'=>true))
			);
		}

		if(in_array($this->_cRequest->getPost('act'), array('changeLang', 'changeFile'))){
    		$this->_view->language = $this->_cRequest->getPost('language');
    	}else{
			// Default is current active language
			if($this->_cSession->hasFlash('selectedLanguage')){
				$this->_view->language = $this->_cSession->getFlash('selectedLanguage');
			}else{
				$this->_view->language = A::app()->getLanguage();
			}
		}
		$this->_view->isActiveLanguage = ($this->_view->language == A::app()->getLanguage());

    	// Get list of files in the selected language folder
		$this->_view->filesList = $this->_getFilesList($this->_view->language);

		if($this->_cRequest->getPost('act') == 'changeFile'){
	    	$this->_view->fileName = $this->_cRequest->getPost('fileName');
    	}else{
			// Default is the first file in the folder
			if($this->_cSession->hasFlash('selectedFileName')){
				$this->_view->fileName = $this->_cSession->getFlash('selectedFileName');
			}else{
				$this->_view->fileName = isset($this->_view->filesList['app.php']) ? 'app.php' : '';
			}
		}

		// Read the messages file
		$this->_view->fileContent = $this->_getMessagesFileContent($this->_view->language, $this->_view->fileName);
        if(!$this->_view->fileContent) $this->redirect($this->_backendPath.'vocabulary/manage');

		$this->_view->render($this->_backendPath.'vocabulary/manage');
    }

    /**
     * Update vocabulary action handler
     */
	public function updateAction()
	{		
		// Block access if admin has no active privilege to edit vocabulary
		Website::prepareBackendAction('edit', 'vocabulary', 'vocabulary/manage');

    	$alert = '';
		$alertType = '';
		
    	if($this->_cRequest->getPost('act') == 'send'){

			$this->_view->language = $this->_cRequest->getPost('language');
			$this->_view->isActiveLanguage = ($this->_view->language == A::app()->getLanguage());
			$this->_view->fileName = $this->_cRequest->getPost('fileName');
			$fileContentKeys = $this->_cRequest->getPost('fileContent_keys');
			$fileContent = $this->_cRequest->getPost('fileContent');

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
				$alert = $result['errorMessage'];
				$alertType = 'validation';
    			$this->_view->errorField = $result['errorField'];
    		}else{
   				// Validate the input: 'key' => 'value',
				$validationResult = '';
				if(is_array($fileContentKeys) && is_array($fileContent) && count($fileContentKeys) != count($fileContent)){
					$validationResult = A::t('app', 'Array of keys and values are not the same! Check carefully your vocabulary content.');
				}else{
					$tempFileContent = '';
					if(is_array($fileContentKeys)){
						foreach($fileContentKeys as $key => $val){
							if(isset($fileContent[$key])){
								$keyValue = $fileContent[$key];
								$keyValue = str_replace('\\', '', $keyValue);
								$keyValue = str_replace("'", '&#039;', $keyValue);
								$tempFileContent .= "'".$val."'=>'".$keyValue."',".PHP_EOL;
							}
						}
					}
					$fileContent = trim($tempFileContent, ',');
				}

				if(empty($validationResult)){
					$validationResult = $this->_validateRows($fileContent);
				}

     			if(empty($validationResult)){
					if(APPHP_MODE == 'demo'){
						$alertType = 'warning';
						$alert = A::t('core', 'This operation is blocked in Demo Mode!');
					}else{
						// Save the messages file
						$result = $this->_saveMessagesFileContent($this->_view->language, $this->_view->fileName, $fileContent);
						if($result){
							$alert = A::t('app', 'Vocabulary Update Success Message');
							$alertType = 'success';
						}else{
							$alert = A::t('app', 'Vocabulary Update Error Message');
							$alertType = 'error';
						}
					}
    			}else{
    				// Validation failed
					$alert = $validationResult;
					$alertType = 'error';
       			}
    		}

    		if(!empty($alert)){
				$this->_cSession->setFlash('alert', $alert);
				$this->_cSession->setFlash('alertType', $alertType);
	    	}
		}

		$this->_cSession->setFlash('selectedLanguage', $this->_view->language);
		$this->_cSession->setFlash('selectedFileName', $this->_view->fileName);

		$this->redirect($this->_backendPath.'vocabulary/manage');
	}

	
	/**
	 * Import vocabulary files from active language to selected language
	 * @param string $lang The language code
	 */
	public function importAction($lang = '')
	{
		// Block access if admin has no active privilege to import vocabulary
		Website::prepareBackendAction('edit', 'vocabulary', 'vocabulary/manage');

		$alert = '';
		$alertType = '';

    	// check that the language exists and active 
		if(!Languages::model()->exists('code = :lang AND is_active=1', array(':lang'=>$lang))){
			$this->redirect($this->_backendPath.'vocabulary/manage');
		}
		$this->_view->language = $lang;
		$this->_view->isActiveLanguage = ($this->_view->language == A::app()->getLanguage());
		
		$src = '/protected/messages/'.A::app()->getLanguage();
		$dest = '/protected/messages/'.$lang;
		if(APPHP_MODE == 'demo'){
			$alert = A::t('core', 'This operation is blocked in Demo Mode!');
			$alertType = 'warning';
		}else{
			if(CFile::copyDirectory($src, $dest)){
				$alert .= A::t('app', 'Folder Copy Success Message', array('{source}'=>$src, '{destination}'=>$dest));
				$alertType = 'success';
			}else{
				$alert .= A::t('app', 'Folder Copy Error Message', array('{source}'=>$src, '{destination}'=>$dest));
				$alertType = 'error';
			}
		}
		$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
		
    	// Get list of files in the selected language folder
		$this->_view->filesList = $this->_getFilesList($lang);
		// Default is the first file in the folder			
		$this->_view->fileName = isset($this->_view->filesList['app.php']) ? 'app.php' : ''; 
		// read the messages file
		$this->_view->fileContent = $this->_getMessagesFileContent($this->_view->language, $this->_view->fileName);		
		
		$this->_view->render($this->_backendPath.'vocabulary/manage');
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
            $fileContent = trim($fileContent, " ()".PHP_EOL);
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
		return CFile::writeToFile('protected/messages/'.$language.'/'.$fileName, $fileContent);
		//file_put_contents();
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
	 * @return string
	 */
	private function _validateRows($string)
	{
		// Pattern: 'text' => 'text',
		// Note: text can include \'
		$linePattern = "/^\s*'(?:\.|(\\\')|[^\''])*'\s*=>\s*'(?:\.|(\\\')|[^\''])*'\s*,\s*/";
		$lastLinePattern = "/^\s*'(?:\.|(\\\')|[^\''])*'\s*=>\s*'(?:\.|(\\\')|[^\''])*'\s*\s*/";
		$linesArray = explode(PHP_EOL, $string);
		$linesCount = count($linesArray);
		$lineNum = 1;
		foreach($linesArray as $line){
			$pattern = ($linesCount == $lineNum) ? $lastLinePattern : $linePattern;
			if(!empty($line) && !preg_match($pattern, $line)){
				return A::t('app', 'Parsing Error Message', array('{line}'=>$lineNum));
			}
			$lineNum++;
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

