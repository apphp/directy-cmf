<?php 
/**
 * UserGroups Controller
 *
 * PUBLIC:                  PRIVATE:
 * -----------              ------------------
 * __construct              _checkUserGroupsAccess
 * indexAction                
 * manageAction
 * addAction
 * editAction
 * deleteAction
 * 
 */


namespace Modules\Users\Controllers;

// Modules
use \Modules\Users\Components\UsersComponent,
	\Modules\Users\Models\UserGroups,
	\Modules\Users\Models\Users;

// Framework
use \A,
	\CAuth,
	\CConfig,
	\CController,
	\CDatabase,
	\CFile,
	\CLoader,
	\CLocale,
	\CTime,
	\CWidget;

// Directy
use \LocalTime,
	\Modules,
	\Website,
	\Bootstrap,
	\ModulesSettings;


class UserGroupsController extends CController
{
	
	private $_backendPath = '';

    /**
	 * Class default constructor
     */
    public function __construct()
	{            
        parent::__construct();
        
		// Get BackEnd path
		$this->_backendPath = Website::getBackendPath();

        // Block access to this controller for not-logged users
        CAuth::handleLogin(Website::getDefaultPage());
        
        // Block access if module is not installed
        if(!Modules::model()->isInstalled('users')){
        	if(CAuth::isLoggedInAsAdmin()){
				$this->redirect($this->_backendPath.'modules/index');
        	}else{
        		$this->redirect(Website::getDefaultPage());
        	}
        }

        // Set meta tags according to active language
        Website::setMetaTags(array('title'=>A::t('users', 'User Groups Management')));
        
        $this->_settings = Bootstrap::init()->getSettings();
		$this->_cSession = A::app()->getSession();
        
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';	
        $this->_view->backendPath = $this->_backendPath;

        $this->_view->tabs = UsersComponent::prepareTab('usergroup');
	}
    
	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
		$this->redirect('userGroups/manage');	   	
    }
        
    /**
     * Manage user groups action handler
     */
    public function manageAction()
    { 
        Website::prepareBackendAction('manage', 'users', 'userGroups/manage', false);
		
        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }
		
        $this->_view->render('userGroups/manage');
    }	
  
    /**
     * Add User Group action handler
     */
    public function addAction()
    {
        Website::prepareBackendAction('add', 'users', 'userGroups/manage', false);
        $this->_view->render('userGroups/add');
    }  
    
    /**
     * User Group edit action handler
     * @param int $id 
     */
    public function editAction($id = 0)
    {        
        Website::prepareBackendAction('edit', 'users', 'userGroups/manage', false);
        $userGroups = $this->_checkUserGroupsAccess($id);
    
        $this->_view->id = $userGroups->id;
        $this->_view->render('userGroups/edit');
    } 

    /**
     * Delete user group action handler
     * @param int $id the user group id
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'users', 'userGroups/manage', false);
        $userGroups = $this->_checkUserGroupsAccess($id);

        $alert = '';
        $alertType = '';
    
        if($userGroups->delete()){             
            $alert = A::t('users', 'User group deleted successfully');
            $alertType = 'success'; 
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = CDatabase::init()->getErrorMessage();
                $alertType = 'warning';
            }else{
                $alert = A::t('users', 'User group deleting error');
                $alertType = 'error';
            }           
        }
		
		if(!empty($alert)){
			$this->_cSession->setFlash('alert', $alert);
			$this->_cSession->setFlash('alertType', $alertType);
        }
		
        $this->redirect('userGroups/manage');
    }
   
    /**
    * Check if passed user group ID is valid
    * @param int $id
    */
    private function _checkUserGroupsAccess($id = 0)
    {        
        $userGroups = UserGroups::model()->findByPk((int)$id);
        if(!$userGroups){
            $this->redirect('userGroups/manage');
        }
        return $userGroups;
    }      

}      