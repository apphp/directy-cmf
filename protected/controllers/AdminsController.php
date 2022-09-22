<?php
/**
 * Admins controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              
 * indexAction
 * manageAction
 * addAction
 * editAction
 * deleteAction
 * myAccountAction
 * sendNewAccountEmail
 *
 */

class AdminsController extends CController
{
    /** @var int The logged admin id */    
    private $_loggedId;

    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();
        
        // Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());
        $this->_loggedId = CAuth::getLoggedId();
        
        // Allow access to any type of admins only 
        if(!CAuth::isLoggedInAsAdmin()){
        	$this->redirect('backend/index');
        }
        
        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Admins Management')));
        // Set backend mode
        Website::setBackend();
        
		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

        $this->_view->actionMessage = '';
        $this->_view->errorField = '';        
	
        // Prepare list of all active languages
        $languages = Languages::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'));
        $langList = array();
		if(is_array($languages)){
			foreach($languages as $lang){
	        	$langList[$lang['code']] = $lang['name_native'];
	        }
	  	}
	  	$this->_view->langList = $langList;
        
        // Prepare list of roles that the logged admin can deal with
        $allRolesList = array(); 
        $rolesList = array(); 
        
		$roles = Roles::model()->findAll();
		if(is_array($roles)){
        	foreach($roles as $role){
	        	$allRolesList[$role['code']] = $role['name'];

				if(CAuth::isLoggedInAs('owner') && $role['code'] == 'owner'){
					continue;
				}elseif(CAuth::isLoggedInAs('mainadmin') && ($role['code'] == 'mainadmin' || $role['code'] == 'owner')){
					continue;
				}else{
					$rolesList[$role['code']] = $role['name'];
				}				
	        }
		}

        $this->_view->rolesListStr = "'".implode("','", array_keys($rolesList))."'";
        $this->_view->rolesList = $rolesList;
        $this->_view->allRolesList = $allRolesList;
		
	    // Fetch datetime format from settings table
        $this->_view->dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');
	}	
		
	/**
	 * Controller default action handler
	 */
	public function indexAction()
	{
        $this->redirect('admins/manage');
    }
    
    /**
     * Manage admins action handler
     */
    public function manageAction()
    {
    	// Allow access only to site owner or main admin
        if(!CAuth::isLoggedInAs('owner', 'mainadmin')){
        	$this->redirect('backend/index');
        }

		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}

        $this->_view->render('admins/manage');
    }
    
    /*
     * Add new admin action handler
     */
    public function addAction()
    {
        // Allow access only to site owner or main admin
        if(!CAuth::isLoggedInAs('owner', 'mainadmin')){
        	$this->redirect('backend/index');
        }
	
		// Prepare salt
		$this->_view->salt = '';
		if(A::app()->getRequest()->getPost('password') != ''){
			$this->_view->salt = CConfig::get('password.encryptSalt') ? CHash::salt() : '';
		}
		
        $this->_view->render('admins/add');
    }    		
   
    /**
     * Edit admin action handler
     * @param int $id the admin id 
     * @param string $avatar has value 'delete' in order to delete the avatar image file
     * @param bool $isMyAccount
     */
    public function editAction($id = 0, $avatar = '', $isMyAccount = false)
    {
		$admin = Admins::model()->findByPk($id, ($isMyAccount ? 'is_active = 1' : ''));
    	if(!$admin){
            if($isMyAccount){
                A::app()->getSession()->endSession();
                $this->redirect('backend/login');
            }else{
                $this->redirect('backend/index');    
            }
    	}
    	$this->_view->isMyAccount = ($admin->id == $this->_loggedId ? true : false);
		
    	// Allow access to edit other admins only to site owner or main admin
        if(!$this->_view->isMyAccount && 
       		!CAuth::isLoggedInAs('owner', 'mainadmin') && 
       		!in_array($admin->role, array_keys($this->_view->rolesList))){
        	$this->redirect('backend/index');
        }
        $this->_view->admin = $admin;

		// Prepare salt
		$this->_view->salt = '';
		$this->_view->saltDisabled = true;		

		if(A::app()->getRequest()->getPost('password') != ''){
			$this->_view->salt = CConfig::get('password.encryptSalt') ? CHash::salt() : '';
			$this->_view->saltDisabled = false;		
		}

        // Delete the avatar file
        if($avatar === 'delete' && $admin->avatar != ''){
        	$avatar = 'templates/backend/images/accounts/'.$admin->avatar;
        	$admin->avatar = '';
			$alert = '';
			$alertType = '';
        	
			// Save the changes in admins table
        	if($admin->save()){
        		// Delete the file
        		if(CFile::deleteFile($avatar)){
        			$alert = A::t('app', 'Image Delete Success Message');
        			$alertType = 'success';
        		}else{
        			$alert = A::t('app', 'Image Delete Warning');
        			$alertType = 'warning';
        		}
        		
        		if($this->_view->isMyAccount){
	        		// Use default avatar image in session variable
	        		$session = A::app()->getSession();
	        		$session->set('loggedAvatar', 'no_image.png');
        		}
        	}else{
        		$alert = A::t('app', 'Image Delete Error Message');
        		$alertType = 'error';
        	}
        	
			if(!empty($alert)){
        		$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        	}
        }

        $this->_view->render('admins/edit');
    }
    
    /**
     * Delete admin action handler
     * @param int $id The admin id 
     */
    public function deleteAction($id = 0)
    {
        // Allow access only to site owner or main admin
        if(!CAuth::isLoggedInAs('owner', 'mainadmin')){
        	$this->redirect('backend/index');
        }
    	    		
    	$alert = '';
    	$alertType = '';

    	$admin = Admins::model()->findByPk($id);
    	if(!$admin){
    		$this->redirect('admins/manage');
    	}    	
    	// Check if this delete operation is allowed
    	if(!in_array($admin->role, array_keys($this->_view->rolesList))){
    		$alert = A::t('app', 'Operation Blocked Error Message');
    		$alertType = 'error';
    	// Delete the admin
    	}elseif($admin->delete()){
        	$alert = A::t('app', 'Delete Success Message');		
			// Delete admin images
			$hash = CConfig::get('installationKey') ? CConfig::get('installationKey') : 'admins_';
			CFile::deleteDirectory('images/upload/'.md5($hash.$id).'/');
            
            $avatar = $admin->avatar;
            $avatarPath = 'templates/backend/images/accounts/'.$avatar;    
			// Delete the avatar file
        	if(CFile::deleteFile($avatarPath)){
        		$alertType = 'success';
        	}else{
                if($avatar == ''){
                    $alertType = 'success';	    
                }else{
                    $alert .= '<br>'.A::t('app', 'Image Delete Warning');
                    $alertType = 'warning';
                }
        	}
    	}else{
			if(APPHP_MODE == 'demo'){
				$alert = CDatabase::init()->getErrorMessage();
				$alertType = 'warning';
		   	}else{
				$alert = A::t('app', 'Delete Error Message');
				$alertType = 'error';
		   	}    		
    	}
		
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);
		
		$this->redirect('admins/manage');
    }

    /**
     * My Account action handler
     * Calls the editAction with id of logged admin.
     * @param string $avatar has value 'delete' in order to delete the avatar image file
     */
	public function myAccountAction($avatar = '')
	{
        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'My Account')));
		$this->editAction($this->_loggedId, $avatar, true);
    }

	/**
	 * Send email to admin when new account was created by site owner or main admin
	 * @param int $id
	 * return void
	 */
	public function sendNewAccountEmail($id)
	{
		// Retrieve admin info
		$admin = Admins::model()->findByPk($id);
		
		// Set admin role for email template
		$role = in_array($admin->role, array('mainadmin', 'admin')) ? 'admin' : $admin->role;
		
		if(!empty($admin)){
			$emailTo = $admin->email;
			$templateCode = 'bo_'.$role.'_account_created_by_owner';
			$emailParams = array(
				'{FIRST_NAME}'  => $admin->first_name,
				'{LAST_NAME}'   => $admin->last_name,
				'{USERNAME}'    => $admin->username,
				'{PASSWORD}'    => A::app()->getRequest()->getPost('password'),
				'{WEB_SITE}'    => A::app()->getRequest()->getBaseUrl()
			);

			Website::sendEmailByTemplate($emailTo, $templateCode, A::app()->getLanguage(), $emailParams);					
		}
	}
	
}