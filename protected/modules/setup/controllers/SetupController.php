<?php
/**
 * SetupController
 *
 * PUBLIC:                   PRIVATE
 * -----------               ------------------
 * __construct               _checkModRewrite   	
 * indexAction               _checkPdoExtension
 * requirementsAction
 * databaseAction
 * administratorAction
 * readyAction
 * completedAction
 * testModeRewriteAction
 *
 */

class SetupController extends CController
{
    
    private $_cSession;
    private $_cRequest;
	private $_configMain;
    private $_pdoExtensionRequired;
    private $_languages;
    
	/**
     * Class constructor
	 */
	public function __construct()
	{
        parent::__construct();
        
        $this->_cSession = A::app()->getSession();
        $this->_cRequest = A::app()->getRequest();
        $this->_pdoExtensionRequired = true;        
        $this->_configMain = include(APPHP_PATH.'/protected/data/config.main.tpl');
        $this->_languages = array('en'=>'English', 'es'=>utf8_encode('Espa�ol'), 'de'=>utf8_encode('Deutsch'));
        
        $this->_view->errorField = '';
        $this->_view->_programName = isset($this->_configMain['name']) ? $this->_configMain['name'] : '';
        $this->_view->_programVersion = isset($this->_configMain['version']) ? $this->_configMain['version'] : '';

        // block access to setup files when application is already installed
        $configMain = APPHP_PATH.'/protected/config/main.php';
        if(file_exists($configMain)){            
            $this->_view->errorMessage = CWidget::create('CMessage', array('error', 'You\'re not authorized to view this page.'));
            $this->_view->render('setup/error');
            exit;
        }
        
        $this->_view->setMetaTags('description', 'Setup Wizard for ApPHP MVC Framework Applications');
        $this->_view->setMetaTags('keywords', 'ApPHP MVC Framework, setup, setup wizard, installation wizard');
		
		ini_set('magic_quotes_runtime', 0);
		ini_set('magic_quotes_gpc', 0);
		ini_set('magic_quotes_sybase', 0);
    }
    
	/**
     * Index action
	 */
	public function indexAction()
	{        
        if($this->_cRequest->getPost('act') == 'send'){
            $language = $this->_cRequest->getPost('language', 'string');
        }else{
            $language = $this->_cSession->get('language', 'en');
        }
        
        $this->_view->actionMessage = '';
        $msg = '';

        $this->_view->formFields = array(
            'act'      =>array('type'=>'hidden', 'value'=>'send'),            
            'language' =>array('type'=>'dropdownlist', 'value'=>$language, 'title'=>A::t('setup', 'Language'), 'mandatoryStar'=>false, 'data'=>$this->_languages, 'htmlOptions'=>array(), 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($this->_languages))),
        );            

        if($this->_cRequest->getPost('act') == 'send'){
            
            $result = CWidget::create('CFormValidation', array(
                'fields' => $this->_view->formFields
            ));
             
            if($result['error']){
                $msg = $result['errorMessage'];
                $this->_view->errorField = $result['errorField'];
            }else{
                A::app()->setLanguage($language);
                $this->redirect('setup/requirements');
            }

			if(!empty($msg)){
				$this->_view->actionMessage = CWidget::create('CMessage', array('validation', $msg));
            }
        }
        
        $this->_cSession->set('step', 1);
        $this->_view->setMetaTags('title', A::t('setup', 'General | Setup Wizard'));
        $this->_view->render('setup/index');
    }

	/**
     * Requirements action
	 */
	public function requirementsAction()
	{        
        // check if previous step was passed
        if($this->_cSession->get('step') < 1){
            $this->redirect('setup/index');
        }else if(A::app()->getRequest()->getPost('act') == 'send'){
            $this->redirect('setup/database');    
        }
        
        $this->_view->notifyMessage = '';
        
        ob_start();        
        if(function_exists('phpinfo')) @phpinfo(-1);
        $phpinfo = array('phpinfo' => array());
        if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
        foreach($matches as $match){
			$arrayKeys = array_keys($phpinfo);
			$endArrayKeys = end($arrayKeys);
            if(strlen($match[1])){
                $phpinfo[$match[1]] = array();
            }else if(isset($match[3])){
                $phpinfo[$endArrayKeys][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
            }else{				
                $phpinfo[$endArrayKeys][] = $match[2];
            }
        }
        
        // check all required settings
        if(version_compare(phpversion(), '5.1.0', '<')){	
            $this->_view->notifyMessage = CWidget::create('CMessage', array('error', 'This program requires at least <b>PHP version 5.1.0</b> installed. You cannot proceed current installation.'));	
        }else if(!is_writable(APPHP_PATH.'/protected/config/')){
            $this->_view->notifyMessage = CWidget::create('CMessage', array('error', 'The directory <b>'.APPHP_PATH.'/protected/config/</b> is not writable! You must grant "write" permissions (access rights 0755 or 777, depending on your system settings) to this directory before you start current installation!'));	
        }else if(!is_writable(APPHP_PATH.'/images/modules/')){
            $this->_view->notifyMessage = CWidget::create('CMessage', array('error', 'The directory <b>'.APPHP_PATH.'/images/modules/</b> is not writable! You must grant "write" permissions (access rights 0755 or 777, depending on your system settings) to this directory before you start current installation!'));	
        }else if(!$this->_checkModRewrite()){
            $this->_view->notifyMessage = CWidget::create('CMessage', array('error', 'This program requires "<b>mod_rewrite</b>" allowed. You cannot proceed current installation.'));	
        }else if($this->_pdoExtensionRequired && !$this->_checkPdoExtension($phpinfo)){
            $this->_view->notifyMessage = CWidget::create('CMessage', array('error', 'This program requires "<b>PDO</b>" extension enabled. You cannot proceed current installation.'));
        }
        
        $this->_view->phpVersion = function_exists('phpversion') ? '<span class="found">'.phpversion().'</span>' : '<span class="unknown">Unknown</span>';
        $this->_view->system = isset($phpinfo['phpinfo']['System']) ? '<span class="found">'.$phpinfo['phpinfo']['System'].'</span>' : '<span class="unknown">Unknown</span>';
        $this->_view->systemArchitecture = isset($phpinfo['phpinfo']['Architecture']) ? '<span class="found">'.$phpinfo['phpinfo']['Architecture'].'</span>' : '<span class="unknown">Unknown</span>';
        $this->_view->buildDate = isset($phpinfo['phpinfo']['Build Date']) ? '<span class="found">'.$phpinfo['phpinfo']['Build Date'].'</span>' : '<span class="unknown">Unknown</span>';
        $this->_view->serverApi = isset($phpinfo['phpinfo']['Server API']) ? '<span class="found">'.$phpinfo['phpinfo']['Server API'].'</span>' : '<span class="unknown">Unknown</span>';
        $this->_view->vdSupport = isset($phpinfo['phpinfo']['Virtual Directory Support']) ? $phpinfo['phpinfo']['Virtual Directory Support'] : 'Unknown';
        $this->_view->vdSupport = ($this->_view->vdSupport == 'enabled') ? '<span class="found">'.$this->_view->vdSupport.'</span>' : '<span class="disabled">'.$this->_view->vdSupport.'</span>';

        $this->_view->pdoExtension = '<span class="found">enabled</span>';            
        $this->_view->modeRewrite = '<span class="found">enabled</span>';
        
        $phpCoreIndex = ((version_compare(phpversion(), '5.3.0', '<'))) ? 'PHP Core' : 'Core';
        $this->_view->aspTags   = isset($phpinfo[$phpCoreIndex]) ? '<span class="found">'.$phpinfo[$phpCoreIndex]['asp_tags'][0].'</span>' : '<span class="unknown">Unknown</span>';
        $this->_view->safeMode = isset($phpinfo[$phpCoreIndex]['safe_mode']) ? '<span class="found">'.$phpinfo[$phpCoreIndex]['safe_mode'][0].'</span>' : '<span class="unknown">Unknown</span>';
        $this->_view->shortOpenTag = isset($phpinfo[$phpCoreIndex]) ? '<span class="found">'.$phpinfo[$phpCoreIndex]['short_open_tag'][0].'</span>' : '<span class="unknown">Unknown</span>';

        $this->_view->sessionSupport = isset($phpinfo['session']['Session Support']) ? $phpinfo['session']['Session Support'] : 'Unknown';
        $this->_view->sessionSupport = ($this->_view->sessionSupport == "enabled") ? '<span class="found">'.$this->_view->sessionSupport.'</span>' : '<span class="disabled">'.$this->_view->sessionSupport.'</span>';
        
        $this->_view->magicQuotesGpc = ini_get('magic_quotes_gpc') ? '<span class="found">On</span>' : '<span class="disabled">Off</span>';
        $this->_view->magicQuotesRuntime = ini_get('magic_quotes_runtime') ? '<span class="found">On</span>' : '<span class="disabled">Off</span>';
        $this->_view->magicQuotesSybase = ini_get('magic_quotes_sybase') ? '<span class="found">On</span>' : '<span class="disabled">Off</span>';									
        
        $this->_view->smtp = (ini_get("SMTP") != '') ? '<span class="found">'.ini_get('SMTP').'</span>' : '<span class="unknown">Unknown</span>';
        $this->_view->smtpPort = (ini_get('smtp_port') != '') ? '<span class="found">'.ini_get('smtp_port').'</span>' : '<span class="unknown">Unknown</span>';

        if(!$this->_view->notifyMessage) $this->_cSession->set('step', 2);
        
        $this->_view->setMetaTags('title', A::t('setup', 'Check Application Requirements | Setup Wizard'));
        $this->_view->render('setup/requirements');
    }

	/**
     * Database action
	 */
	public function databaseAction()
	{
        if($this->_cRequest->getPost('act') == 'send'){
            $this->_view->setupType = $this->_cRequest->getPost('setupType', 'string');
            $this->_view->dbDriver = $this->_cRequest->getPost('dbDriver', 'string');
            $this->_view->dbHost = $this->_cRequest->getPost('dbHost', 'string');
            $this->_view->dbName = $this->_cRequest->getPost('dbName', 'string');
            $this->_view->dbUser = $this->_cRequest->getPost('dbUser', 'string');
            $this->_view->dbPassword = $this->_cRequest->getPost('dbPassword', 'string');
            $this->_view->dbPrefix = $this->_cRequest->getPost('dbPrefix', 'string');
        }else{
            $this->_view->setupType = $this->_cSession->get('setupType', 'install');
            $this->_view->dbDriver = $this->_cSession->get('dbDriver', 'mysql');
            $this->_view->dbHost = $this->_cSession->get('dbHost', 'localhost');
            $this->_view->dbName = $this->_cSession->get('dbName');
            $this->_view->dbUser = $this->_cSession->get('dbUser');
            $this->_view->dbPassword = $this->_cSession->get('dbPassword');
            $this->_view->dbPrefix = $this->_cSession->get('dbPrefix');            
        }
        
        $this->_view->actionMessage = '';
        $msg = '';

        $separatorGeneralFields = array(
            'separatorInfo' => array('legend'=>'General Settings'),
            'setupType'  =>array('type'=>'dropdownlist', 'value'=>$this->_view->setupType, 'title'=>A::t('setup', 'Setup Type'), 'mandatoryStar'=>false, 'data'=>array('install'=>A::t('setup', 'New Installation'), 'update'=>A::t('setup', 'Update')), 'htmlOptions'=>array(), 'validation'=>array('required'=>true, 'type'=>'text', 'source'=>array('install'))),
            'dbDriver'   =>array('type'=>'dropdownlist', 'value'=>$this->_view->dbDriver, 'title'=>A::t('setup', 'Database Driver'), 'mandatoryStar'=>true, 'data'=>array('mysql'=>'MySql'), 'htmlOptions'=>array(), 'validation'=>array('required'=>true, 'type'=>'text', 'source'=>array('mysql'))),
            'dbPrefix'   =>array('type'=>'textbox', 'value'=>$this->_view->dbPrefix, 'title'=>A::t('setup', 'Database (tables) Prefix'), 'mandatoryStar'=>false, 'htmlOptions'=>array('maxLength'=>'10', 'autocomplete'=>'off'), 'validation'=>array('required'=>false, 'type'=>'variable')),
        );
        $separatorConenctionSettingsFields = array(
            'separatorInfo' => array('legend'=>'Connection Settings'),
            'dbHost'     =>array('type'=>'textbox', 'value'=>$this->_view->dbHost, 'title'=>A::t('setup', 'Database Host'), 'mandatoryStar'=>true, 'htmlOptions'=>array('maxLength'=>'32', 'autocomplete'=>'off'), 'validation'=>array('required'=>true, 'type'=>'text')),
            'dbName'     =>array('type'=>'textbox', 'value'=>$this->_view->dbName, 'title'=>A::t('setup', 'Database Name'), 'mandatoryStar'=>true, 'htmlOptions'=>array('maxLength'=>'20', 'autocomplete'=>'off'), 'validation'=>array('required'=>true, 'type'=>'text')),
            'dbUser'     =>array('type'=>'textbox', 'value'=>$this->_view->dbUser, 'title'=>A::t('setup', 'Database User'), 'mandatoryStar'=>true, 'htmlOptions'=>array('maxLength'=>'20', 'autocomplete'=>'off'), 'validation'=>array('required'=>true, 'type'=>'text')),
            'dbPassword' =>array('type'=>'password', 'value'=>$this->_view->dbPassword, 'title'=>A::t('setup', 'Database Password'), 'mandatoryStar'=>false, 'htmlOptions'=>array('maxLength'=>'20', 'autocomplete'=>'off'), 'validation'=>array('required'=>false, 'type'=>'text')),
        );
        $validationFields = array_merge($separatorGeneralFields, $separatorConenctionSettingsFields);
        $this->_view->formFields = array(
            'act'               =>array('type'=>'hidden', 'value'=>'send'),
            'separatorGeneral'  =>$separatorGeneralFields,
            'separatorConenctionSettings' =>$separatorConenctionSettingsFields,
        );            

        // check if previous step was passed
        if($this->_cSession->get('step') < 2){
            $this->redirect('setup/index');
        }else if($this->_cRequest->getPost('act') == 'send'){

            $result = CWidget::create('CFormValidation', array(
                'fields' => $validationFields
            ));
             
            if($result['error']){
                $msg = $result['errorMessage'];
                $this->_view->errorField = $result['errorField'];
            }else{
                $model = new Setup(array(
                    'dbDriver' => $this->_view->dbDriver,
                    'dbHost' => $this->_view->dbHost,
                    'dbName' => $this->_view->dbName,
                    'dbUser' => $this->_view->dbUser,
                    'dbPassword' => $this->_view->dbPassword
                ));
                
                if($model->getError()){
                    $this->_view->actionMessage = CWidget::create('CMessage', array('error', $model->getErrorMessage()));
                }else{
                    // go to the next step
                    $this->_cSession->set('setupType', $this->_view->setupType);
                    $this->_cSession->set('dbDriver', $this->_view->dbDriver);
                    $this->_cSession->set('dbHost', $this->_view->dbHost);
                    $this->_cSession->set('dbName', $this->_view->dbName);
                    $this->_cSession->set('dbUser', $this->_view->dbUser);
                    $this->_cSession->set('dbPassword', $this->_view->dbPassword);
                    $this->_cSession->set('dbPrefix', $this->_view->dbPrefix);
                    $this->_cSession->set('step', 3);
                    
                    $this->redirect('setup/administrator');                    
                }
            }

			if(!empty($msg)){
				$this->_view->actionMessage = CWidget::create('CMessage', array('validation', $msg));
            }                        
        }
        
        $this->_view->setMetaTags('title', A::t('setup', 'Database Settings | Setup Wizard'));
        $this->_view->render('setup/database');
    }

	/**
     * Administrator action
	 */
	public function administratorAction()
	{
        $this->_view->email = $this->_cSession->get('email');
        $this->_view->username = $this->_cSession->get('username');
        $this->_view->password = $this->_cSession->get('password');
        
        $this->_view->actionMessage = '';
        $msg = '';
        
        // check if previous step was passed
        if($this->_cSession->get('step') < 3){
            $this->redirect('setup/index');
        }else if($this->_cSession->get('setupType') == 'update'){
            $this->_cSession->set('step', 4);
            $this->redirect('setup/ready');
        }else if($this->_cRequest->getPost('act') == 'send'){
            $this->_view->email = $this->_cRequest->getPost('email');
            $this->_view->username = $this->_cRequest->getPost('username');
            $this->_view->password = $this->_cRequest->getPost('password');

            $result = CWidget::create('CFormValidation', array(
                'fields'=>array(
                    'email'=>array('title'=>A::t('setup', 'Email'), 'validation'=>array('required'=>false, 'type'=>'email')),
                    'username'=>array('title'=>A::t('setup', 'Username'), 'validation'=>array('required'=>true, 'type'=>'any', 'minLength'=>4)),
                    'password'=>array('title'=>A::t('setup', 'Password'), 'validation'=>array('required'=>true, 'type'=>'any', 'minLength'=>4)),
                ),            
            ));
             
            if($result['error']){
                $msg = $result['errorMessage'];
                $this->_view->errorField = $result['errorField'];
            }else{
                // go to the next step                
                $this->_cSession->set('email', $this->_view->email);
                $this->_cSession->set('username', $this->_view->username);
                $this->_cSession->set('password', $this->_view->password);
                $this->_cSession->set('step', 4);

                $this->redirect('setup/ready');
            }

			if(!empty($msg)){
				$this->_view->actionMessage = CWidget::create('CMessage', array('validation', $msg));
            }
        }

        $this->_view->setMetaTags('title', A::t('setup', 'Administrator Account | Setup Wizard'));
        $this->_view->render('setup/administrator');
    }

	/**
     * Ready action
	 */
	public function readyAction()
	{
        $this->_view->actionMessage = '';
        $this->_view->installed = false;
        
        // check if previous step was passed
        if($this->_cSession->get('step') < 4){
            $this->redirect('setup/index');
        }else if($this->_cRequest->getPost('act') == 'send'){
            // get sql schema
            $sqlDumpPath = APPHP_PATH.'/protected/data/schema'.($this->_cSession->get('setupType') == 'update' ? '.update' : '').'.mysql.sql';
            $sqlDump = file($sqlDumpPath);
            if(empty($sqlDump)){
                $this->_view->actionMessage = CWidget::create('CMessage', array('error', 'Could not read file <b>'.$sqlDumpPath.'</b>! Please check if this file exists.'));
            }else{
                $encryption = isset($this->_configMain['password']['encryption']) ? $this->_configMain['password']['encryption'] : false;
                $encryptAlgorithm = isset($this->_configMain['password']['encryptAlgorithm']) ? $this->_configMain['password']['encryptAlgorithm'] : '';
                $hashKey = isset($this->_configMain['password']['hashKey']) ? $this->_configMain['password']['hashKey'] : '';
                $components = isset($this->_configMain['components']) ? $this->_configMain['components'] : '';
        
                // replace placeholders
                $sqlDump = str_ireplace('<DB_PREFIX>', $this->_cSession->get('dbPrefix'), $sqlDump);
                $sqlDump = str_ireplace('<USERNAME>', $this->_cSession->get('username'), $sqlDump);
                $sqlDump = str_ireplace('<PASSWORD>', (($encryption) ? CHash::create($encryptAlgorithm, $this->_cSession->get('password'), $hashKey) : $this->_cSession->get('password')), $sqlDump);
                $sqlDump = str_ireplace('<EMAIL>', $this->_cSession->get('email'), $sqlDump);
                $sqlDump = str_ireplace('<CREATED_AT>', date('Y-m-d H:i:s', time() + (date('I', time()) ? 3600 : 0)), $sqlDump);
                
                $model = new Setup(array(
                    'dbDriver' => $this->_cSession->get('dbDriver'),
                    'dbHost' => $this->_cSession->get('dbHost'),
                    'dbName' => $this->_cSession->get('dbName'),
                    'dbUser' => $this->_cSession->get('dbUser'),
                    'dbPassword' => $this->_cSession->get('dbPassword')
                ));
                
                if($model->getError()){
                    $this->_view->actionMessage = CWidget::create('CMessage', array('error', $model->getErrorMessage()));
                }else{
                    if($model->install($sqlDump)){
                        $modulesError = false;
                        $modulesWarning = false;
                        if($this->_cSession->get('setupType') == 'install'){
                            // install modules 
                            $modulesList = isset($this->_configMain['modules']) ? $this->_configMain['modules'] : '';
                            if(is_array($modulesList)){
                                foreach($modulesList as $module => $modValue){
                                    $enable = isset($modValue['enable']) ? (bool)$modValue['enable'] : false;
                                    if($enable && !empty($module)){
                                        $modulePath = '/protected/modules/'.htmlspecialchars($module).'/';
                                        $xml = simplexml_load_file(APPHP_PATH.$modulePath.'info.xml');
                                        if(is_object($xml)){		
                                            $sqlDumpFile = isset($xml->files->data->install) ? $xml->files->data->install : '';
                                            $sqlDump = file(APPHP_PATH.$modulePath.'data/'.$sqlDumpFile);
                                            if(!empty($sqlDump)){											
                                                // get and run sql schema filename for the module
                                                $sqlDump = str_ireplace('<DB_PREFIX>', $this->_cSession->get('dbPrefix'), $sqlDump);
                                                $model->doBeginTransaction();
                                                if(!$model->install($sqlDump, false)){
                                                    $modulesError = true;
                                                    $this->_view->actionMessage = CWidget::create('CMessage', array('error', $model->getErrorMessage()));
                                                }else{
                                                    // copy module files
                                                    foreach($xml->files->children() as $folder){
                                                        if(isset($folder['exclude']) && strtolower($folder['exclude']) == 'yes') continue;
                                                        if(!isset($folder['installationPath'])) continue;
                                                        
                                                        $src = APPHP_PATH.$modulePath.$folder->getName().'/';
                                                        if(isset($folder['byDirectory']) && strtolower($folder['byDirectory']) == 'true'){
                                                            // copy by whole directory
                                                            $srcFolder = $modulePath.$folder->getName().'/';
                                                            $destFolder = '/'.$folder['installationPath'];
                                                            if(!CFile::copyDirectory($srcFolder, $destFolder)){
                                                                $modulesWarning = true;
                                                                $this->_view->actionMessage .= CWidget::create('CMessage', array('warning', A::t('core', 'An error occurred while copying the folder {source} to {destination}.', array('{source}'=>$srcFolder, '{destination}'=>$destFolder))));
                                                            }														
                                                        }else{
                                                            // copy file by file (default)
                                                            if(substr($folder['installationPath'], -1) === '*'){
                                                                // prepare array of destinations for copying to all subfolders
                                                                $destPaths = CFile::findSubDirectories(substr($folder['installationPath'], 0, -1), true);
                                                            }else{
                                                                $destPaths = array($folder['installationPath']);
                                                            }							
                                                            foreach($destPaths as $destPath){
                                                                $dest = APPHP_PATH.'/'.trim($destPath, '/').'/';
                                                                foreach($folder->children() as $file){
                                                                    if(count($file->children())){
                                                                        $destSubPath = $file->getName().'/';
                                                                        if(basename($destPath) != trim($destSubPath, '/')) continue;
                                                                        if(!CFile::copyFile($src.$destSubPath.$file->filename, $dest.$file->filename)){
                                                                            $modulesError = true;
                                                                            $this->_view->actionMessage .= CWidget::create('CMessage', array('warning', A::t('core', 'An error occurred while copying the file {source} to {destination}.', array('{source}'=>$file, '{destination}'=>trim($destPath, '/').'/'.$file->filename))));
                                                                        }
                                                                    }else{
                                                                        if(isset($file['exclude']) && strtolower($file['exclude']) == 'yes') continue;
                                                                        if(!file_exists($dest)) mkdir($dest);
                                                                        if(!CFile::copyFile($src.$file, $dest.$file)){
                                                                            $modulesWarning = true;
                                                                            $this->_view->actionMessage .= CWidget::create('CMessage', array('warning', A::t('core', 'An error occurred while copying the file {source} to {destination}.', array('{source}'=>$file, '{destination}'=>trim($destPath, '/').'/'.$file))));
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }												
                                                }											
                                                if($modulesError){
                                                    $model->doRollBack();
                                                    break;
                                                }else{
                                                    $model->doCommit();	
                                                }											
                                            }else{
                                                $modulesError = true;
                                                $this->_view->actionMessage .= CWidget::create('CMessage', array('error', $model->getErrorMessage()));
                                                break;	
                                            }									
                                        }else{
                                            $modulesError = true;
                                            $this->_view->actionMessage .= CWidget::create('CMessage', array('error', A::t('core', 'Failed to load XML file {file}.', array('{file}'=>$modulePath.'/info.xml'))));
                                            break;	
                                        } 									
                                    }
                                } // modules loop
                            }                            
                        }
						if(!$modulesError){
                            $this->_view->installed = true;
                            $this->_cSession->set('step', 5);
                            if(!$modulesWarning){
                                $this->redirect('setup/completed');
                            }
                        }
                    }else{
                        $this->_view->actionMessage = CWidget::create('CMessage', array('error', $model->getErrorMessage()));
                    }
                }                
            }						
        }
		
        $this->_view->componentsList = isset($this->_configMain['components']) ? $this->_configMain['components'] : '';
		$this->_view->modulesList = isset($this->_configMain['modules']) ? $this->_configMain['modules'] : '';
        $this->_view->setMetaTags('title', (($this->_cSession->get('setupType') == 'update') ? A::t('setup', 'Ready to Install Updates | Setup Wizard') : A::t('setup', 'Ready to Install | Setup Wizard')));
        $this->_view->render('setup/ready');
    }

	/**
     * Complete installation
	 */
    public function completedAction()
	{
        // check if previous step was passed
        if($this->_cSession->get('step') < 5){
            $this->redirect('setup/index');
        }       
        
        $this->_view->username = $this->_cSession->get('username');
        $this->_view->password = $this->_cSession->get('password');
        $this->_view->actionMessage = CWidget::create('CMessage', array('success', A::t('setup', 'Installation Success Notice')));
        
        $dbContent = file_get_contents(APPHP_PATH.'/protected/data/config.db.tpl');
        $dbContent = str_ireplace('<DB_DRIVER>', $this->_cSession->get('dbDriver'), $dbContent);
        $dbContent = str_ireplace('<DB_HOST>', $this->_cSession->get('dbHost'), $dbContent);
        $dbContent = str_ireplace('<DB_NAME>', $this->_cSession->get('dbName'), $dbContent);
        $dbContent = str_ireplace('<DB_USER>', $this->_cSession->get('dbUser'), $dbContent);
        $dbContent = str_ireplace('<DB_PASSWORD>', $this->_cSession->get('dbPassword'), $dbContent);
        $dbContent = str_ireplace('<DB_PREFIX>', $this->_cSession->get('dbPrefix'), $dbContent);

        $mainContent = file_get_contents(APPHP_PATH.'/protected/data/config.main.tpl');
        $mainContent = str_ireplace('<INSTALLATION_KEY>', CHash::getRandomString(10), $mainContent);
        
        $dbFile = APPHP_PATH.'/protected/config/db.php';
        $mainFile = APPHP_PATH.'/protected/config/main.php';
        
        $dbFileHandler = fopen($dbFile, 'w+');
        $mainFileHandler = fopen($mainFile, 'w+');
        
        $dbFileWrite = fwrite($dbFileHandler, $dbContent);
        $mainFileWrite = fwrite($mainFileHandler, $mainContent);
        
        if($dbFileWrite > 0 && $mainFileWrite > 0){
            $this->_view->actionMessage = CWidget::create('CMessage', array('success', A::t('setup', 'Installation Success Notice')));
            $this->_cSession->endSession();
        }else{				
            $this->_view->actionMessage = CWidget::create('CMessage', array('error', A::t('setup', 'Configuration File Access Error', array('{path}'=>APPHP_PATH))));            
        }
        fclose($dbFileHandler);
        fclose($mainFileHandler);
        
        $this->_view->setMetaTags('title', A::t('setup', 'Completed | Setup Wizard'));        
        $this->_view->render('setup/completed');
    }
    
	/**
     * Tests mod_rewrite
	 */
    public function testModeRewriteAction()
    {
        echo 'test_mod_rewrite';
    }

	/**
     * Checks mod_rewrite
	 */
    private function _checkModRewrite()
    {
        if(function_exists('apache_get_modules')){
            // works only if PHP is not running as CGI module
            $mod_rewrite = in_array('mod_rewrite', apache_get_modules());
        }else{
            // old - $mod_rewrite = getenv('HTTP_MOD_REWRITE') == 'On' ? true : false ;
            $file_content = file_get_contents($this->_cRequest->getBaseUrl().'setup/testModeRewrite');
            $mod_rewrite = (substr($file_content, 0, 16) == 'test_mod_rewrite') ? true : false;
        }   
        return $mod_rewrite;            
    }
    
	/**
     * Checks PDO extension
     * @param array $phpinfo
	 */
    private function _checkPdoExtension($phpinfo)
    {
        return (isset($phpinfo['PDO']['PDO support']) && $phpinfo['PDO']['PDO support'] == 'enabled') ? true : false;
    }
   
}
