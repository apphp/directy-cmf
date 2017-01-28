<?php
/**
 * Admins controller
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              
 * indexAction
 * manageAction
 * addAction
 * deleteAction
 * editAction
 * myAccountAction
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
        
        // block access to this controller for not-logged users
		CAuth::handleLogin('backend/login');
        $this->_loggedId = CAuth::getLoggedId();
        
        // allow access to any type of admins
        if(!CAuth::isLoggedInAsAdmin()){
        	$this->redirect('backend/index');
        }
        
        // set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Admins Management')));
        // set backend mode
        Website::setBackend();
        
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';        
	
        // prepare list of all active languages
        $languages = Languages::model()->findAll('is_active = 1');
        $langList = array();
		if(is_array($languages)){
			foreach($languages as $lang){
	        	$langList[$lang['code']] = $lang['name_native'];
	        }
	  	}
	  	$this->_view->langList = $langList;
        
        // prepare list of roles that the logged admin can deal with
        $allRolesList = array(); 
        $rolesList = array();
        if(CAuth::isLoggedInAs('owner')){
        	$rolesList = array('mainadmin'=>'mainadmin', 'admin'=>'admin');
        }else if(CAuth::isLoggedInAs('mainadmin')){
        	$rolesList = array('admin'=>'admin');
        }        	        	
        $roles = Roles::model()->findAll();
		if(is_array($roles)){
        	foreach($roles as $role){
	        	$allRolesList[$role['code']] = $role['name'];
	        	if(in_array($role['code'], $rolesList)){
	        		$rolesList[$role['code']] = $role['name'];
	        	}
	        }
		}
        $this->_view->rolesListStr = "'".implode("','", array_keys($rolesList))."'";
        $this->_view->rolesList = $rolesList;
        $this->_view->allRolesList = $allRolesList;

        // fetch datetime format from settings table
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
     * @param string $msg 
     */
    public function manageAction($msg = '')
    {
    	// allow access only to site owner or main admin
        if(!CAuth::isLoggedInAs('owner', 'mainadmin')){
        	$this->redirect('backend/index');
        }
        switch($msg){
        	case 'added': 
				$message = A::t('core', 'The adding operation has been successfully completed!');
				break;						
        	case 'updated': 
				$message = A::t('core', 'The updating operation has been successfully completed!');
				break;						
			default:
				$message = '';						
        }
        if(!empty($message)){
    		$this->_view->actionMessage = CWidget::create('CMessage', array('success', $message, array('button'=>true)));
    	}
        $this->_view->render('admins/manage');
    }
    
    /*
     * Add new admin action handler
     */
    public function addAction()
    {
        // allow access only to site owner or main admin
        if(!CAuth::isLoggedInAs('owner', 'mainadmin')){
        	$this->redirect('backend/index');
        }
        $this->_view->render('admins/add');
    }
    		
    /**
     * Delete admin action handler
     * @param int $id The admin id 
     */
    public function deleteAction($id = 0)
    {
        // allow access only to site owner or main admin
        if(!CAuth::isLoggedInAs('owner', 'mainadmin')){
        	$this->redirect('backend/index');
        }
    	    		
    	$msg = '';
    	$msgType = '';

    	$admin = Admins::model()->findByPk((int)$id);
    	if(!$admin){
    		$this->redirect('admins/manage');
    	}    	
    	// check if this delete operation is allowed
    	if(!in_array($admin->role, array_keys($this->_view->rolesList))){
    		$msg = A::t('app', 'Operation Blocked Error Message');
    		$msgType = 'error';
    	// delete the admin
    	}else if($admin->delete()){
        	$msg = A::t('app', 'Delete Success Message');		
			// delete admin images
			$salt = CConfig::get('installationKey') ? CConfig::get('installationKey') : 'admins_';
			CFile::deleteDirectory('images/upload/'.md5($salt.$id).'/');
            
            $avatar = $admin->avatar;
            $avatarPath = 'templates/backend/images/accounts/'.$avatar;    
			// delete the avatar file
        	if(CFile::deleteFile($avatarPath)){
        		$msgType = 'success';
        	}else{
                if($avatar == ''){
                    $msgType = 'success';	    
                }else{
                    $msg .= '<br>'.A::t('app', 'Image Delete Warning');
                    $msgType = 'warning';
                }
        	}
    	}else{
			if(APPHP_MODE == 'demo'){
				$msg = CDatabase::init()->getErrorMessage();
				$msgType = 'warning';
		   	}else{
				$msg = A::t('app', 'Delete Error Message');
				$msgType = 'error';
		   	}    		
    	}
    	if(!empty($msg)){
    		$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
    	}
    	$this->_view->render('admins/manage');
    }
    
    /**
     * Edit admin action handler
     * @param int $id the admin id 
     * @param string $avatar has value 'delete' in order to delete the avatar image file
     * @param bool $isMyAccount
     */
    public function editAction($id = 0, $avatar = '', $isMyAccount = false)
    {
		$admin = Admins::model()->findByPk((int)$id, ($isMyAccount ? 'is_active = 1' : ''));
    	if(!$admin){
            if($isMyAccount){
                A::app()->getSession()->endSession();
                $this->redirect('backend/login');
            }else{
                $this->redirect('backend/index');    
            }
    	}
    	$this->_view->isMyAccount = ($admin->id == $this->_loggedId ? true : false);
		
    	// allow access to edit other admins only to site owner or main admin
        if(!$this->_view->isMyAccount && 
        		!CAuth::isLoggedInAs('owner', 'mainadmin') && 
        		!in_array($admin->role, array_keys($this->_view->rolesList))){
        	$this->redirect('backend/index');
        }
        $this->_view->admin = $admin;
    	$this->_view->password = '';
    	$this->_view->passwordRetype = '';
    	 
        // delete the avatar file
        if($avatar === 'delete'){
        	$msg = '';
        	$msgType = '';
        	$avatar = 'templates/backend/images/accounts/'.$admin->avatar;
        	$admin->avatar = '';
        	// save the changes in admins table
        	if($admin->save()){
        		// delete the file
        		if(CFile::deleteFile($avatar)){
        			$msg = A::t('app', 'Image Delete Success Message');
        			$msgType = 'success';
        		}else{
        			$msg = A::t('app', 'Image Delete Warning');
        			$msgType = 'warning';
        		}
        		
        		if($this->_view->isMyAccount){
	        		// use default avatar image in session variable
	        		$session = A::app()->getSession();
	        		$session->set('loggedAvatar', 'no_image.png');
        		}
        	}else{
        		$msg = A::t('app', 'Image Delete Error Message');
        		$msgType = 'error';
        	}
        	if(!empty($msg)){
        		$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
        	}
        }

        $this->_view->render('admins/edit');
    }
    
    /**
     * My Account action handler
     * Calls the editAction with id of logged admin.
     * @param string $avatar has value 'delete' in order to delete the avatar image file
     */
	public function myAccountAction($avatar = '')
	{
        // set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'My Account')));
		$this->editAction($this->_loggedId, $avatar, true);
    }

}