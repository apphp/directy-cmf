<?php
/**
 * Backup controller
 *														
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct             	_prepareTab
 * indexAction				_getExistingBackups
 * createAction				_create
 * restoreAction			_restore
 * deleteAction				
 * 
 */

namespace Modules\Backup\Controllers;

// Framework
use \A,
	\CAuth,
	\CController,
	\CConfig,
	\CFile,
	\CValidator,
	\CWidget;

// Directy
use \Admins,
	\LocalTime,
	\Modules,
	\Website;

use \Modules\Backup\Models\Backup;



class BackupController extends CController
{	
	private $_backupDir = 'protected/tmp/backups/';
	private $_backupFilePrefix = 'db-backup-';
	private $_backupFileExt = 'sql';
	
    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

		// Get BackEnd path
		$this->_backendPath = Website::getBackendPath();

		// Block access if the module is not installed
		if(!Modules::model()->isInstalled('backup')){
			if(CAuth::isLoggedInAsAdmin()){
				$this->redirect($this->_backendPath.'modules/index');
			}else{
				$this->redirect('index/index');
			}
		}

		// Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());

        // Block access if admin has no active privilege view modules
        if(!Admins::hasPrivilege('modules', 'view')){
        	$this->redirect($this->_backendPath.'dashboard');
        }		
		
		// Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('backup', 'Create and Restore Backups')));
        // Set backend mode
        Website::setBackend();
    	
    	// Create the backup directory if it doesn't exist
    	if(!file_exists($this->_backupDir)){
    		@mkdir($this->_backupDir, 0755);
    	}

		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
		$this->_view->backendPath = $this->_backendPath;
	}	
		
	/**
	 * Controller default action handler
	 */
	public function indexAction()
	{
        $this->redirect('backup/create');
    }

    /**
     * Create action handler
     */
	public function createAction()
	{		
		if(!Admins::hasPrivilege('backup', 'create')) {
			$this->redirect($this->_backendPath.'modules/settings/code/backup');
		}

		// Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('backup', 'Create Backups')));

        $cRequest = A::app()->getRequest();
		$alert = '';
		$alertType = '';

		if($this->_cSession->hasFlash('alert')){
			$alert = $this->_cSession->getFlash('alert');
			$alertType = $this->_cSession->getFlash('alertType');

			$this->_view->actionMessage = CWidget::create(
				'CMessage', array($alertType, $alert, array('button'=>true))
			);
		}

		if($cRequest->getPost('act') == 'send'){
			if(!Admins::hasPrivilege('modules', 'edit')) {
				$this->redirect($this->_backendPath.'modules/application');
			}
			$this->_view->backupFileName = $cRequest->getPost('backupFileName');
				
            $result = CWidget::create('CFormValidation', array(
                'fields'=>array(
                	'backupFileName' =>array('title'=>A::t('backup', 'Backup File Name'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>32)),
                ),
            ));
            if($result['error']){
				$alert = $result['errorMessage'];
				$alertType = 'validation';
				$this->_view->errorField = $result['errorField'];
				
            // Check filename validity
            }elseif(!CValidator::isFileName($this->_view->backupFileName)){
				$alert = A::t('backup', 'Invalid Filename Alert', array('{title}'=>A::t('backup', 'Backup File Name')));
				$alertType = 'validation';
            	$this->_view->errorField = 'backupFileName';            	 
            }else{
				// Run the backup
				if(APPHP_MODE == 'demo'){
					$alert = A::t('core', 'This operation is blocked in Demo Mode!');
					$alertType = 'warning';
				}elseif($this->_create($this->_view->backupFileName)){
					$alert = A::t('backup', 'Backup Success Message');
					$alertType = 'success';
				}else{
					$alert = A::t('backup', 'Backup Error Message');
					$alertType = 'error';
					$this->_view->errorField = '';
				}
			}

			if(!empty($alert)){
				$this->_cSession->setFlash('alert', $alert);
				$this->_cSession->setFlash('alertType', $alertType);
				$this->redirect('backup/create');
			}
        }else{
        	$this->_view->backupFileName = LocalTime::currentDate();
        }

        $this->_view->backupsList = $this->_getExistingBackups();
		$this->_view->tabs = $this->_prepareTab('create');		
        $this->_view->render('backup/create');
    }
    
    /**
     * Restore action handler
     * @param string $file the backup file name
     */
    public function restoreAction($file = '')
    {
		if(!Admins::hasPrivilege('backup', 'restore')){
			$this->redirect($this->_backendPath.'modules/settings/code/backup');
		}
		
		// Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('backup', 'Restore Backups')));

		$this->_view->actionMessage = CWidget::create('CMessage', array('info', A::t('backup', 'Restore Warning Message'), array('button'=>false)));

		if($this->_cSession->hasFlash('alert')){
			$alert = $this->_cSession->getFlash('alert');
			$alertType = $this->_cSession->getFlash('alertType');

			$this->_view->actionMessage = CWidget::create(
				'CMessage', array($alertType, $alert, array('button'=>true))
			);
		}

    	if($file != ''){
			if(!Admins::hasPrivilege('modules', 'edit')){
				$this->redirect($this->_backendPath.'modules/application');
			}

    		// Run the restore
			if(APPHP_MODE == 'demo'){
				$alert = A::t('core', 'This operation is blocked in Demo Mode!');
				$alertType = 'warning';
			}elseif($this->_restore($file)){
				$alert = A::t('backup', 'Restore Success Message');
				$alertType = 'success';
    		}else{
				$alert = A::t('backup', 'Restore Error Message');
				$alertType = 'error';
    		}

			if(!empty($alert)){
				$this->_cSession->setFlash('alert', $alert);
				$this->_cSession->setFlash('alertType', $alertType);
				$this->redirect('backup/restore');
			}
    	}

        $this->_view->backupsList = $this->_getExistingBackups();
    	$this->_view->tabs = $this->_prepareTab('restore');
    	$this->_view->render('backup/restore');
    }
    	 
    /**
     * Delete backup file action handler
     * @param string $file the backup file name
     */
    public function deleteAction($file = '')
    {
		if(!Admins::hasPrivilege('modules', 'edit')){
			$this->redirect($this->_backendPath.'modules/application');
		}
    	if($file == '') $this->redirect('backup/backup');

		$alert = '';
		$alertType = '';
    	$backupFile = $this->_backupDir.$file;

		if($this->_cSession->hasFlash('alert')){
			$alert = $this->_cSession->getFlash('alert');
			$alertType = $this->_cSession->getFlash('alertType');

			$this->_view->actionMessage = CWidget::create(
				'CMessage', array($alertType, $alert, array('button'=>true))
			);
		}

		if(APPHP_MODE == 'demo'){
			$alert = A::t('core', 'This operation is blocked in Demo Mode!');
			$alertType = 'warning';
		}elseif(CFile::deleteFile($backupFile)){
			$alert = A::t('backup', 'Backup Delete Success Message');
			$alertType = 'success';
    	}else{
			$alert = A::t('backup', 'Backup Delete Error Message');
			$alertType = 'error';
    	}

		if(!empty($alert)){
			$this->_cSession->setFlash('alert', $alert);
			$this->_cSession->setFlash('alertType', $alertType);
			$this->redirect('backup/create');
		}

		$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
		$this->_view->backupFileName = LocalTime::currentDate();
    	$this->_view->backupsList = $this->_getExistingBackups();
    	$this->_view->tabs = $this->_prepareTab('create');
    	$this->_view->render('backup/create');
    }
    
    /**
     * Performs the database backup and saves the resulting sql in the backup file
     * @param string $file the backup file name (without constant prefix and extension)
     */
    private function _create($file = '')
    {
       	if($file == '') return false;
    	$backupFilePath = $this->_backupDir.$this->_backupFilePrefix.$file.'.'.$this->_backupFileExt;
    	
    	$backupModel = new Backup();
    	$sqlQuery = $backupModel->create();
    	if($sqlQuery == ''){
    		return false;
    	}
    	
    	// Save file
    	@chmod($backupFilePath, 0755);
    	$handle = @fopen($backupFilePath, 'w+');
    	if($handle){
    		@fwrite($handle, $sqlQuery);
    		@fclose($handle);
    		$result = true;
    	}else{
    		$result = false;
    	}
    	// Give permissions
    	@chmod($backupFilePath, 0644);
    	return $result;
    }
    
    /**
     * Performs the restore of the database by running the sql saved in the backup file
     * @param string $file the backup file name
     */
    private function _restore($file = '')
    {
       	if($file == '') return false;
    	$backupFile = $this->_backupDir.$file;
		$sqlDump = file_get_contents($backupFile);
		if($sqlDump == '') return false;
		
		$backupModel = new Backup();
		return $backupModel->restore($sqlDump);
    }
    
    /**
     * Prepares backup module tabs
     * @param string $activeTab backup|restore
     */
    private function _prepareTab($activeTab = 'create')
    {
    	return CWidget::create('CTabs', array(
			'tabsWrapper'=>array('tag'=>'div', 'class'=>'title'),
			'tabsWrapperInner'=>array('tag'=>'div', 'class'=>'tabs'),
			'contentWrapper'=>array(),
			'contentMessage'=>'',
			'tabs'=>array(
				A::t('backup', 'Settings') => array('href'=>$this->_backendPath.'modules/settings/code/backup', 'id'=>'tabSettings', 'content'=>'', 'active'=>false, 'htmlOptions'=>array('class'=>'modules-settings-tab')),
				A::t('backup', 'Create')   => array('href'=>'backup/create', 'id'=>'tabCreate', 'content'=>'', 'active'=>($activeTab == 'create' ? true : false), 'disabled'=>(!Admins::hasPrivilege('backup', 'create') ? true : false)),
				A::t('backup', 'Restore')  => array('href'=>'backup/restore', 'id'=>'tabRestore', 'content'=>'', 'active'=>($activeTab == 'restore' ? true : false), 'disabled'=>(!Admins::hasPrivilege('backup', 'restore') ? true : false)),
			),
			'events'=>array(
				//'click'=>array('field'=>$errorField)
			),
			'return'=>true,
    	));
    }
    
    /**
     * Returns array of existing backup files with fileName and fileSize values for each one. 
     */    
	private function _getExistingBackups()
    {
		// Get list of existing backup files 
    	$files = CFile::findFiles($this->_backupDir, array('fileTypes'=>array($this->_backupFileExt)));
    	
    	// Sort files by file name
		usort($files, function ($a, $b){return ($a <= $b) ? 1 : 0;});

		$backupsList = array();
    	$i = 0;
    	if(is_array($files)){
	    	foreach($files as $file){
	    		$backupsList[$i]['fileName'] = $file;
				$backupsList[$i]['fileSize'] = CFile::getFileSize($this->_backupDir.$file, 'kb') . ' KB';
	    		$i++;
	    	}
    	}
	    return $backupsList; 
    }
}