<?php
/**
 * Modules controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              _getSystemModules
 * indexAction			   	_getApplicationModules
 * systemAction             _getNotInstalledModules
 * applicationAction        _getModules
 * editAction				_prepareTab 
 * installAction            _prepareSettingsTab
 * updateAction             _readModuleXml 
 * uninstallAction          _runSqlFile
 * deleteTestDataAction		_processInfoXml 
 * settingsAction           _getSubDirectories
 *                          _copyFile
 *                          _deleteFile  
 *                          
 */

use \Modules\Setup\Models\Setup;


class ModulesController extends CController
{

	private $_backendPath = '';

	/**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

		// Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());

		// Block access if admin has no active privilege to view modules and modules management pages
		if(!Admins::hasPrivilege('modules', 'view') && !Admins::hasPrivilege('modules', 'view_management')){
			$this->redirect(Website::getBackendPath().'dashboard/index');
		}

		// Get BackEnd path
		$this->_backendPath = Website::getBackendPath();

		// Set meta tags according to active language
		Website::setMetaTags(array('title'=>A::t('app', 'Modules Management')));
		// Set backend mode
		Website::setBackend();

		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

		// Get detetime formats
		$settings = Bootstrap::init()->getSettings();
		$this->_view->dateTimeFormat = $settings->datetime_format;
		$this->_view->dateFormat = $settings->date_format;

		$this->_view->backendPath = $this->_backendPath;
		$this->_view->actionMessage = '';
        $this->_view->errorField = '';
		$this->_view->notInstalledModulesList = array();
		$this->_view->frameworkVersion = A::app()->version();
	}
        
	/**
	 * Controller default action handler
	 */
	public function indexAction()
	{
        $this->redirect($this->_backendPath.'modules/system');
    }

    /**
     * View system modules action handler
     */
	public function systemAction()
	{
		// Block access if admin has no active privilege to view modules management page
     	if(!Admins::hasPrivilege('modules', 'view_management')){
     		$this->redirect($this->_backendPath.'dashboard/index');
     	}

		$this->_view->modulesList = $this->_getSystemModules();		
        $this->_view->notInstalledModulesList = $this->_getNotInstalledModules('system');
        $this->_view->allModulesList = $this->_getModules('application');
		$this->_view->tabs = $this->_prepareTab('system');		
		
		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}

    	$this->_view->render($this->_backendPath.'modules/system');
    }

    /**
     * View application modules action handler
     */
	public function applicationAction()
	{
		// Block access if admin has no active privilege to view modules management page
     	if(!Admins::hasPrivilege('modules', 'view_management')){
     		$this->redirect($this->_backendPath.'dashboard/index');
     	}

		$this->_view->modulesList = $this->_getApplicationModules();		
        $this->_view->notInstalledModulesList = $this->_getNotInstalledModules('application');
        $this->_view->allModulesList = $this->_getModules('application');
		$this->_view->tabs = $this->_prepareTab('application');		
		
		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}

    	$this->_view->render($this->_backendPath.'modules/application');
    }
    
    /**
     * Edit module action handler
     * @param int $id the module id 
     */
	public function editAction($id = 0)
	{	
		// Block access if admin has no active privilege to edit on modules management page
     	if(Admins::hasPrivilege('modules', 'view_management') && !Admins::hasPrivilege('modules', 'edit_management')){
     		$this->redirect($this->_backendPath.'dashboard/index');
     	}
     	
		$module = Modules::model()->findByPk($id);
		if(!$module){
			$this->redirect($this->_backendPath.'modules/view');
		}		

		// Calculate days elapsed after installation
		$this->_view->days_after_installation = CTime::getTimeDiff(LocalTime::currentDateTime(), $module->installed_at, 'day');

		$this->_view->module = $module;		
		$this->_view->render($this->_backendPath.'modules/edit');
	}

	/**
	 * View and edit module settings action handler
	 * @param string $code the module code 
	 */
	public function settingsAction($code = '')
	{	
		$cRequest = A::app()->getRequest();
		$alert = '';
		$alertType = '';
		
		// Fetch the module
		$moduleCode = $cRequest->getPost('act') == 'send' ? $cRequest->getPost('code') : $code;
        $module = Modules::model()->find('code = :code', array(':code'=>$moduleCode));
		if(!$module){
            $this->redirect($this->_backendPath.'modules/index');
        }else{
            $moduleName = $module->name;
		}		
		
		$moduleSettings = ModulesSettings::model()->findAll(array('condition'=>'module_code = :moduleCode', 'order'=>'property_group ASC, id ASC'), array(':moduleCode'=>$moduleCode));
    	$valuesArray = array();    	
		if($cRequest->getPost('act') == 'send'){
    		// Module settings form submit
			
			$valuesArray = array();
			$fields = array();
			if(is_array($moduleSettings)){
				// Prepare array with posted (new) values of settings
				$triggerSettings = array();
				foreach($moduleSettings as $setting){
					$triggerSettings[$setting['property_key']] = $cRequest->getPost('value_'.$setting['id']);
				}

				foreach($moduleSettings as $setting){
					$valuesArray[$setting['id']] = $cRequest->getPost('value_'.$setting['id']);
				
					// Check if trigger condition exists and run it
					// Example for trigger definition:
					//  serialize(array(
					//		'trigger'=>array('key'=>'send_admin_email_notification', 'operation'=>'!=', 'value'=>'0'),
					//		'action'=>array('field'=>'is_required', 'value'=>'1')
					//  ));
					if(!empty($setting['trigger_condition'])){
						$triggerCondition = unserialize($setting['trigger_condition']);
						$triggerKey = isset($triggerCondition['trigger']['key']) ? $triggerCondition['trigger']['key'] : '';
						$triggerOperation = isset($triggerCondition['trigger']['operation']) ? $triggerCondition['trigger']['operation'] : '';
						$triggerValue = isset($triggerCondition['trigger']['value']) ? $triggerCondition['trigger']['value'] : '';
						$actionField = isset($triggerCondition['action']['field']) ? $triggerCondition['action']['field'] : '';
						$actionValue = isset($triggerCondition['action']['value']) ? $triggerCondition['action']['value'] : '';
						
						if(isset($triggerSettings[$triggerKey])){
							switch($triggerOperation){
								case '!=':
									if($triggerSettings[$triggerKey]['property_value'] != $triggerValue){
										$setting[$actionField] = $actionValue;
									}									
									break;
								default:
									if($triggerSettings[$triggerKey]['property_value'] == $triggerValue){
										$setting[$actionField] = $actionValue;
									}
									break;
							}
						}
					}

					// Array of fields for form validation
					// TODO: validate each value according to its type (property_type)
					$validationType = 'any';
					$validationSource = '';
					$min = '';
					$max = '';
					$maxLength = 255;
					$propertyLength = isset($setting['property_length']) ? $setting['property_length'] : '';
					
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
                        case 'phone':
                            $maxLength = 32;
                            $validationType = $setting['property_type'];
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
							if(!empty($propertyLength) && $propertyLength < 255){
								$maxLength = $propertyLength;
							}
							break;
					}					
					$fields['value_'.$setting['id']] = array('title'=>$setting['name'], 'validation'=>array('required'=>$setting['is_required'], 'type'=>$validationType, 'minValue'=>$min, 'maxValue'=>$max, 'source'=>$validationSource, 'maxLength'=>$maxLength));
				}
			}
						
			// Validate the form
			$result = CWidget::create('CFormValidation', array('fields'=>$fields));
			if($result['error']){
				$alert = $result['errorMessage'];
				$this->_view->errorField = $result['errorField'];
				$alertType = 'validation';
			}else{
				if(APPHP_MODE == 'demo'){
					$alert = A::t('core', 'This operation is blocked in Demo Mode!');
					$alertType = 'warning';
				}else{
					if(ModulesSettings::model()->updateSettings($valuesArray)){
						$alert = A::t('app', 'Module Settings Update Success Message');
						$alertType = 'success';
					}else{
						$alert = A::t('app', 'Module Settings Update Error Message');
						$alertType = 'error';
						$this->_view->errorField = '';
					}					
				}
			}
			if(!empty($alert)){
				$this->_cSession->setFlash('alert', $alert);
				$this->_cSession->setFlash('alertType', $alertType);
				$this->redirect($this->_backendPath.'modules/settings/code/'.$code);
			}				
    	}
		
		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}
		
        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>$moduleName));

		$this->_view->module = $module;
		$this->_view->tabs = $this->_prepareSettingsTab($module->code);
		$this->_view->moduleSettings = $moduleSettings;
		$this->_view->valuesArray = $valuesArray;
		$this->_view->render($this->_backendPath.'modules/settings');
	}
	
	/**
	 * Install module action handler
	 * @param string $code the module code
	 */
	public function installAction($code = '')
	{
		// Block access if admin has no active privilege to edit on modules management page
     	if(Admins::hasPrivilege('modules', 'view_management') && !Admins::hasPrivilege('modules', 'edit_management')){
     		$this->redirect($this->_backendPath.'dashboard/index');
     	}
		
     	$installed = false;
		$alert = '';
		$alertType = '';
		$moduleType = '';
		
		// Check if the module is already installed
        if(APPHP_MODE == 'demo'){
			$alert = A::t('core', 'This operation is blocked in Demo Mode!');			
			$alertType = 'warning';
       	}elseif(Modules::model()->isInstalled($code)){
       		$alert = A::t('app', 'Module Already Installed Message');
       		$alertType = 'warning';
       		$moduleType = 'application';
		}else{
			// Read module xml file
			$xml = $this->_readModuleXml($code);
			if(!is_object($xml)){
				// If failed to read XML file, $xml will contain error message 
				$alert = A::t('app', 'XML File Error Message', array('{file}'=>CFile::createShortenName(APPHP_PATH.'/modules/'.$code.'/info.xml', 30, 50)));
				$alertType = 'error';				
			//}elseif($xml->moduleType == 'system'){
				// check if this module is a system module
				//$alert = A::t('app', 'Operation Blocked Error Message');
				//$alertType = 'error';
			}else{
				$name = isset($xml->name) ? $xml->name : $code;
				$version = isset($xml->version) ? $xml->version : '';
				$moduleType = $xml->moduleType;
				
				// Get sql schema filename from the xml
				$sqlInstallFile = isset($xml->files->data->install) ? $xml->files->data->install : '';
				$sqlUninstallFile = isset($xml->files->data->uninstall) ? $xml->files->data->uninstall : '';
                $alertSql = $this->_runSqlFile(APPHP_PATH.'/protected/modules/'.$code.'/data/'.$sqlInstallFile);
				if($alertSql){
					$alert = A::t('app', 'Module Installation Error Message', array('{module}'=>$name)).'<br><br>'.htmlspecialchars($alertSql);
    				$alertType = 'error';
					$this->_runSqlFile(APPHP_PATH.'/protected/modules/'.$code.'/data/'.$sqlUninstallFile, true);
				}else{
					$installed = true;					

					$alert = A::t('app', 'Module Installation Success Message', array('{module}'=>$name, '{version}'=>$version));
					$alert .= '<br><br>'.A::t('app', 'Adding information to database').' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';
					$alert .= '<br>'.A::t('app', 'Copying files and directories');
					$alertType = 'success';
                    
					// Create module directory
					if(!file_exists('assets/modules/'.$code)){
						mkdir('assets/modules/'.$code);
					}
					
                    $result = $this->_processInfoXml($xml, $code);
					if(!empty($result['error_type'])){
						$alert = $result['msg'];
						$alertType = $result['error_type'];						
					}else{
						$alert .= $result['msg_success'];
					}
				}
			}
		}
		
		if(!empty($alert)){
			$this->_cSession->setFlash('alert', $alert);
			$this->_cSession->setFlash('alertType', $alertType);			
		}
		
		$this->redirect($this->_backendPath.'modules/'.($moduleType == 'application' ? 'application' : 'system'));
	}
	
	/**
	 * Update module action handler
	 * @param string $code the module code
	 */
	public function updateAction($code = '')
	{
		// Block access if admin has no active privilege to edit on modules management page
     	if(Admins::hasPrivilege('modules', 'view_management') && !Admins::hasPrivilege('modules', 'edit_management')){
     		$this->redirect($this->_backendPath.'dashboard/index');
     	}
		
        $allModulesList = $this->_getModules('application');
        $moduleVersion = isset($allModulesList[$code]['version']) ? $allModulesList[$code]['version'] : '';
     	$updated = false;
		$alert = '';
		$alertSuccess = '';
		$alertType = '';
		$moduleType = '';
        
		// Check if the last version of the module is already installed
        if(APPHP_MODE == 'demo'){
			$alert = A::t('core', 'This operation is blocked in Demo Mode!');			
			$alertType = 'warning';
		}elseif(Modules::model()->isInstalled($code) && (!empty($moduleVersion) ? Modules::model()->param($code, 'version') >= $moduleVersion : true)){
       		$alert = A::t('app', 'Module Already Updated Message', array('{module}'=>$code));
       		$alertType = 'error';
       		$moduleType = 'application';
		}else{
            $module = Modules::model()->find('code = :code', array(':code'=>$code));
            $installedVersion = $module ? $module->version : '';
			// Read module xml file
			$xml = $this->_readModuleXml($code);
			$name = isset($xml->name) ? $xml->name : $code;
			$moduleType = $xml->moduleType;
			if(!is_object($xml)){
				// If failed to read XML file, $xml will contain error message                
				$alert = A::t('app', 'XML File Error Message', array('{file}'=>CFile::createShortenName(APPHP_PATH.'/modules/'.$code.'/info.xml', 30, 50)));
				$alertType = 'error';				
			//}elseif($xml->moduleType == 'system'){
			//	// check if this module is a system module
			//	$alert = A::t('app', 'Operation Blocked Error Message');
			//	$alertType = 'error';
			}else{								
				// Get sql schema filename from the xml
				$sqlUpdateFiles = isset($xml->files->data->update) ? $xml->files->data->update : '';
                $moduleLastVersion = isset($xml->version) ? $xml->version : '';
                if(is_object($sqlUpdateFiles)){
                    $count = 0;
                    foreach($sqlUpdateFiles->children() as $sqlUpdateFile){
                        $count++;
                        // Continue if there is a smaller version
                        if(preg_replace('/[^0-9]/', '', $sqlUpdateFile) <= preg_replace('/[^0-9]/', '', $installedVersion)) continue;
                        $alertSql = $this->_runSqlFile(APPHP_PATH.'/protected/modules/'.$code.'/data/'.$sqlUpdateFile);
                        if($alertSql){
                            $alert = A::t('app', 'Module Updating Error Message', array('{module}'=>$name)).'<br><br>'.$alertSql;
                            $alertType = 'error';
                            break;
                        }                        
                    }
					
                    if(!$count){
                        // Run single file
                        $alertSql = $this->_runSqlFile(APPHP_PATH.'/protected/modules/'.$code.'/data/'.$sqlUpdateFiles);
                        if($alertSql){
                            $alert = A::t('app', 'Module Updating Error Message', array('{module}'=>$name)).'<br><br>'.$alertSql;
                            $alertType = 'error';
                        }                        
                    }
					
                    if(!$alertType){
                        $updated = true;
                        $alertSuccess = A::t('app', 'Module Updating Success Message', array('{module}'=>$name, '{version}'=>$moduleLastVersion));
                        $alertSuccess .= '<br><br>'.A::t('app', 'Updating information in database').' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';

                        $result = $this->_processInfoXml($xml, $code);
                        $alert .= $result['msg'];
                        $alertSuccess .= $result['msg_success'];
                        $alertType .= $result['error_type'];
                    }
                }
			}
		}
		
		if($updated){
			Modules::model()->reLoadData();
			$this->_view->actionMessage .= CWidget::create('CMessage', array('success', $alertSuccess, array('button'=>true)));
		}
		$this->_view->actionMessage .= CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
			
		$this->_view->modulesList = ($moduleType == 'application') ? $this->_getApplicationModules() : $this->_getSystemModules();
		$this->_view->notInstalledModulesList = $this->_getNotInstalledModules(($moduleType == 'application' ? 'application' : 'system'));
        $this->_view->allModulesList = $this->_getModules('application');        
		$this->_view->tabs = $this->_prepareTab($moduleType == 'application' ? 'application' : 'system');
		$this->_view->render($this->_backendPath.'modules/'.($moduleType == 'application' ? 'application' : 'system'));
	}

	/**
	 * Uninstall module action handler
	 * @param int $id the module id 
	 */
	public function uninstallAction($id = 0)
	{
		// Block access if admin has no active privilege to edit on modules management page
     	if(Admins::hasPrivilege('modules', 'view_management') && !Admins::hasPrivilege('modules', 'edit_management')){
     		$this->redirect($this->_backendPath.'dashboard/index');
     	}
		
     	$alert = '';
		$alertType = '';
		
		$module = Modules::model()->findByPk((int)$id);
       	if(!$module){
			$this->redirect($this->_backendPath.'modules/index');
       	}
		
		// Get module type
		$moduleType = $module->is_system ? 'system' : 'application';
		$removable = (CConfig::exists('modules.'.$module->code.'.removable') && CConfig::get('modules.'.$module->code.'.removable')) ? true : false;
		
        if(APPHP_MODE == 'demo'){
			$alert = A::t('core', 'This operation is blocked in Demo Mode!');
			$alertType = 'warning';
		}elseif(!$module->is_installed){
            // Check if the module is already installed
       		$alert = A::t('app', 'Module Not Installed Message');
       		$alertType = 'warning';
		}elseif(!$removable){
            // Check if the module is removable
       		$alert = A::t('app', 'Operation Blocked Error Message');
       		$alertType = 'error';       	
		}else{
       		// Read module xml file
			$xml = $this->_readModuleXml($module->code);
			if(!is_object($xml)){
				// If failed to read XML file, $xml will contain error message 
				$alert = A::t('app', 'XML File Error Message', array('{file}'=>CFile::createShortenName(APPHP_PATH.'/modules/'.$module->code.'/info.xml', 30, 50)));
				$alertType = 'error';
			}else{
				// Get sql schema filename from the xml
				$sqlFile = isset($xml->files->data->uninstall) ? $xml->files->data->uninstall : '';
                $alertSql = $this->_runSqlFile(APPHP_PATH.'/protected/modules/'.$module->code.'/data/'.$sqlFile, true);
				if($alertSql){
					$alert = A::t('app', 'Module Uninstallation Error Message', array('{module}'=>$module->name)).'<br><br>'.htmlspecialchars($alertSql);
    				$alertType = 'error';
				}else{
                    // Remove module from backend menu (if exists - to prevent cases when it wasn't mentioned in uninstall SQL)
                    BackendMenus::model()->deleteMenu($module->code);
					
					// Re-order modules
					$records = CDatabase::init()->createCommand()
						 ->select('*')
						 ->from(CConfig::get('db.prefix').'modules')
						 ->where('is_system=:is_system', array(':is_system'=>($moduleType == 'system' ? 1 :0)))
						 ->order('id ASC')
						 ->queryAll();

					$count = 0;
					foreach($records as $record){
						CDatabase::init()->update('modules', array('sort_order'=>++$count), 'id = '.$record['id']);
					}

					// Re-load module data
					Modules::model()->reLoadData();

					// Remove module files
					$alert = A::t('app', 'Module Uninstallation Success Message', array('{module}'=>$module->name));
					$alert .= '<br><br>'.A::t('app', 'Removing information from database').' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';
					$alertType = 'success';	
					$deletedFiles = '';
					foreach($xml->files->children() as $folder){
                        if(isset($folder['exclude']) && strtolower($folder['exclude']) == 'yes') continue;
						if(!isset($folder['installationPath'])) continue;
						
                        if(isset($folder['byDirectory']) && strtolower($folder['byDirectory']) == 'true'){
                            // Remove by whole directory
                            $deletedFiles .= '<br> - dir.: '.$folder['installationPath'].'*';
							if(!CFile::deleteDirectory(trim($folder['installationPath']), true)){	
                                $alert .= (!empty($alert) ? '<br>' : '').A::t('app', 'Module Uninstallation Warning Message', array('{module}'=>$module->name));
                                $deletedFiles .= ' ... <span style="color:darkred;">'.A::t('app', 'Failed').'</span>';
                                $alertType = 'warning';									
                            }else{
                                $deletedFiles .= ' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';
                            }
                        }else{
                            foreach($folder->children() as $file){
                                if(isset($file['exclude']) && strtolower($file['exclude']) == 'yes') continue;
								$destPaths = $this->_getSubDirectories($folder);
								foreach($destPaths as $destPath){                                        
									// By files in subdirectories
									// if($file->count()){ $file->count() is not supported in PHP prior to 5.3.0 
									if(count($file->children())){
										// Subdirectory exists
										if(basename($destPath) != trim($file->getName(), '/')) continue;
										$destFile = APPHP_PATH.'/'.trim($destPath, '/').'/'.$file->filename;
										$deletedFiles .= '<br> - file: '.trim($destPath, '/').'/'.$file->filename;                                                
										$this->_deleteFile($destFile, $module->name, $deletedFiles, $alert, $alertType);
									// By files in directory
									}else{
										$destFile = APPHP_PATH.'/'.trim($destPath, '/').'/'.$file;
										$deletedFiles .= '<br> - file: '.trim($destPath, '/').'/'.$file;
										if(file_exists($destFile)){
											$this->_deleteFile($destFile, $module->name, $deletedFiles, $alert, $alertType);
										}
									}
								}
                            }
                        }
					}

					// Remove module directory in assets/
					$deletedFiles .= '<br> - dir.: assets/modules/'.$module->code.'/';
					if(!CFile::deleteDirectory('assets/modules/'.$module->code, true)){	
						$alert .= (!empty($alert) ? '<br>' : '').A::t('app', 'Module Uninstallation Warning Message', array('{module}'=>$module->name));
						$deletedFiles .= ' ... <span style="color:darkred;">'.A::t('app', 'Failed').'</span>';
						$alertType = 'warning';									
					}else{
						$deletedFiles .= ' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';
					}
					
					// Remove backend/images/icons/ module icon (if exists - to prevent cases when it wasn't mentioned in uninstall SQL)
					$backendIcon = APPHP_PATH.'/templates/backend/images/icons/'.$module->code.'.png';
					if(CFile::fileExists($backendIcon)){
						CFile::deleteFile($backendIcon);
						$deletedFiles .= '<br> - file: templates/backend/images/icons/'.$module->code.'.png';
						$deletedFiles .= ' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';
					}

					$alert .= '<br>'.A::t('app', 'Removing files and directories').$deletedFiles;
				}
			}	
		}

		if(!empty($alert)){
			$this->_cSession->setFlash('alert', $alert);
			$this->_cSession->setFlash('alertType', $alertType);			
		}
		
		$this->redirect($this->_backendPath.'modules/'.($moduleType == 'application' ? 'application' : 'system'));
	}
		
    /**
     * Delete all test data
     * @param int $id the module id 
     * @return void
     */
    public function deleteTestDataAction($id)
    {
		// Block access if admin has no active privilege to edit on modules management page
     	if(Admins::hasPrivilege('modules', 'view_management') && !Admins::hasPrivilege('modules', 'edit_management')){
     		$this->redirect($this->_backendPath.'dashboard/index');
     	}
		
     	$alert = '';
		$alertType = '';
		
		$module = Modules::model()->findByPk((int)$id);
       	if(!$module){
			$this->redirect($this->_backendPath.'modules/index');
       	}
		
        if(APPHP_MODE == 'demo'){
			$alert = A::t('core', 'This operation is blocked in Demo Mode!');
			$alertType = 'warning';
		}elseif(!$module->is_installed){
            // Check if the module is already installed
       		$alert = A::t('app', 'Module Not Installed Message');
       		$alertType = 'warning';
		}else{
			// No "test data" - nothing todo, so redirect to edit mode
			if(!$module->has_test_data){
				$this->redirect($this->_backendPath.'modules/edit/id/'.$id);
			}

			$sqlFile = APPHP_PATH.'/protected/modules/'.$module->code.'/data/delete.test.mysql.sql';
			$ignoreErrors = false;
			$alert = A::t('app', 'All test data has been successfully deleted!');
			$alertType = 'success';
			
			// Get sql schema content
			if(file_exists($sqlFile)){
				$sqlDump = file($sqlFile);
				if(!empty($sqlDump)){
					// Replace placeholders
					$sqlDump = str_ireplace('<DB_PREFIX>', CConfig::get('db.prefix'), $sqlDump);
					// Run the sql
					$model = new Setup();
					$result = $model->install($sqlDump, true, $ignoreErrors);
					if(!$result || $ignoreErrors){
						$alert = $model->getErrorMessage();
						$alertType = 'error';
					}
				}else{
					$alert = A::t('app', 'File Empty Error Message', array('{file}'=>CFile::createShortenName(trim($sqlFile, "<br />\r\n"), 30, 50)));
					$alertType = 'error';
				}
			}else{
				$alert = A::t('app', 'File Opening Error Message', array('{file}'=>CFile::createShortenName(trim($sqlFile, "<br />\r\n"), 30, 50)));
				$alertType = 'error';
			}			
		}
		
        A::app()->getSession()->setFlash('alert', $alert);
        A::app()->getSession()->setFlash('alertType', $alertType);
		
		$this->redirect($this->_backendPath.'modules/edit/id/'.$id);
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
		
    	// Load XML file for module info
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
		// Get sql schema content
		if(file_exists($sqlFile)){
			$sqlDump = file($sqlFile);
			if(!empty($sqlDump)){
				// Replace placeholders
				$sqlDump = str_ireplace('<DB_PREFIX>', CConfig::get('db.prefix'), $sqlDump);
				$sqlDump = str_ireplace('<CURRENT_DATE>', date('Y-m-d', time() + (date('I', time()) ? 3600 : 0)), $sqlDump);
				$sqlDump = str_ireplace('<CURRENT_DATETIME>', date('Y-m-d H:i:s', time() + (date('I', time()) ? 3600 : 0)), $sqlDump);
				$sqlDump = str_ireplace('<SITE_BO_URL>', CConfig::get('defaultBackendDirectory').'/', $sqlDump);
				// Run the sql
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
		
		// Return empty message on success
		return '';
	}
	
	/**
	 * Returns list of system modules that are installed in the database
	 * @return array 
	 */
	private function _getSystemModules()
	{
		// Get list of all modules folders
		$modulesFolders = CFile::findSubDirectories('protected/modules/');
		$codesList = "'".implode("','", $modulesFolders)."'";
		
		// Get system modules
		return Modules::model()->findAll('code IN ('.$codesList.') AND is_system = 1');
	}	
	
	/**
	 * Returns list of application modules that are installed in the database
	 * @return array 
	 */
	private function _getApplicationModules()
	{
		// Get list of all modules folders
		$modulesFolders = CFile::findSubDirectories('protected/modules/');
		$codesList = "'".implode("','", $modulesFolders)."'";
		
		// Get application (not system) modules
		return Modules::model()->findAll(array('condition'=>'code IN ('.$codesList.') AND is_system = 0', 'orderBy'=>'sort_order ASC'));
    }
    
	/**
	 * Returns list of not installed modules 
     * @param string $moduleType the modules type (application|system)
	 * @return array 
	 */
    private function _getNotInstalledModules($moduleType = 'application')
    {
    	// Get all not installed modules from configuration file
    	$modulesList = array();
    	$modules = CConfig::get('modules');
    	if(!is_array($modules)) return $modulesList;
        
        foreach($modules as $code => $module){
            $enable = isset($module['enable']) ? (bool)$module['enable'] : false;
            if($enable){
                $moduleCode = strtolower($code);
                // Check if this module is installed
                if(!Modules::model()->isInstalled($moduleCode)){
                    $xml = $this->_readModuleXml($moduleCode);    				
                    if(is_object($xml)){
                        // Check the module type
                        if($moduleType == '' || ($moduleType != '' && $xml->moduleType == $moduleType)){
                            $modulesList[$moduleCode] = array(
                                'name' 			=> isset($xml->name) ? $xml->name : A::t('app', 'Unknown'),
                                'description' 	=> isset($xml->description) ? $xml->description : A::t('app', 'Unknown'),
                                'version' 		=> isset($xml->version) ? $xml->version : A::t('app', 'Unknown'),
								'framework' 	=> isset($xml->requirements->framework) ? $xml->requirements->framework : '',
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
    	// Get all not installed modules from configuration file
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
                    // Check the module type
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
	private function _prepareTab($activeTab = 'system')
    {
		return CWidget::create('CTabs', array(
		   	'tabsWrapper'=>array('tag'=>'div', 'class'=>'title'),
			'tabsWrapperInner'=>array('tag'=>'div', 'class'=>'tabs'),
			'contentWrapper'=>array(),
			'contentMessage'=>'',
			'tabs'=>array(
				A::t('app', 'System Modules') => array('href'=>$this->_backendPath.'modules/system', 'id'=>'tab1', 'content'=>'', 'active'=>($activeTab == 'system' ? true : false)),
				A::t('app', 'Application Modules') => array('href'=>$this->_backendPath.'modules/application', 'id'=>'tab2', 'content'=>'', 'active'=>($activeTab == 'application' ? true : false)),
			),
			'events'=>array(
				//'click'=>array('field'=>$errorField)
			),
			'return'=>true,
		));
	}

	/**
	 * Prepare separate module settings tab
	 * @param string $code the module code
	 */
    private function _prepareSettingsTab($code = '')
    {
		$configModule = include(APPHP_PATH.'/protected/modules/'.$code.'/config/main.php');
		$managementLinks = (isset($configModule['managementLinks']) && is_array($configModule['managementLinks'])) ? $configModule['managementLinks'] : array();
		$tabs = array(A::t('app', 'Settings') => array('href'=>$this->_backendPath.'modules/settings/code/'.$code, 'id'=>'tabSettings', 'content'=>'', 'active'=>true, 'htmlOptions'=>array('class'=>'modules-settings-tab')));
		
		foreach($managementLinks as $property => $val){
			$tabs[$property] = array('href'=>$val, 'id'=>'tab'.$property, 'content'=>'', 'active'=>false);
			// Block access to prohibited tabs
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
        
        // Copy module files
        foreach($xml->files->children() as $folder){
            if(isset($folder['exclude']) && strtolower($folder['exclude']) == 'yes') continue;
            if(!isset($folder['installationPath'])) continue;
            $byDirectory = (isset($folder['byDirectory']) && strtolower($folder['byDirectory']) === 'true') ? true : false;

            $src = '/protected/modules/'.$code.'/'.$folder->getName().'/';
            if($byDirectory){
                // Copy by whole directory
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
                // Copy file by file (default)
                $destPaths = $this->_getSubDirectories($folder);
                foreach($destPaths as $destPath){
                    $destPath = trim($destPath, '/').'/';
                    $dest = APPHP_PATH.'/'.$destPath;                    
                    $return['msg_success'] .= '<br> - file: '.$destPath;
                    $found = false;
                    $defaultPath = $defaultFile = '';
                    foreach($folder->children() as $file){                        
                        // Find and stire info about default file
                        if(isset($file['default'])){
                            $defaultPath = $file->getName().'/';
                            $defaultFile = $file->filename;
                        } 
                        // By files in subdirectories
                        // if($file->count()){ $file->count() is not supported in PHP prior to 5.3.0 
                        if(count($file->children())){
                            // Subdirectory exists
                            $destSubPath = $file->getName().'/';
                            if(basename($destPath) != trim($destSubPath, '/')) continue;
                            $return['msg_success'] .= $file->filename;
                            $this->_copyFile(APPHP_PATH.$src.$destSubPath, $dest, $file->filename, $return);
                            $found = true;
                        // By files in directory    
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
            // Prepare array of destinations for copying to all subfolders
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
	 * @param string &$alert
	 * @param string &$alertType
	 */
    private function _deleteFile($destFile, $moduleName, &$deletedFiles, &$alert, &$alertType)
    {                                
        if(CFile::deleteFile($destFile)){
            $deletedFiles .= ' ... <span style="color:darkgreen;">'.A::t('app', 'OK').'</span>';
        }else{
            $deletedFiles .= ' ... <span style="color:darkred;">'.A::t('app', 'Failed').'</span>';
            $alert = A::t('app', 'Module Uninstallation Warning Message', array('{module}'=>$moduleName));
            $alertType = 'warning';
        }
    }
   
}
