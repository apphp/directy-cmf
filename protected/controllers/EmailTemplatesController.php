<?php
/**
 * EmailTemplates controller
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              
 * indexAction
 * manageAction
 * addAction
 * editAction
 * deleteAction
 *
 */

class EmailTemplatesController extends CController
{

    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

        // block access to this controller for not-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to view locations
		if(!Admins::hasPrivilege('email_templates', array('view', 'edit'))){
			$this->redirect('backend/index');
		}
		
        // set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Email Templates Management')));
        // set backend mode
        Website::setBackend();
        
        // prepare modules distinct names
        $result = EmailTemplates::model()->findAll(array('group'=>CConfig::get('db.prefix').'email_templates.id'));
        $modules = array(''=>'');
        if(is_array($result)){
            foreach($result as $key => $val){
                $modules[$val['module_code']] = $val['module_code'];
            }
        }
        $this->_view->modules = $modules;
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
    }

	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
        $this->redirect('emailTemplates/manage');    
    }

    /**
     * Manage email templates action handler
     * @param string $msg 
     */
	public function manageAction($msg = '')
	{			
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

		$this->_view->render('emailTemplates/manage');    
    }

    /**
     * Add new email template action handler
     */
	public function addAction()
	{		
		// block access if admin has no active privilege to edit locations
     	if(!Admins::hasPrivilege('email_templates', 'edit')){
     		$this->redirect('backend/index');
     	}
     	
		$this->_view->render('emailTemplates/add');        
	}
	
	/**
	 * Edit email template action handler
	 * @param int $id the template id 
	 */
	public function editAction($id = 0)
	{		
		// block access if admin has no active privilege to edit locations
     	if(!Admins::hasPrivilege('email_templates', 'edit')){
     		$this->redirect('backend/index');
     	}
		
     	$template = EmailTemplates::model()->findByPk((int)$id);
		if(!$template){
			$this->redirect('emailTemplates/manage');
		}
        
		$this->_view->template = $template;		
		$this->_view->render('emailTemplates/edit');        
	}
    
	/**
	 * Delete email template action handler
	 * @param int $id the template id 
	 */
	public function deleteAction($id = 0)
	{
		// block access if admin has no active privilege to edit locations
     	if(!Admins::hasPrivilege('email_templates', 'edit')){
     		$this->redirect('backend/index');
     	}
     	
     	$template = EmailTemplates::model()->findByPk((int)$id);
		if(!$template){
			$this->redirect('emailTemplates/manage');
		}

		$msg = '';
		$msgType = '';

		// check if the template is system 
		if($template->is_system){
			$msg = A::t('app', 'Delete System Template Alert');
			$msgType = 'error';
		}else if($template->delete()){				
			if($template->getError()){
				$msg = A::t('app', 'Delete Warning Message');
				$msgType = 'warning';
			}else{		
				$msg = A::t('app', 'Delete Success Message');
				$msgType = 'success';	
			}		
		}else{
			if(APPHP_MODE == 'demo'){
				$msg = CDatabase::init()->getErrorMessage();
				$msgType = 'warning';
		   	}else{
				$msg = $template->getError() ? $template->getErrorMessage() : A::t('app', 'Delete Error Message');
				$msgType = 'error';
		   	}			
		}
		if(!empty($msg)){
			$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
		}
        
		$this->_view->render('emailTemplates/manage');
	}
    
}