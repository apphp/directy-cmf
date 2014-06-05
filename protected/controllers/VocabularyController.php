<?php
/**
* VocabularyController
*
* PUBLIC:                  PRIVATE
* -----------              ------------------
* __construct              getMessagesFileContent
* indexAction			   saveMessagesFileContent
* manageAction			   getPredefinedLanguages 
* updateAction			   validate			   
* importAction			   getFilesList
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

        // block access to this controller for not-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to view vocabulary
		if(!Admins::hasPrivilege('vocabulary', array('view','edit'))){
			$this->redirect('backend/index');
		}
		
        // set meta tags according to active language
    	SiteSettings::setMetaTags(array('title'=>A::t('app', 'Vocabulary')));
				
        A::app()->view->setTemplate('backend');
		$this->view->errorField = '';
		$this->view->actionMessage = '';
				
		// get list of all messages folders
		$languages = $this->getPredefinedLanguages();
		$langList = array();
		if(is_array($languages)){
			foreach($languages as $lang){
				$langList[$lang['code']] = $lang['name_native'];
			}
		}
		$this->view->langList = $langList;
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
	    $cRequest = A::app()->getRequest();

    	if(in_array($cRequest->getPost('act'), array('changeLang', 'changeFile'))){
    		$this->view->language = $cRequest->getPost('language');
    	}else{
			// default is current active language			
			$this->view->language = A::app()->getLanguage(); 
		}		
		$this->view->isActiveLanguage = ($this->view->language == A::app()->getLanguage());

    	// get list of files in the selected language folder
		$this->view->filesList = $this->getFilesList($this->view->language);

		if($cRequest->getPost('act') == 'changeFile'){
	    	$this->view->fileName = $cRequest->getPost('fileName');
    	}else{
			// default is the first file in the folder			
			$this->view->fileName = isset($this->view->filesList['app.php']) ? 'app.php' : ''; 
		}
		
		// read the messages file
		$this->view->fileContent = $this->getMessagesFileContent($this->view->language, $this->view->fileName);
		
        $this->view->render('vocabulary/manage');    
    }

    /**
     * Update vocabulary action handler
     */
	public function updateAction()
	{		
		// block access if admin has no active privilege to edit vocabulary
     	if(!Admins::hasPrivilege('vocabulary', 'edit')){
     		$this->redirect('backend/index');
     	}
     	
		$cRequest = A::app()->getRequest();
    	$msg = '';
    	$errorType = '';
		
    	if($cRequest->getPost('act') == 'send'){

			$this->view->language = $cRequest->getPost('language');
			$this->view->isActiveLanguage = ($this->view->language == A::app()->getLanguage());
			$this->view->fileName = $cRequest->getPost('fileName');
			$this->view->fileContent = trim($cRequest->getPost('fileContent'));
			
	    	// get list of files in the selected language folder
			$this->view->filesList = $this->getFilesList($this->view->language);
			
    	 	$result = CWidget::create('CFormValidation', array(
				'fields'=>array(
                	'language'   =>array('title'=>A::t('app', 'Language'), 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($this->view->langList))),
					'fileName'   =>array('title'=>A::t('app', 'Language File'), 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($this->view->filesList))),
					'fileContent'=>array('title'=>A::t('app', 'Content'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>30000)),
				),
	   		));
    		if($result['error']){
    			$msg = $result['errorMessage'];
    			$this->view->errorField = $result['errorField'];
    			$errorType = 'validation';
    		}else{				
   				// validate the input: 'key' => 'value',
    			$res = $this->validate($this->view->fileContent);
     			if($res == ''){ 
					if(APPHP_MODE == 'demo'){
						$msg = A::t('core', 'This operation is blocked in Demo Mode!');
						$errorType = 'warning';
					}else{
						// save the messages file
						if($this->saveMessagesFileContent($this->view->language, $this->view->fileName, $this->view->fileContent)){
							$msg = A::t('app', 'Vocabulary Update Success Message');
							$errorType = 'success';
						}else{
							$msg = A::t('app', 'Vocabulary Update Error Message');
							$errorType = 'error';
						}
					}
    			}else{
    				// validation failed
    				$msg = $res;
    				$errorType = 'error';
       			}
    		}
	    	if(!empty($msg)){
	    		$this->view->actionMessage = CWidget::create('CMessage', array($errorType, $msg, array('button'=>true)));
	    	}	    
			$this->view->render('vocabulary/manage');
		
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
		// block access if admin has no active privilege to edit vocabulary
     	if(!Admins::hasPrivilege('vocabulary', 'edit')){
     		$this->redirect('backend/index');
     	}
     	
		$msg = '';
    	$errorType = '';

    	// check that the language exists and active 
		if(!Languages::model()->exists('code = :lang AND is_active=1', array(':lang'=>$lang))){
			$this->redirect('vocabulary/manage');
		}
		$this->view->language = $lang;
		$this->view->isActiveLanguage = ($this->view->language == A::app()->getLanguage());
		
		$src = '/protected/messages/'.A::app()->getLanguage();
		$dest = '/protected/messages/'.$lang;
		if(APPHP_MODE == 'demo'){
			$msg = A::t('core', 'This operation is blocked in Demo Mode!');
			$errorType = 'warning';			
		}else{
			if(CFile::copyDirectory($src, $dest)){
				$msg .= A::t('app', 'Folder Copy Success Message', array('{source}'=>$src, '{destination}'=>$dest));
				$errorType = 'success';
			}else{
				$msg .= A::t('app', 'Folder Copy Error Message', array('{source}'=>$src, '{destination}'=>$dest));
				$errorType = 'error';
			}
		}
		$this->view->actionMessage = CWidget::create('CMessage', array($errorType, $msg, array('button'=>true)));
		
    	// get list of files in the selected language folder
		$this->view->filesList = $this->getFilesList($lang);
		// default is the first file in the folder			
		$this->view->fileName = isset($this->view->filesList['app.php']) ? 'app.php' : ''; 
		// read the messages file
		$this->view->fileContent = $this->getMessagesFileContent($this->view->language, $this->view->fileName);		
		
		$this->view->render('vocabulary/manage');
	}
	
	/**
	 * Read messages file content and extract only the messages
	 * @param string $language
	 * @param string $fileName
	 **/
	private function getMessagesFileContent($language, $fileName)
	{		
		// read the whole file as string
		$fileContent = file_get_contents('protected/messages/'.$language.'/'.$fileName);
		$fileContent = str_replace(array('<?php', 'return array', ');'), '', $fileContent);
		$fileContent = trim($fileContent, " ()\r\n");
		
		return $fileContent;
	}
	
	/**
	 * Save messages to language file
	 * @param string $language
	 * @param string $fileName
	 * @param string $fileContent
	 * @return boolean
	 */
	private function saveMessagesFileContent($language, $fileName, $fileContent)
	{
		// add array declaration
		$fileContent = self::FILE_START.$fileContent.self::FILE_END;	
		// write the file
		return file_put_contents('protected/messages/'.$language.'/'.$fileName, $fileContent);
	}
	
	/*
	 * Returns a list of pre-defined languages in application
	 */
	private function getPredefinedLanguages()
	{
		$messagesFolders = CFile::findSubDirectories('protected/messages/');
		$codesList = "'".implode("','", $messagesFolders)."'";
	    return Languages::model()->findAll('is_active = 1 AND code IN ('.$codesList.')');
	}
	
	/**
	 * Validate the syntax of vocabulary file
	 * Each line should be in form: 'text' => 'text',
	 * Note: text can include \' 
	 */
	private function validate($string)
	{
		// pattern: 'text' => 'text',
		// note: text can include \'
		$pattern = "/^\s*'(?:\.|(\\\')|[^\''])*'\s*=>\s*'(?:\.|(\\\')|[^\''])*'\s*,\s*/"; 
		$linesArray = explode("\r\n", $string);
		$i = 1;
		foreach($linesArray as $line){
			if(!preg_match($pattern, $line)){
				return A::t('app', 'Parsing Error Message', array('{line}'=>$i));
			}
			$i++;
		}
		// return empty error message on success
		return '';
	}
	
	/**
	 * Returns list of files in the selected language folder
	 * @param string $language The language code
	 */
    private function getFilesList($language = 'en')
    {
    	// get list of files in the selected language folder
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

