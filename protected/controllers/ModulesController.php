<?php
/**
 * Modules controller
 *
 * PUBLIC:                  	PRIVATE
 * -----------              	------------------
 * __construct              	_getSystemModules
 * indexAction			   	    _getApplicationModules
 * systemAction                 _getNotInstalledModules
 * applicationAction            _getModules
 * editAction				    _prepareTab 
 * installAction                _prepareSettingsTab
 * updateAction                 _readModuleXml 
 * uninstallAction              _runSqlFile 
 * settingsAction               _processInfoXml 
 *                              _getSubDirectories
 *                              _copyFile
 *                              _deleteFile  
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
    	Website::setMetaTags(array('title'=>A::t('app', 'Modules Management')));				
        // set backend mode
        Website::setBackend();
        
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
		$this->_view->notInstalledModulesList = array();
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
		$this->_view->modulesList = $this->_getSystemModules();		
        $this->_view->notInstalledModulesList = $this->_getNotInstalledModules('system');
        $this->_view->allModulesList = $this->_getModules('application');
		$this->_view->tabs = $this->_prepareTab('system');		
		
		if($msg == 'updated'){
			$this->_view->actionMessage = CWidget::create('CMessage', array('success', A::t('core', 'The updating operation has been successfully completed!'), array('button'=>true)));
		}
    	$this->_view->render('modules/system');		
    }

    /**
     * View application modules action handler
     * @param string $msg 
     */
	public function applicationAction($msg = '')
	{
		$this->_view->modulesList = $this->_getApplicationModules();		
        $this->_view->notInstalledModulesList = $this->_getNotInstalledModules('application');
        $this->_view->allModulesList = $this->_getModules('application');
		$this->_view->tabs = $this->_prepareTab('application');		
		
		if($msg == 'updated'){
			$this->_view->actionMessage = CWidget::create('CMessage', array('success', A::t('core', 'The updating operation has been successfully completed!'), array('button'=>true)));
		}
    	$this->_view->render('modules/application');		
    }
    
    /**
     * Edit module action handler
     * @param int $id the module id 
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
		$this->_view->module = $module;		
		$this->_view->render('modules/edit');
	}

	/**
	 * View and edit module settings action handler
	 * @param string $code the module code 
	 */
	public function settingsAction($code = '')
	{	
		$cRequest = A::app()->getRequest();
		$msg = '';
		$msgType = '';
		
		// fetch the module
		$moduleCode = $cRequest->getPost('act') == 'send' ? $cRequest->getPost('code') : $code;
        $module = Modules::model()->find('code = :code', array(':code'=>$moduleCode));
		if(!$module){
            $this->redirect('modules/index');
        }else{
            $moduleName = $module->name;
		}		
		
		$moduleSettings = ModulesSettings::model()->findAll(array('condition'=>'module_code = :moduleCode', 'order'=>'property_group ASC, id ASC'), array(':moduleCode'=>$moduleCode));
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
				$this->_view->errorField = $result['errorField'];
				$msgType = 'validation';
			}else{
				if(APPHP_MODE == 'demo'){
					$msg = A::t('core', 'This operation is blocked in Demo Mode!');
					$msgType = 'warning';
				}else{
					$model = new ModulesSettings();
					if($model->update($valuesArray)){
						$msg = A::t('app', 'Module Settings Update Success Message');
						$msgType = 'success';
						$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
					}else{
						$msg = A::t('app', 'Module Settings Update Error Message');
						$msgType = 'error';
						$this->_view->errorField = '';
					}					
				}
			}
			if(!empty($msg)){
				$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
			}				
    	}

        // set meta tags according to active language
    	Website::setMetaTags(array('title'=>$moduleName));

		$this->_view->module = $module;
		$this->_view->tabs = $this->_prepareSettingsTab($module->code);
		$this->_view->moduleSettings = $moduleSettings;
		$this->_view->valuesArray = $valuesArray;
		$this->_view->render('modules/settings');
	}
	
	/**
	 * Install module action handler
	 * @param string $code the module code
	 */
	public function installAction($code = '')
	{
		// block access if admin has no active privilege to edit modules
     	if(!Admins::hasPrivilege('modules', 'edit')){
     		$this->redirect('backend/index');
     	}
		
     	$installed = false;
		$msgType = '';
		$moduleType = '';
		$msg = '';
		
		// check if the module is already installed
        if(APPHP_MODE == 'demo'){
			$msg = A::t('core', 'This operation is blocked in Demo Mode!');			
			$msgType = 'warning';
       	}else if(Modules::model()->exists('code = :code AND is_installed = 1', array(':code'=>$code))){
       		$msg = A::t('app', 'Module Already Installed Message');
       		$msgType = 'warning';
       		$moduleType = 'application';
		}else{
			// read module xml file
			$xml = $this->_readModuleXml($code);
			$name = isset($xml->name) ? $xml->name : $code;
			$moduleType = $xml->moduleType;
			if(!is_object($xml)){
				// if failed to read XML file, $xml will contain error message 
				$msg = A::t('app', 'XML File Error Message', array('{file}'=>CFile::createShortenName(APPHP_PATH.'/modules/'.$code.'/info.xml', 30, 50)));
				$msgType = 'error';				
			}else if($xml->moduleType == 'system'){
				// check if this module is a system module
				$msg = A::t('app', 'Operation Blocked Error Message');
				$msgType = 'error';
			}else{								
				// get sql schema filename from the xml
				$sqlInstallFile = isset($xml->files->data->install) ? $xml->files->data->install : '';
				$sqlUninstallFile = isset($xml->files->data->uninstall) ? $xml->files->data->uninstall : '';
                $msgSql = $this->_runSqlFile(APPHP_PATH.'/protected/modules/'.$code.'/data/'.$sqlInstallFile);
				if($msgSql){
					$msg = A::t('app', 'Module Installation Error Message', array('{module}'=>$name)).'<br><br>'.$msgSql;
    				$msgType = 'error';
					$this->_runSqlFile(APPHP_PATH.'/protected/modules/'.$code.'/data/'.$sqlUninstallFile, true);
				}else{
					$installed = true;					

					$msgSuccess = A::t('app', 'Module Installation Success Message', array('{module}'=>$name));
					$msgSuccess .= '<br><br>'.A::t('app', 'Adding information to database').' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';
					$msgSuccess .= '<br>'.A::t('app', 'Copying files and directories');
                    
                    $result = $this->_processInfoXml($xml, $code);
                    $msg .= $result['msg'];
                    $msgSuccess .= $result['msg_success'];
                    $msgType .= $result['error_type'];
				}
			}
		}	
		if($installed){
			$this->_view->actionMessage .= CWidget::create('CMessage', array('success', $msgSuccess, array('button'=>true)));
		}
		$this->_view->actionMessage .= CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
			
        $this->_view->modulesList = $this->_getApplicationModules();
		$this->_view->notInstalledModulesList = $this->_getNotInstalledModules();
        $this->_view->allModulesList = $this->_getModules('application');
		$this->_view->tabs = $this->_prepareTab(($moduleType == 'application') ? 'application' : 'system');
		$this->_view->render('modules/'.(($moduleType == 'application') ? 'application' : 'system'));
	}
	
	/**
	 * Update module action handler
	 * @param string $code the module code
	 */
	public function updateAction($code = '')
	{
		// block access if admin has no active privilege to edit modules
     	if(!Admins::hasPrivilege('modules', 'edit')){
     		$this->redirect('backend/index');
     	}
		
        $allModulesList = $this->_getModules('application');
        $moduleVersion = isset($allModulesList[$code]['version']) ? $allModulesList[$code]['version'] : '';
     	$updated = false;
		$msg = '';
		$msgSuccess = '';
		$msgType = '';
		$moduleType = '';
        
		// check if the last version of the module is already installed
        if(APPHP_MODE == 'demo'){
			$msg = A::t('core', 'This operation is blocked in Demo Mode!');			
			$msgType = 'warning';
		}else if(Modules::model()->exists('code = :code AND is_installed = 1'.(!empty($moduleVersion) ? ' AND version >= \''.$moduleVersion.'\'' : ''), array(':code'=>$code))){
       		$msg = A::t('app', 'Module Already Updated Message', array('{module}'=>$code));
       		$msgType = 'error';
       		$moduleType = 'application';
		}else{
            $module = Modules::model()->find('code = :code', array(':code'=>$code));
            $installedVersion = $module ? $module->version : '';
			// read module xml file
			$xml = $this->_readModuleXml($code);
			$name = isset($xml->name) ? $xml->name : $code;
			$moduleType = $xml->moduleType;
			if(!is_object($xml)){
				// if failed to read XML file, $xml will contain error message                
				$msg = A::t('app', 'XML File Error Message', array('{file}'=>CFile::createShortenName(APPHP_PATH.'/modules/'.$code.'/info.xml', 30, 50)));
				$msgType = 'error';				
			}else if($xml->moduleType == 'system'){
				// check if this module is a system module
				$msg = A::t('app', 'Operation Blocked Error Message');
				$msgType = 'error';
			}else{								
				// get sql schema filename from the xml
				$sqlUpdateFiles = isset($xml->files->data->update) ? $xml->files->data->update : '';
                $moduleLastVersion = isset($xml->version) ? $xml->version : '';
                if(is_object($sqlUpdateFiles)){
                    $count = 0;
                    foreach($sqlUpdateFiles->children() as $sqlUpdateFile){
                        $count++;
                        // continue if there is a smaller version
                        if(preg_replace('/[^0-9]/', '', $sqlUpdateFile) <= preg_replace('/[^0-9]/', '', $installedVersion)) continue;
                        $msgSql = $this->_runSqlFile(APPHP_PATH.'/protected/modules/'.$code.'/data/'.$sqlUpdateFile);
                        if($msgSql){
                            $msg = A::t('app', 'Module Updating Error Message', array('{module}'=>$name)).'<br><br>'.$msgSql;
                            $msgType = 'error';
                            break;
                        }                        
                    }
                    if(!$count){
                        // run single file
                        $msgSql = $this->_runSqlFile(APPHP_PATH.'/protected/modules/'.$code.'/data/'.$sqlUpdateFiles);
                        if($msgSql){
                            $msg = A::t('app', 'Module Updating Error Message', array('{module}'=>$name)).'<br><br>'.$msgSql;
                            $msgType = 'error';
                        }                        
                    }
                    if(!$msgType){
                        $updated = true;
                        $msgSuccess = A::t('app', 'Module Updating Success Message', array('{module}'=>$name, '{version}'=>$moduleLastVersion));
                        $msgSuccess .= '<br><br>'.A::t('app', 'Updating information in database').' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';

                        $result = $this->_processInfoXml($xml, $code);
                        $msg .= $result['msg'];
                        $msgSuccess .= $result['msg_success'];
                        $msgType .= $result['error_type'];
                    }
                }
			}
		}	
		if($updated){
			$this->_view->actionMessage .= CWidget::create('CMessage', array('success', $msgSuccess, array('button'=>true)));
		}
		$this->_view->actionMessage .= CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
			
        $this->_view->modulesList = $this->_getApplicationModules();
		$this->_view->notInstalledModulesList = $this->_getNotInstalledModules();
        $this->_view->allModulesList = $this->_getModules('application');        
		$this->_view->tabs = $this->_prepareTab(($moduleType == 'application') ? 'application' : 'system');
		$this->_view->render('modules/'.(($moduleType == 'application') ? 'application' : 'system'));
	}

	/**
	 * Uninstall module action handler
	 * @param int $id the module id 
	 */
	public function uninstallAction($id = 0)
	{
		// block access if admin has no active privilege to edit modules
     	if(!Admins::hasPrivilege('modules', 'edit')){
     		$this->redirect('backend/index');
     	}
		
     	$msg = '';
		$msgType = '';
		
		$module = Modules::model()->findByPk((int)$id);
       	if(!$module){
			$this->redirect('modules/index');
       	}
        if(APPHP_MODE == 'demo'){
			$msg = A::t('core', 'This operation is blocked in Demo Mode!');
			$msgType = 'warning';
		}else if(!$module->is_installed){
            // check if the module is already installed
       		$msg = A::t('app', 'Module Not Installed Message');
       		$msgType = 'warning';
		}else if($module->is_system){
            // check if the module is system module
       		$msg = A::t('app', 'Operation Blocked Error Message');
       		$msgType = 'error';       	
		}else{
       		// read module xml file
			$xml = $this->_readModuleXml($module->code);
			if(!is_object($xml)){
				// if failed to read XML file, $xml will contain error message 
				$msg = A::t('app', 'XML File Error Message', array('{file}'=>CFile::createShortenName(APPHP_PATH.'/modules/'.$module->code.'/info.xml', 30, 50)));
				$msgType = 'error';
			}else{
				// get sql schema filename from the xml
				$sqlFile = isset($xml->files->data->uninstall) ? $xml->files->data->uninstall : '';
                $msgSql = $this->_runSqlFile(APPHP_PATH.'/protected/modules/'.$module->code.'/data/'.$sqlFile, true);
				if($msgSql){
					$msg = A::t('app', 'Module Uninstallation Error Message', array('{module}'=>$module->name)).'<br><br>'.$msgSql;
    				$msgType = 'error';
				}else{
                    // remove module from backend menu (if exists)
                    BackendMenus::model()->deleteMenu($module->code);
                    
					// remove module files
					$msg = A::t('app', 'Module Uninstallation Success Message', array('{module}'=>$module->name));
					$msg .= '<br><br>'.A::t('app', 'Removing information from database').' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';
					$msgType = 'success';	
					$deletedFiles = '';
					foreach($xml->files->children() as $folder){
                        if(isset($folder['exclude']) && strtolower($folder['exclude']) == 'yes') continue;
						if(!isset($folder['installationPath'])) continue;

                        if(isset($folder['byDirectory']) && strtolower($folder['byDirectory']) == 'true'){
                            // remove by whole directory
                            $deletedFiles .= '<br> - dir.: '.$folder['installationPath'].'*';
                            if(!CFile::emptyDirectory(trim($folder['installationPath']))){
                                $msg .= (!empty($msg) ? '<br>' : '').A::t('app', 'Module Uninstallation Warning Message', array('{module}'=>$module->name));
                                $deletedFiles .= ' ... <span style="color:darkred;">'.A::t('app', 'Failed').'</span>';
                                $msgType = 'warning';									
                            }else{
                                $deletedFiles .= ' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';
                            }
                        }else{
                            foreach($folder->children() as $file){
                                if(isset($file['exclude']) && strtolower($file['exclude']) == 'yes') continue;
                                    $destPaths = $this->_getSubDirectories($folder);
                                    foreach($destPaths as $destPath){                                        
                                        // by files in subdirectories
                                        if(count($file->children())){
                                            // subdirectory exists
                                            if(basename($destPath) != trim($file->getName(), '/')) continue;
                                            $destFile = APPHP_PATH.'/'.trim($destPath, '/').'/'.$file->filename;
                                            $deletedFiles .= '<br> - file: '.trim($destPath, '/').'/'.$file->filename;                                                
                                            $this->_deleteFile($destFile, $module->name, $deletedFiles, $msg, $msgType);
                                        // by files in directory
                                        }else{
                                            $destFile = APPHP_PATH.'/'.trim($destPath, '/').'/'.$file;
                                            $deletedFiles .= '<br> - file: '.trim($destPath, '/').'/'.$file;                                            
                                            $this->_deleteFile($destFile, $module->name, $deletedFiles, $msg, $msgType);
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
       		$msgType = 'success';
		}
		$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
					
        $this->_view->modulesList = $this->_getApplicationModules();
		$this->_view->notInstalledModulesList = $this->_getNotInstalledModules();
        $this->_view->allModulesList = $this->_getModules('application');        
    	$this->_view->tabs = $this->_prepareTab('application');
		$this->_view->render('modules/application');
	}
		
	/**
	 * Reads the module info.xml file
	 * @param string $moduleCode the module code
	 * @return the contents of the XML file or error message 
	 */
	private function _readModuleXml($moduleCode)
	{
		if(!file_exists('protected/modules/'.$moduleCode.'/info.xml')) {
			return A::t('app', 'File Reading Error Message', array('{file}'=>CFile::createShortenName(APPHP_PATH.'/modules/'.$moduleCode.'/info.xml', 30, 50)));
		}
		
    	// load XML file for module info
		$xml = simplexml_load_file('protected/modules/'.$moduleCode.'/info.xml');
		if(!is_object($xml)){		
			return A::t('app', 'XML File Error Message', array('{file}'=>CFile::createShortenName(APPHP_PATH.'/modules/'.$moduleCode.'/info.xml', 30, 50)));
		} 
		
		return $xml;
	}
	
	/**
	 * Executes the module sql file 
	 * @param string $sqlFile the sql file name with path
	 * @param bool $ignoreErrors
	 * @return string Error message, will be empty on success
	 */
	private function _runSqlFile($sqlFile = '', $ignoreErrors = false)
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
			}else{
                return A::t('app', 'File Empty Error Message', array('{file}'=>CFile::createShortenName(trim($sqlFile, "<br />\r\n"), 30, 50)));
            }
		}else{
			return A::t('app', 'File Opening Error Message', array('{file}'=>CFile::createShortenName(trim($sqlFile, "<br />\r\n"), 30, 50)));
		}
		
		// return empty message on success
		return '';
	}
	
	/**
	 * Returns list of system modules that are installed in the database
	 * @return array 
	 */
	private function _getSystemModules()
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
	private function _getApplicationModules()
	{
		// get list of all modules folders
		$modulesFolders = CFile::findSubDirectories('protected/modules/');
		$codesList = "'".implode("','", $modulesFolders)."'";
		
		// get application (not system) modules
		return Modules::model()->findAll('code IN ('.$codesList.') AND is_system = 0');
    }
    
	/**
	 * Returns list of not installed modules 
     * @param string $moduleType the modules type (application|system)
	 * @return array 
	 */
    private function _getNotInstalledModules($moduleType = 'application')
    {
    	// get all not installed modules from configuration file
    	$modulesList = array();
    	$modules = CConfig::get('modules');
    	if(!is_array($modules)) return $modulesList;
        
        foreach($modules as $code => $module){
            $enable = isset($module['enable']) ? (bool)$module['enable'] : false;
            if($enable){
                $moduleCode = strtolower($code);
                // check if this module is installed
                if(!Modules::model()->exists('code = :code AND is_installed = 1', array(':code'=>$moduleCode))){
                    $xml = $this->_readModuleXml($moduleCode);    				
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
    	return $modulesList;
    }
    
	/**
	 * Returns list of modules 
     * @param string $moduleType the modules type (application|system)
     * @param string $mCode
	 * @return array 
	 */
    private function _getModules($moduleType = 'application', $mCode = '')
    {
    	// get all not installed modules from configuration file
    	$modulesList = array();
    	$modules = CConfig::get('modules');
    	if(!is_array($modules)) return $modulesList;
        
        foreach($modules as $code => $module){
            if(!empty($mCode) && $code != $mCode) continue;
            $enable = isset($module['enable']) ? (bool)$module['enable'] : false;
            if($enable){
                $moduleCode = strtolower($code);
                $xml = $this->_readModuleXml($moduleCode);    				
                if(is_object($xml)){
                    // check the module type
                    if(!empty($moduleType) || ($moduleType != '' && $xml->moduleType == $moduleType)){
                        $modulesList[$moduleCode] = array(
                            'name' => isset($xml->name) ? $xml->name : A::t('app', 'Unknown'),
                            'description' => isset($xml->description) ? $xml->description : A::t('app', 'Unknown'),
                            'version' => isset($xml->version) ? $xml->version : A::t('app', 'Unknown'),
                        );
                    }
                }
            }
        }
    	return $modulesList;        
    }
    
    /**
     * Prepare modules view tabs
     * @param string $activeTab the active tab (system|application)
     */
	private function _prepareTab($activeTab = 'application')
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
    private function _prepareSettingsTab($code = '')
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
    
	/**
	 * Handles info.xml file
	 * @param object $xml
	 * @param string $code
	 */
    private function _processInfoXml($xml, $code)
    {
        $return = array('msg'=>'', 'msg_success' => '', 'error_type'=>'');
        
        // copy module files
        foreach($xml->files->children() as $folder){
            if(isset($folder['exclude']) && strtolower($folder['exclude']) == 'yes') continue;
            if(!isset($folder['installationPath'])) continue;
            $byDirectory = (isset($folder['byDirectory']) && strtolower($folder['byDirectory']) === 'true') ? true : false;

            $src = '/protected/modules/'.$code.'/'.$folder->getName().'/';
            if($byDirectory){
                // copy by whole directory
                $return['msg_success'] .= '<br> - dir.: '.$folder['installationPath'].'*';
                $dest = '/'.$folder['installationPath'];
                if(!CFile::copyDirectory($src, $dest)){
                    $return['msg'] .= (($return['msg'] != '') ? '<br>' : '').A::t('app', 'Folder Copy Error Message', array('{source}'=>$src, '{destination}'=>$dest));
                    $return['error_type'] = 'warning';									
                    $return['msg_success'] .= ' ... <span style="color:darkred;">'.A::t('app', 'Failed').'</span>';
                }else{
                    $return['msg_success'] .= ' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';
                }
            }else{
                // copy file by file (default)
                $destPaths = $this->_getSubDirectories($folder);
                foreach($destPaths as $destPath){
                    $destPath = trim($destPath, '/').'/';
                    $dest = APPHP_PATH.'/'.$destPath;                    
                    $return['msg_success'] .= '<br> - file: '.$destPath;
                    $found = false;
                    $defaultPath = $defaultFile = '';
                    foreach($folder->children() as $file){                        
                        // find and stire info about default file
                        if(isset($file['default'])){
                            $defaultPath = $file->getName().'/';
                            $defaultFile = $file->filename;
                        } 
                        // by files in subdirectories
                        if(count($file->children())){
                            // subdirectory exists
                            $destSubPath = $file->getName().'/';
                            if(basename($destPath) != trim($destSubPath, '/')) continue;
                            $return['msg_success'] .= $file->filename;                                
                            $this->_copyFile(APPHP_PATH.$src.$destSubPath, $dest, $file->filename, $return);
                            $found = true;
                        // by files in directory    
                        }else{
                            if(isset($file['exclude']) && strtolower($file['exclude']) == 'yes') continue;
                            if(!file_exists($dest)) mkdir($dest);
                            $return['msg_success'] .= $file;
                            $this->_copyFile(APPHP_PATH.$src, $dest, $file, $return);
                            $found = true;
                        }                        
                    }
                    if(!$found){
                        $return['msg_success'] .= $defaultFile;
                        if($defaultPath != '' && $defaultFile != ''){
                            $this->_copyFile(APPHP_PATH.$src.$defaultPath, $dest, $defaultFile, $return);                            
                        }
                    }
                }
            }
        }
        
        return $return;        
    }
    
	/**
	 * Returns subdirectories paths
	 * @param object $folder
	 */
    private function _getSubDirectories($folder)
    {
        if(substr($folder['installationPath'], -1) === '*'){
            // prepare array of destinations for copying to all subfolders
            $destPaths = CFile::findSubDirectories(substr($folder['installationPath'], 0, -1), true);
        }else{
            $destPaths = array($folder['installationPath']);
        }
        return $destPaths;
    }

	/**
	 * Performs copying operation
	 * @param string $source
	 * @param string $dest
	 * @param string $filename
	 * @param array &$return
	 */
    private function _copyFile($source, $dest, $filename, &$return)
    {                                
        if(!CFile::copyFile($source.$filename, $dest.$filename)){ 
            $return['msg'] .= (($return['msg'] != '') ? '<br>' : '').A::t('app', 'File Copy Error Message', array('{source}'=>$filename, '{destination}'=>$dest.$filename));
            $return['error_type'] = 'warning';
            $return['msg_success'] .= ' ... <span style="color:darkred;">'.A::t('app', 'Failed').'</span>';
        }else{
            $return['msg_success'] .= ' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';
        }        
    }

	/**
	 * Performs deleting operation
	 * @param string $destFile
	 * @param string $moduleName
	 * @param string &$deletedFiles
	 * @param string &$msg,
	 * @param string &$msgType
	 */
    private function _deleteFile($destFile, $moduleName, &$deletedFiles, &$msg, &$msgType)
    {                                
        if(CFile::deleteFile($destFile)){
            $deletedFiles .= ' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';
        }else{
            $deletedFiles .= ' ... <span style="color:darkred;">'.A::t('app', 'Failed').'</span>';
            $msg = A::t('app', 'Module Uninstallation Warning Message', array('{module}'=>$moduleName));
            $msgType = 'warning';
        }
    }
   
}
