<?php
/**
* ModulesController
*
* PUBLIC:                  	PRIVATE
* -----------              	------------------
* __construct              	getModules
* indexAction			   	prepareTab 
* systemAction              prepareSettingsTab
* applicationAction         readModuleXml
* editAction				
* installAction
* uninstallAction
* settingsAction
*
*/

class ModulesController extends CController
{
	
    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

        // block access to this controller for not-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to view modules
		if(!Admins::hasPrivilege('modules', 'view')){
			$this->redirect('backend/index');
		}
		
        // set meta tags according to active language
    	SiteSettings::setMetaTags(array('title'=>A::t('app', 'Modules Management')));
				
        A::app()->view->setTemplate('backend');
        $this->view->actionMessage = '';
        $this->view->errorField = '';
		$this->view->notInstalledModulesList = array();
	}
        
	/**
	 * Controller default action handler
	 */
	public function indexAction()
	{
        $this->redirect('modules/application');    
    }

    /**
     * View system modules action handler
     * @param string $msg 
     */
	public function systemAction($msg = '')
	{
		$this->view->modulesList = $this->getSystemModules();		
        $this->view->notInstalledModulesList = $this->getNotInstalledModules('system');
		$this->view->tabs = $this->prepareTab('system');		
		
		if($msg == 'updated'){
			$this->view->actionMessage = CWidget::create('CMessage', array('success', A::t('core', 'The updating operation has been successfully completed!'), array('button'=>true)));
		}
    	$this->view->render('modules/system');		
    }

    /**
     * View application modules action handler
     * @param string $msg 
     */
	public function applicationAction($msg = '')
	{
		$this->view->modulesList = $this->getApplicationModules();		
        $this->view->notInstalledModulesList = $this->getNotInstalledModules('application');
		$this->view->tabs = $this->prepareTab('application');		
		
		if($msg == 'updated'){
			$this->view->actionMessage = CWidget::create('CMessage', array('success', A::t('core', 'The updating operation has been successfully completed!'), array('button'=>true)));
		}
    	$this->view->render('modules/application');		
    }
    
    /**
     * Edit module action handler
     * @param int $id The module id 
     */
	public function editAction($id = 0)
	{	
		// block access if admin has no active privilege to edit modules
     	if(!Admins::hasPrivilege('modules', 'edit')){
     		$this->redirect('backend/index');
     	}
     	
		$module = Modules::model()->findByPk($id);
		if(!$module){
			$this->redirect('modules/view');
		}
		$this->view->module = $module;		
		$this->view->render('modules/edit');
	}

	/**
	 * View and edit module settings action handler
	 * @param string $code The module code 
	 */
	public function settingsAction($code = '')
	{	
		$cRequest = A::app()->getRequest();
		$msg = '';
		$errorType = '';
		
		// fetch the module
		$moduleCode = $cRequest->getPost('act') == 'send' ? $cRequest->getPost('code') : $code;
		$module = Modules::model()->find('code = :code', array(':code'=>$moduleCode));
		if(empty($module)){
			$this->redirect('modules/index');
		}
		$moduleName = $module[0]['name'];
		
		$moduleSettings = ModulesSettings::model()->findAll(array('condition'=>'module_code = :moduleCode', 'order'=>'id ASC'), array(':moduleCode'=>$moduleCode));
    	$valuesArray = array();    	
		if($cRequest->getPost('act') == 'send'){
    		// module settings form submit
			
			$valuesArray = array();
			$fields = array();
			if(is_array($moduleSettings)){
				foreach($moduleSettings as $setting){
					$valuesArray[$setting['id']] = $cRequest->getPost('value_'.$setting['id']);
				
					// array of fields for form validation
					// todo: validate each value according to its type (property_type)
					$validationType = 'any';
					$validationSource = '';
					$min = $max = '';
					$maxLength = 1000;
					switch($setting['property_type']){
						case 'enum': 
							$validationType = 'set';
							$validationSource = explode(',', $setting['property_source']);
							break;
						case 'range':
							$validationType = 'range';
							$sourceParts = explode('-', $setting['property_source']);
							$min = isset($sourceParts[0]) ? $sourceParts[0] : '';
							$max = isset($sourceParts[1]) ? $sourceParts[1] : '';
							break;
						case 'bool': 
							$validationType = 'set';
							$validationSource = array(0,1);
							break;
						case 'email': 
							$maxLength = 100;
						case 'numeric': 
						case 'integer':
						case 'float':
							$validationType = $setting['property_type'];
							break;
						case 'positive integer':
							$validationType = 'positiveInteger';
							break;
						case 'label':
							unset($valuesArray[$setting['id']]);
							break;
						case 'string':	 
						case 'text': 
						default:	 
							$validationType = 'any';
					}					
					$fields['value_'.$setting['id']] = array('title'=>$setting['name'], 'validation'=>array('required'=>$setting['is_required'], 'type'=>$validationType, 'minValue'=>$min, 'maxValue'=>$max, 'source'=>$validationSource, 'maxLength'=>$maxLength));
				}
			}
						
			// validate the form
			$result = CWidget::create('CFormValidation', array('fields'=>$fields));
			if($result['error']){
				$msg = $result['errorMessage'];
				$this->view->errorField = $result['errorField'];
				$errorType = 'validation';
			}else{
				if(APPHP_MODE == 'demo'){
					$msg = A::t('core', 'This operation is blocked in Demo Mode!');
					$errorType = 'warning';
				}else{
					$model = new ModulesSettings();
					if($model->update($valuesArray)){
						$msg = A::t('app', 'Module Settings Update Success Message');
						$errorType = 'success';
						$this->view->actionMessage = CWidget::create('CMessage', array($errorType, $msg, array('button'=>true)));
					}else{
						$msg = A::t('app', 'Module Settings Update Error Message');
						$this->view->errorField = '';
						$errorType = 'error';
					}					
				}
			}
			if(!empty($msg)){
				$this->view->actionMessage = CWidget::create('CMessage', array($errorType, $msg, array('button'=>true)));
			}				
    	}

        // set meta tags according to active language
    	SiteSettings::setMetaTags(array('title'=>$moduleName));

		$this->view->module = $module;
		$this->view->tabs = $this->prepareSettingsTab($module[0]['code']);
		$this->view->moduleSettings = $moduleSettings;
		$this->view->valuesArray = $valuesArray;
		$this->view->render('modules/settings');
	}
	
	/**
	 * Install module action handler
	 * @param string $code The module code
	 */
	public function installAction($code = '')
	{
		// block access if admin has no active privilege to edit modules
     	if(!Admins::hasPrivilege('modules', 'edit')){
     		$this->redirect('backend/index');
     	}
		
     	$installed = false;
		$msg = '';
		$msgSuccess = '';
		$errorType = '';
		$moduleType = '';
		
		// check if the module is already installed
       	if(Modules::model()->exists('code = :code AND is_installed = 1', array(':code'=>$code))){
       		$msg = A::t('app', 'Module Installed Message');
       		$errorType = 'warning';
       		$moduleType = 'application';
		}else if(APPHP_MODE == 'demo'){
			$msg = A::t('core', 'This operation is blocked in Demo Mode!');			
			$errorType = 'warning';
		}else{
			// read module xml file
			$xml = $this->readModuleXml($code);
			$name = isset($xml->name) ? $xml->name : $code;
			$moduleType = $xml->moduleType;
			if(!is_object($xml)){
				// if failed to read XML file, $xml will contain error message 
				$msg = $xml;
				$errorType = 'error';				
			}else if($xml->moduleType == 'system'){
				// check if this module is a system module
				$msg = A::t('app', 'Operation Blocked Error Message');
				$errorType = 'error';
			}else{								
				// get sql schema filename from the xml
				$sqlInstallFile = isset($xml->files->data->install) ? $xml->files->data->install : '';
				$sqlUninstallFile = isset($xml->files->data->uninstall) ? $xml->files->data->uninstall : '';
				$msg = $this->runSqlFile(APPHP_PATH.'/protected/modules/'.$code.'/data/'.$sqlInstallFile);
				if($msg != ''){
    				$errorType = 'error';
					$msg = A::t('app', 'Module Installation Error Message', array('{module}'=>$name)).'<br><br>'.$msg;
					$this->runSqlFile(APPHP_PATH.'/protected/modules/'.$code.'/data/'.$sqlUninstallFile, true);
				}else{
					$installed = true;					

					$msgSuccess = A::t('app', 'Module Installation Success Message', array('{module}'=>$name));
					$msgSuccess .= '<br><br>'.A::t('app', 'Adding information to database').' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';
					$msgSuccess .= '<br>'.A::t('app', 'Copying files and directories');

					// copy module files
					foreach($xml->files->children() as $folder){
						if(isset($folder['installationPath'])){
							$src = '/protected/modules/'.$code.'/'.$folder->getName().'/';
							if(isset($folder['byDirectory']) && $folder['byDirectory'] == 'true'){
								// copy by whole directory
								$msgSuccess .= '<br> - dir.: '.$folder['installationPath'].'*';
								$dest = '/'.$folder['installationPath'];
								if(!CFile::copyDirectory($src, $dest)){
									$msg .= (!empty($msg) ? '<br>' : '').A::t('app', 'Folder Copy Error Message', array('{source}'=>$src, '{destination}'=>$dest));
									$errorType = 'warning';									
									$msgSuccess .= ' ... <span style="color:darkred;">'.A::t('app', 'Failed').'</span>';
								}else{
									$msgSuccess .= ' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';
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
										$installable = (isset($file['exclude']) && $file['exclude'] == 'yes') ? false : true;
										if($installable){
											if(!file_exists($dest)) mkdir($dest);
											$msgSuccess .= '<br> - file: '.trim($destPath, '/').'/'.$file;
											if(!CFile::copyFile(APPHP_PATH.$src.$file, $dest.$file)){ 
												$msg .= (!empty($msg) ? '<br>' : '').A::t('app', 'File Copy Error Message', array('{source}'=>$file, '{destination}'=>$destPath.$file));
												$errorType = 'warning';
												$msgSuccess .= ' ... <span style="color:darkred;">'.A::t('app', 'Failed').'</span>';
											}else{
												$msgSuccess .= ' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}	
		if($installed){
			$this->view->actionMessage .= CWidget::create('CMessage', array('success', $msgSuccess, array('button'=>true)));
		}
		$this->view->actionMessage .= CWidget::create('CMessage', array($errorType, $msg, array('button'=>true)));
			
        $this->view->modulesList = $this->getApplicationModules();
		$this->view->notInstalledModulesList = $this->getNotInstalledModules();
		$this->view->tabs = $this->prepareTab(($moduleType == 'application') ? 'application' : 'system');
		$this->view->render('modules/'.(($moduleType == 'application') ? 'application' : 'system'));
	}
	
	/**
	 * Uninstall module action handler
	 * @param int $id The module id 
	 */
	public function uninstallAction($id = 0)
	{
		// block access if admin has no active privilege to edit modules
     	if(!Admins::hasPrivilege('modules', 'edit')){
     		$this->redirect('backend/index');
     	}
		
     	$msg = '';
		$errorType = '';
		
		$module = Modules::model()->findByPk((int)$id);
       	if(!$module){
			$this->redirect('modules/index');
       	}
		// check if the module is already installed
       	if(!$module->is_installed){
       		$msg = A::t('app', 'Module Not Installed Message');
       		$errorType = 'warning';
       		
       	// check if the module is system module
		}else if($module->is_system){
       		$msg = A::t('app', 'Operation Blocked Error Message');
       		$errorType = 'error'; 
       		      		
       	}else if(APPHP_MODE == 'demo'){
			$msg = A::t('core', 'This operation is blocked in Demo Mode!');
			$errorType = 'warning';
		}else{
       		// read module xml file
			$xml = $this->readModuleXml($module->code);
			if(!is_object($xml)){
				// if failed to read XML file, $xml will contain error message 
				$msg = $xml;
				$errorType = 'error';
			}else{
				// get sql schema filename from the xml
				$sqlFile = isset($xml->files->data->uninstall) ? $xml->files->data->uninstall : '';
				$msg = $this->runSqlFile(APPHP_PATH.'/protected/modules/'.$module->code.'/data/'.$sqlFile, true);
				if($msg != ''){
    				$errorType = 'error';
					$msg = A::t('app', 'Module Uninstallation Error Message', array('{module}'=>$module->name)).'<br><br>'.$msg;
				}else{
					// remove module files
					$msg = A::t('app', 'Module Uninstallation Success Message', array('{module}'=>$module->name));
					$msg .= '<br><br>'.A::t('app', 'Removing information from database').' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';
					$errorType = 'success';	
					$deletedFiles = '';
					foreach($xml->files->children() as $folder){
						if(isset($folder['installationPath'])){
							if(isset($folder['byDirectory']) && $folder['byDirectory'] == "true"){
								// remove by whole directory
								$deletedFiles .= '<br> - dir.: '.$folder['installationPath'].'*';
								if(!CFile::emptyDirectory(trim($folder['installationPath']))){
									$msg .= (!empty($msg) ? '<br>' : '').A::t('app', 'Module Uninstallation Warning Message', array('{module}'=>$module->name));
									$deletedFiles .= ' ... <span style="color:darkred;">'.A::t('app', 'Failed').'</span>';
									$errorType = 'warning';									
								}else{
									$deletedFiles .= ' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';
								}
							}else{
								if(substr($folder['installationPath'], -1) === '*'){
									// prepare array of destinations for deleting from all subfolders
									$destPaths = CFile::findSubDirectories(substr($folder['installationPath'], 0, -1), true);
								}else{
									$destPaths = array($folder['installationPath']);
								}
								foreach($folder->children() as $file){
									$installable = (isset($file['exclude']) && $file['exclude'] == 'yes') ? false : true;
									if($installable){
										foreach($destPaths as $destPath){
											$destFile = APPHP_PATH.'/'.trim($destPath, '/').'/'.$file;
											$deletedFiles .= '<br> - file: '.trim($destPath, '/').'/'.$file;
											if(@unlink($destFile)){
												$deletedFiles .= ' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';
											}else{
												$deletedFiles .= ' ... <span style="color:darkred;">'.A::t('app', 'Failed').'</span>';
												$msg = A::t('app', 'Module Uninstallation Warning Message', array('{module}'=>$module->name));
												$errorType = 'warning';
											}
										}
									}
								}
							}
						}
					}
					$msg .= '<br>'.A::t('app', 'Removing files and directories').$deletedFiles;
				}
			}	
		}
		if(empty($msg)){
			// success
			$msg = A::t('app', 'Module Uninstallation Success Message', array('{module}'=>$module->name));
       		$errorType = 'success';
		}
		$this->view->actionMessage = CWidget::create('CMessage', array($errorType, $msg, array('button'=>true)));
					
        $this->view->modulesList = $this->getApplicationModules();
		$this->view->notInstalledModulesList = $this->getNotInstalledModules();
    	$this->view->tabs = $this->prepareTab('application');
		$this->view->render('modules/application');
	}
		
	/**
	 * Reads the module info.xml file
	 * @param string $moduleCode the module code
	 * @return the contents of the XML file or error message 
	 */
	private function readModuleXml($moduleCode)
	{
		if(!file_exists('protected/modules/'.$moduleCode.'/info.xml')) {
			return A::t('app', 'File Reading Error Message', array('{file}'=>APPHP_PATH.'/modules/'.$moduleCode.'/info.xml'));
		}
		
    	// load XML file for module info
		$xml = simplexml_load_file('protected/modules/'.$moduleCode.'/info.xml');
		if(!is_object($xml)){		
			return A::t('app', 'XML File Error Message', array('{file}'=>APPHP_PATH.'/modules/'.$moduleCode.'/info.xml'));
		} 
		
		return $xml;
	}
	
	/**
	 * Executes the module sql file 
	 * @param string $sqlFile the sql file name with path
	 * @param bool $ignoreErrors
	 * @return string Error message, will be empty on success
	 */
	private function runSqlFile($sqlFile = '', $ignoreErrors = false)
	{
		// get sql schema content
		if(file_exists($sqlFile)){
			$sqlDump = file($sqlFile);
			if(!empty($sqlDump)){
				// replace placeholders
				$sqlDump = str_ireplace('<DB_PREFIX>', CConfig::get('db.prefix'), $sqlDump);			
				// run the sql
				$model = new Setup();
				$result = $model->install($sqlDump, true, $ignoreErrors);
				if(!$result || $ignoreErrors){
					return $model->getErrorMessage();
				}
			}		
		}else{
			return A::t('app', 'File Opening Error Message', array('{file}'=>$sqlFile));
		}
		
		// return empty message on success
		return '';
	}
	
	/**
	 * Returns list of system modules that are installed in the database
	 * @return array 
	 */
	private function getSystemModules()
	{
		// get list of all modules folders
		$modulesFolders = CFile::findSubDirectories('protected/modules/');
		$codesList = "'".implode("','", $modulesFolders)."'";
		
		// get system modules
		return Modules::model()->findAll('code IN ('.$codesList.') AND is_system = 1');
	}	
	
	/**
	 * Returns list of application modules that are installed in the database
	 * @return array 
	 */
	private function getApplicationModules()
	{
		// get list of all modules folders
		$modulesFolders = CFile::findSubDirectories('protected/modules/');
		$codesList = "'".implode("','", $modulesFolders)."'";
		
		// get application (not system) modules
		return Modules::model()->findAll('code IN ('.$codesList.') AND is_system = 0');
    }
    
	/**
	 * Returns list of not installed modules 
     * @param string $moduleType The modules type (application|system)
	 * @return array 
	 */
    private function getNotInstalledModules($moduleType = 'application')
    {
    	// get all not installed modules from configuration file
    	$modulesList = array();
    	$modules = CConfig::get('modules');
    	if(is_array($modules)){
    		foreach($modules as $code => $module){
	    		$enable = isset($module['enable']) ? (bool)$module['enable'] : false;
	    		if($enable){
	    			$moduleCode = strtolower($code);
	    			// check if this module is installed
	    			if(!Modules::model()->exists('code = :code AND is_installed = 1', array(':code'=>$moduleCode))){
	    				$xml = $this->readModuleXml($moduleCode);    				
	    				if(is_object($xml)){
		    				// check the module type
		    				if($moduleType == '' || ($moduleType != '' && $xml->moduleType == $moduleType)){
			    				$modulesList[$moduleCode] = array(
									'name' => isset($xml->name) ? $xml->name : A::t('app', 'Unknown'),
									'description' => isset($xml->description) ? $xml->description : A::t('app', 'Unknown'),
									'version' => isset($xml->version) ? $xml->version : A::t('app', 'Unknown'),
			    				);
		    				}
	    				}
	    			}
	    		}
	    	}
    	}
    	return $modulesList;
    }
    
    /**
     * Prepare modules view tabs
     * @param string $activeTab The active tab (system|application)
     */
	private function prepareTab($activeTab = 'application')
    {
		return CWidget::create('CTabs', array(
		   	'tabsWrapper'=>array('tag'=>'div', 'class'=>'title'),
			'tabsWrapperInner'=>array('tag'=>'div', 'class'=>'tabs'),
			'contentWrapper'=>array(),
			'contentMessage'=>'',
			'tabs'=>array(
				A::t('app', 'Application Modules') => array('href'=>'modules/application', 'id'=>'tab2', 'content'=>'', 'active'=>($activeTab == 'application' ? true : false)),
				A::t('app', 'System Modules')     => array('href'=>'modules/system', 'id'=>'tab1', 'content'=>'', 'active'=>($activeTab == 'system' ? true : false)),
			),
			'events'=>array(
				//'click'=>array('field'=>$errorField)
			),
			'return'=>true,
		));
	}

	/**
	 * Prepare module settings tab
	 * @param string $code the module code
	 */
    private function prepareSettingsTab($code = '')
    {
		$configModule = include(APPHP_PATH.'/protected/modules/'.$code.'/config/main.php');
		$managementLinks = (isset($configModule['managementLinks']) && is_array($configModule['managementLinks'])) ? $configModule['managementLinks'] : array();
		$tabs = array(A::t('app', 'Settings') => array('href'=>'modules/settings/code/'.$code, 'id'=>'tabSettings', 'content'=>'', 'active'=>true, 'htmlOptions'=>array('class'=>'modules-settings-tab')));
		
		foreach($managementLinks as $property => $val){
			$tabs[$property] = array('href'=>$val, 'id'=>'tab'.$property, 'content'=>'', 'active'=>false);
			// block access to prohibited tabs
			if(Admins::privilegeExists($code, $property) && !Admins::hasPrivilege($code, $property)){
				$tabs[$property]['disabled'] = true;
			}
		}

    	return CWidget::create('CTabs', array(
			'tabsWrapper'=>array('tag'=>'div', 'class'=>'title'),
			'tabsWrapperInner'=>array('tag'=>'div', 'class'=>'tabs'),
			'contentWrapper'=>array(),
			'contentMessage'=>'',
			'tabs'=>$tabs,
			'events'=>array(
				//'click'=>array('field'=>$errorField)
			),
			'return'=>true,
    	));
    }
 
}
